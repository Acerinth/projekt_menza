<?php
include_once('aplikacijskiOkvir.php');
$uri = $_SERVER["REQUEST_URI"];
$pos = strrpos($uri, "/");
$dir = $_SERVER["SERVER_NAME"] . substr($uri, 0, $pos + 1);

if (!isset($_SERVER["HTTPS"]) || strtolower($_SERVER["HTTPS"]) != "on") {
    $adresa = 'https://' . $dir . 'prijava.php';
    header("Location: $adresa");
    exit();
}

$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);

if (session_id() == '') {
    session_start();
}

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Prijava</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">
        <title></title>
    </head>
    <body>

        <?php
        $prijavaG = '';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $p_korime = $_POST["korime"];
            $p_lozinka = $_POST["lozinka"];

            if (!empty($p_korime) && !empty($p_lozinka)) {
                $provjera = 'select ime, prezime, id_uloga, status_racuna, aktiviran, uspjesna_prijava from korisnik where kor_ime="' . $p_korime . '" and lozinka="' . $p_lozinka . '"';
                $rs = $dbc->selectDB($provjera);
                if ($rs) {
                    dnevnik_zapis($korisnik, UPIT, $provjera);
                }
                if ($rs->num_rows == 1) {
                    $red = $rs->fetch_assoc();
                    $status_racuna = $red["status_racuna"];
                    $aktiviran = $red["aktiviran"];
                    if ($aktiviran) {
                        if ($status_racuna) {
                            $naziv = "WebDiP";
                            if ($_POST['upamti'] == 1) {
                                $vrijedi_do = ucitajVrijeme() + 200;
                                setcookie($naziv, $p_korime, $vrijedi_do);
                            } else {
                                unset($_COOKIE[$naziv]);
                                setcookie($naziv, '', ucitajVrijeme() - 3800);
                            }
                            $up = $red["uspjesna_prijava"];
                            $up_novo = $up + 1;
                            $sql = 'update korisnik set uspjesna_prijava=' . $up_novo . ' where kor_ime="' . $p_korime . '"';
                            $rs = $dbc->selectDB($sql);
                            if ($rs) {
                                dnevnik_zapis($korisnik, AZURIRAJ, $sql);
                            }
                            $vrsta = $red["id_uloga"];
                            $p_ime = $red["ime"];
                            $p_prezime = $red["prezime"];
                            $korisnik = new Korisnik($p_korime, $p_ime, $p_prezime, $p_lozinka, $vrsta);

                            $_SESSION["prijava"] = TRUE;
                            $_SESSION["korisnik"] = $korisnik;
                            dnevnik_zapis($korisnik, PRIJAVA);

                            $dbc->zatvoriDB();
                            header('Location: index.php');
                        } else {
                            $prijavaG = "Račun s upisanim korisničkim imenom je zaključan.";
                        }
                    } else {
                        $prijavaG = "Račun s upisanim korisničkim imenom nije aktiviran.";
                    }
                } else {
                    $sql1 = 'select neuspjesna_prijava from korisnik where kor_ime="' . $p_korime . '"';
                    $rs1 = $dbc->selectDB($sql1);
                    if ($rs1) {
                        dnevnik_zapis($korisnik, UPIT, $sql1);
                    }
                    if ($rs1->num_rows == 1) {
                        $prijavaG = "Pogrešna lozinka!";
                        $red = $rs1->fetch_assoc();
                        $np = $red["neuspjesna_prijava"];
                        $np_novo = $np + 1;
                        $sql2 = 'update korisnik set neuspjesna_prijava = ' . $np_novo . ' where kor_ime="' . $p_korime . '"';
                        $rs2 = $dbc->selectDB($sql2);
                        if ($rs2) {
                            dnevnik_zapis($korisnik, AZURIRAJ, $sql2);
                        }
                        
                        
                        if (!isset($_SESSION["pokusaj"]) && !isset($_SESSION["kor_ime"])) {
                            $_SESSION["pokusaj"] = 1;
                            $_SESSION["kor_ime"] = $p_korime;
                        }
                        else if ($_SESSION["kor_ime"] == $p_korime) {
                            $_SESSION["pokusaj"] ++;
                        } else {
                            $_SESSION["kor_ime"] = $p_korime;
                            $_SESSION["pokusaj"] = 1;
                        }

                        if ($_SESSION["pokusaj"] == 4) {
                            $sql3 = 'update korisnik set status_racuna=0 where kor_ime="'.$p_korime.'"';
                            $rez3 = $dbc->selectDB($sql3);
                            if ($rez3) {
                                dnevnik_zapis($korisnik, AZURIRAJ, $sql3);
                                $prijavaG = "Pogriješili ste lozinku 4 puta. Vaš račun je sada zaključan.";
                            }
                        }
                    } else {
                        $prijavaG = "Ne postoji korisnik s upisanim korisničkim imenom!";
                    }
                }

                $dbc->zatvoriDB();
            } else {
                $prijavaG = "Korisničko ime i lozinka moraju biti upisani!";
            }
        }
        ?>
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
                    <a href="prijava.php">Prijava</a>
                    <a href="registracija.php">Registracija</a>';
                </span>
            </div>

            <section id="sadrzaj">
                <h2>Prijava</h2>
                <form id="prijava" method="post" name="prijava" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <label for="korime">Korisničko ime: </label>
                        <input type="text" id="korime" name="korime" size="15" placeholder="korisničko ime" autofocus="autofocus"><br>
                        <label for="lozinka">Lozinka: </label>
                        <input type="password" id="lozinka" name="lozinka" size="15" placeholder="lozinka"><br>
                        <input type="checkbox" name="upamti" value="1" checked="checked">Zapamti me<br>
                        <input type="submit" value=" Prijavi se ">
                    </p>
                    <p>
                        <a href="zaboravljenaLozinka.php">Zaboravili ste lozinku?</a>
                    </p>
                    <div id="greska1"><p><?php echo $prijavaG ?></p></div>
                </form>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>

