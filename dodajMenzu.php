<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(ADMINISTRATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

$naziv = $adresa = $broj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $naziv = $_POST["naziv"];
    $adresa = $_POST["adresa"];
    $broj = $_POST["broj"];

    if (!empty($naziv)) {
        $sql = "insert into menza (naziv, adresa, broj_mjesta) values " .
                "('$naziv', '$adresa', '$broj')";
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, DODAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: panelMenze.php');
        }
    }

}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Dodaj menzu</title>
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
                <h2>Nova menza</h2>

                <form id="dodajMenzu" name="dodajMenzu" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <label for="naziv">Naziv: </label>
                        <input type="text" id="naziv" name="naziv" size="50" maxlength="45" autofocus="autofocus" required="required"><br>
                        <label for="adresa">Adresa: </label>
                        <input type="text" id="adresa" name="adresa" size="70" maxlength="100"><br>
                        <label for="broj">Broj mjesta: </label>
                        <input type="number" id="broj" name="broj" size="10"><br>
                        <input id="submit" type="submit" value=" Dodaj ">
                    </p>
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




