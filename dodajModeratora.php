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

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'select naziv, adresa, broj_mjesta from menza where id_menza=' . $id;
    $rezultat = $dbc->selectDB($sql);
    if ($rezultat) {
        dnevnik_zapis($korisnik, UPIT, $sql);
    }
    if ($rezultat->num_rows > 0) {
        $red = $rezultat->fetch_assoc();
        $naziv = $red["naziv"];
        $adresa = $red["adresa"];
        $broj = $red["broj_mjesta"];

        $sql2 = 'select ime, prezime from korisnik, mod_menze where mod_menze.id_menza=' . $id . ' and korisnik.kor_ime=mod_menze.kor_ime';
        $rezultat2 = $dbc->selectDB($sql2);
        if ($rezultat2) {
            dnevnik_zapis($korisnik, UPIT, $sql2);
        }
    } else {
        $pogreska = "Pogreška kod upita na bazu.";
    }
    $dbc->zatvoriDB();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id_menza"];
    $kor_ime = $_POST["mod"];
    $datum = date('Y-m-d H:i:s', ucitajVrijeme());

    if (!empty($id) && !empty($kor_ime)) {
        $sql = "insert into mod_menze values " .
                "('$kor_ime', '$id', '$datum')";
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, DODAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: pregledajMenzu.php?id=' . $id);
        } else {
            $pogreska = "Greška tijekom unosa u bazu.";
        }
    } else {
        header("Location: panelMenze.php");
    }
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Dodaj moderatora</title>
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

                <p>Moderatori menze:</p>

                <?php
                if ($rezultat2->num_rows > 0) {
                    while ($red = $rezultat2->fetch_assoc()) {
                        echo $red["ime"] . ' ' . $red["prezime"] . '<br>';
                    }
                } else {
                    echo 'Nema rezultata.';
                }
                ?>
                <br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <input name="id_menza" type="number" hidden="hidden" value="<?php echo $id ?>">
                        <label for="mod">Novi moderator: </label>

                        <?php
                        $dbc->spojiDB();
                        $sql3 = 'select distinct korisnik.kor_ime, ime, prezime from korisnik where korisnik.id_uloga>1 and korisnik.kor_ime not in (select korisnik.kor_ime from korisnik, mod_menze where mod_menze.id_menza=' . $id . ' and korisnik.kor_ime=mod_menze.kor_ime)';
                        $rezultat3 = $dbc->selectDB($sql3);
                        dnevnik_zapis($korisnik, UPIT, $sql3);
                        if ($rezultat3->num_rows > 0) {
                            echo '<select id="mod" name="mod">';
                            while ($red3 = $rezultat3->fetch_assoc()) {
                                echo '<option value="' . $red3["kor_ime"] . '">' . $red3["ime"] . ' ' . $red3["prezime"] . '</option>';
                            }
                            echo '</select>';
                            echo '<p><input type="submit" value=" Dodaj "> ';
                            echo '<a href=pregledajMenzu.php?id=' . $id . '><button type="button"> Odustani </button></a></p>';
                        } else
                            echo '<br>Nema novih moderatora za dodati.';
                        $dbc->zatvoriDB();
                        ?>
                    </p>

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

