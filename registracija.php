<?php

$naziv = "Registracija";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';
$smarty->display("registracija.tpl");
$smarty->display("footer.tpl");

$passed = false;
$errorMessage = ["", "", "", "", "", "", ""];

function CheckUserName(string $korime) {
    global $errorMessage;
    $baza = new Baza();
    $baza->spojiDB();
    $upit = "select * from korisnik";
    $korisnik = $baza->selectDB($upit);
    $baza->zatvoriDB();
    while ($red = mysqli_fetch_array($korisnik)) {
        if ($red) {
            if ($red['korisnicko_ime'] === $korime) {
                $errorMessage[2] = "Korisničko ime već postoji.";
            }
        }
    }
}

function CheckEmail(string $email) {
    global $errorMessage;
    $emailFormat = "/^([A-Za-z0-9][\w\.]*[A-Za-z0-9])+@((?=[A-Za-z0-9])([A-Za-z0-9]+[\w-]*[A-Za-z0-9])+\.[\w]{2,})$/";
    if (!preg_match($emailFormat, $email)) {
        $errorMessage[4] = "Email nije ispravnog formata";
    }
}

function CheckPassword(string $lozinka) {
    global $errorMessage;
    $lozinkaFormat = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{1,}$/";
    if (!preg_match($lozinkaFormat, $lozinka)) {
        $errorMessage[5] = "Lozinka mora sadržavati minimalno 1 broj, 1 veliko slovo i 1 malo slovo.";
    }
}

function CheckConfirm(string $lozinka, string $potvrda) {
    global $errorMessage;
    if ($potvrda !== $lozinka) {
        $errorMessage[6] = "Lozinka nije ista";
    }
}

function CreateCode() {
    $code = CreateRandomSalt(55);
    return $code;
}

function SendCode(string $email, string $kod) {
    global $putanja;
    $sender = 'upravljanje.nekretninama@email.com';
    $aktivacijskiLink = 'http://' . $_SERVER['HTTP_HOST'] . $putanja . "/aktivacija.php?email=$email&aktivacijski_kod=$kod";
    $subject = 'Aktivacijski link "Upravljanje nekretninama"';
    $message = "Aktivacijski link: " . $aktivacijskiLink;
    $header = "From:" . $sender;

    mail($email, $subject, $message, $header);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $adresa = $_POST['adresa'];
    $korime = $_POST['korisnickoIme'];
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];
    $potvrda = $_POST['potvrda'];
    $recaptcha = $_POST['g-recaptcha-response'];
    if (empty($ime)) {
        $errorMessage[0] = "Ime mora biti popunjeno.";
    }
    if (empty($prezime)) {
        $errorMessage[1] = "Prezime mora biti popunjeno.";
    }
    if (empty($korime)) {
        $errorMessage[2] = "Korisničko ime mora biti popunjeno.";
    } else {
        CheckUserName($korime);
    }
    if (empty($adresa)) {
        $errorMessage[3] = "Adresa mora biti popunjena.";
    }
    if (empty($email)) {
        $errorMessage[4] = "Email mora biti popunjen.";
    } else {
        CheckEmail($email);
    }
    if (empty($lozinka)) {
        $errorMessage[5] = "Lozinka mora biti popunjena.";
    } else {
        if (strlen($lozinka) < 8) {
            $errorMessage[5] = "Lozinka mora imati minimalno 8 znakova.";
        } else {
            CheckPassword($lozinka);
        }
    }
    if (empty($potvrda)) {
        $errorMessage[6] = "Ponovljena lozinka mora biti popunjena.";
    } else {
        if ($errorMessage[5] === "") {
            CheckConfirm($lozinka, $potvrda);
        } else {
            $errorMessage[6] = "Nije moguće provjeriti.";
        }
    }

    $secret_key = '*';
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
            . $secret_key . '&response=' . $recaptcha;
    $response = file_get_contents($url);
    $response = json_decode($response);

    if ($response->success == true) {
        $passed = true;
    }
    $checkErrors = empty(implode($errorMessage));
    if ($checkErrors) {
        if ($passed) {
            $salt = CreateRandomSalt(10);
            $lozinkaHash = $salt;
            $lozinkaHash .= hash("sha256", $lozinka);
            $kod = CreateCode();
            $expiry = 10 * 60 * 60;
            $vrijediDo = date('Y-m-d H:i:s', time() + $expiry);
            $baza = new Baza();
            $baza->spojiDB();
            $upit = "insert into korisnik (ime, prezime, adresa, korisnicko_ime, email,  lozinka, lozinka_hash, aktivacijski_kod, kod_vrijedi_do) values" .
                    "('$ime','$prezime','$adresa','$korime','$email','$lozinka','$lozinkaHash', '$kod', '$vrijediDo')";
            $res = $baza->updateDB($upit);
            if ($res == 1) {
                SendCode($email, $kod);
            }
        } else {
            echo '<script>alert("Potvrdite da niste robot!");</script>';
        }
    } else {
        $message = "";
        foreach ($errorMessage as $value) {
            if ($value !== "") {
                $message .= $value;
                $message .= "\\n";
            }
        }
        echo '<script>alert("' . $message . '");</script>';
    }
}
