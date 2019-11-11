<?php
include_once('aplikacijskiOkvir.php');
$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, ODJAVA);
$dbc->zatvoriDB();

session_start();
session_unset();
session_destroy();


header("Location: index.php");
?>