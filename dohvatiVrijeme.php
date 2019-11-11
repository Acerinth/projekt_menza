<?php

$obj = json_decode(file_get_contents("http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=json"), TRUE);
$sati = $obj["WebDiP"]["vrijeme"]["pomak"]["brojSati"];

$json["pomak"] = $sati;
$ok = file_put_contents("vrijeme.json", json_encode($json,TRUE));

if ($ok) {
    echo 'Uspjesno dohvaceno';
}
else echo 'Greska u dohvacanju.';

?>

