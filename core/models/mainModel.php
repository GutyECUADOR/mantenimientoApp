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

            case 'mantenimientosEXT':
            $contenido = "views/modulos/mantenimientosEXT.php";
            break;

                case 'mantenimientosEXT/nuevo':
                $contenido = "views/modulos/nuevoMantExterno.php";
                break;

                case 'editMantenimientoExt':
                $contenido = "views/modulos/editMantenimientoEXT.php";
                break;
                
            case 'editMantenimiento':
            $contenido = "views/modulos/editMantenimiento.php";
            break;

            case 'mantenimientosAG':
            $contenido = "views/modulos/mantenimientosAG.php";
            break;   
            
            case 'mantenimientosHistorico':
            $contenido = "views/modulos/mantenimientosHistorico.php";
            break;   

            case 'checkListSupervisoresBasicas':
            $contenido = "views/modulos/checkActBasicasSup.php";
            break;   

                case 'tableListActBasicasSup':
                $contenido = "views/modulos/tableListActBasicasSup.php";
                break;

            case 'userconfig':
            $contenido = "views/modulos/userConfig.php";
            break;    
            
            case 'logout':
                $contenido = "views/modulos/cerrarSesion.php";
            break;

            case 'configuracionSis':
            $contenido = "views/modulos/configuracionSis.php";
            break;
            
            default:
                $contenido = "views/modulos/inicio.php";
                break;
        }
        
       
        return $contenido;
        
    }
}
