<?php

require_once '../core/models/ajaxModel.php';
$ajaxModel = new \models\ajaxModel();

$codIMPORTKAO = $ajaxModel->getDatosClienteWINFENIXByRUC('1790417581001', 'FALVAREZ_V7')['CODIGO'];

$serieDocs = $ajaxModel->getDatosDocumentsWINFENIXByTypo('C02', 'FALVAREZ_V7')['Serie'];

$newCodigoWith0 = $ajaxModel->getNextNumDocWINFENIX('C02','FALVAREZ_V7');

var_dump($serieDocs);
var_dump($codIMPORTKAO);
var_dump($newCodigoWith0);





