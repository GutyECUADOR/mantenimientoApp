<?php namespace models;

class venCabClass {
    public $pcID;
    public $oficina;
    public $ejercicio;
    public $tipoDoc;
    public $numeroDoc;
    public $fecha;
    public $cliente;
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
    public $codigos_prod;
    public $nombres_prod;
    public $cants_prod;
    public $precios_prod;
    
    public function __construct() {
        
    }
    
    function getProductos() {
        return $this->productos;
    }

    function setProductos($productos) {
        $this->productos = $productos;
    }

        
    function getCliente() {
        return $this->cliente;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
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

    function getCodigos_prod() {
        return $this->codigos_prod;
    }

    function getNombres_prod() {
        return $this->nombres_prod;
    }

    function getCants_prod() {
        return $this->cants_prod;
    }

    function getPrecios_prod() {
        return $this->precios_prod;
    }

    function setCodigos_prod($codigos_prod) {
        $this->codigos_prod = $codigos_prod;
    }

    function setNombres_prod($nombres_prod) {
        $this->nombres_prod = $nombres_prod;
    }

    function setCants_prod($cants_prod) {
        $this->cants_prod = $cants_prod;
    }

    function setPrecios_prod($precios_prod) {
        $this->precios_prod = $precios_prod;
    }


    
}