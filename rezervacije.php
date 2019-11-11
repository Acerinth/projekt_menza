<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

//$datum_danas = date('Y-m-d H:i:s', ucitajVrijeme());
$kor_ime = $korisnik->get_kor_ime();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kor_ime = $_POST["kor_ime"];
    $datum = $_POST["datum"];
    $id_menza = $_POST["id_menza"];
    if (isset($_POST["dolazak"]) && $_POST["dolazak"] == 1) {
        $sql = 'update rezervacija set dosao=1 where kor_ime="' . $kor_ime . '" and datum="' . $datum . '"';
        $rez = $dbc->selectDB($sql);
        if ($rez) {
            dnevnik_zapis($korisnik, AZURIRAJ, $sql);
        }
//        $id_meni = $_POST["id_meni"];
//        
//        
//        $sqlPon = 'select distinct rezervacija.id_menza, dnevna_ponuda.id_ponuda, rezervacija.id_meni, kolicina from stavke_ponude, rezervacija, dnevna_ponuda where stavke_ponude.id_meni=rezervacija.id_meni and stavke_ponude.id_ponuda=dnevna_ponuda.id_ponuda and rezervacija.id_menza=dnevna_ponuda.id_menza and dnevna_ponuda.datum="'.date('Y-m-d',strtotime($datum)).'" and dnevna_ponuda.id_menza='.$id_menza.' and stavke_ponude.id_meni='.$id_meni;
//        $rezPon = $dbc->selectDB($sqlPon);
//        $redPon = $rezPon->fetch_assoc();
//        echo $redPon["id_ponuda"]. " ". $redPon["id_meni"] . " ". $redPon["kolicina"] .'<br>';
//        $id_ponuda = $redPon["id_ponuda"];
//        $kolicina_stara = $redPon["kolicina"];
//        $kolicina_nova = $kolicina_stara-1;
//        $sqlR = 'update stavke_ponude set kolicina='.$kolicina_nova.' where id_meni='.$id_meni.' and id_ponuda='.$id_ponuda;
//        $rezR = $dbc->selectDB($sqlR);
        
        
        $dbc->zatvoriDB();
        header('Location: rezervacije.php');
    }
    if (isset($_POST["dolazak"]) && $_POST["dolazak"] == 0) {
        $sql = 'update rezervacija set dosao=0 where kor_ime="' . $kor_ime . '" and datum="' . $datum . '"';
        $rez = $dbc->selectDB($sql);
        if ($rez) {
            dnevnik_zapis($korisnik, AZURIRAJ, $sql);
        }
        $datum_danas = date('Y-m-d H:i:s', ucitajVrijeme());
        $sql2 = 'insert into crna_lista values ("' . $datum_danas . '", ' . $id_menza . ', "' . $kor_ime . '")';
        $rez2 = $dbc->selectDB($sql2);
        if ($rez2) {
            dnevnik_zapis($korisnik, DODAJ, $sql2);
        }
        $dbc->zatvoriDB();
        header('Location: rezervacije.php');
    }
    if (isset($_POST["stanje"]) && $_POST["stanje"] == 0) {
        $sql = 'update rezervacija set stanje=0 where kor_ime="' . $kor_ime . '" and datum="' . $datum . '"';
        $rez = $dbc->selectDB($sql);
        dnevnik_zapis($korisnik, AZURIRAJ, $sql);
        $sqlK = 'select email from korisnik where kor_ime="'.$kor_ime.'"';
        $rezK = $dbc->selectDB($sqlK);
        dnevnik_zapis($korisnik, UPIT, $sqlK);
        $red = $rezK->fetch_row();
        $mail_to = $red[0];
        $mail_from = "From: WebDiP2015x039@foi.hr";
        $mail_subject = "Rezervacija menija";
        $mail_body = 'Vasa rezervacija je odbijena s datumom: '.date('d.m.Y. H:i',strtotime($datum));
        mail($mail_to, $mail_subject, $mail_body, $mail_from);
        $dbc->zatvoriDB();
        header('Location: rezervacije.php');
    }
    if (isset($_POST["stanje"]) && $_POST["stanje"] == 1) {
        $id_menza = $_POST["id_menza"];
        $sql = 'update rezervacija set stanje=1 where kor_ime="' . $kor_ime . '" and datum="' . $datum . '"';
        $rez = $dbc->selectDB($sql);
        dnevnik_zapis($korisnik, AZURIRAJ, $sql);
        $sqlK = 'select email from korisnik where kor_ime="'.$kor_ime.'"';
        $rezK = $dbc->selectDB($sqlK);
        dnevnik_zapis($korisnik, UPIT, $sqlK);
        $red = $rezK->fetch_row();
        $mail_to = $red[0];
        $mail_from = "From: WebDiP2015x039@foi.hr";
        $mail_subject = "Rezervacija menija";
        $mail_body = 'Vasa rezervacija prihvacena s datumom: '.date('d.m.Y. H:i',strtotime($datum));
        mail($mail_to, $mail_subject, $mail_body, $mail_from);
        $dbc->zatvoriDB();
        header('Location: rezervacije.php');
    }
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Rezervacije</title>
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
                <h2>Rezervacije</h2>

                <h3>Nove rezervacije</h3>
                <p>Potrebno je potvrditi rezervaciju.</p>
                <?php
                $sql2 = 'select distinct rezervacija.datum, korisnik.kor_ime, korisnik.prezime, menza.id_menza, menza.naziv, katalog.*, rezervacija.stanje, rezervacija.dosao from rezervacija, katalog, korisnik, menza, stavke_ponude where rezervacija.kor_ime=korisnik.kor_ime and rezervacija.id_meni=katalog.id_meni and rezervacija.id_menza=menza.id_menza and rezervacija.dosao is null and rezervacija.stanje is null and menza.id_menza in (select distinct id_menza from mod_menze where kor_ime="' . $kor_ime . '") order by datum desc';
                $rez2 = $dbc->selectDB($sql2);
                if ($rez2->num_rows > 0) {
                    echo '<table><thead><th>Datum</th><th>Korisnik</th><th>Naziv menze</th><th>Šifra menija</th><th>Juha</th><th>Glavno jelo</th><th>Prilog</th><th>Salata</th><th>Desert</th><th colspan="2">Stanje</th><tr></thead><tbody>';
                    while ($red2 = $rez2->fetch_assoc()) {
                        echo '<tr><td>' . date('d.m.Y. H:i',strtotime($red2["datum"])) . '</td><td>' . $red2["prezime"] . '</td><td>' . $red2["naziv"] . '</td><td>' . $red2["id_meni"] . '</td><td>' . $red2["juha"] . '</td><td>' . $red2["glavno_jelo"] . '</td><td>' . $red2["prilog"] . '</td><td>' . $red2["salata"] . '</td><td>' . $red2["desert"] . '</td>'
                        . '<form class="formaTablica" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="text" value="' . $red2["kor_ime"] . '" hidden="hidden" name="kor_ime"><input type="text" value="' . $red2["datum"] . '" hidden="hidden" name="datum"><input type="number" value="' . $red2["id_menza"] . '" hidden="hidden" name="id_menza">'
                        . '<td><button type="submit" name="stanje" value="1"> Potvrdi rezervaciju </button></td><td><button type="submit" name="stanje" value="0"> Odbij rezervaciju </button></td></form></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>Nema novih rezervacija.</p>';
                }
                ?>

                <h3>Potvrđene rezervacije</h3>
                <p>Potrebno je potvrditi dolazak korisnika.</p>
                <?php
                $sql1 = 'select distinct rezervacija.datum, rezervacija.id_meni, korisnik.kor_ime, korisnik.prezime, menza.id_menza, menza.naziv, katalog.*, rezervacija.stanje, rezervacija.dosao from rezervacija, katalog, korisnik, menza, stavke_ponude where rezervacija.kor_ime=korisnik.kor_ime and rezervacija.id_meni=katalog.id_meni and rezervacija.id_menza=menza.id_menza and rezervacija.dosao is null and rezervacija.stanje = 1 and menza.id_menza in (select distinct id_menza from mod_menze where kor_ime="' . $kor_ime . '") order by datum desc';
                $rez1 = $dbc->selectDB($sql1);
                if ($rez1->num_rows > 0) {
                    echo '<table><thead><th>Datum</th><th>Korisnik</th><th>Naziv menze</th><th>Šifra menija</th><th>Juha</th><th>Glavno jelo</th><th>Prilog</th><th>Salata</th><th>Desert</th><th colspan="2">Dolazak</th><tr></thead><tbody>';
                    while ($red1 = $rez1->fetch_assoc()) {
                        echo '<tr><td>' . date('d.m.Y. H:i', strtotime($red1["datum"])) . '</td><td>' . $red1["prezime"] . '</td><td>' . $red1["naziv"] . '</td><td>' . $red1["id_meni"] . '</td><td>' . $red1["juha"] . '</td><td>' . $red1["glavno_jelo"] . '</td><td>' . $red1["prilog"] . '</td><td>' . $red1["salata"] . '</td><td>' . $red1["desert"] . '</td>'
                        . '<form class="formaTablica" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="text" value="' . $red1["kor_ime"] . '" hidden="hidden" name="kor_ime"><input type="text" value="' . $red1["datum"] . '" hidden="hidden" name="datum"><input type="number" value="' . $red1["id_menza"] . '" hidden="hidden" name="id_menza"><input type="number" value="' . $red1["id_meni"] . '" hidden="hidden" name="id_meni">'
                        . '<td><button type="submit" name="dolazak" value="1"> Potvrdi dolazak </button></td><td><button type="submit" name="dolazak" value="0"> Nije dosao </button></td></form></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>Nema nepotvrđenih rezervacija.</p>';
                }
                ?>

                <h3>Prošle rezervacije</h3>
                <p>Pregled svih prošlih rezervacija.</p>
                <?php
                $sql3 = 'select distinct rezervacija.datum, korisnik.kor_ime, korisnik.prezime, menza.id_menza, menza.naziv, katalog.*, rezervacija.stanje, rezervacija.dosao from rezervacija, katalog, korisnik, menza, stavke_ponude where rezervacija.kor_ime=korisnik.kor_ime and rezervacija.id_meni=katalog.id_meni and rezervacija.id_menza=menza.id_menza and ((rezervacija.stanje=1 and rezervacija.dosao is not null) or (rezervacija.stanje=0 and rezervacija.dosao is null)) and menza.id_menza in (select distinct id_menza from mod_menze where kor_ime="' . $kor_ime . '") order by datum desc';
                $rez3 = $dbc->selectDB($sql3);
                if ($rez3->num_rows > 0) {
                    echo '<table><thead><th>Datum</th><th>Korisnik</th><th>Naziv menze</th><th>Šifra menija</th><th>Juha</th><th>Glavno jelo</th><th>Prilog</th><th>Salata</th><th>Desert</th><th>Stanje</th><th>Dolazak</th><tr></thead><tbody>';
                    while ($red3 = $rez3->fetch_assoc()) {
                        if ($red3["stanje"]) {
                            $stanje = "Potvrđeno.";
                            if ($red3["dosao"]) {
                                $dosao = "Došao.";
                            } else {
                                $dosao = "Nije došao.";
                            }
                        } else {
                            $stanje = "Odbijeno.";
                            $dosao = "";
                        }

                        echo '<tr><td>' . date('d.m.Y. H:i',strtotime($red3["datum"])) . '</td><td>' . $red3["prezime"] . '</td><td>' . $red3["naziv"] . '</td><td>' . $red3["id_meni"] . '</td><td>' . $red3["juha"] . '</td><td>' . $red3["glavno_jelo"] . '</td><td>' . $red3["prilog"] . '</td><td>' . $red3["salata"] . '</td><td>' . $red3["desert"] . '</td><td>' . $stanje . '</td><td>' . $dosao . '</td></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>Nema novih rezervacija.</p>';
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


