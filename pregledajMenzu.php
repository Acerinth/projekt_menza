<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(ADMINISTRATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

$naziv = $adresa = $broj = "";
$pogreska = "";

$id = null;
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'select naziv, adresa, broj_mjesta from menza where id_menza=' . $id;
    $rezultat = $dbc->selectDB($sql);
    if ($rezultat) {
        dnevnik_zapis($korisnik, UPIT, $sql);
        if ($rezultat->num_rows > 0) {
            $red = $rezultat->fetch_assoc();
            $naziv = $red["naziv"];
            $adresa = $red["adresa"];
            $broj = $red["broj_mjesta"];

            $sql2 = 'select korisnik.kor_ime, ime, prezime from korisnik, mod_menze where mod_menze.id_menza=' . $id . ' and korisnik.kor_ime=mod_menze.kor_ime';
            $rezultat2 = $dbc->selectDB($sql2);
            if ($rezultat2) {
                dnevnik_zapis($korisnik, UPIT, $sql2);
            }
        }
    } else {
        $pogreska = "Pogreška kod upita na bazu.";
    }
    $dbc->zatvoriDB();
}

if (null == $id) {
    header("Location: panelMenze.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id_menza"];
    $mod = $_POST["kor_ime"];
    if (!empty($id) && !empty($mod)) {
        $sql = 'delete from mod_menze WHERE id_menza= ' . $id . ' and kor_ime="' . $mod . '"';
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, BRISI, $sql);
            $dbc->zatvoriDB();
            header('Location: pregledajMenzu.php?id=' . $id);
        } else {
            $pogreska = "Problem prilikom upita u bazu!";
        }
    }

    
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Pregledaj menzu</title>
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
                        echo '<span id="korisnik">Dobrodošli, ' . $korisnik->get_ime_prezime() . '</span>';
                        echo '<a href="odjava.php">Odjava</a>';
                    ?>
                </span>
            </div>

            <section id="sadrzaj">
                <h2>Pregled menze</h2>

                <p>Šifra menze: <?php echo $id ?><br>
                    Naziv: <?php echo $naziv ?><br>
                    Adresa: <?php echo $adresa ?><br>
                    Broj mjesta: <?php echo $broj ?></p>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="number" name="id_menza" hidden="hidden" value="<?php echo $id ?>">
                    <p>Moderatori menze:</p>

                    <?php
                    if ($rezultat2->num_rows > 0) {
                        echo '<table class="posebnaTablica"><tbody>';
                        while ($red = $rezultat2->fetch_assoc()) {
                            echo '<tr><td>' . $red["ime"] . ' ' . $red["prezime"] . '</td><td width="20%"></td>';
                            echo '<td><a href="pregledajMenzu.php"> <button type="submit"> Obriši moderatora </button></a><input type="text" name="kor_ime" hidden="hidden" value="' . $red["kor_ime"] . '"></td></tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>Nema rezultata.</p>';
                    }
                    ?>
                    <br>
                    <p><a href="<?php echo 'dodajModeratora.php?id=' . $id ?>" ><button type="button"> Dodaj moderatora </button></a></p>

                    <span id="greska1"><?php echo $pogreska ?></span>

                </form>
                <p><a href="panelMenze.php">Povratak na Panel za menze</a></p>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>



