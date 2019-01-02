<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';

class ajax{
  private $ajaxController;

    public function __construct() {
      $this->ajaxController = new \controllers\ajaxController();
    }

    public function getConteoMantenimientos() {
        return $this->ajaxController->getCountMantenimientosController();
    }

}

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {
        case 'getConteoMantenimientos':
        $respuesta = $ajax->getConteoMantenimientos();
        $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
        echo json_encode($rawdata);

        break;

        case 'test':
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta');
            echo json_encode($rawdata);

            break;

        default:
            $rawdata = array('status' => 'error', 'mensaje' =>'el API no ha podido responder la solicitud, revise el tipo de action');
            echo json_encode($rawdata);
            break;
    }
    
  } catch (Exception $ex) {
    //Return error message
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = $ex->getMessage();
    echo json_encode($jTableResult);
  }


