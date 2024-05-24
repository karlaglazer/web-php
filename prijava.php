<?php

$naziv = "Prijava";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$dnevnik = new Dnevnik();

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}
$korisnicko_ime_prijava = "";
$greska = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $greska;
    $korisnicko_ime = $_POST['korime_prijava'];
    $lozinka = $_POST['lozinka_prijava'];
    $zapamti = isset($_POST['zapamti']);
    if (!empty($korisnicko_ime) && !empty($lozinka)) {
        $baza = new Baza();
        $baza->spojiDB();
        $upit = "select lozinka_hash, tip_korisnika, aktivan, neuspjesni_unosi, blokiran from korisnik where korisnicko_ime = '" . $korisnicko_ime . "'";
        $korisnik = mysqli_fetch_array($baza->selectDB($upit));
        if ($korisnik !== null) {
            $aktivan = $korisnik['aktivan'];
            if ($aktivan == 1) {
                $blokiran = $korisnik['blokiran'];
                if ($blokiran == 0) {
                    $lozinkaHash = $korisnik['lozinka_hash'];
                    $cistaLozinka = substr($lozinkaHash, 10);
                    $provjera = hash('sha256', $lozinka);
                    //$provjera = substr($provjera, 0, 54);
                    if ($provjera === $cistaLozinka) {
                        $restart = "update korisnik set neuspjesni_unosi = 0 where korisnicko_ime = '" . $korisnicko_ime . "'";
                        $baza->updateDB($restart);
                        $uloga = $korisnik['tip_korisnika'];
                        Sesija::kreirajKorisnika($korisnicko_ime, $uloga);
                        $zapis = "$korisnicko_ime,prijava/odjava,prijava";
                        $dnevnik->spremiDnevnik($zapis);
                        if ($zapamti) {
                            setcookie('korisnicko_ime', $korisnicko_ime);
                        }
                        if ($_SERVER['HTTPS']) {
                            $host = $_SERVER['HTTP_HOST'];
                            $good_url = "http://" . $host . $putanja . "/index.php";
                            header("HTTP/1.1 301 Moved Permanently");
                            header("Location: $good_url");
                            exit;
                        }
                    } else {
                        $greska = "Neispravna lozinka!";
                        $neuspjesni = $korisnik['neuspjesni_unosi'];
                        $neuspjesni++;
                        $povecaj = "update korisnik set neuspjesni_unosi = '" . $neuspjesni . "' where korisnicko_ime = '" . $korisnicko_ime . "'";
                        $baza->updateDB($povecaj);
                        if ($neuspjesni == 5) {
                            $blokiraj = "update korisnik set blokiran = 1 where korisnicko_ime = '" . $korisnicko_ime . "'";
                            $baza->updateDB($blokiraj);
                        }
                    }
                } else {
                    $greska = "Korisnik je blokiran!";
                }
            } else {
                $greska = "Račun nije aktivan!";
            }
        } else {
            $greska = "Korisničko ime ne postoji!";
        }
        $baza->zatvoriDB();
    } else {
        $greska = "Polja nisu popunjena!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_COOKIE['korisnicko_ime'])) {
        global $korisnicko_ime_prijava;
        $korisnicko_ime_prijava = $_COOKIE['korisnicko_ime'];
    }
    if (isset($_GET['oporavakEmail'])) {
        global $greska;
        $email = $_GET['oporavakEmail'];
        $upit = "select id_korisnik from korisnik where email = '" . $email . "'";
        $baza = new Baza();
        $baza->spojiDB();
        $korisnik = mysqli_fetch_array($baza->selectDB($upit));
        if (!isset($korisnik['id_korisnik'])) {
            $greska = "Email ne postoji.";
        } else {
            $nova = "";
            $lozinkaFormat = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{1,}$/";
            while (!preg_match($lozinkaFormat, $nova)) {
                $nova = CreateRandomSalt(10);
            }         
            $salt = CreateRandomSalt(10);
            $novaLozinkaHash = $salt;
            $novaLozinkaHash .= hash("sha256", $nova);
            $azuriranje = "update korisnik set lozinka = '" . $nova . "', lozinka_hash = '" . $novaLozinkaHash . "' where id_korisnik = '" . $korisnik['id_korisnik'] . "'";
            $proslo = $baza->updateDB($azuriranje);
            if ($proslo==1) {
                global $putanja;
                $sender = 'upravljanje.nekretninama@email.com';
                $subject = 'Nova lozinka "Upravljanje nekretninama"';
                $prijava = 'https://' . $_SERVER['HTTP_HOST'] . $putanja . "/prijava.php";
                $message = "Nova lozinka: " . $nova;
                $message .= "\nLink do prijave: ".$prijava;
                $header = "From:" . $sender;

                mail($email, $subject, $message, $header);
                var_dump($message);
            }
        }
    }
}
$smarty->assign("greska", $greska);
$smarty->assign("korisnickoIme", $korisnicko_ime_prijava);
$smarty->display("prijava.tpl");
$smarty->display("footer.tpl");

