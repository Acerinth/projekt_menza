<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(ADMINISTRATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Konfiguracija sustava</title>
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
                <div class="tekst">
                    
                    <h2>Konfiguracija sustava</h2>
                    <h3>Vrijeme sustava</h3>
                    <?php
                        $vrijeme_servera = time();
                        $vrijeme_sustava = ucitajVrijeme();
                        echo "<p>Stvarno vrijeme servera: " . date('d.m.Y H:i:s', $vrijeme_servera) . "<br>";
                        echo "Virtualno vrijeme sustava: " . date('d.m.Y H:i:s', $vrijeme_sustava) . "<br></p>";
                    ?>
                    <p><a href="http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html" target="_blank" >Postavi pomak vremena</a></p>
                    <p><a href="dohvatiVrijeme.php" target="_blank" >Spremi pomak vremena</a></p>
                    

                </div>


            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>

