<?php

require("privatno/baza.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Content-Type: text/xml');

    $korisnickoIme = $_GET["korisnicko_ime"];
    $baza = new Baza();
    $baza->spojiDB();
    $sqlQuery = "SELECT korisnicko_ime FROM korisnik";

    $korisnik = $baza->selectDB($sqlQuery);
    $baza->zatvoriDB();
    echo "<korisnici>";
    while ($red = mysqli_fetch_array($korisnik)) {
        if ($red) {
            echo "<korime>" . $red['korisnicko_ime'] . "</korime>";
        }
    }
    echo "</korisnici>";
}


