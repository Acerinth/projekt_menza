<?php
include_once('aplikacijskiOkvir.php');

$korisnik = provjeraUloge(MODERATOR);
if (!$korisnik) {
    header('Location: error.php?e=0');
    exit();
}
dnevnik_zapis($korisnik, POSJET);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $opis = $_POST["opis"];
    $id_menza = $_POST["id_menza"];
    $datum = $_POST["datum"];

    if (!empty($opis) && !empty($id_menza) && !empty($datum)) {
        $sql = 'insert into dnevna_ponuda (opis, datum, id_menza) values ("'.$opis.'", "'.$datum.'", '.$id_menza.')';   
        $rs = $dbc->selectDB($sql);
        if ($rs) {
            dnevnik_zapis($korisnik, DODAJ, $sql);
            $dbc->zatvoriDB();
            header('Location: ponude.php?id='.$id_menza);
        }
    }
    
}
?>




