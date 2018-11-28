<?php namespace models;

class venCabClass {
    public $pcID;
    public $oficina;
    public $ejercicio;
    public $tipoDoc;
    public $numeroDoc;
    public $fecha;
    public $bodega;
    public $divisa;
    public $subtotal;
    public $impuesto;
    public $total;
    public $formaPago;
    public $serie;
    public $secuencia;
    public $observacion;
    public $productos;
    
    public function __construct() {
        
    }
    
    
    function getProductos() {
        return $this->productos;
    }

    function setProductos($productos) {
        $this->productos = $productos;
    }

    function getPcID() {
        return $this->pcID;
    }

    function getOficina() {
        return $this->oficina;
    }

    function getEjercicio() {
        return $this->ejercicio;
    }

    function getTipoDoc() {
        return $this->tipoDoc;
    }

    function getNumeroDoc() {
        return $this->numeroDoc;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getBodega() {
        return $this->bodega;
    }

    function getDivisa() {
        return $this->divisa;
    }

    function getSubtotal() {
        return $this->subtotal;
    }

    function getImpuesto() {
        return $this->impuesto;
    }

    function getTotal() {
        return $this->total;
    }

    function getFormaPago() {
        return $this->formaPago;
    }

    function getSerie() {
        return $this->serie;
    }

    function getSecuencia() {
        return $this->secuencia;
    }

    function getObservacion() {
        return $this->observacion;
    }

    function setPcID($pcID) {
        $this->pcID = $pcID;
    }

    function setOficina($oficina) {
        $this->oficina = $oficina;
    }

    function setEjercicio($ejercicio) {
        $this->ejercicio = $ejercicio;
    }

    function setTipoDoc($tipoDoc) {
        $this->tipoDoc = $tipoDoc;
    }

    function setNumeroDoc($numeroDoc) {
        $this->numeroDoc = $numeroDoc;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setBodega($bodega) {
        $this->bodega = $bodega;
    }

    function setDivisa($divisa) {
        $this->divisa = $divisa;
    }

    function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }

    function setImpuesto($impuesto) {
        $this->impuesto = $impuesto;
    }

    function setTotal($total) {
        $this->total = $total;
    }

    function setFormaPago($formaPago) {
        $this->formaPago = $formaPago;
    }

    function setSerie($serie) {
        $this->serie = $serie;
    }

    function setSecuencia($secuencia) {
        $this->secuencia = $secuencia;
    }

    function setObservacion($observacion) {
        $this->observacion = $observacion;
    }


    
}