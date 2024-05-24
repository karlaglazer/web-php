<?php

$naziv = "Popis ugovora";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$sqlUgovori = "select ugovor.*, concat(korisnik.ime,' ',korisnik.prezime) as stanar, nekretnina.opis from ugovor "
        . "join korisnik on ugovor.stanar = korisnik.id_korisnik "
        . "join nekretnina on ugovor.nekretnina = nekretnina.id_nekretnina";
$dnevnik = new Dnevnik();
$baza = new Baza();
$baza->spojiDB();
$rezultat = $baza->selectDB($sqlUgovori);
$ugovori = array();
while ($red = mysqli_fetch_array($rezultat)) {
    array_push($ugovori, $red);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['potvrdi'])) {
        $potvrdi = "update ugovor set status = 'odobreno' where id_ugovor = " . $_GET['potvrdi'];
        if ($baza->updateDB($potvrdi)) {
            $sqlNekretnina = "select nekretnina from ugovor where id_ugovor = " . $_GET['potvrdi'];
            $rezultat = mysqli_fetch_array($baza->selectDB($sqlNekretnina));
            if ($rezultat) {
                $trenutnoVrijeme = date('Y-m-d');
                $sqlUpdate = "update nekretnina set status_nekretnine = 'iznajmljen', datum_najma = '$trenutnoVrijeme' where id_nekretnina=" . $rezultat['nekretnina'];
                $tekst = $_SESSION['korisnik'] . ",rad s bazom,potvrda ugovora o najmu";
                $dnevnik->spremiDnevnik($tekst);
                $baza->updateDB($sqlUpdate, "ugovori.php");
            }
        }
    }
    if (isset($_GET['odbij'])) {
        $odbij = "update ugovor set status = 'odbijeno' where id_ugovor = " . $_GET['odbij'];
        $tekst = $_SESSION['korisnik'] . ",rad s bazom,odbijanje ugovora o najmu";
        $dnevnik->spremiDnevnik($tekst);
        $baza->updateDB($odbij, "ugovori.php");
    }
    if (isset($_GET['najam'])) {
        $sqlNajam = "select * from zaduzenja_najam where ugovor=" . $_GET['najam'];
        $rezultat = $baza->selectDB($sqlNajam);
        $postoji = false;
        while ($red = mysqli_fetch_array($rezultat)) {
            if ($red) {
                $trenutniMjesec = date('m');
                $unesenoPolje = explode('-', $red['mjesec']);
                if ($trenutniMjesec == $unesenoPolje[1]) {
                    $postoji = true;
                }
            }
        }
        if ($postoji == 1) {
            echo '<script>alert("Najamnina je već unesena.");</script>';
        } else {
            $sqlCijena = "select ukupna_cijena from nekretnina "
                    . "join ugovor on nekretnina.id_nekretnina = ugovor.nekretnina "
                    . "where ugovor.id_ugovor = " . $_GET['najam'] . " and ugovor.status = 'odobreno'";
            $rezultat = mysqli_fetch_array($baza->selectDB($sqlCijena));
            $datum = date('Y-m-d');
            $sql = "insert into zaduzenja_najam (ugovor, cijena, mjesec) values ('" . $_GET['najam'] . "', '" . $rezultat['ukupna_cijena'] . "', '$datum')";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,kreiranje zaduženja za trenutni mjesec";
            $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($sql, "ugovori.php");
        }
    }
}

$smarty->assign("ugovori", $ugovori);
$smarty->display("ugovori.tpl");
$smarty->display("footer.tpl");
