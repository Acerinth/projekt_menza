<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(ADMINISTRATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $kor_ime = $_POST["korime"];
    $ime = $_POST["ime"];
    $prezime = $_POST["prez"];
    $email = $_POST["email"];
    $spol = $_POST["spol"];
    $dan = $_POST["dan"];
    $mj = $_POST["mj"];
    $god = $_POST["god"];
    $loz = $_POST["lozinka1"];
    $adresa = $_POST["adresa"];
    $uloga = $_POST["uloga"];
    
    $datum = $god . "-" . $mj . "-" . $dan;
    $datum_reg = date('Y-m-d H:i:s', ucitajVrijeme());
    $sql2 = "insert into korisnik (kor_ime, ime, prezime, adresa, email, lozinka, datum_rodjenja, spol, id_uloga, datum_registracije, status_racuna) values " .
             "('$kor_ime', '$ime', '$prezime', '$adresa', '$email', '$loz', '$datum', '$spol', '$uloga','$datum_reg', 1)";
    $rs = $dbc->selectDB($sql2);
    if ($rs) {
            dnevnik_zapis($korisnik, DODAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: panelKorisnici.php');
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
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
        <script type="text/javascript" src="js/paukokic_jquery.js"></script>
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
                <h2>Novi korisnik</h2>

                <form id="registracija" method="post" name="dodajKorisnika" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <label for="ime">Ime: </label>
                        <input type="text" id="ime" name="ime" size="15" required="required" autofocus="autofocus"><br>
                        <label for="prez">Prezime: </label>
                        <input type="text" id="prez" name="prez" required="required" size="25"><br>
                        <label for="korime">Korisničko ime: </label>
                        <input type="text" id="korime" name="korime" size="15" required="required" onblur="provjeri_korime();"><br>
                        <label for="lozinka1">Lozinka: </label>
                        <input type="password" id="lozinka1" name="lozinka1" size="15" required="required" onblur="provjeri_lozinku()"><br>
                        <label for="lozinka2">Ponovi lozinku: </label>
                        <input type="password" id="lozinka2" name="lozinka2" size="15" required="required" onblur="provjeri_lozinku2()"><br>
                        Datum rođenja:<br>
                        <label for="dan">Dan: </label>
                        <input type="number" id="dan" name="dan" required="required" onblur="provjeri_dan()">
                        <label for="mj"> Mjesec: </label>
                        <select id="mj" name="mj">
                            <option value="01">Sijecanj</option>
                            <option value="02">Veljaca</option>
                            <option value="03">Ozujak</option>
                            <option value="04">Travanj</option>
                            <option value="05">Svibanj</option>
                            <option value="06">Lipanj</option>
                            <option value="07">Srpanj</option>
                            <option value="08">Kolovoz</option>
                            <option value="09">Rujan</option>
                            <option value="10">Listopad</option>
                            <option value="11">Studeni</option>
                            <option value="12">Prosinac</option>
                        </select>
                        <label for="god"> Godina: </label>
                        <input type="number" id="god" name="god" required="required" onblur="provjeri_godinu();"><br>
                        <label for="spol">Spol: </label>
                        <select id="spol" name="spol">
                            <option value="musko">Musko</option>
                            <option value="zensko">Zensko</option>
                        </select><br>
                        <label for="adresa">Adresa: </label>
                        <input type="text" id="adresa" name="adresa" size="50" required="required"><br>
                        <label for="email">Email adresa: </label>
                        <input type="text" id="email" name="email" size="35" required="required" onblur="provjeri_email()"><br>
                        <label for="uloga">Uloga: </label>
                        <select id="uloga" name="uloga">
                            <?php
                            $sql = 'select id_uloga, naziv from uloga';
                            $rez = $dbc->selectDB($sql);
                            if ($rez->num_rows > 0) {
                                while ($red = $rez->fetch_assoc()) {
                                    echo '<option value="'.$red["id_uloga"].'">'.$red["naziv"].'</option>';
                                }
                            }
                            
                            ?>
                        </select>
                    </p>
                    
                    
                    <p>
                        <input id="submit" type="submit" value=" Dodaj ">
                        
                    </p>
                </form>

                <p><a href="panelKorisnici.php">Povratak na Panel za korisnike</a></p>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>






