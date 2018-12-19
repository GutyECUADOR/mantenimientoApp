<?php

require_once '../core/models/ajaxModel.php';
$ajaxModel = new \models\ajaxModel();

$codIMPORTKAO = $ajaxModel->getDatosClienteWINFENIXByRUC('1790417581001', 'MODELOKIND_V7')['CODIGO'];

$serieDocs = $ajaxModel->getDatosDocumentsWINFENIXByTypo('C02', 'MODELOKIND_V7')['Serie'];

$newCodigoWith0 = $ajaxModel->formatoNextNumDocWINFENIX('MODELOKIND_V7','00000799');

var_dump($serieDocs);
var_dump($codIMPORTKAO);
var_dump($newCodigoWith0);





