<?php
date_default_timezone_set('America/Lima');
session_start();

require_once  '../../../vendor/autoload.php';

class ajax{
  private $ajaxController;
  private $mantenimientosClass;

    public function __construct() {
      $this->ajaxController = new \controllers\ajaxController();
      $this->mantenimientosClass = new models\MantenimientosClass();
    }

    public function getConteoMantenimientos($codEmpresa) {
        return $this->ajaxController->getCountMantenimientosController($codEmpresa);
    }

    public function getHistorico($fechaINI, $fechaFIN, $tiposDocs, $rucCliente, $codEmpresa) {
      return $this->mantenimientosClass->getMantenimientosHistorico($fechaINI, $fechaFIN, $tiposDocs, $rucCliente, 1000, $codEmpresa );
    }

    public function getHistoricoExternos($fechaINI, $fechaFIN, $tiposDocs, $bodega, $rucCliente, $codEmpresa) {
      return $this->mantenimientosClass->getMantenimientosHistoricoEXT($fechaINI, $fechaFIN, $tiposDocs, $bodega, $rucCliente, 1000, $codEmpresa );
    }

}

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {
        case 'getConteoMantenimientos':
          $codEmpresa = $_SESSION["codEmpresaAUTH"];
          $respuesta = $ajax->getConteoMantenimientos($codEmpresa);
          $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          echo json_encode($rawdata);

        break;

        case 'getHistorico':

          if (isset($_GET["fechaINI"]) && isset($_GET["fechaFIN"]) && isset($_GET["tiposDocs"]) && isset($_GET["rucCliente"]) ) {
            $fechaINI = $_GET["fechaINI"];
            $fechaFIN = $_GET["fechaFIN"];
            $tiposDocs = $_GET["tiposDocs"];
            $rucCliente = $_GET["rucCliente"];
  
            $fechaFormatINI = date('Ymd', strtotime($fechaINI));
            $fechaFormatFIN = date('Ymd', strtotime($fechaFIN));
  
            $codEmpresa = $_SESSION["empresaAUTH"];
            $respuesta = $ajax->getHistorico($fechaFormatINI, $fechaFormatFIN, $tiposDocs, $rucCliente, $codEmpresa);
            $rawdata = array('status' => 'OK', 
                            'mensaje' => $fechaFormatINI, 
                            'horaINI' => 'recuperado historico', 
                            'data' => $respuesta
                          );
          }else{
            $rawdata = array('status' => 'FAIL', 
                'mensaje' => 'No se indicaron todos los parametros', 
                'data' => ''
              );
          }
          
          echo json_encode($rawdata);

        break;

        case 'getHistoricoExternos':

          if (isset($_GET["fechaINI"]) && isset($_GET["fechaFIN"]) && isset($_GET["tiposDocs"]) && isset($_GET["rucCliente"]) && isset($_GET["bodega"]) ) {
          $fechaINI = $_GET["fechaINI"];
          $fechaFIN = $_GET["fechaFIN"];
          $tiposDocs = $_GET["tiposDocs"];
          $rucCliente = $_GET["rucCliente"];
          $bodega = $_GET["bodega"];

          $fechaFormatINI = date('Ymd', strtotime($fechaINI));
          $fechaFormatFIN = date('Ymd', strtotime($fechaFIN));

          $codEmpresa = $_SESSION["empresaAUTH"];
          $respuesta = $ajax->getHistoricoExternos($fechaFormatINI, $fechaFormatFIN, $tiposDocs, $bodega, $rucCliente, $codEmpresa);
          $rawdata = array('status' => 'OK', 
                          'mensaje' => 'Recuperando Historico', 
                          'horaINI' => $fechaFormatINI, 
                          'data' => $respuesta
                        );

          }else{
            $rawdata = array('status' => 'FAIL', 
                'mensaje' => 'No se indicaron todos los parametros', 
                'data' => ''
              );
          }
                      
          
          echo json_encode($rawdata);

        break;

        case 'test':
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => 'JOSE GUTIERREZ');
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


