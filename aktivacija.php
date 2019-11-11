<?php
include_once('aplikacijskiOkvir.php');

if (!empty($_GET["id"]) && !empty($_GET["token"])) {
    $id = $_GET["id"];
    $token = $_GET["token"];
    $sql = 'select count(*) from korisnik where kor_ime="' . $id . '" and token="' . $token . '"';
    $rezultat = $dbc->selectDB($sql);
    $red = $rezultat->fetch_row();
    if ($red[0] == 1) {
        $sql3 = 'select datum_registracije from korisnik where kor_ime="' . $id . '"';
        $rezultat3 = $dbc->selectDB($sql3);
        $red2 = $rezultat3->fetch_row();
        $datum = date_create($red2[0]);
        $datum_sada = date_create(date('Y-m-d H:i:s',ucitajVrijeme()));
        $razlika = date_diff($datum_sada, $datum); 
        $broj = (int)$razlika->format('%h');
        if ($broj < 12) {
            $sql2 = 'update korisnik set aktiviran=1 where kor_ime="' . $id . '"';
            $rezultat2 = $dbc->selectDB($sql2);
            if ($rezultat2) {
                $poruka = 'Vaš račun je uspješno aktiviran.';
            } else
                $poruka = "Problem prilikom aktivacije računa.";
        }
        else {
            $poruka = "Vaš aktivacijski link je istekao.";
        }
    } else
        $poruka = "Problem prilikom aktivacije računa.";

    $dbc->zatvoriDB();
}

else {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Aktivacija</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">
    </head>
    <header>
        <h1 id="zaglavlje">Menze</h1>
    </header>
    <body>
        <div class="glavna">
            <section id="sadrzaj">
                <div class="tekst">
                    <p>
                        <?php
                        echo $poruka . '<br>';
                        ?>
                    </p>
                    <p><a href="prijava.php">Prijava</a></p> 
                    <p><a href="index.php">Početna stranica</a></p> 
                </div>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>

    </body>
</html>

