<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(KORISNIK);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

$jelNaListi = FALSE;
$poruka = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_menza = $_POST["id_menza"];
    $opis = $_POST["opis"];
    $datum_danas_str = $_POST["datum"];
    $datum_danas = strtotime($datum_danas_str);
    $kor_ime = $korisnik->get_kor_ime();
    $sqlP = 'select datum_poc from crna_lista where kor_ime="' . $kor_ime . '" and id_menza=' . $id_menza;
    $rs = $dbc->selectDB($sqlP);
    if ($rs) {
        dnevnik_zapis($korisnik, UPIT, $sqlP);
        if ($rs->num_rows > 0) {
            while ($red = $rs->fetch_row()) {
                $datum_ucitani = DateTime::createFromFormat('Y-m-d H:i:s', $red[0]);
                $datum_ucitani->modify('+1 week');
                if (strtotime($datum_ucitani->format('Y-m-d H:i:s')) > $datum_danas) {
                    $jelNaListi = TRUE;
                    $poruka = "Nalaziš se na crnoj listi na ovoj menzi do: " . $datum_ucitani->format('d.m.Y H:i:s') . '<br>';
                    break;
                } else {
                    $jelNaListi = FALSE;
                }
            }
        } else {
            $jelNaListi = FALSE;
        }
    }
} else {
    header('Location: rezervirajMeni.php');
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Dnevna ponuda</title>
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
                <h2>Rezerviraj meni u menzi</h2>
                <?php
                $sqlM = 'select naziv from menza where id_menza =' . $id_menza;
                dnevnik_zapis($korisnik, UPIT, $sqlM);
                $rez = $dbc->selectDB($sqlM);
                $red = $rez->fetch_row();
                $naziv = $red[0];
                ?>
                <p>
                    Odabrana menza: <?php echo $naziv ?><br>
                    Odabrani datum: <?php echo date('d.m.Y', $datum_danas) ?><br>
                    Odabrana vrsta ponude: <?php echo $opis ?><br>
                </p>

                <form class="formaTablica" action="napraviRezervaciju.php" method="post">
                    <label for="sat">Odaberi vrijeme dolaska: </label>
                    <select name="sat">
                        <?php
                        if ($opis == "ručak") {
                            for ($i = 11; $i <= 16; $i++) {
                                echo '<option value="' . $i . ':00:00">' . $i . ':00</option>';
                                echo '<option value="' . $i . ':30:00">' . $i . ':30</option>';
                            }
                        } else {
                            for ($i = 17; $i <= 21; $i++) {
                                echo '<option value="' . $i . ':00:00">' . $i . ':00</option>';
                                echo '<option value="' . $i . ':30:00">' . $i . ':30</option>';
                            }
                        }
                        ?>
                    </select>
                    <br><br>
                    <input type="number" name="id_menza" value="<?php echo $id_menza ?>" hidden="hidden">
                    <input type="text" name="kor_ime" value="<?php echo $kor_ime ?>" hidden="hiddnen">
                    <input type="number" name="datum" value="<?php echo $datum_danas ?>" hidden="hidden">
                    <?php
                    if ($jelNaListi) {
                        echo '<p>' . $poruka . '</p>';
                    } else {
                        $sqlP2 = 'select id_ponuda from dnevna_ponuda where datum="' . $datum_danas_str . '" and id_menza=' . $id_menza . ' and opis="' . $opis . '"';
                        $rs2 = $dbc->selectDB($sqlP2);
                        dnevnik_zapis($korisnik, UPIT, $sqlP2);
                        if ($rs2->num_rows > 0) {
                            while ($red2 = $rs2->fetch_row()) {
                                $sql = 'select katalog.*, stavke_ponude.kolicina
                            from katalog, stavke_ponude
                            where katalog.id_meni = stavke_ponude.id_meni
                            and stavke_ponude.id_ponuda=' . $red2[0];
                                $rez2 = $dbc->selectDB($sql);
                                dnevnik_zapis($korisnik, UPIT, $sql);
                                if ($rez2->num_rows > 0) {
                                    echo '<table>
                                            <thead>
                                            <tr>
                                            <th>Šifra menija</th>
                                            <th>Juha</th>
                                            <th>Glavno jelo</th>
                                            <th>Prilog</th>
                                            <th>Desert</th>
                                            <th>Količina</th>
                                            <th>Opcije</th>
                                            </tr>
                                            </thead>';
                                    echo '<tbody>';

                                    while ($red3 = $rez2->fetch_assoc()) {
                                        echo '<tr><td>' . $red3["id_meni"] . '</td><td>' . $red3["juha"] . '</td><td>' . $red3["glavno_jelo"] .
                                        '</td><td>' . $red3["prilog"] . '</td><td>' . $red3["desert"] . '</td><td>' . $red3["kolicina"] . '</td><td>';

                                        if ($red3["kolicina"] > 0) {
                                            echo '<button type="submit" name="id_meni" value="' . $red3["id_meni"] . '"> Rezerviraj </button></td></tr>';
                                        } else {
                                            echo 'Nije moguće rezervirati</td></tr>';
                                        }
                                    }
                                    echo '</tbody></table>';
                                }
                                else {
                                    echo 'U tijeku je ažuriranje ove dnevne ponude. Molimo Vas pokušajte kasnije.';
                                }
                            }
                            
                        } else {
                            echo 'Nije definirana dnevna ponuda za odabranu menzu. Pokušajte s drugim datumom ili vrstom ponude.';
                        }
                    }

                    $dbc->zatvoriDB();
                    ?>

                </form>

                <p><a href="rezervirajMeni.php">Povratak na odabir menze</a></p>
            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>




