<?php

    require_once '../core/models/MantenimientosClass.php';

    $model = new models\MantenimientosClass();
    
    $result = $model->getPrimerDiaMes()['StartOfMonth'];
    var_dump($result);
    

