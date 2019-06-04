<?php
date_default_timezone_set('America/Lima');
session_start();

require_once  '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function generaExcelMantInternos($select_empresa) {
   
    
      $spreadsheet = new Spreadsheet();
      $worksheet = $spreadsheet->getActiveSheet();
      $worksheet->setCellValue('A1', 'Valores Originales')
          ->setCellValue('A2', '-15')
          ->setCellValue('A3', '-30')
          ->setCellValue('A4', '50');

      $worksheet->setCellValue('B2', '=ABS(A2)')
          ->setCellValue('B3', '=ABS(A3)')
          ->setCellValue('B4', '=ABS(A4)');

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

        case 'generaReporteMantInternos':
          if (isset($_GET['select_empresa'])) {
            $select_empresa = $_GET['select_empresa'];
           
            $ajax->generaExcelMantInternos($select_empresa);
           
            
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


