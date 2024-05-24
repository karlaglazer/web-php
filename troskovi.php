<?php

$naziv = "Nekretnina";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$dnevnik = new Dnevnik();
$baza = new Baza();
$baza->spojiDB();

$sqlStanar = "select id_korisnik from korisnik where korisnicko_ime='" . $_SESSION['korisnik'] . "'";
$rezultat = mysqli_fetch_array($baza->selectDB($sqlStanar));
$korisnik = $rezultat['id_korisnik'];

$sqlPricuve = "select pricuva.*, nekretnina.opis from pricuva "
        . "join nekretnina on pricuva.nekretnina = nekretnina.id_nekretnina "
        . "join ugovor on nekretnina.id_nekretnina = ugovor.nekretnina "
        . "where ugovor.stanar = $korisnik";
$rezultat = $baza->selectDB($sqlPricuve);
$pricuve = array();
$trenutniDatum = date('Y-m-d');
while ($red = mysqli_fetch_array($rezultat)) {
    if ($red['mjesec'] < $trenutniDatum) {
        array_push($pricuve, $red);
    }
}

$sqlNajam = "select zaduzenja_najam.*, nekretnina.opis from zaduzenja_najam "
        . "join ugovor on zaduzenja_najam.ugovor = ugovor.id_ugovor "
        . "join nekretnina on ugovor.nekretnina = nekretnina.id_nekretnina "
        . "where ugovor.stanar = $korisnik";
$rezultat = $baza->selectDB($sqlNajam);
$najmovi = array();
while ($red = mysqli_fetch_array($rezultat)) {
    array_push($najmovi, $red);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['nijePodmireno'])) {
        echo '<script>alert("Nisu podmirena zaduzenja za nekretninu: ' . $_GET['nijePodmireno'] . '");</script>';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['platiPricuvu'])) {
        $sql = "update pricuva set placeno=1 where id_pricuva = " . $_POST['platiPricuvu'];
        $tekst = $_SESSION['korisnik'] . ",rad s bazom,plaćanje pričuve: " . $_POST['platiPricuvu'];
        $dnevnik->spremiDnevnik($tekst);
        $baza->updateDB($sql, "troskovi.php");
    }
    if (isset($_POST['platiNajam'])) {
        $sql = "update zaduzenja_najam set placeno=1 where id_zaduzenja_najam = " . $_POST['platiNajam'];
        $tekst = $_SESSION['korisnik'] . ",rad s bazom,plaćanje najamnine: " . $_POST['platiNajam'];
        $dnevnik->spremiDnevnik($tekst);
        $baza->updateDB($sql, "troskovi.php");
    }
}

$smarty->assign("najamnine", $najmovi);
$smarty->assign("pricuve", $pricuve);
$smarty->display("troskovi.tpl");
$smarty->display("footer.tpl");
