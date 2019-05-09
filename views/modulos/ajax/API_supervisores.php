<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../core/controllers/supervisoresController.php';
require_once '../../../core/models/supervisoresModel.php';

class ajax{
  private $ajaxController;

    public function __construct() {
      $this->ajaxController = new \controllers\supervisoresController();
    }

    public function getCheckListActBasicas() {
        return $this->ajaxController->getCheckListActBasicasController();
    }

    public function saveActividadesBasicasController($formDataObject) {
      return $this->ajaxController->saveActividadesBasicasController($formDataObject);
    }

    public function getActividades1xmes($condition) {
      return $this->ajaxController->getActividades1xmesController($condition);
    }

    public function countEvaluacionesSup($evaluador, $evaluado, $fechaMesActual, $semana) {
      return $this->ajaxController->countEvaluacionesSupController($evaluador, $evaluado, $fechaMesActual, $semana);
    }
   
    public function getCanDoEvaluation($evaluador, $evaluado, $fechaMesActual, $semana) {
      return $this->ajaxController->getCanDoEvaluationController($evaluador, $evaluado, $fechaMesActual, $semana);
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
            $formDataObject = json_decode($_POST['solicitud']);
            $respuesta = $ajax->saveActividadesBasicasController($formDataObject);
            $rawdata = array('status' => 'OK', 'mensaje' => 'CheckList registrado.', 'respuesta' => $respuesta);
          }else {
            $rawdata = array('status' => 'FAIL', 'mensaje' => 'Error en post, el objeto de datos no es correcto');
          }
        
        
        echo json_encode($rawdata);

        break;


        case 'getActividadesByCondicion':

          if (isset($_GET['condicion'])) {
            $condicion = $_GET['condicion'];
            $respuesta = $ajax->getActividades1xmes($condicion);
            $rawdata = array('status' => 'OK', 'respuesta' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'Indique el tipo de condicion, ejem: 1XMES');
          }
          
       
          echo json_encode($rawdata);

        break;

        case 'countEvaluacionesSup':
        
          if (isset($_GET['evaluador']) && isset($_GET['evaluado']) && isset($_GET['semana'])) {
            $evaluador = $_GET['evaluador'];
            $evaluado = $_GET['evaluado'];
            $fechaMesActual = date('Ym01');
            $semana = $_GET['semana'];
            $respuesta = $ajax->countEvaluacionesSup($evaluador, $evaluado, $fechaMesActual, $semana);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se especifico Evaluador, evaluado o semana');
          }  
            echo json_encode($rawdata);
        break;

        case 'getCanDoEvaluation':
        
          if (isset($_GET['evaluador']) && isset($_GET['evaluado']) && isset($_GET['semana'])) {
            $evaluador = $_GET['evaluador'];
            $evaluado = $_GET['evaluado'];
            $fechaMesActual = date('Ym01');
            $semana = $_GET['semana'];
            $respuesta = $ajax->getCanDoEvaluation($evaluador, $evaluado, $fechaMesActual, $semana);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se especifico Evaluador, evaluado o semana');
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


