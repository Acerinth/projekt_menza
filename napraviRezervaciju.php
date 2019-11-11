<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(KORISNIK);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

$poruka = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kor_ime = $_POST["kor_ime"];
    $id_menza = $_POST["id_menza"];
    $datum = $_POST["datum"];
    $sat = $_POST["sat"];
    $id_meni = $_POST["id_meni"];
    $datum_puni = date('Y-m-d',$datum) . " " . $sat;
    $sql = 'insert into rezervacija (datum, kor_ime, id_menza, id_meni) values ("'.$datum_puni.'", "'.$kor_ime.'", '.$id_menza.', '.$id_meni.')';
    $rez = $dbc->selectDB($sql);
    if ($rez) {
        dnevnik_zapis($korisnik, AZURIRAJ, $sql);
        $poruka = "Uspjesno ste rezervirali meni.";
    }
    else {
        $poruka = "Pogreska prilikom rezervacije.<br>Moguci uzrok: već ste rezervirali obrok u nekoj menzi u to vrijeme.";
    }    
    $dbc->zatvoriDB();
    
}
else {
    header('Location: rezervirajMeni.php');
}

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Potvrda rezervacije</title>
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
                
                <p><?php echo $poruka ?></p>
                
                    
                <p><a href="rezervirajMeni.php">Povratak na odabir menze za rezervaciju</a></p>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>
