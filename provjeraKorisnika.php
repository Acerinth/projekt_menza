<?php

include_once('korisnik.php');

function provjeraPrijave() {
    session_start();
    if (isset($_SESSION["prijava"]) && $_SESSION["prijava"] == TRUE && isset($_SESSION["korisnik"])) {
        $korisnik = $_SESSION["korisnik"];
        if ($korisnik->get_adresa() != $_SERVER["REMOTE_ADDR"]) {
            return false;
        } else {
            return $korisnik;
        }
    } else {
        return false;
    }
}

function provjeraUloge($uloga) {
    $korisnik = provjeraPrijave();
    if ($korisnik) {
        if ($korisnik->get_vrsta() >=$uloga) {
            return $korisnik;
        }
    }
    return false;   
}

?>