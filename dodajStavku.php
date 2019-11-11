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
} 
else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_meni = $_POST["id_meni"];
    $id_ponuda = $_POST["id_ponuda"];
    $kolicina = $_POST["kolicina"];
    
    if (!empty($id_meni) && !empty($id_ponuda)) {
        $sql = 'insert into stavke_ponude values ('.$id_ponuda.', '.$id_meni.', '.$kolicina.')';
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, DODAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: pregledajPonudu.php?id='.$id_ponuda);
        } 
    }
    
} else {
    header('Location: index.php');
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
                
                    <?php
                    $sql1 = 'select katalog.*, stavke_ponude.kolicina from katalog, stavke_ponude '
                            . 'where katalog.id_meni=stavke_ponude.id_meni and stavke_ponude.id_ponuda=' . $id_ponuda;
                    $rez1 = $dbc->selectDB($sql1);
                    dnevnik_zapis($korisnik, UPIT, $sql1);
                    if ($rez1->num_rows > 0) {
                        echo '<table>
                            <thead>
                                <tr>
                                    <th>Šifra menija</th>
                                    <th>Juha</th>
                                    <th>Glavno jelo</th>
                                    <th>Prilog</th>
                                    <th>Salata</th>
                                    <th>Desert</th>
                                    <th>Količina</th>';
                        echo '</tr></thead><tbody>';
                        while ($red1 = $rez1->fetch_assoc()) {
                            echo '</td><td>' . $red1["id_meni"] . '</td><td>' . $red1["juha"] . '</td><td>' . $red1["glavno_jelo"] .
                            '</td><td>' . $red1["prilog"] . '</td><td>' . $red1["salata"] . '</td><td>'. $red1["desert"] . '</td><td>' . $red1["kolicina"] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>Ova ponuda još nema stavki.</p>';
                    }
                    
                    ?>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <p>
                    <input type="number" name="id_ponuda" hidden="hidden" value="<?php echo $id_ponuda ?>">
                    <label for="meni">Novi meni: </label>
                    <?php
                        $sql3 = 'select distinct katalog.* from katalog where katalog.id_meni not in (select katalog.id_meni from katalog, stavke_ponude '
                            . 'where katalog.id_meni=stavke_ponude.id_meni and stavke_ponude.id_ponuda=' . $id_ponuda.')';
                        $rezultat3 = $dbc->selectDB($sql3);
                        dnevnik_zapis($korisnik, UPIT, $sql3);
                        if ($rezultat3->num_rows > 0) {
                            echo '<select id="id_meni" name="id_meni">';
                            while ($red3 = $rezultat3->fetch_assoc()) {
                                echo '<option value="' . $red3["id_meni"] . '">' . $red3["juha"] . ', ' . $red3["glavno_jelo"] . ', ' . $red3["prilog"].', ' . $red3["salata"].', ' . $red3["desert"].'</option>';
                            }
                            echo '</select><br>';
                            echo '<label for="kolicina">Unesi količinu: </label>';
                            echo '<input type="number" min="1" name="kolicina"><br>';
                            
                            echo '<p><input type="submit" value=" Dodaj "> ';
                            echo '<a href=pregledajPonudu.php?id=' . $id_ponuda . '><button type="button"> Odustani </button></a></p>';
                        } else
                            echo '<br>Nema novih menija za dodati.';
                        $dbc->zatvoriDB();

                        ?>
                    </p>

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


