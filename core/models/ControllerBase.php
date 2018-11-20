<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controllerBase
 *
 * @author GUTYGAME
 */
class ControllerBase {
    public function __construct() {
        require_once './entidadBase.php';
        
        foreach (glob("/model/*.php") as $archivoPHP) { // Carga y realiza un require de carpeta model
            require_once $archivoPHP;
        }
    }
    
    // Crea un grupo de IDs con los valores enviados a la vista
    public function dataViews($view, $datos){
        foreach ($datos as $idElemento => $valor) {
            ${$idElemento}=$valor;
        }
        
        require_once '../clases/helperVistas.php';
        $helper = new helperVistas();
        require_once '../../views/'.$view.'view.php';
    }
    
    public function redirect($accion=DEFAUL_ACTION,$controller=DEFAULT_CONTROLLER){
        header("Location:index.php?controller=".$controller."&action=".$accion);
        
    }
}
