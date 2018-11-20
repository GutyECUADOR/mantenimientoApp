<?php namespace controllers;

class loginController  {

    public $loginModel;

    public function __construct()
    {
        require_once 'config/global.php';
        require_once 'core/models/loginModel.php';
        $this->loginModel = new \models\loginModel();
    }
    
    public function loadtemplate() {
        require_once 'views\template.php';
    }
    
    public function actionCatcherController(){
        if (isset($_POST['login_username']) && isset($_POST['login_password']) && isset($_POST['select_empresa'])) {
                $codigoDB = $_POST['select_empresa']; // recuperamos el codigo del select
                $arrayDatos = array("usuario"=>$_POST['login_username'],"password"=>$_POST['login_password']);

                $dataBaseName = $this->loginModel->getDBNameByCodigo($codigoDB); // Obtenemos nombre de la DB segun codigo, retorno de un array
                $arrayResultados = $this->loginModel->validaIngreso($arrayDatos); // Validamos info del usuario en esa DB
                
                //Funcion validar acceso retorna array de resultados
                    if (!empty($arrayResultados)) {
                        session_start();
                        $_SESSION["usuarioRUC"] =  $arrayResultados['Cedula'] ;
                        $_SESSION["usuarioNOMBRE"] =  $arrayResultados['Nombre']. " " . $arrayResultados['Apellido'] ;
                        $_SESSION["usuarioTipo"] =  $arrayResultados['CodDpto'];
                        $_SESSION["empresaAUTH"] = $dataBaseName['NameDatabase'];
                        $codEmpresa = $this->loginModel->getCodeDBByName($dataBaseName['NameDatabase'])['Codigo']; // Funcion del modelo retorna el array con campo codigo
                        $_SESSION["codEmpresaAUTH"] = $codEmpresa;

                        header("Location: index.php?&action=inicio");
                    
                    }else{
                        echo '
                            <div class="uk-alert uk-alert-danger" data-uk-alert="">
                                <a href="#" class="uk-alert-close uk-close"></a>
                                No se pudo realizar el logeo con el usuario: '. $arrayDatos['usuario'] .' en la empresa seleccionada.
                            </div>
                        ';
                       
                    }
        }
    }
    

    public function resetPassword(){

        if (isset($_POST['txt_recuperaMail'])&& isset($_POST['action'])) {

            $maildestinatario = $_POST['txt_recuperaMail'];
            $mailenDB = $maildestinatario.";".EDOCS_MAIL;
           
            $arrayResultados = $this->loginModel->validaMail($mailenDB);

            if ($arrayResultados){
                // Varios destinatarios
                $para  = $maildestinatario . ', '; // atención a la coma
                // título
                $título = 'RESTABLECIMIENTO DE CONTRASENA KAO';
                // mensaje
                $mensaje = '
                <html>
                    <head>
                    <title>RESTABLECIMIENTO DE PASSWORD - KAOSPORT</title>
                    </head>
                    <body>
                        <p>Estes es un mensaje de recuperacion!, no responda este mensaje</p>
                        <p>Nombre: '.$arrayResultados['nombre'].'</p>
                        <p>Usuario: '.$arrayResultados['ruc'].'</p>
                        <p>Password: '.$arrayResultados['password'].'</p>
                    </body>
                </html>
                <label><br>No comparta sus datos de acceso.</label>    ';

                // Para enviar un correo HTML, debe establecerse la cabecera Content-type
                $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
                $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Cabeceras adicionales
                $cabeceras .= 'To:'. $maildestinatario . "\r\n";
                $cabeceras .= 'From: KAOSPORTCENTER <admin@kaosport.com>' . "\r\n";


                // Enviarlo
                    if (mail($para, $título, $mensaje, $cabeceras)){
                       
                        echo '
                        <div class="alert alert-info alert-dismissible fade in" role="alert">
                            <p>Envio correcto a: '.$maildestinatario.'. verifique el mail en su bandeja de entrada.</p>
                            
                        </div>';            
                    }else{
                        echo '
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <p>Error, No se pudo enviar datos al correo '.$maildestinatario.'. Comuníquese con KAO Oficinas para reestablecer su acceso.</p>
                            
                        </div>';
                    }
            }
            else{
                    echo '
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <p>No se encontraron clientes registrados con: '.$mailenDB.'. Comuníquese con KAO Oficinas para reestablecer su acceso.</p>
                        
                    </div>';
                }

        }
    }

    /*Crea elementos HTML opcion button para ser listados en el select*/
    public function showAllDataBaseList(){
        $opciones = $this->loginModel->getAllDataBaseList();

        foreach ($opciones as $opcion) {
            $codigo = $opcion['Codigo'];
            $texto = $opcion['Nombre'];
            echo "<option value='$codigo'>$texto</option>";
    
        }
    }
}
