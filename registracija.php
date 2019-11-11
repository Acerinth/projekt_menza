<?php
include_once('aplikacijskiOkvir.php');

$uri = $_SERVER["REQUEST_URI"];
$pos = strrpos($uri, "/");
$dir = $_SERVER["SERVER_NAME"] . substr($uri, 0, $pos + 1);

if (!isset($_SERVER["HTTPS"]) || strtolower($_SERVER["HTTPS"]) != "on") {
    $adresa = 'https://' . $dir . 'registracija.php';
    header("Location: $adresa");
    exit();
}
$korisnik = provjeraPrijave();
dnevnik_zapis($korisnik, POSJET);

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Registracija</title>
        <meta charset="UTF-8">
        <meta name="description" content="Web aplikacija koja omogućuje studentima rezervaciju menija u menzi.">
        <meta name="author" content="Paula Kokic">
        <link href="css/paukokic.css" rel="stylesheet" type="text/css">

        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
        <script type="text/javascript" src="js/paukokic_jquery.js"></script>
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
                    <a href="prijava.php">Prijava</a>
                    <a href="registracija.php">Registracija</a>';
                </span>
            </div>

            <section id="sadrzaj">
                <?php
                $ime = $prez = $korime = $loz = $loz2 = $dan = $mj = $god = $mail = $ad = "";
                $imeG = $prezG = $korimeG = $lozG = $loz2G = $danG = $mjG = $godG = $mailG = $adG = "";
                $regG = $capG = '';

                // ----------------------- VALIDACIJA UNESENIH PODATAKA -------------------------------------------

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (empty($_POST["ime"])) {
                        $imeG = "Ime mora biti ispunjeno!";
                    }

                    if (empty($_POST["prez"])) {
                        $prezG = "Prezime mora biti ispunjeno!";
                    }

                    if (empty($_POST["adresa"])) {
                        $adG = "Adresa mora biti ispunjena!";
                    }

                    if (empty($_POST["korime"])) {
                        $korimeG = "Korisničko ime mora biti ispunjeno!";
                    } else {
                        $korime = str_split($_POST["korime"]);
                        if (strlen($_POST["korime"]) < 6 || strlen($_POST["korime"])>16) {
                            $korimeG = "Korisničko ime mora imati najmanje 6 i najviše 16 znakova!";
                        }
                    }

                    if (empty($_POST["lozinka1"])) {
                        $lozG = "Lozinika mora biti ispunjena!";
                    } else {
                        $loz = str_split($_POST["lozinka1"]);
                        $b1 = $b2 = $b3 = $b4 = 0;
                        $posebna2 = ["!", "#", "$", "?"];
                        foreach ($loz as $slovo) {
                            if ($slovo >= 'a' && $slovo <= 'z') {
                                $b1++;
                            } else if ($slovo >= 'A' && $slovo <= 'Z') {
                                $b2++;
                            } else if ($slovo >= '0' && $slovo <= '9') {
                                $b3++;
                            }
                            foreach ($posebna2 as $znak) {
                                if ($slovo === $znak) {
                                    $b4++;
                                }
                            }
                        }
                        if (strlen($_POST["lozinka1"]) < 8) {
                            $lozG = "Lozinka mora imati najmanje 8 znakova!";
                        } else if ($b1 === 0) {
                            $lozG = "Lozinka mora imati barem jedno malo slovo!";
                        } else if ($b2 === 0) {
                            $lozG = "Lozinka mora imati barem jedno veliko slovo!";
                        } else if ($b3 === 0) {
                            $lozG = "Lozinka mora imati barem jedan broj!";
                        } else if ($b4 === 0) {
                            $lozG = "Lozinka mora imati barem jedan poseban znak!";
                        }
                    }

                    if (empty($_POST["lozinka2"])) {
                        $loz2G = "Ponovljena lozinka mora biti ispunjena!";
                    } else {
                        if ($_POST["lozinka1"] !== $_POST["lozinka2"]) {
                            $loz2G = "Ponovljena lozinka ne odgovara upisanoj lozinki!";
                        }
                    }

                    if (empty($_POST["dan"])) {
                        $danG = "Dan mora biti ispunjen!";
                    } else {
                        $dan = $_POST["dan"];
                        if (!ctype_digit($dan)) {
                            $danG = "Dan mora biti broj!";
                        } else if ($dan < 1 || $dan > 31) {
                            $danG = "Dan mora biti u rasponu 1-31!";
                        }
                    }

                    if (!isset($_POST["mj"])) {
                        $mjG = "Mjesec mora biti ispunjen!";
                    } else {
                        $mj = $_POST["mj"];
                        if ($mj < 1 || $mj > 12) {
                            $mjG = "Nepravilan unos mjeseca!";
                        }
                    }

                    if (empty($_POST["god"])) {
                        $godG = "Godina mora biti ispunjena!";
                    } else {
                        $god = $_POST["god"];
                        if ($god < 1930 || $god > 2015) {
                            $godG = "Godina mora biti u rasponu 1930-2015!";
                        }
                    }

                    if (empty($_POST["email"])) {
                        $mailG = "Email mora biti ispunjen!";
                    } else {
                        $mail = $_POST["email"];
                        if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+$/", $mail)) {
                            $mailG = "Email mora biti oblika nesto@nesto.nesto!";
                        }
                    }
                    
                    $captcha = $_POST["g-recaptcha-response"];
                    if (empty($captcha)) {
                        $capG = "Niste riješili captcha zahtjev.";
                    }

                    //--------------------------------------- BAZA -----------------------------------------------------

                    $korime = $_POST["korime"];
                    $ime = $_POST["ime"];
                    $prez = $_POST["prez"];
                    $loz = $_POST["lozinka1"];
                    $spol = $_POST["spol"];
                    $ad = $_POST["adresa"];

                    if (!empty($korime) && !empty($mail) && !empty($captcha)) {

                        $sql = "select kor_ime, email from korisnik";
                        $rs = $dbc->selectDB($sql);
                        $nadjen = FALSE;

                        while ($red = $rs->fetch_row()) {
                            if ($red[0] === $korime) {
                                $korimeG = "Korisničko ime već postoji!";
                                $nadjen = TRUE;
                            }
                            if ($red[1] == $mail) {
                                $mailG = "E-mail već postoji!";
                                $nadjen = TRUE;
                            }
                        }

                        if (!$nadjen) {
                            $datum = $god . "-" . $mj . "-" . $dan;
                            $datum_reg = date('Y-m-d H:i:s', ucitajVrijeme());
                            $token = md5(rand(0, 1000));
                            $sql2 = "insert into korisnik (kor_ime, ime, prezime, adresa, email, lozinka, datum_rodjenja, spol, id_uloga, datum_registracije, status_racuna, token) values " .
                                    "('$korime', '$ime', '$prez', '$ad', '$mail', '$loz', '$datum', '$spol', 1,'$datum_reg', 1, '$token')";
                            $rs = $dbc->selectDB($sql2);
                            if ($rs) {
                                dnevnik_zapis($korisnik, DODAJ, $sql2);
                                $mail_to = $mail;
                                $mail_from = "From: WebDiP2015x039@foi.hr";
                                $mail_subject = "Aktivacijski link";
                                $link = 'http://'.$dir.'aktivacija.php?id='.$korime.'&token='.$token;
                                $mail_body = 'Za aktivaciju korisnickog racuna kliknite na sljedeci link: '.$link;

                                if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                                    header('Location: registracijaUspjeh.php');
                                } else {
                                    header('Location: error.php?e=p3');
                                }
                                
                            } else {
                                $regG = "Problem prilikom registracije u bazu!";
                            }
                        }


                        $dbc->zatvoriDB();
                    }
                }
                ?>

                <h2>Registracija</h2>
                <p>Korisničko ime može sadržavati mala slova, velika slova, brojeve te znakove _ i -. Mora biti dugačko 6-16 znakova.</p>
                <p>Lozinka mora imati minimalno 8 znakova, sadržavati po 1 malo slovo, veliko slovo i posebni znak (!, ?, #, $).</p>
                <form id="registracija" method="post" name="registracija" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>
                        <label for="ime">Ime: </label>
                        <input type="text" id="ime" name="ime" size="15" autofocus="autofocus"><span class="greska1"><?php echo $imeG; ?></span><br>
                        <label for="prez">Prezime: </label>
                        <input type="text" id="prez" name="prez" size="25"><span class="greska1"><?php echo $prezG; ?></span><br>
                        <label for="korime">Korisničko ime: </label>
                        <input type="text" id="korime" name="korime" size="15" onblur="provjeri_korime();"><span class="greska1" id="korimeG"><?php echo $korimeG; ?></span><br>
                        <label for="lozinka1">Lozinka: </label>
                        <input type="password" id="lozinka1" name="lozinka1" size="15" onblur="provjeri_lozinku()"><span class="greska1" id="lozG"><?php echo $lozG; ?></span><br>
                        <label for="lozinka2">Ponovi lozinku: </label>
                        <input type="password" id="lozinka2" name="lozinka2" size="15" onblur="provjeri_lozinku2()"><span class="greska1" id="loz2G"><?php echo $loz2G; ?></span><br>
                        Datum rođenja:<br>
                        <label for="dan">Dan: </label>
                        <input type="number" id="dan" name="dan" onblur="provjeri_dan()">
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
                        <input type="number" id="god" name="god" onblur="provjeri_godinu();"><span class="greska1">
                            <br><?php echo $danG . " " . $mjG . " " . $godG; ?></span><br>
                        <label for="spol">Spol: </label>
                        <select id="spol" name="spol">
                            <option value="musko">Musko</option>
                            <option value="zensko">Zensko</option>
                        </select><br>
                        <label for="adresa">Adresa: </label>
                        <input type="text" id="adresa" name="adresa" size="50" placeholder="Ive Ivica 9, 10000 Zagreb"><span class="greska1" id="adG"><?php echo $adG; ?></span><br>
                        <label for="email">Email adresa: </label>
                        <input type="text" id="email" name="email" size="35" onblur="provjeri_email()"><span class="greska1"><?php echo $mailG; ?></span><br>
                    </p>
                    <div class="g-recaptcha" data-sitekey="6Lci0B4TAAAAAHUc-kumxo8j_tcK7__9mx4bkuVg"></div><span class="greska1"><?php echo $capG; ?></span><br>
                    <span class="greska1"><?php echo $regG; ?></span><br>
                    <p>
                        <input id="submit" type="submit" value=" Registriraj se ">
                        <input id="reset" type="reset" value=" Inicijaliziraj ">
                    </p>
                </form>

            </section> 



            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>


