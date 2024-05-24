<?php

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/baza.class.php';

$dnevnik = new Dnevnik();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $email = $_GET['email'];
    $kod = $_GET['aktivacijski_kod'];

    $baza = new Baza();
    $baza->spojiDB();
    $upit = "select * from korisnik where email = '" . $email . "' and aktivacijski_kod='" . $kod . "'";
    $korisnik = mysqli_fetch_array($baza->selectDB($upit));
    if ($korisnik) {
        $id = $korisnik['id_korisnik'];
        $korisnikEmail = $korisnik['email'];
        $korisnikKod = $korisnik['aktivacijski_kod'];
        $korisnikVrijediDo = $korisnik['kod_vrijedi_do'];
        $currentDateTime = date('Y-m-d H:i:s', time());
        var_dump($currentDateTime < $korisnikVrijediDo);
        if ($currentDateTime < $korisnikVrijediDo) {
            $update = "update korisnik set aktivan = 1 where id_korisnik = '" . $id . "'";
            $tekst = $korisnik['korisnicko_ime'] . ",rad s bazom,aktivacija korisničkog računa";
            $dnevnik->spremiDnevnik($tekst);
            $baza->updateDB($update, "index.php");
        } else {
            echo '<script>alert("Aktivacijski link je istekao.");</script>';
        }
    }
}



