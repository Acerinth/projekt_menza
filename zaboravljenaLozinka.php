<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);

$poruka = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $sql = 'select count(*) email from korisnik where email="' . $email . '"';
    $rez = $dbc->selectDB($sql);
    if ($rez) {
        $red = $rez->fetch_row();
        if ($red[0]==1) {
            $mail_to = $email;
            $mail_from = "From: WebDiP2015x039@foi.hr";
            $mail_subject = "Nova lozinka";
            $lozinka = randomPassword(8);
            $mail_body = 'Vas email: ' . $email . PHP_EOL. 'Lozinka: ' . $lozinka;
            $sql2 = 'update korisnik set lozinka="'.$lozinka.'" where email="'.$email.'"';
            $rez2 = $dbc->selectDB($sql2);

            if (mail($mail_to, $mail_subject, $mail_body, $mail_from) && $rez2) {
                $poruka = "Lozinka je uspješno generirana i poslana na Vaš mail.";
            } else {
                header('Location: error.php?e=p3');
            }
        } else {
            $poruka = "Navedeni e-mail ne postoji u bazi podataka.";
        }
    }
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Zaboravljena lozinka</title>
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
                <h2>Zaboravljena lozinka</h2>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <label for="email">Unesite e-mail s kojim ste se registrirali: </label>
                        <input type="email" name="email" size="35"><br>
                    </p>
                    <p><input id="submit" type="submit" value=" Pošalji novu lozinku "></p>
                    <span class="greska1"><?php echo $poruka; ?></span>
                </form>               


            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>

