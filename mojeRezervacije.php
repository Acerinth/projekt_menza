<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(KORISNIK);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

$kor_ime = $korisnik->get_kor_ime();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_menza"])) {
        $id = $_POST["id_menza"];
        $sql1 = 'select count(*) from ocjene_menza where kor_ime="' . $kor_ime . '" and id_menza=' . $id;
        $rez1 = $dbc->selectDB($sql1);
        $red1 = $rez1->fetch_row();

        if (!empty($_POST["svidja_se"])) {
            if ($red1[0]) {
                $sql = 'update ocjene_menza set svidja_se="1", ne_svidja_se="0" where kor_ime="' . $kor_ime . '" and id_menza=' . $id;
            } else {
                $sql = 'insert into ocjene_menza values ("' . $kor_ime . '", ' . $id . ', 1, 0)';
            }
        } else if (!empty($_POST["ne_svidja_se"])) {
            if ($red1[0]) {
                $sql = 'update ocjene_menza set svidja_se="0", ne_svidja_se="1" where kor_ime="' . $kor_ime . '" and id_menza=' . $id;
            } else {
                $sql = 'insert into ocjene_menza values ("' . $kor_ime . '", ' . $id . ', 0, 1)';
            }
        } else if (!empty($_POST["ukloni"])) {
            $sql = 'update ocjene_menza set svidja_se="0", ne_svidja_se="0" where kor_ime="' . $kor_ime . '" and id_menza=' . $id;
        }
        $rez = $dbc->selectDB($sql);
        if ($rez) {
            if ($red1[0]) {
                dnevnik_zapis($korisnik, AZURIRAJ, $sql);
            } else {
                dnevnik_zapis($korisnik, DODAJ, $sql);
            }
        }
    } else if (isset($_POST["id_meni"])) {
        $id = $_POST["id_meni"];
        $sql1 = 'select count(*) from ocjene_meni where kor_ime="' . $kor_ime . '" and id_meni=' . $id;
        $rez1 = $dbc->selectDB($sql1);
        $red1 = $rez1->fetch_row();

        if (!empty($_POST["svidja_se_meni"])) {
            if ($red1[0]) {
                $sql = 'update ocjene_meni set svidja_se="1", ne_svidja_se="0" where kor_ime="' . $kor_ime . '" and id_meni=' . $id;
            } else {
                $sql = 'insert into ocjene_meni values ("' . $kor_ime . '", ' . $id . ', 1, 0)';
            }
        } else if (!empty($_POST["ne_svidja_se_meni"])) {
            if ($red1[0]) {
                $sql = 'update ocjene_meni set svidja_se="0", ne_svidja_se="1" where kor_ime="' . $kor_ime . '" and id_meni=' . $id;
            } else {
                $sql = 'insert into ocjene_meni values ("' . $kor_ime . '", ' . $id . ', 0, 1)';
            }
        } else if (!empty($_POST["ukloni_meni"])) {
            $sql = 'update ocjene_meni set svidja_se="0", ne_svidja_se="0" where kor_ime="' . $kor_ime . '" and id_meni=' . $id;
        }
        $rez = $dbc->selectDB($sql);
        if ($rez) {
            if ($red1[0]) {
                dnevnik_zapis($korisnik, AZURIRAJ, $sql);
            } else {
                dnevnik_zapis($korisnik, DODAJ, $sql);
            }
        }
    }
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Moje rezervacije</title>
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

                <h2>Moje rezervacije</h2>
                <p>Popis rezervacija prema menzama</p>

                <?php
                $datum_danas = ucitajVrijeme();
                $sql = 'select distinct rezervacija.id_menza, menza.naziv from rezervacija, menza'
                        . ' where rezervacija.kor_ime="' . $kor_ime . '" and menza.id_menza=rezervacija.id_menza';
                $rez = $dbc->selectDB($sql);
                if ($rez->num_rows > 0) {
                    while ($red = $rez->fetch_assoc()) {
                        $id_menza = $red["id_menza"];
                        $naziv = $red["naziv"];
                        echo '<h3>' . $naziv . '</h3>';

                        $sql4 = 'select svidja_se, ne_svidja_se from ocjene_menza where kor_ime="' . $kor_ime . '" and id_menza=' . $id_menza;
                        $rez4 = $dbc->selectDB($sql4);
                        $red4 = $rez4->fetch_assoc();
                        $svidja_se = $red4["svidja_se"];
                        $ne_svidja_se = $red4["ne_svidja_se"];

                        echo '<form class="formaTablica" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        echo '<input type="number" value="' . $id_menza . '" name="id_menza" hidden="hidden">';
                        if ($svidja_se) {
                            echo 'Vaša trenutna ocjena menze: Sviđa mi se :)<br>';
                            echo 'Promijeni mišljenje: <button type="submit" value="1" name="ne_svidja_se"> Ne sviđa mi se </button> ';
                            echo '<button type="submit" value="1" name="ukloni"> Ukloni ocjenu </button>';
                        } else if ($ne_svidja_se) {
                            echo 'Vaša trenutna ocjena menze: Ne sviđa mi se :( <br>';
                            echo 'Promijeni mišljenje: <button type="submit" value="1" name="svidja_se"> Sviđa mi se </button> ';
                            echo '<button type="submit" value="1" name="ukloni"> Ukloni ocjenu </button>';
                        } else {
                            echo 'Još niste ocijenili ovu menzu.<br>';
                            echo 'Daj svoje mišljenje: <button type="submit" value="1" name="svidja_se"> Sviđa mi se </button> ';
                            echo '<button type="submit" value="1" name="ne_svidja_se"> Ne sviđa mi se </button>';
                        }
                        echo '</form>';

                        $sql3 = 'select datum_poc from crna_lista where id_menza=' . $id_menza . ' and kor_ime="' . $kor_ime . '"';
                        $rez3 = $dbc->selectDB($sql3);
                        if ($rez3->num_rows > 0) {
                            while ($red3 = $rez3->fetch_row()) {
                                $datum_ucitani = DateTime::createFromFormat('Y-m-d H:i:s', $red3[0]);
                                $datum_ucitani->modify('+1 week');
                                if (strtotime($datum_ucitani->format('Y-m-d H:i:s')) > $datum_danas) {
                                    echo 'Nalaziš se na crnoj listi na ovoj menzi do: ' . $datum_ucitani->format('d.m.Y H:i:s') . '<br><br>';
                                    break;
                                }
                            }
                        }
                        echo '<table><thead><tr><th>Datum</th><th>Juha</th><th>Glavno jelo</th><th>Prilog</th><th>Salata</th><th>Desert</th><th>Stanje</th><th>Dolazak</th></tr></thead>';
                        echo '<tbody>';
                        $sql2 = 'select rezervacija.datum, katalog.juha, katalog.glavno_jelo, katalog.prilog, katalog.salata, katalog.desert, rezervacija.stanje, rezervacija.dosao '
                                . 'from katalog, rezervacija where katalog.id_meni=rezervacija.id_meni and '
                                . 'rezervacija.id_menza=' . $id_menza . ' and rezervacija.kor_ime="' . $kor_ime . '" order by 1 desc';
                        $rez2 = $dbc->selectDB($sql2);
                        while ($red2 = $rez2->fetch_assoc()) {
                            $datum = strtotime($red2["datum"]);
                            $datum_novi = date('d.m.Y H:i', $datum);
                            echo '<tr><td>' . $datum_novi . '</td>';
                            echo '<td>' . $red2["juha"] . '</td><td>' . $red2["glavno_jelo"] . '</td><td>' . $red2["prilog"] . '</td><td>' . $red2["salata"] . '</td><td>' . $red2["desert"] . '</td>';
                            if ($red2["stanje"] == 1) {
                                $stanje = "Potvrđeno.";
                            } else if (is_null($red2["stanje"])) {
                                $stanje = "Nije potvrđeno.";
                            } else {
                                $stanje = "Odbijeno.";
                            }
                            if ($red2["dosao"] == 1) {
                                $dosao = "Došli ste.";
                            } else if (is_null($red2["dosao"])) {
                                $dosao = "U tijeku.";
                            } else {
                                $dosao = "Niste došli.";
                            }
                            echo '<td>' . $stanje . '</td><td>' . $dosao . '</td></tr>';
                        }
                        echo '</tbody>';
                        echo '</table><br><br>';
                    }

                    echo '<h2>Ocijeni konzumirane menije</h2>
                            <p>U tablici su navedeni meniji koje ste jeli u nekoj od menzi. Ocijenite ih i time pomozite poboljšati.</p>';
                    echo '<table><thead><th>Šifra menija</th><th>Juha</th><th>Glavno jelo</th><th>Prilog</th><th>Salata</th><th>Desert</th><th>Vaša ocjena</th><th colspan="2">Opcije</th></thead><tbody>';
                    $sql5 = 'select distinct katalog.* from katalog, rezervacija where katalog.id_meni = rezervacija.id_meni and rezervacija.kor_ime="' . $kor_ime . '"';
                    $rez5 = $dbc->selectDB($sql5);
                    while ($red5 = $rez5->fetch_assoc()) {
                        $id_meni = $red5["id_meni"];
                        echo '<tr><td>' . $red5["id_meni"] . '</td><td>' . $red5["juha"] . '</td><td>' . $red5["glavno_jelo"] . '</td><td>' . $red5["prilog"] . '</td><td>' . $red5["salata"] . '</td><td>' . $red5["desert"] . '</td>';

                        $sql6 = 'select svidja_se, ne_svidja_se from ocjene_meni where id_meni = ' . $id_meni . ' and kor_ime="' . $kor_ime . '"';
                        $rez6 = $dbc->selectDB($sql6);
                        $red6 = $rez6->fetch_assoc();
                        $svidja_se_meni = $red6["svidja_se"];
                        $ne_svidja_se_meni = $red6["ne_svidja_se"];

                        echo '<form class="formaTablica" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        echo '<input type="number" value="' . $id_meni . '" name="id_meni" hidden="hidden">';
                        if ($svidja_se_meni) {
                            echo '<td> Sviđa mi se :) </td><td><button type="submit" value="1" name="ne_svidja_se_meni"> Ne sviđa mi se </button></td>';
                            echo '<td><button type="submit" value="1" name="ukloni_meni"> Ukloni ocjenu </button></td></tr>';
                        } else if ($ne_svidja_se_meni) {
                            echo '<td> Ne sviđa mi se :( </td><td><button type="submit" value="1" name="svidja_se_meni"> Sviđa mi se </button></td>';
                            echo '<td><button type="submit" value="1" name="ukloni_meni"> Ukloni ocjenu </button></td></tr>';
                        } else {
                            echo '<td>Nema ocjene</td>';
                            echo '<td><button type="submit" value="1" name="svidja_se_meni"> Sviđa mi se </button></td> ';
                            echo '<td><button type="submit" value="1" name="ne_svidja_se_meni"> Ne sviđa mi se </button></td>';
                        }
                        echo '</form></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo 'Nemate nijednu rezervaciju.';
                }
                $dbc->zatvoriDB();
                ?>

            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>


