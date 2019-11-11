<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(KORISNIK);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Rezerviraj meni</title>
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
                <h2>Rezerviraj meni u menzi</h2>
                <p>Odaberi datum i vrstu ponude te menzu.</p>
                <form method="post" action="dnevnaPonuda.php" class="formaTablica">
                    <label for="datum">Odaberi datum: </label>
                    <?php 
                    $danas = date ('Y-m-d', ucitajVrijeme());
                    $sql1 = 'select distinct datum from dnevna_ponuda where datum >= "'.$danas.'"';
                    $rs = $dbc->selectDB($sql1);
                    dnevnik_zapis($korisnik, UPIT, $sql1);
                    echo '<select name="datum">';
                    while ($red = $rs->fetch_row()) {
                        echo '<option value="'.$red[0].'">'.date('d.m.Y', strtotime($red[0])).'</option>';
                    }
                    echo '</select>';                    
                    ?>
                    <label for="opis">Vrsta ponude: </label>
                    <select name="opis">
                        <option value="ručak" selected="selected">Ručak</option>
                        <option value="večera">Večera</option>
                    </select>
                    <br><br>
                <table>
                    <thead>
                        <tr>
                            <th>Naziv menze</th>
                            <th>Adresa</th>
                            <th>Broj mjesta</th>
                            <th>Opcije</th>
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
                            echo '<tr><td>'. $red["naziv"] . '</td><td>'. $red["adresa"] .'</td><td>'. $red["broj_mjesta"] . '</td>';
                            echo '<td><button type="submit" name="id_menza" value="'.$red["id_menza"].'"> Prikaži ponudu </button></td></tr>';
                        }
                        echo '</tbody>';
                    } else {
                        echo 'Nema rezultata!';
                    }
                    $dbc->zatvoriDB();
                    ?>
                </table>
                    </form>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>


