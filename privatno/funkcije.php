<?php

include "baza.class.php";
include "sesija.class.php";
include "dnevnik.class.php";
require "$direktorij/vanjske_biblioteke/smarty-4.3.0/libs/Smarty.class.php";

Sesija::kreirajSesiju();
$dnevnik = new Dnevnik();
$kolacic = false;
if (!isset($_COOKIE['uvjeti'])) {
    global $kolacic;
    $kolacic = true;
}

$smarty = new Smarty();
$smarty->setTemplateDir("$direktorij/templates")
        ->setCompileDir("$direktorij/templates_c")
        ->setConfigDir("$direktorij/configs");

$smarty->assign("naziv", $naziv);
$smarty->assign("putanja", $putanja);
$smarty->assign("kolacic", $kolacic);
if (!$kolacic) {
    $smarty->display("layout.tpl");
}

if (isset($_GET["odjava"])) {
    $zapis = $_SESSION['korisnik'] . ",prijava/odjava,odjava";
    $dnevnik->spremiDnevnik($zapis);
    Sesija::obrisiSesiju();
    header("Location: prijava.php");
}

function CreateRandomSalt($length = 10) {
    $availableChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $generatedArray = [];
    $max = mb_strlen($availableChars, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $generatedArray[] = $availableChars[random_int(0, $max)];
    }
    return implode('', $generatedArray);
}
