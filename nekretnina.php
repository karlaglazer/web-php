<?php

$naziv = "Nekretnina";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$dnevnik = new Dnevnik();
$baza = new Baza();
$baza->spojiDB();
$nekretnina = "Nije moguće pogledati odabranu nekretninu.";

$sqlTip = "select * from vaznost_nedostatka";
$rezultat = $baza->selectDB($sqlTip);
$tipovi = array();
while ($red = mysqli_fetch_array($rezultat)) {
    if ($red) {
        array_push($tipovi, $red);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_nekretnina'])) {
        $idNekretnina = $_GET['id_nekretnina'];
        $id_korisnik = $_GET['stanar'];

        $upit = "select ugovor.id_ugovor, nekretnina.id_nekretnina, nekretnina.opis, nekretnina.slika, nekretnina.ukupna_cijena, nekretnina.datum_najma from nekretnina "
                . "join ugovor on ugovor.nekretnina = $idNekretnina "
                . "where nekretnina.id_nekretnina = $idNekretnina and ugovor.stanar = $id_korisnik and ugovor.status = 'odobreno'";
        $rezultat = mysqli_fetch_array($baza->selectDB($upit));
        if ($rezultat) {
            $nekretnina = $rezultat;
        }
        $nekretnina['slika'] = "data:image/jpeg;base64, " . base64_encode($rezultat['slika']);
    }

    if (isset($_GET['raskini'])) {
        $id = $_GET['nekretnina'];
        $sql = "select ugovor.nekretnina, nekretnina.opis from ugovor "
                . "join nekretnina on ugovor.nekretnina=nekretnina.id_nekretnina "
                . "where ugovor.id_ugovor = $id";
        $nekretninaRaskini = mysqli_fetch_array($baza->selectDB($sql));
        $trenutniDatum = date('Y-m-d');
        $sqlNajam = "select zaduzenja_najam.mjesec as najam, zaduzenja_najam.placeno as nplaceno, "
                . "nekretnina.datum_najma from nekretnina "
                . "join ugovor on nekretnina.id_nekretnina = ugovor.nekretnina "
                . "join zaduzenja_najam on ugovor.id_ugovor = zaduzenja_najam.ugovor "
                . "where ugovor.id_ugovor = $id && ugovor.status = 'odobreno' && nekretnina.status_nekretnine = 'iznajmljen'";
        $sqlPricuva = "select pricuva.mjesec as pricuva, pricuva.placeno as pplaceno, nekretnina.datum_najma from nekretnina "
                . "join pricuva on nekretnina.id_nekretnina = pricuva.nekretnina "
                . "where nekretnina.id_nekretnina =". $nekretninaRaskini['nekretnina'] ." && nekretnina.status_nekretnine = 'iznajmljen'";
        $rezultatNajam = $baza->selectDB($sqlNajam);
        $rezultatPricuva = $baza->selectDB($sqlPricuva);
        $nizNajam = array();
        $nizPricuva=array();
        while ($red = mysqli_fetch_array($rezultatPricuva)) {
            $poljePricuva = explode('-', $red['pricuva']);
            $poljeNajam = explode('-', $red['datum_najma']);
            $pricuva = $poljePricuva[1];
            $najam = $poljeNajam[1];
            if ($pricuva > $najam && $red['pricuva'] < $trenutniDatum) {
                array_push($nizPricuva, $red);
            }
        }
        while($red = mysqli_fetch_array($rezultatNajam)){
            array_push($nizPricuva, $red);
        }
        $placeno = true;
        foreach ($nizNajam as $value) {
            if ($value['nplaceno'] != 1) {
                $placeno = false;
            }
        }
        foreach ($nizPricuva as $value) {
            if ($value['pplaceno'] != 1) {
                $placeno = false;
            }
        }
        if ($placeno == false) {
            header("Location: troskovi.php?nijePodmireno=".$nekretninaRaskini['opis']);
        } else {
            $sqlRaskidUgovora = "update ugovor set status = 'raskinut' where id_ugovor = $id";
            $baza->updateDB($sqlRaskidUgovora);
            $sqlNekretnina = "update nekretnina set datum_najma = 'NULL', status_nekretnine = 'slobodan' where id_nekretnina =" . $nekretninaRaskini['nekretnina'];
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,raskid ugovora o najmu";
                $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($sqlNekretnina, "najam.php");
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nekretnina'])) {
        $id = $_POST['nekretnina'];
        $opis = $_POST['opisNedostatka'];
        $vaznost = $_POST['vaznost'];
        $sql = "insert into nedostatak_nekretnine (najam, nedostatak, vaznost) values ('$id', '$opis', '$vaznost')";
        $tekst = $_SESSION['korisnik'] . ",rad s bazom,prijava nedostatka nekretnine";
                $dnevnik->spremiDnevnik($tekst);
        $baza->updateDB($sql);
        header("Location: najam.php");
    }
}

$smarty->assign("tipovi", $tipovi);
$smarty->assign("nekretnina", $nekretnina);
$smarty->display("nekretnina.tpl");
$smarty->display("footer.tpl");
