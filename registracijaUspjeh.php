<?php
include_once('aplikacijskiOkvir.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Aktivacija</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">
    </head>
    <header>
        <h1 id="zaglavlje">Menze</h1>
    </header>
    <body>
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
                        echo '<a href="prijava.php">Prijava</a>
                           <a href="registracija.php">Registracija</a>';
                    ?>
                </span>
            </div>
            <section id="sadrzaj">
                <div class="tekst">
                    <p>
                        Registracija uspješna! Aktivacijski link je poslan na mail.
                    </p>
                </div>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>

    </body>
</html>



