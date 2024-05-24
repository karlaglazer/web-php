<?php

$naziv = "Dnevnik";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';

$dnevnik = new Dnevnik();

$dokument = $dnevnik->citajDnevnik();
$logovi = array();
foreach ($dokument as $value) {
    $niz = explode(',', $value);
    array_push($logovi, $niz);
}
function Check($var) {
    global $od, $do;
    $polje = explode(' ', $var[0]);
    $datum = date("Y-m-d", strtotime($polje[0]));
    $postoji = $datum <= $do && $datum >= $od;
    return $postoji;
}
$od = "";
$do = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['filterDatum'])) {
        if (isset($_POST["od"]) || isset($_POST["do"])) {
            global $od, $do, $logovi;
            $od = $_POST["od"];
            $do = $_POST["do"];
            if ($do === "") {
                $do = "9999-12-31";
            }
            $logovi = array_filter($logovi, "Check");
        }
    }
}

$smarty->assign("od", $od);
$smarty->assign("do", $do);
$smarty->assign("dnevnik", $logovi);
$smarty->display("dnevnik.tpl");
$smarty->display("footer.tpl");
