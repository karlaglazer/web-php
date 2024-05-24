<?php

$naziv = "Popis pričuva";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$dnevnik = new Dnevnik();
$baza = new Baza();
$baza->spojiDB();

$sqlNekretnine = "select * from nekretnina "
        . "join korisnik on nekretnina.upravitelj = korisnik.id_korisnik "
        . "where korisnik.korisnicko_ime = '" . $_SESSION['korisnik'] . "'";
$rezultat = $baza->selectDB($sqlNekretnine);
$nekretnine = array();
while ($red = mysqli_fetch_array($rezultat)) {
    array_push($nekretnine, $red);
}
$pricuve = array();
$nedostaci = array();
foreach ($nekretnine as $nekretnina) {
    $sqlPricuva = "select pricuva.* from pricuva "
            . "where pricuva.nekretnina = " . $nekretnina['id_nekretnina'];
    $rezultat = $baza->selectDB($sqlPricuva);
    $popis = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        if ($red) {
            array_push($popis, $red);
        }
    }
    $pricuve += array($nekretnina['id_nekretnina'] => $popis);
    $sqlNedostatak = "select nedostatak_nekretnine.id_nedostatak_nekretnine, nedostatak_nekretnine.nedostatak, nedostatak_nekretnine.status, nedostatak_nekretnine.razlog, vaznost_nedostatka.vaznost from nedostatak_nekretnine "
            . "join ugovor on nedostatak_nekretnine.najam = ugovor.id_ugovor "
            . "join vaznost_nedostatka on vaznost_nedostatka.id_vaznost_nedostatka = nedostatak_nekretnine.vaznost "
            . "where ugovor.nekretnina = " . $nekretnina['id_nekretnina'];
    $rezultat = $baza->selectDB($sqlNedostatak);
    $popisNedostataka = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        if ($red) {
            array_push($popisNedostataka, $red);
        }
    }
    $nedostaci += array($nekretnina['id_nekretnina'] => $popisNedostataka);
    //var_dump($pricuve);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $datum = $_POST['datum'];
        $cijena = $_POST['cijena'];
        $sqlPricuve = "select * from pricuva where nekretnina = $id";
        $rezultat = $baza->selectDB($sqlPricuve);
        $postoji = false;
        $idPricuva = "";
        while ($red = mysqli_fetch_array($rezultat)) {
            if ($red) {
                $postojecePolje = explode('-', $red['mjesec']);
                $unesenoPolje = explode('-', $datum);
                var_dump($postojecePolje[0]);
                var_dump($unesenoPolje[0]);
                var_dump($postojecePolje[1]);
                var_dump($unesenoPolje[1]);
                if ($postojecePolje[0] == $unesenoPolje[0] && $postojecePolje[1] == $unesenoPolje[1]) {
                    $postoji = true;
                    $idPricuva = $red['id_pricuva'];
                }
            }
        }
        if ($postoji == 1) {
            $sql = "update pricuva set cijena = '$cijena' where id_pricuva = $idPricuva";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,izmjena pričuve: $idPricuva";
            $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($sql, "popis_pricuva.php");
        } else {
            $sql = "insert into pricuva (mjesec, cijena, nekretnina) values ('$datum', '$cijena', '$id')";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,kreiranje pričuve: $idPricuva";
            $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($sql, "popis_pricuva.php");
        }
    }
    if (isset($_POST['id_nedostatak'])) {
        $id = $_POST['id_nedostatak'];
        $status = $_POST['status'];
        if ($status == 'da') {
            $sql = "update nedostatak_nekretnine set status = 'riješen' where id_nedostatak_nekretnine = $id";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,rješavanje nedostatka: $id";
            $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($sql, "popis_pricuva.php");
        } else {
            $razlog = $_POST['razlog'];
            $sql = "update nedostatak_nekretnine set status = 'neće se razriješiti', razlog = '$razlog' where id_nedostatak_nekretnine = $id";
            $tekst = $_SESSION['korisnik'] . ",rad s bazom,odbijanje nedostatka: $id";
            $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($sql, "popis_pricuva.php");
        }
    }
}


$smarty->assign("nekretnine", $nekretnine);
$smarty->assign("pricuve", $pricuve);
$smarty->assign("nedostaci", $nedostaci);
$smarty->display("popis_pricuva.tpl");
$smarty->display("footer.tpl");

