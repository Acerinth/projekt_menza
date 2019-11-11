<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $glavno_jelo = $_POST["glavno_jelo"];
    $prilog = $_POST["prilog"];
    $salata = $_POST["salata"];
    $juha = $_POST["juha"];
    $desert = $_POST["desert"];

    if (!empty($glavno_jelo) && !empty($prilog)) {
        $sql = 'insert into katalog values (default, "'.$juha.'", "'.$glavno_jelo.'", "'.$prilog.'", "'.$salata.'", "'.$desert.'")';
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, DODAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: katalog.php');
        }
    }

}




?>

<!DOCTYPE html>

<html>
    <head>
        <title>Novi meni</title>
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

                <h2>Novi meni</h2>

                <form id="dodajMeni" name="dodajMeni" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <label for="juha">Juha: </label>
                        <input type="text" name="juha" size="50" maxlength="100" autofocus="autofocus"><br>
                        <label for="naziv">Glavno jelo: </label>
                        <input type="text" name="glavno_jelo" size="50" maxlength="100" required="required"><br>
                        <label for="adresa">Prilog: </label>
                        <input type="text" name="prilog" size="50" maxlength="100" required="required"><br>
                        <label for="broj">Salata: </label>
                        <input type="text" name="salata" size="50" maxlength="100"><br>
                        <label for="broj">Desert: </label>
                        <input type="text" name="desert" size="50" maxlength="100"><br><br>
                        <input id="submit" type="submit" value=" Dodaj ">
                    </p>
                </form>
                <p><a href="katalog.php">Povratak Katalog</a></p>

            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>

