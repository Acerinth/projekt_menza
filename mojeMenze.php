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
        <title>Moje menze</title>
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

                <h2>Moje menze</h2>

                <?php
                
                $kor_ime = $korisnik->get_kor_ime();
                $sql = 'select menza.id_menza, naziv, adresa, broj_mjesta from menza, mod_menze where mod_menze.id_menza=menza.id_menza and mod_menze.kor_ime="'.$kor_ime.'"';
                $rez = $dbc->selectDB($sql);
                dnevnik_zapis($korisnik, UPIT, $sql);
                if ($rez->num_rows > 0) {
                    echo '<table><thead><tr><th>Šifra menze</th><th>Naziv</th><th>Adresa</th><th>Broj mjesta</th><th>Opcije</th></tr></thead><tbody>';
                    while ($red = $rez->fetch_assoc()) {
                        echo '<tr><td>'.$red["id_menza"].'</td><td>'.$red["naziv"].'</td><td>'.$red["adresa"].'</td><td>'.$red["broj_mjesta"].'</td><td><a href="ponude.php?id='.$red["id_menza"].'">Pregledaj ponude</a></td></tr>';
                    }
                    echo '</tbody></table>';
                }
                else {
                    echo '<p>Nemate dodijeljenih menzi! Molimo Vas da se javite administratoru sustava.</p>';
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

