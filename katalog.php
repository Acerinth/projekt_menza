<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Katalog</title>
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

                <h2>Katalog menija</h2>
               
                <p><a href="dodajMeni.php">Dodaj novi meni</a></p>
                <?php
                $sql = 'select katalog.* from katalog';
                $rez = $dbc->selectDB($sql);
                if ($rez->num_rows > 0) {
                    echo '<table>
                            <thead>
                                <tr>
                                    <th>Šifra menija</th>
                                    <th>Juha</th>
                                    <th>Glavno jelo</th>
                                    <th>Prilog</th>
                                    <th>Salata</th>
                                    <th>Desert</th>';
                        echo '</tr></thead><tbody>';
                        while ($red = $rez->fetch_assoc()) {
                            echo '<td>' . $red["id_meni"] . '</td><td>' . $red["juha"] . '</td><td>' . $red["glavno_jelo"] .
                            '</td><td>' . $red["prilog"] . '</td><td>' . $red["salata"] .'</td><td>' . $red["desert"] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                }
                else {
                    echo '<p>Ne postoje definirani meniji.</p>';
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
