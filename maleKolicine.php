<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
header('Location: error.php?e=0');
exit();
}
dnevnik_zapis($korisnik, POSJET);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kolicina_dodana = $_POST["kolicina"];
    $id_ponuda = $_POST["id_ponuda"];
    $id_meni = $_POST["id_meni"];
    
    $sqlK = 'select kolicina from stavke_ponude where id_ponuda='.$id_ponuda.' and id_meni='.$id_meni;
    $rez = $dbc->selectDB($sqlK);
    $red = $rez->fetch_row();
    $kolicina_stara = $red[0];
    $kolicina_nova = $kolicina_stara+$kolicina_dodana;
    $sql1 = 'update stavke_ponude set kolicina='.$kolicina_nova.'  where id_ponuda='.$id_ponuda.' and id_meni='.$id_meni;
    $rez1 = $dbc->selectDB($sql1);
    $dbc->zatvoriDB();
    header('Location: maleKolicine.php');
    
}



?>

<!DOCTYPE html>

<html>
    <head>
        <title>Niske količine menija</title>
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
                <h2>Niske količine menija</h2>

                <?php
                $sql = 'select stavke_ponude.*, katalog.*, menza.naziv from stavke_ponude, katalog, dnevna_ponuda, menza where katalog.id_meni=stavke_ponude.id_meni and stavke_ponude.id_ponuda=dnevna_ponuda.id_ponuda and dnevna_ponuda.id_menza=menza.id_menza and stavke_ponude.kolicina<10';
                $rez = $dbc->selectDB($sql);
                if ($rez->num_rows > 0) {
                    echo '<table>
                            <thead><tr><th>Menza</th><th>Šifra ponude</th><th>Šifra menija</th><th>Juha</th><th>Glavno jelo</th>
                             <th>Prilog</th><th>Salata</th><th>Desert</th><th>Količina</th><th>Povećaj količinu</th></tr></thead>';
                    echo '<tbody>';

                    while ($red = $rez->fetch_assoc()) {
                        echo '<tr><td>' . $red["naziv"] . '</td><td>' . $red["id_ponuda"] .'</td><td>' . $red["id_meni"] .'</td><td>' . $red["juha"] . '</td><td>' . $red["glavno_jelo"] .
                        '</td><td>' . $red["prilog"] . '</td><td>' . $red["salata"] . '</td><td>' . $red["desert"] .'</td><td>' . $red["kolicina"] . '</td><td>';
                        $id_meni = $red["id_meni"];
                        $id_ponuda = $red["id_ponuda"];
                        if ($red["kolicina"] != 0) {
                            echo '<form class="formaTablica" method="post" action="maleKolicine.php"><input type="number" name="id_ponuda" hidden="hidden" value="'.$id_ponuda.'"><input type="number" name="id_meni" hidden="hidden" value="'.$id_meni.'"><input type="number" name="kolicina" size="2" min="1"> <button type="submit"> Povećaj </button></form></td></tr>';
                        } else {
                            echo 'Nije moguće povećati količinu.</td></tr>';
                        }
                    }
                    echo '</tbody></table>';
                }
                else {
                    echo '<p>Trenutno nema ni jednog menija s kolicinom manjom od 10.</p>';
                }
                
                ?>

            </section>


            <footer>
                <address>Kontakt: <a href="mailto:paukokic@foi.hr">Paula Kokić</a></address>
                <p>&copy; 2016 P. Kokić</p>
            </footer>
        </div>
    </body>
</html>



