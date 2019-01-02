<?php 

    require_once '../core/models/EstadisticasClass.php';
    require_once '../core/models/conexion.php';


    $model = new models\EstadisticasClass();
    
    $result = $model->getCountMantenimientos();
    var_dump($result);
    
