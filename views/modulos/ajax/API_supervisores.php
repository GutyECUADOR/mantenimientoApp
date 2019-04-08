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

    public function getCheckListActBasicas() {
        return $this->ajaxController->getCheckListActBasicasController();
    }

   

}

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {
        case 'getCheckListActBasicas':
        $respuesta = $ajax->getCheckListActBasicas();
        $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
        echo json_encode($rawdata);

        break;

        case 'saveActividadesBasicas':

          if (isset($_POST['solicitud'])) {
            $formData = json_decode($_POST['solicitud']);
            $rawdata = array('status' => 'OK', 'mensaje' => 'solicitud grabada', 'formData' => $formData);
          }else {
            $rawdata = array('status' => 'FAIL', 'mensaje' => 'Error en post');
          }
        
        
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
    $rawdata = array();
    $rawdata['status'] = "ERROR";
    $rawdata['mensaje'] = $ex->getMessage();
    echo json_encode($rawdata);
  }


