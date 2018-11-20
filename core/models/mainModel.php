<?php namespace models;

class mainModel {
    
    public function actionCatcherModel($action){
        switch ($action) {
            case 'inicio':
                $contenido = "views/modulos/inicio.php";
                break;
            
            case 'consultaDocumento':
                $contenido = "views/modulos/consultaDocumento.php";
                break;
            
            case 'login':
                $contenido = "views/modulos/loginView.php";
                break;

            case 'mantenimientosEQ':
                $contenido = "views/modulos/mantenimientosEQ.php";
                break;

            case 'editMantenimiento':
            $contenido = "views/modulos/editMantenimiento.php";
            break;

            case 'mantenimientosAG':
            $contenido = "views/modulos/mantenimientosAG.php";
            break;    

            case 'userconfig':
            $contenido = "views/modulos/userConfig.php";
            break;    
            
            case 'logout':
                $contenido = "views/modulos/cerrarSesion.php";
                break;
            
            default:
                $contenido = "views/modulos/inicio.php";
                break;
        }
        
       
        return $contenido;
        
    }
}
