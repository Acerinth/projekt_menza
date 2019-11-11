<?php

function ucitajVrijeme() {
    $json = json_decode(file_get_contents("vrijeme.json"), TRUE);
    $sati = $json["pomak"];

    $vrijeme_servera = time();
    $vrijeme_sustava = $vrijeme_servera + ($sati * 60 * 60);

    return $vrijeme_sustava;
}
?>

