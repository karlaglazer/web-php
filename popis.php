<?php

$naziv = "Nekretnine";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$baza = new Baza();
$baza->spojiDB();
$dnevnik = new Dnevnik();
$popisSlobodnih = array();
$popisIznajmljenih = array();
$sqlSlobodne = "select nekretnina.opis, nekretnina.slika, tip_nekretnine.naziv, concat(korisnik.ime,' ',korisnik.prezime) as upravitelj, nekretnina.ukupna_cijena from nekretnina "
        . "join korisnik on nekretnina.upravitelj = korisnik.id_korisnik "
        . "join tip_nekretnine on nekretnina.tip_nekretnine = tip_nekretnine.id_tip_nekretnine "
        . "where status_nekretnine = 'slobodan'";
$sqlIznajmljene = "select nekretnina.opis, nekretnina.slika, tip_nekretnine.naziv, concat(korisnik.ime,' ',korisnik.prezime) as upravitelj, nekretnina.datum_najma from nekretnina "
        . "join korisnik on nekretnina.upravitelj = korisnik.id_korisnik "
        . "join tip_nekretnine on nekretnina.tip_nekretnine = tip_nekretnine.id_tip_nekretnine "
        . "where status_nekretnine = 'iznajmljen' order by upravitelj";
$slobodne = $baza->selectDB($sqlSlobodne);
while ($red = mysqli_fetch_array($slobodne)) {
    $red['slika'] = "data:image/jpeg;base64, " . base64_encode($red['slika']);
    array_push($popisSlobodnih, $red);
}
$iznajmljene = $baza->selectDB($sqlIznajmljene);
while ($red = mysqli_fetch_array($iznajmljene)) {
    $red['slika'] = "data:image/jpeg;base64, " . base64_encode($red['slika']);
    array_push($popisIznajmljenih, $red);
}
$sqlUpravitelji = "select id_korisnik, concat(ime,' ',prezime) as ime from korisnik where tip_korisnika = 2";
$rezultat = $baza->selectDB($sqlUpravitelji);
$upravitelji = array();
while ($red = mysqli_fetch_array($rezultat)) {
    if ($red) {
        array_push($upravitelji, $red);
    }
}
$sqlTipNekretnine = "select * from tip_nekretnine";
$rezultat = $baza->selectDB($sqlTipNekretnine);
$tipNekretnine = array();
while ($red = mysqli_fetch_array($rezultat)) {
    if ($red) {
        array_push($tipNekretnine, $red);
    }
}
$sqlSve = "select * from nekretnina";
$rezultat = $baza->selectDB($sqlSve);
$nekretnine = array();
while ($red = mysqli_fetch_array($rezultat)) {
    if ($red) {
        array_push($nekretnine, $red);
    }
}

function Check($var) {
    global $od, $do;
    $postoji = $var['datum_najma'] < $do && $var['datum_najma'] >= $od;
    return $postoji;
}

function CheckDesc($var) {
    global $opis;
    $postoji = strpos($var['opis'], $opis);
    if ($postoji !== false) {
        $postoji = 1;
    }
    return $postoji;
}
function PriceSortAsc($a, $b){
    return $a['ukupna_cijena'] <=> $b['ukupna_cijena'];
}
function PriceSortDesc($a, $b){
    return $b['ukupna_cijena'] <=> $a['ukupna_cijena'];
}

$od = "";
$do = "";
$opis = "";
$redoslijed="sort0";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['filterDatum'])) {
        if (isset($_POST["od"]) || isset($_POST["do"])) {
            global $od, $do, $popisIznajmljenih;
            $od = $_POST["od"];
            $do = $_POST["do"];
            if ($do === "") {
                $do = "9999-12-31";
            }
            $popisIznajmljenih = array_filter($popisIznajmljenih, "Check");
        }
    }
    if (isset($_POST['filterOpis'])) {
        if (isset($_POST['opis'])) {
            if ($_POST['opis'] != "") {
                global $popisSlobodnih, $opis;
                $opis = $_POST["opis"];
                $popisSlobodnih = array_filter($popisSlobodnih, "CheckDesc");
            }
        }
        $redoslijed = $_POST['redoslijed'];
        switch ($redoslijed){
            case "sort1":
                usort($popisSlobodnih, "PriceSortAsc");
                break;
            case "sort2":
                usort($popisSlobodnih, "PriceSortDesc");
                break;
        }
    }

    if (isset($_POST['nova'])) {
        $opis = $_POST['opis'];
        $upravitelj = $_POST['upravitelj'];
        //$slikaPost = $_POST['slika'];
        $cijena = $_POST['cijena'];
        $tip = $_POST['tip'];
        $fileName = basename($_FILES["slika"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $image = $_FILES['slika']['tmp_name'];
        $slika = addslashes(file_get_contents($image));
        $sqlSpremi = "insert into nekretnina (opis, upravitelj, slika, ukupna_cijena, tip_nekretnine) values ('$opis', '$upravitelj', '$slika', '$cijena', '$tip')";
        $tekst = $_SESSION['korisnik'] . ",rad s bazom,Kreiranje nove nekretnine: $opis";
        $dnevnik->spremiDnevnik($tekst);
        $baza->updateDB($sqlSpremi, "popis.php");
    }
    if (isset($_POST['uredivanje'])) {
        $nekretnina = $_POST['nekretnine'];
        $opis = $_POST['opis'];
        $upravitelj = $_POST['upravitelj'];
        //$slikaPost = $_POST['slika'];
        $cijena = $_POST['cijena'];
        $tip = $_POST['tip'];
        $fileName = basename($_FILES["slika"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $image = $_FILES['slika']['tmp_name'];
        $slika = addslashes(file_get_contents($image));
        $sqlSpremi= "update nekretnina set opis = '$opis', upravitelj = '$upravitelj', ukupna_cijena='$cijena', tip_nekretnine='$tip', "
                . "slika = '$slika' where id_nekretnina = $nekretnina";
        $tekst = $_SESSION['korisnik'] . ",rad s bazom,Uređivanje nekretnine: $opis";
        $dnevnik->spremiDnevnik($tekst);
        $baza->updateDB($sqlSpremi, "popis.php");
    }
}
$smarty->assign("od", $od);
$smarty->assign("do", $do);
$smarty->assign("opis", $opis);
$smarty->assign("redoslijed", $redoslijed);
$smarty->assign("nekretnine", $nekretnine);
$smarty->assign("upravitelji", $upravitelji);
$smarty->assign("tip_nekretnine", $tipNekretnine);
$smarty->assign("slobodne", $popisSlobodnih);
$smarty->assign("iznajmljene", $popisIznajmljenih);
$smarty->display("popis.tpl");
$smarty->display("footer.tpl");

