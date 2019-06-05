<?php
date_default_timezone_set('America/Lima');
session_start();

require_once  '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ajax{
  private $ajaxController;
  private $mantenimientosClass;
   
    public function __construct() {
      /*Creamos instancia general del controlador*/
      $this->ajaxController = new \controllers\ajaxController();
      $this->mantenimientosClass = new models\MantenimientosClass();
    }

    /*Métodos disponibles del API */

    public function generaProforma($IDDocument) {
      return $this->ajaxController->generaReporte($IDDocument, 'I');
    }

    public function generaInformeMantInternosPDF($fechaINI, $fechaFIN, $tiposDocs, $codEmpresa) {
      return $this->mantenimientosClass->generaInformeMantInternosPDF($fechaINI, $fechaFIN, $tiposDocs, $codEmpresa, 'I');
    }

    public function generaInformeMantInternosExcel($fechaINI, $fechaFIN, $tiposDocs, $codEmpresa) {
   
      $spreadsheet = $this->mantenimientosClass->generaInformeMantInternosExcel($fechaINI, $fechaFIN, $tiposDocs, $codEmpresa);
      
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'. 'reporteEquipos' . date('Y-m-d') .'.xls"'); 
      header('Cache-Control: max-age=0');
      $writer = new Xlsx($spreadsheet);
      $writer->save('php://output');
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

        case 'generaInformeMantInternosPDF':
          if (isset($_SESSION["empresaAUTH"])) {
            $fechaINI = $_GET['fechaINI'];
            $fechaFIN = $_GET['fechaFIN'];
            $codEmpresa = $_SESSION["empresaAUTH"];
            $tiposDocs = $_GET['tiposDocs'];
           
            $fechaFormatINI = date('Ymd', strtotime($fechaINI));
            $fechaFormatFIN = date('Ymd', strtotime($fechaFIN));
          
            $PDFDocument = $ajax->generaInformeMantInternosPDF($fechaFormatINI, $fechaFormatFIN, $tiposDocs, $codEmpresa);
            echo $PDFDocument;
            
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros o no ha iniciado sesion.');
            echo json_encode($rawdata);
          }
        
        break;

        case 'generaInformeMantInternosExcel':
          if (isset($_SESSION["empresaAUTH"])) {
            $fechaINI = $_GET['fechaINI'];
            $fechaFIN = $_GET['fechaFIN'];
            $codEmpresa = $_SESSION["empresaAUTH"];
            $tiposDocs = $_GET['tiposDocs'];
           
            $fechaFormatINI = date('Ymd', strtotime($fechaINI));
            $fechaFormatFIN = date('Ymd', strtotime($fechaFIN));
           
            $ajax->generaInformeMantInternosExcel($fechaFormatINI, $fechaFormatFIN, $tiposDocs, $codEmpresa);
           
            
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


