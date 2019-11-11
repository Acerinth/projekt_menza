<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Pocetna</title>
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
                    if (!$korisnik) {
                        echo '<a href="prijava.php">Prijava</a>
                           <a href="registracija.php">Registracija</a>';
                    } else {
                        echo '<span id="korisnik">Dobrodošli, ' . $korisnik->get_ime_prezime() . '</span>';
                        echo '<a href="odjava.php">Odjava</a>';
                    }
                    ?>
                </span>
            </div>

            <section id="sadrzaj">

                <p>Dobrodošli na početnu stranicu web aplikacije za rezervaciju menija u menzi!</p>

                <?php
                if ($korisnik) {
                    if ($korisnik->get_vrsta() == ADMINISTRATOR) {
                        echo '
                        <div class="admin">
                            <h2 style"text-align: center;">Administrator</h2>
                            <ul>
                                <li><a href="adminBaza.php">Administriranje baze podataka (CRUD)</a></li>
                                <li><a href="panelMenze.php">Panel za menze</a></li>
                                <li><a href="panelKorisnici.php">Panel za korisnike</a></li>
                                <li><a href="konfiguracija.php">Konfiguracija sustava</a></li>
                                <li><a href="adminStat.php">Statistika</a></li>
                                <li><a href="log.php">Log sustava (Dnevnik rada)</a></li>
                            </ul>
                        </div>';
                    }
                    
                    if ($korisnik->get_vrsta() >= MODERATOR) {
                        echo '
                        <div class="moderator">
                            <ul>
                                <li><a href="mojeMenze.php">Moje menze</a></li>
                                <li><a href="rezervacije.php">Rezervacije</a></li>
                                <li><a href="katalog.php">Katalog menija</a></li>
                                <li><a href="maleKolicine.php">Alarmantne količine</a></li>
                            </ul>
                        </div>';
                    }

                    if ($korisnik->get_vrsta() >= KORISNIK) {
                        echo '
                        <div class="korisnik">
                            <ul>
                                <li><a href="rezervirajMeni.php">Rezerviraj meni u menzi</a></li>
                                <li><a href="mojeRezervacije.php">Moje rezervacije</a></li>
                                <li><a href="galerija.php">Galerija</a></li>
                            </ul>
                        </div>';
                    }
                }
                else {
                    echo '<p>Kako biste mogli koristiti sve mogućnosti aplikacije, potrebno se <a href="registracija.php">registrirati.</a></p>';
                }
                ?>

            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>
