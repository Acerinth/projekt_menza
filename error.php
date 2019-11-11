<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);

$dbc->zatvoriDB();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pogreska!</title>
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
            <section id="sadrzaj">
                <div class="tekst">
                    <?php
                    $e = $_GET["e"];
                    $message = "";
                    switch ($e) {
                        case 0: $message = "Neovlašteni pristup stranici.";
                            break;
                        case 1: $message = "Neovlašteni pristup stranici.";
                            break;
                        case 2: $message = "Neautorizirani pristup.";
                            break;
                        case 3: $message = "Problem prilikom slanja maila.";
                            break;
                        default: $message = "Nepoznata pogreska.";
                            break;
                    }
                    print $message;
                    ?>
                    <p><a href="index.php">Povratak na početnu stranicu.</a></p> 
                </div>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>

    </body>
</html>