<?php namespace controllers;

class supervisoresController  {

    private $ajaxModel;
    public $defaulDataBase = "MODELO";

    public function __construct() {
        $this->ajaxModel = new \models\supervisoresModel();
    }
  
    public function saveActividadesBasicasController($formDataObject){
        $response_CAB = $this->ajaxModel->persist_ActividadesBasicas_CAB($formDataObject);

        /*Enviamos arry de chalist + el nuevo codigo al que pertenecen */
        $response_MOV = $this->ajaxModel->persist_ActividadesBasicas_MOV($formDataObject->checkItems, $response_CAB['newCod']);
        
        return array('CAB' => $response_CAB, 'MOV' => $response_MOV);
    }

    public function getActividades1xmesController($condition){
        return $this->ajaxModel->getActividadesModelByCondition($condition);

    }

    /* AJAX SUPERVISORES - Retorna todos los checklist de la DB */
    public function getCheckListActBasicasController(){
        $response = $this->ajaxModel->getCheckListActBasicasModel();
        return $response;
    }

    /* Retorna la cantidad de evaluaciones para ese evaluador, evaluado en el mes */
    public function countEvaluacionesSupController($evaluador, $evaluado, $fechaMesActual, $semana){
       
        return $this->ajaxModel->countEvaluacionesSupModel($evaluador, $evaluado, $fechaMesActual, $semana);
    }

    /* Retorna informacion sobre si la evaluacion es posible en la semana */
    public function getCanDoEvaluationController($evaluador, $evaluado, $fechaMesActual, $semana){
       
        return $this->ajaxModel->getCanDoEvaluationModel($evaluador, $evaluado, $fechaMesActual, $semana);
    }

    
}
