<?php
    date_default_timezone_set('America/Lima');
    @ob_start();
    session_start();
    require_once './config/global.php';
    require_once './core/controllers/mainController.php';
    require_once './core/models/mainModel.php';
    require_once './core/controllers/loginController.php';
    require_once './core/models/loginModel.php';
    require_once './core/controllers/ajaxController.php';
    require_once './core/models/ajaxModel.php';
    require_once './core/models/MantenimientosClass.php';
    require_once './core/models/venCabClass.php';
    
    /* TEST Conexion */    
    /* require_once './core/models/conexion.php';
    $conexion = new models\conexion();
    var_dump($conexion->getInstanciaCNX()); */

    /* TEST LoginModel */ 
    /* $login = new models\loginModel();
    $arrayDatos = array("usuario"=>'0400882940',"password"=>'12346');
    $dbname = $login->validaIngreso($arrayDatos);
    var_dump($login->getAllDataBaseList()); */

    /* TEST LoginController */ 
   /*  $login = new controllers\loginController();
    $login->showAllDataBaseList(); */

    /* TEST AjaxModel  
    $ajaxModel = new models\ajaxModel(); 
    
    $newCodigo = $ajaxModel->getNextNumDocWINFENIX('C02', 'FALVAREZ_V7'); // Recuperamos secuencial de SP de Winfenix
    var_dump($newCodigo);*/

    /* TEST AjaxController */ 
   /*  $ajax = new controllers\ajaxController();
    var_dump($ajax->getAllEquiposSinMantenimiento('2017-10-10','2017-10-10',1,10)); */
    

     /* TEST MantenimientosClass */ 
    /* $mantenimientos = new models\MantenimientosClass();
    $resulset = $mantenimientos->getMantenimientosAgendados('modelo', 100);
    $resulset = $mantenimientos->getCodeDBByName('KINDRED_V7')['Codigo'];
    $resulset = $mantenimientos->getDataMantenimiento('modelo','MNT00012');
    var_dump($resulset); */
    

    $app = new controllers\mainController();
    $app->loadtemplate();
   

    