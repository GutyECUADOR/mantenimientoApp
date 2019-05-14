<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../config/global.php';
require_once '../../../libs/mpdf/mpdf.php';
require_once '../../../core/models/conexion.php';
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';

class ajax{
  private $ajaxController;
   
    public function __construct() {
      /*Creamos instancia general del controlador*/
      $this->ajaxController = new \controllers\ajaxController();
    }

    /*Métodos disponibles del API */

    public function generaProforma($IDDocument) {
      return $this->ajaxController->generaReporte($IDDocument, 'I');
  }

   
}

  /* Cuerpo del API */

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {

        case 'generaProforma':
          if (isset($_GET['IDDocument'])) {
            $IDDocument = $_GET['IDDocument'];
          
            $PDFDocument = $ajax->generaProforma($IDDocument);
            echo $PDFDocument;
            
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
            echo json_encode($rawdata);
          }
        
          break;

        case 'searchDocumentos':
          if (isset($_GET['fechaINI']) && isset($_GET['fechaFIN']) && isset($_GET['stringBusqueda']) ) {
            $fechaINI = date("Ymd", strtotime($_GET['fechaINI']));
            $fechaFIN = date("Ymd", strtotime($_GET['fechaFIN']));
            $stringBusqueda = $_GET['stringBusqueda'];

            $respuesta = $ajax->getAllDocumentos($fechaINI,  $fechaFIN, $stringBusqueda);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
          echo json_encode($rawdata);

        break;

        /* Utiliza PHPMailer para el envio de correo, utiliza los correos del cliente indicados en la tabla*/ 
        case 'sendEmail':

          if (isset($_GET['IDDocument']) ) {
            $IDDocument = $_GET['IDDocument'];
            $respuesta = $ajax->sendEmail($IDDocument);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.' );
          }

          echo json_encode($rawdata);

        break; 


        case 'test':
            $rawdata = array('status' => 'OK', 'mensaje' => 'Respuesta correcta');
            echo json_encode($rawdata);

        break;

        default:
            $rawdata = array('status' => 'error', 'mensaje' =>'El API no ha podido responder la solicitud, revise el tipo de action');
            echo json_encode($rawdata);
        break;
    }
    
  } catch (Exception $ex) {
    //Return error message
    $rawdata = array('status' => 'error');
    $rawdata['mensaje'] = $ex->getMessage();
    echo json_encode($rawdata);
  }


