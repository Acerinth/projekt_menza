<?php
define('ADMINISTRATOR', '3'); define('MODERATOR', '2'); define('KORISNIK', '1');
define('PRIJAVA', '1'); define('ODJAVA', '2');
define('UPIT', '3'); define('POSJET', '4');
define('AZURIRAJ', '5'); define('DODAJ', '6'); define('BRISI', '7');

include_once('generirajLozinku.php');
include_once('korisnik.php');
include_once('baza.class.php');
include_once('provjeraKorisnika.php');
include_once('ucitajVrijeme.php');
include_once('dnevnik.php');

global $dbc;
$dbc = new Baza();
$dbc->spojiDB();
?>