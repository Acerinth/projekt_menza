<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);

if (!(empty($_GET["id"]))) {
    $id_menza = $_GET["id"];
    $datum = date('Y-m-d', ucitajVrijeme());
    $sql = 'select naziv from menza where id_menza=' . $id_menza;
    $rs = $dbc->selectDB($sql);
    $red = $rs->fetch_row();
    $naziv = $red[0];
    $sql2 = 'SELECT katalog.id_meni, katalog.juha, katalog.glavno_jelo, katalog.prilog, katalog.salata, katalog.desert, count(katalog.id_meni) as "broj"
            from katalog, rezervacija
            where katalog.id_meni=rezervacija.id_meni and rezervacija.datum between "' . $datum . ' 00:00:00" and "'.$datum. ' 23:59:59" and rezervacija.id_menza=' . $id_menza . ' 
            group by katalog.id_meni
            order by count(katalog.id_meni)
            desc
            limit 3;';
    $rs2 = $dbc->selectDB($sql2);
} else {
    header('Location: popis_menzi.php');
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Najkorišteniji meni</title>
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
                <h2>Najkorišteniji meni danas</h2>
                <p>
                    Naziv menze: <?php echo $naziv ?><br>
                    Datum: <?php echo date('d.m.Y', ucitajVrijeme()) ?><br>
                </p>


                <?php
                if ($rs2->num_rows > 0) {
                    echo '<table>
                    <thead>
                        <tr>
                            <th>Šifra menija</th>
                            <th>Juha</th>
                            <th>Glavno jelo</th>
                            <th>Prilog</th>
                            <th>Salata</th>
                            <th>Desert</th>
                            <th>Broj rezervacija</th>
                        </tr>
                    </thead><tbody>';
                    while ($red2 = $rs2->fetch_assoc()) {
                        echo '<tr><td>' . $red2["id_meni"] . '</td><td>' . $red2["juha"] . '</td><td>'
                        . $red2["glavno_jelo"] . '</td><td>' . $red2["prilog"] . '</td><td>'
                        . $red2["salata"] . '</td><td>' . $red2["desert"] . '</td><td>' . $red2["broj"] . '</td></tr>';
                    }
                    echo '</tbody>';
                } else {
                    echo 'Nema rezervacija na današnji dan.';
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




