<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

if (!empty($_GET["id"])) {
    $id_ponuda = $_GET["id"];

    $sql = 'select datum, opis, menza.naziv, menza.id_menza from dnevna_ponuda, menza where dnevna_ponuda.id_menza = menza.id_menza and dnevna_ponuda.id_ponuda=' . $id_ponuda;
    $rez = $dbc->selectDB($sql);
    $red = $rez->fetch_assoc();
    $datum = $red["datum"];
    $opis = $red["opis"];
    $naziv = $red["naziv"];
    $id_menza = $red["id_menza"];
    
} else {
    header('Location: index.php');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_meni = $_POST["id_meni"];
    $id_ponuda = $_POST["id_ponuda"];
    if (!empty($id_meni) && !empty($id_ponuda)) {
        $sql = 'delete from stavke_ponude WHERE id_ponuda= ' . $id_ponuda . ' and id_meni=' . $id_meni;
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, BRISI, $sql);
            $dbc->zatvoriDB();
            header('Location: pregledajPonudu.php?id=' . $id_ponuda);
        } else {
            $pogreska = "Problem prilikom upita u bazu!";
        }
    }
    
}

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Pregledaj ponudu</title>
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
                <h2>Pregled ponude</h2>
                <p>Odabrana menza: <?php echo $naziv ?><br>
                    Šifra ponude: <?php echo $id_ponuda ?><br>
                    Datum: <?php echo date('d.m.Y.', strtotime($datum)) ?><br>
                    Vrsta ponude: <?php echo $opis ?></p>      
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="number" name="id_ponuda" hidden="hidden" value="<?php echo $id_ponuda ?>">
                    <?php
                    $datum_danas = ucitajVrijeme();
                    $datum_danas_str = date('Y-m-d', $datum_danas);
                    $sql1 = 'select katalog.*, stavke_ponude.kolicina from katalog, stavke_ponude '
                            . 'where katalog.id_meni=stavke_ponude.id_meni and stavke_ponude.id_ponuda=' . $id_ponuda;
                    $rez1 = $dbc->selectDB($sql1);
                    dnevnik_zapis($korisnik, UPIT, $sql1);
                    if ($rez1->num_rows > 0) {
                        echo '<table class="posebnaTablica">
                            <thead>
                                <tr>
                                    <th>Šifra menija</th>
                                    <th>Juha</th>
                                    <th>Glavno jelo</th>
                                    <th>Prilog</th>
                                    <th>Salata</th>
                                    <th>Desert</th>
                                    <th>Količina</th>';
                        if ($datum >= $datum_danas_str) {
                            echo '<th width="20%"></th><th>Opcije</th>';
                        }
                        echo '</tr></thead><tbody>';
                        while ($red1 = $rez1->fetch_assoc()) {
                            echo '<td>' . $red1["id_meni"] . '</td><td>' . $red1["juha"] . '</td><td>' . $red1["glavno_jelo"] .
                            '</td><td>' . $red1["prilog"] . '</td><td>' . $red1["salata"] .'</td><td>' . $red1["desert"] . '</td><td>' . $red1["kolicina"] . '</td>';
                            if ($datum >= $datum_danas_str) {
                                echo '<td width="20%"></td><td><button type="submit"> Obriši meni </button><input type="text" name="id_meni" hidden="hidden" value="' . $red1["id_meni"] . '"></td>';
                            }
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>Ova ponuda još nema stavki.</p>';
                    }
                    
                    if ($datum >= $datum_danas_str) {
                        echo '<p><a href="dodajStavku.php?id=' . $id_ponuda . '"><button type="button"> Dodaj meni </button></a></p>';
                    }
                    
                    $dbc->zatvoriDB();
                    ?>
                </form>
                    <p><a href="ponude.php?id=<?php echo $id_menza ?>">Povratak na Popis ponuda</a></p>

            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>
