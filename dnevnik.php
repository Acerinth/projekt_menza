<?php

function dnevnik_zapis($kor, $id, $opis = '') {
    if ($kor) {
        $kor = $kor->get_kor_ime();
    }

    $poz = strrpos($_SERVER["REQUEST_URI"], "/");
    $adresa = substr($_SERVER["REQUEST_URI"], $poz + 1);

    $vrijeme = date('Y-m-d H:i:s', ucitajVrijeme());

    $baza = new Baza();
    $baza->spojiDB();
    
    if ($opis !== '') {
        $opis = $baza->zastitiDB($opis);
    }

    if ($kor) {
        $sql = 'insert into dnevnik (vrijeme, kor_ime, id_radnje, adresa, opis) values ("' . $vrijeme . '", "' . $kor . '", ' . $id . ', "' . $adresa . '", "'.$opis.'")';  
    } else {
        $sql = 'insert into dnevnik (vrijeme, id_radnje, adresa, opis) values ("' . $vrijeme . '", ' . $id . ', "' . $adresa .'", "'.$opis. '")';
    }
    
    $rs = $baza->selectDB($sql);
    
    $baza->zatvoriDB();
}

?>
