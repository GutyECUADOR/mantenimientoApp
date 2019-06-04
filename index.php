<?php
    date_default_timezone_set('America/Lima');
    @ob_start();
    session_start();

    require_once  'vendor/autoload.php';

    $app = new controllers\mainController();
    $app->loadtemplate();
   

    