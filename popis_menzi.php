<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Popis menzi</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">
        <title></title>
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
                <h2>Popis menzi</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Naziv menze</th>
                            <th>Adresa</th>
                            <th>Broj mjesta</th>
                        </tr>
                    </thead>
                    
                        <?php
                        $sql = "select id_menza, naziv, adresa, broj_mjesta from menza";
                        $rezultat = $dbc->selectDB($sql);
                        if ($rezultat) {
                            dnevnik_zapis($korisnik, UPIT, $sql);
                        }
                        if ($rezultat->num_rows > 0) {
                            echo '<tbody>';
                            while ($red = $rezultat->fetch_assoc()) {
                                $id = $red["id_menza"];
                                echo '<tr><td><a href="najkoristenijiMeni.php?id='.$id .'">'. $red["naziv"] . '</a></td><td>'. $red["adresa"] .'</td><td>'. $red["broj_mjesta"] . '</td></tr>';
                            }
                            echo '</tbody>';
                        } else {
                            echo 'Nema rezultata!';
                        }
                        $dbc->zatvoriDB();
                        ?>
                    
                </table>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>


