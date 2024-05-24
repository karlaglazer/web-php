<?php

$naziv = "Početna";

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'privatno/funkcije.php';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    setcookie("uvjeti", true, time()+2*24*60*60);
    header("Location: index.php");
}
$smarty->display("index.tpl");
$smarty->display("footer.tpl");

