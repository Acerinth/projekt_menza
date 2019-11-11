<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["id"])) {
    $id_menza = $_GET["id"];
    $datum_danas = ucitajVrijeme();
    $datum_danas_str = date('Y-m-d', $datum_danas);

    $sqlM = 'select naziv from menza where id_menza=' . $id_menza;
    $rezM = $dbc->selectDB($sqlM);
    $redM = $rezM->fetch_row();
    $naziv = $redM[0];
} else {
    header('Location: index.php');
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Ponude menze</title>
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

                <h2>Popis ponuda</h2>


                <p>Odabrana menza: <?php echo $naziv ?></p>
                <h3>Ponude u tijeku</h3>
                <form class="formaTablica" method="post" action="dodajPonudu.php">
                    <input type="number" name="id_menza" value="<?php echo $id_menza ?>" hidden="hidden">
                    <label for="opis">Izaberi vrstu ponude: </label>
                    <select name="opis">
                        <option value="ručak" selected="selected">Ručak</option>
                        <option value="večera">Večera</option>
                    </select>
                    <label for="datum">Datum: </label>
                    <select name="datum">
                    <?php
                        $datum_pocetni = DateTime::createFromFormat('Y-m-d', $datum_danas_str);
                        echo '<option value="'.$datum_pocetni->format('Y-m-d').'">'.$datum_pocetni->format('d.m.Y').'</option>';
                        $datum_iduci = $datum_pocetni;
                        for ($i=1; $i<6; $i++) {
                            $datum_iduci->modify('+1 day');
                            echo '<option value="'.$datum_iduci->format('Y-m-d').'">'.$datum_iduci->format('d.m.Y').'</option>';
                        }
                    ?>
                    </select>
                    <button type="submit"> Dodaj novu ponudu </button>
                </form>
                <br>
                <?php
                $sql = 'select id_ponuda, datum, opis from dnevna_ponuda where id_menza=' . $id_menza . ' and datum >="' . $datum_danas_str . '" order by 2 desc';
                $rez = $dbc->selectDB($sql);
                dnevnik_zapis($korisnik, UPIT, $sql);
                if ($rez->num_rows > 0) {
                    echo '<table><thead><tr><th>Šifra ponude</th><th>Datum</th><th>Vrsta</th><th>Opcije</th></tr></thead><tbody>';
                    while ($red = $rez->fetch_assoc()) {
                        $datum = date('d.m.Y', strtotime($red["datum"]));
                        echo '<tr><td>' . $red["id_ponuda"] . '</td><td>' . $datum . '</td><td>' . $red["opis"] . '</td><td><a href="pregledajPonudu.php?id=' . $red["id_ponuda"] . '"> Pregledaj </a></td></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo 'Odabrana menza nema dnevnih ponuda u tijeku.';
                }

                echo '<h3>Prošle ponude</h3>';
                $sql2 = 'select id_ponuda, datum, opis from dnevna_ponuda where id_menza=' . $id_menza . ' and datum <"' . $datum_danas_str . '" order by 2 desc';
                $rez2 = $dbc->selectDB($sql2);
                dnevnik_zapis($korisnik, UPIT, $sql2);
                if ($rez2->num_rows > 0) {
                    echo '<table><thead><tr><th>Šifra ponude</th><th>Datum</th><th>Vrsta</th><th>Opcije</th></tr></thead><tbody>';
                    while ($red2 = $rez2->fetch_assoc()) {
                        $datum2 = date('d.m.Y', strtotime($red2["datum"]));
                        echo '<tr><td>' . $red2["id_ponuda"] . '</td><td>' . $datum2 . '</td><td>' . $red2["opis"] . '</td><td><a href="pregledajPonudu.php?id=' . $red2["id_ponuda"] . '"> Pregledaj </a></td></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo 'Odabrana menza nema prošlih dnevnih ponuda.';
                }
                ?>

                <p><a href="mojeMenze.php">Povratak na Moje menze</a></p>

            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>


