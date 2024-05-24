<?php

$naziv = "Popis blokiranih";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$sqlKorisnici = "select * from korisnik";
$baza = new Baza();
$baza->spojiDB();
$rezultat = $baza->selectDB($sqlKorisnici);
$korisnici = array();
while ($red = mysqli_fetch_array($rezultat)) {
    array_push($korisnici, $red);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if (isset($_GET['aktivacija'])) {
            $upit = "update korisnik set blokiran=0 where id_korisnik = $id";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,ponovno aktiviranje korisnika: $id";
            $dnevnik->spremiDnevnik($tekst);
        }
        if (isset($_GET['blokiranje'])) {
            $upit = "update korisnik set blokiran=1 where id_korisnik = $id";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,blokiranje korisnika: $id";
            $dnevnik->spremiDnevnik($tekst);
        }
        $baza->updateDB($upit, "popis_korisnika.php");
    }
}

$smarty->assign('korisnici', $korisnici);
$smarty->display("popis_korisnika.tpl");
$smarty->display("footer.tpl");
