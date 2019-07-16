<?php 

    session_start();
    require_once  '../vendor/autoload.php';

    $codEmpresa = trim($_SESSION["empresaAUTH"]);  // Nombre de la db asiganda en el login 
    $mantenimientos = new models\MantenimientosClass();
    $arrayMantenimiento = $mantenimientos->getMantenimientoExternoByCod($codEmpresa, 'MNE00031'); //Devuelve array de mantenimientos
    
    var_dump($arrayMantenimiento);
    
