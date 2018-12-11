
<?php
require_once '../core/models/ajaxModel.php';
$ajaxModel = new models\ajaxModel(); 

$resultado = $ajaxModel->isDisponibleOrdenFisica('7855');

var_dump($resultado);

if ($resultado <= 0) {
    echo 'Esta disponible';
}else{
    echo 'No disponible, ya existe';
}