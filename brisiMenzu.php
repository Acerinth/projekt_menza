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
    $sql = 'select naziv, adresa, broj_mjesta from menza where id_menza='.$id;
    $rezultat = $dbc->selectDB($sql);
    if ($rezultat) {
        dnevnik_zapis($korisnik, UPIT, $sql);
    }
    if ($rezultat->num_rows > 0) {
        $red = $rezultat->fetch_assoc();
        $naziv = $red["naziv"];
        $adresa = $red["adresa"];
        $broj = $red["broj_mjesta"];
    }
    else {
    $pogreska = "Pogreška kod upita na bazu.";    
    }
    $dbc->zatvoriDB();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST["id"];

    if (!empty($id)) {
        $sql = 'delete from menza WHERE id_menza= ' . $id;
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, BRISI, $sql);
            header('Location: panelMenze.php');
        } else {
            $pogreska = "Problem prilikom upita u bazu!";
        }
    }

    $dbc->zatvoriDB();
    
}

if (null == $id) {
    header("Location: panelMenze.php");
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Obriši menzu</title>
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
                <h2>Brisanje menze</h2>
                <p>Šifra menze: <?php echo $id ?></p>
                <form id="brisiMenzu" name="brisiMenzu" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <input type="number" id="id" name="id" value="<?php echo $id ?>" hidden="hidden">
                        Jeste li sigurni da želite obrisati?<br>
                        <button type="submit"> Da </button>
                        <a href="panelMenze.php"><button> Ne </button></a>
                        
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
