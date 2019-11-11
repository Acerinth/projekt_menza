<?php
include_once '../aplikacijskiOkvir.php';

$sql = 'select korisnik.kor_ime, korisnik.lozinka, korisnik.ime, korisnik.prezime, korisnik.email, uloga.naziv from korisnik, uloga where korisnik.id_uloga=uloga.id_uloga';
$rezultat = $dbc->selectDB($sql);
if ($rezultat->num_rows > 0) {
    echo '<table border="1"><thead><tr><td>Korisnicko ime</td><td>Lozinka</td><td>Ime</td><td>Prezime</td><td>E-mail</td><td>Vrsta</td></tr></thead><tbody>';
    while ($red = $rezultat->fetch_assoc()) {
        echo '<tr><td>'.$red["kor_ime"].'</td><td>'.$red["lozinka"].'</td><td>'.$red["ime"].'</td><td>'.$red["prezime"].'</td><td>'.$red["email"].'</td><td>'.$red["naziv"].'</td></tr>';
    }
    echo '</tbody></table>';
}




?>

