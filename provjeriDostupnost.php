<?php
include_once('aplikacijskiOkvir.php');

if (!empty($_POST["korime"])) {
    $sql = "select count(*) from korisnik where kor_ime = '" . $_POST["korime"]. "'";
    $rs = $dbc->selectDB($sql);
    $red = $rs->fetch_row();
    $broj = $red[0];
    echo $broj;
    $dbc->zatvoriDB();
}
?>

