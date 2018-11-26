<?php namespace models;

class VenCabClass {
    private $pcID;
    private $oficina;
    private $ejercicio;
    
    public function __construct($pcID, $oficina, $ejercicio) {
        $this->pcID = $pcID;
        $this->oficina = $oficina;
        $this->ejercicio = $ejercicio;
    }

    public function getCode(){
        return $this->pcID.$this->oficina.$this->ejercicio;
    }
}