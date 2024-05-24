<?php

if (isset($_SERVER['HTTPS'])) {
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $good_url = "http://" . $host . $request_uri;

    //header("HTTP/1.1 301 Moved Permanently");
    header("Location: $good_url");
    exit;
} else {
    $naziv = "Popis korisnika";

    $direktorij = dirname(getcwd());
    $putanja = dirname(dirname($_SERVER['REQUEST_URI']));

    include 'funkcije.php';

    $baza = new Baza();
    $baza->spojiDB();
    $upit = "select id_korisnik, korisnicko_ime, prezime, ime, email, lozinka from korisnik";
    $korisnici = $baza->selectDB($upit);
    $baza->zatvoriDB();
    $popisKorisnika = array();
    while ($red = mysqli_fetch_array($korisnici)) {
        if ($red) {
            array_push($popisKorisnika, $red);
        }
    }

    $smarty->assign("popisKorisnika", $popisKorisnika);
    $smarty->display("korisnici.tpl");
    $smarty->display("footer.tpl");
}

