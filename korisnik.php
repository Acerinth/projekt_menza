<?php

class Korisnik {

    private $kor_ime;
    private $ime;
    private $prezime;
    private $lozinka;
    private $vrsta;
    private $prijavljen_od;
    private $adresa;

    public function Korisnik($p_kor_ime, $p_ime, $p_prezime, $p_lozinka, $vrsta) {
        $this->kor_ime = $p_kor_ime;
        $this->ime = $p_ime;
        $this->prezime = $p_prezime;
        $this->lozinka = $p_lozinka;
        $this->vrsta = $vrsta;
        $this->prijavljen_od = ucitajVrijeme();
        $this->adresa = $_SERVER["REMOTE_ADDR"];
    }

    public function get_kor_ime() {
        return $this->kor_ime;
    }

    public function get_ime_prezime() {
        return $this->ime . " " . $this->prezime;
    }

    public function get_prezime() {
        return $this->prezime;
    }

    public function get_ime() {
        return $this->ime;
    }

    public function get_vrsta() {
        return $this->vrsta;
    }

    public function get_prijavljen_od() {
        return date("d.m.Y H:i:s", $this->prijavljen_od);
    }

    public function get_adresa() {
        return $this->adresa;
    }    
}

?>
