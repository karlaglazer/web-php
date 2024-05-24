<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>{$naziv}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Karla Glazer">
        <meta name="keywords" content="nekretnine">
        <meta name="description" content="22.5.2023.">
        <!--<script src="https://www.google.com/recaptcha/enterprise.js?render=6Lepw04mAAAAACtKm_gGtA6ZR2K9-9qbzepta8fw"></script>-->
        <script src="https://www.google.com/recaptcha/api.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="{$putanja}/css/kglazer.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script  type="text/javascript" src="{$putanja}/js/kglazer.js"></script>
    </head>
    <body>
        <nav>
            <ul id="navigation">
                <li><a href="{$putanja}/index.php">Početna</a></li>
                <li><a href="{$putanja}/popis.php">Nekretnine</a></li>
                <li><a href="{$putanja}/privatno/korisnici.php">Popis korisnika</a></li>
                    {if isset($smarty.session.uloga)}
                        {if $smarty.session.uloga >2}
                        <li><a href="{$putanja}/ugovori.php">Ugovori</a></li>
                        <li><a href="{$putanja}/popis_korisnika.php">Korisnici</a></li>
                        <li><a href="{$putanja}/dnevnik.php">Dnevnik rada</a></li>
                        {/if}
                        {if $smarty.session.uloga >1}
                        <li><a href="{$putanja}/popis_pricuva.php">Pričuve</a></li>
                        {/if}

                    <li><a href="{$putanja}/najam.php">Najam</a></li>
                    <li><a href="{$putanja}/troskovi.php">Troškovi</a></li>
                    <li style="float:right"><a href="?odjava=1">Odjava</a></li>
                    {/if}
                    {if !isset($smarty.session.uloga)}
                    <li style="float:right"><a href="{$putanja}/prijava.php">Prijava</a></li>
                    <li style="float:right"><a href="{$putanja}/registracija.php">Registracija</a></li>
                    {/if}

            </ul>
        </nav>