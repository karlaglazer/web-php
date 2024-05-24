<?php

$naziv = "Najam";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';
$dnevnik = new Dnevnik();
$baza = new Baza();
$baza->spojiDB();
$korime = Sesija::dajKorisnika();
$sqlKorisnik = "select id_korisnik from korisnik where korisnicko_ime = '" . $korime['korisnik'] . "'";
$rezultat = mysqli_fetch_array($baza->selectDB($sqlKorisnik));
$stanar = $rezultat['id_korisnik'];

$sqlSlobodne = "select id_nekretnina, opis from nekretnina where status_nekretnine = 'slobodan'";
$rezultat = $baza->selectDB($sqlSlobodne);
$slobodne = array();

while ($red = mysqli_fetch_array($rezultat)) {
    array_push($slobodne, $red);
}
$sqlUgovori = "select nekretnina.opis, nekretnina.slika, ugovor.status, ugovor.slika_stanara from nekretnina "
        . "join ugovor on nekretnina.id_nekretnina = ugovor.nekretnina "
        . "where ugovor.stanar = $stanar";
$rezultat = $baza->selectDB($sqlUgovori);
$ugovori=array();
while ($red = mysqli_fetch_array($rezultat)) {
    if ($red) {
        $red['slika_stanara'] = "data:image/jpeg;base64, " . base64_encode($red['slika_stanara']);
        $red['slika'] = "data:image/jpeg;base64, " . base64_encode($red['slika']);
        array_push($ugovori, $red);
    }
}
$sqlUnajmljene = "select nekretnina.id_nekretnina, nekretnina.opis from nekretnina "
        . "join ugovor on nekretnina.id_nekretnina = ugovor.nekretnina "
        . "where nekretnina.status_nekretnine = 'iznajmljen' and ugovor.status='odobreno' and ugovor.stanar =".$stanar;
$baza->spojiDB();
$proba = $baza->selectDB($sqlUnajmljene);
$iznajmljene = array();
while ($red = mysqli_fetch_array($proba)) {
    if ($red) {
        array_push($iznajmljene, $red);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oznacene = array();
    $slika = "";
    foreach ($_POST as $key => $value) {
        if (strpos($key, "nekretnina")) {
            array_push($oznacene, $value);
        }
        if ($key === "slika") {
            $slika = $_POST['slika'];
        }
    }
    $fileName = basename($_FILES["slika"]["name"]);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    $image = $_FILES['slika']['tmp_name'];
    $slikaStanara = addslashes(file_get_contents($image));
    global $stanar;
    foreach ($oznacene as $najam) {
        $sqlNajam = "insert into ugovor (nekretnina, stanar, slika_stanara) values ('$najam', '$stanar', '$slikaStanara')";
        $baza->updateDB($sqlNajam, "najam.php");
    }
    $tekst = $_SESSION['korisnik'].",rad s bazom,kreiranje ugovora o najmu";
    $dnevnik->spremiDnevnik($tekst);
    $baza->zatvoriDB();
}
$smarty->assign("ugovori", $ugovori);
$smarty->assign("stanar", $stanar);
$smarty->assign("iznajmljene", $iznajmljene);
$smarty->assign("slobodne", $slobodne);
$smarty->display("najam.tpl");
$smarty->display("footer.tpl");


