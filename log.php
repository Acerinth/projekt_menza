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
        <title>Dnevnik sustava</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
        <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"> </script>
        <script type="text/javascript" src="js/paukokic_jquery.js"></script>

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
                <h2>Dnevnik sustava</h2>
                
                <?php
                
                $sql = 'select dnevnik.*, tip_radnje.naziv from dnevnik, tip_radnje where dnevnik.id_radnje=tip_radnje.id_radnje';
                $rs = $dbc->selectDB($sql);
                if ($rs->num_rows > 0) {
                    echo '<table id="dnevnik"><thead><tr><th>Šifra zapisa</th><th>Vrijeme</th><th>Korisnik</th><th>Radnja</th><th>Opis</th></tr></thead><tbody>';
                    while ($red = $rs->fetch_assoc()) {
                        echo '<tr><td>'.$red["id_zapisa"].'</td><td>'.$red["vrijeme"].'</td><td>'.$red["kor_ime"].'</td><td>'.$red["naziv"].'</td><td>'.$red["opis"].'</td></tr>';
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



