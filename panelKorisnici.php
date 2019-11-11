<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(ADMINISTRATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $kor_ime = $_POST["kor_ime"];
    $status = $_POST["status"];

    if (!empty($kor_ime)) {
        $sql = 'update korisnik set status_racuna='.$status.' where kor_ime="'.$kor_ime.'"';
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, AZURIRAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: panelKorisnici.php');
        }
    }

}




?>

<!DOCTYPE html>

<html>
    <head>
        <title>Panel za korisnike</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <header>
            <h1 id="zaglavlje">Menze</h1>

        </header>
        <div class="glavna">
            <div class="traka">
                <span class="lijevo">
                    <a href="index.php">Početna</a>
                    <a href="popis_menzi.php">Popis menzi</a>
                    <a href="dokumentacija.html">Dokumentacija</a>
                    <a href="o_autoru.html">O autoru</a>
                </span>
                <span class="desno">
                    <?php
                        echo '<span id="korisnik">Dobrodošli, ' . $korisnik->get_ime_prezime() . '</span>';
                        echo '<a href="odjava.php">Odjava</a>';
                    ?>
                </span>
            </div>

            <section id="sadrzaj">
                <h2>Korisnici</h2>
                
                <p><a href="dodajKorisnika.php"> Dodaj korisnika </a></p>
                <?php
                
                $sql ='select korisnik.*, uloga.naziv from korisnik, uloga where korisnik.id_uloga=uloga.id_uloga';
                $rs = $dbc->selectDB($sql);
                if ($rs->num_rows > 0) {
                    echo '<table><thead><tr><th>Korisničko ime</th><th>Ime</th><th>Prezime</th><th>E-mail</th><th>Uloga</th><th>Status računa</th><th>Opcije</th></tr></thead><tbody>';
                    while ($red = $rs->fetch_assoc()) {
                        if ($red["status_racuna"]) {
                            $status ="Aktiviran";
                            $z = 0;
                            $poruka = "Zaključaj račun";
                        }
                        else {
                            $status="Zaključan";
                            $z = 1;
                            $poruka = "Otključaj račun";
                        }
                        echo '<tr><td>'.$red["kor_ime"].'</td><td>'.$red["ime"].'</td><td>'.$red["prezime"].'</td><td>'.$red["email"].'</td><td>'.$red["naziv"].'</td><td>'.$status.'</td><td><form class="formaTablica" method="post" action="panelKorisnici.php"><input type="text" name="kor_ime" hidden="hidden" value="'.$red["kor_ime"].'"><button type="submit" name="status" value="'.$z.'"> '.$poruka.' </button></form></td></tr>';
                    }
                    echo '</tbody></table>';
                }
                else {
                    echo 'Nema rezultata.';
                }
                
                $dbc->zatvoriDB();
                
                ?>


            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>





