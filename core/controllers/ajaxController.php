<?php namespace controllers;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

class ajaxController  {

    private $ajaxModel;
    public $defaulDataBase = "MODELOKIND_V7";

    public function __construct() {
        $this->ajaxModel = new \models\ajaxModel();
    }
  
    /* Devuelve array en el formato requerido para el plugin JTable */
    public function getAllEquiposSinMantenimiento($fechaInicio, $fechaFinal, $startIndex, $pageSize) {

        $ajaxModel = new \models\ajaxModel();
        //Respuesta de informacion de VEN_MOV
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $arrayEquipos = $ajaxModel->getArraysMantenimientosEQ($dbEmpresa, $pageSize, $fechaInicio, $fechaFinal);
        $arrayUTF8 = array();
        foreach ($arrayEquipos as $equipo) {
           
            /* Recuperamos filas para validar UTF-8 */
            $CodigoFac= iconv("iso-8859-1", "UTF-8",  $equipo['CodigoFac']);
            $TipoDocumento= iconv("iso-8859-1", "UTF-8",  $equipo['TipoDoc']);
            $FechaCompra= $equipo['FechaCompra'];
            $FechaCompraSPAN = date("Y-m-d", strtotime($FechaCompra));
            $NombreCliente= trim(iconv("iso-8859-1", "UTF-8", $equipo['NombreCliente']));
            $Telefono = trim(iconv("iso-8859-1", "UTF-8", $equipo['Telefono']));
            $CodProducto = trim(iconv("iso-8859-1", "UTF-8", $equipo['CodProducto']));
            $Producto = trim(iconv("iso-8859-1", "UTF-8", $equipo['Producto']));
            $DiasGarantia = trim(iconv("iso-8859-1", "UTF-8", $equipo['DiasGarantiarestantes']));
            $CantitadProd = trim(iconv("iso-8859-1", "UTF-8", $equipo['CantitadProd']));
            $Direccion = trim(iconv("iso-8859-1", "UTF-8", $equipo['Direccion']));
            $Email = trim(iconv("iso-8859-1", "UTF-8", $equipo['Email']));
            $NombreBodega = trim(iconv("iso-8859-1", "UTF-8", $equipo['NombreBodega']));

            $rowdata = ([
                "CodigoFac"=>$CodigoFac, 
                "TipoDocumento"=>$TipoDocumento, 
                "FechaCompra"=>$FechaCompraSPAN, 
                "CodProducto"=>$CodProducto, 
                "Producto"=>$Producto,
                "DiasGarantia"=>$DiasGarantia,
                "CantitadProd"=>$CantitadProd,
                "NombreCliente"=>$NombreCliente, 
                "Telefono"=>$Telefono, 
                "Direccion"=>$Direccion,
                "Email"=>$Email,
                "NombreBodega"=>$NombreBodega
            ]);

            array_push($arrayUTF8, $rowdata);
        }

        return $arrayUTF8;
    }

    /* Envia correo por PHPMailer */
    /* ATECION LOS DATOS DE CUERPO Y LOGS DEBEN NO DEBEN SER MODIFICADOS ESTAS DIRECCIONADOS PARA AJAX */
    public function sendEmail($email, $codMNT){
       
        $IDDocument = '992014V0700062208';
        
        $correoCliente = $email;


        $mail = new PHPMailer(true);  // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = false;                                 // Enable verbose debug output 0->off 2->debug
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'kaomantenimientos@hotmail.com';                 // SMTP username
            $mail->Password = 'kaomnt2019$$';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('kaomantenimientos@hotmail.com', 'Administrador KAO');
            $mail->addAddress($correoCliente, 'Cliente KAO');     // Add a recipient
            $mail->addAddress('soporteweb@sudcompu.net', 'Sistemas');
            $mail->addAddress('mantenimiento@kaosportcenter.com', 'Administrador KAO');
           
            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'KAO Sport - Mantenimiento de Equipos - ' . $codMNT;
            $mail->Body    = $this->getBodyHTMLofEmail($IDDocument);
        
            $mail->send();
            $detalleMail = 'Correo ha sido enviado a : '. $correoCliente;
           
            $pcID = php_uname('n'); // Obtiene el nombre del PC


            function getIP(){
                if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] )) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if( isset( $_SERVER ['HTTP_VIA'] ))  $ip = $_SERVER['HTTP_VIA'];
                else if( isset( $_SERVER ['REMOTE_ADDR'] ))  $ip = $_SERVER['REMOTE_ADDR'];
                else $ip = null ;
                return $ip;
            }

            $ip = getIP();

                $log  = "User: ".$ip.' - '.date("F j, Y, g:i a").PHP_EOL.
                "PCid: ".$pcID.PHP_EOL.
                "Detail: ".$detalleMail.PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.

                file_put_contents('../../../logs/logMailOK.txt', $log, FILE_APPEND );
            
            return array('status' => 'ok', 'mensaje' => $detalleMail ); 

        } catch (Exception $e) {

            function getIP(){
                if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] )) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if( isset( $_SERVER ['HTTP_VIA'] ))  $ip = $_SERVER['HTTP_VIA'];
                else if( isset( $_SERVER ['REMOTE_ADDR'] ))  $ip = $_SERVER['REMOTE_ADDR'];
                else $ip = null ;
                return $ip;
            }

            $ip = getIP();

                $pcID = php_uname('n'); // Obtiene el nombre del PC
                $log  = "User: ".$ip.' - '.date("F j, Y, g:i a").PHP_EOL.
                "PCid: ".$pcID.PHP_EOL.
                "Detail: ".$mail->ErrorInfo .' No se pudo enviar correo a: ' . $correoCliente . PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.
                file_put_contents('../../../logs/logMailError.txt', $log, FILE_APPEND);
                $detalleMail = 'Error al enviar el correo. Mailer Error: '. $mail->ErrorInfo;
                return array('status' => 'false', 'mensaje' => $detalleMail ); 
            
        }

    }


    protected function getBodyHTMLofEmail($IDDocument, $customMesagge=''){
        $empresaData = $this->getInfoEmpresaController();
        $VEN_CAB = $this->getVEN_CABController($IDDocument, '05-9950B');
        if (empty($customMesagge)) {
            $customMesagge = BODY_EMAIL_TEXT;
        }
        return '
        <!doctype html>
            <html>
                <head>
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Email</title>
                <style>
                    /* -------------------------------------
                        GLOBAL RESETS
                    ------------------------------------- */
                    
                    /*All the styling goes here*/
                    
                    img {
                    border: none;
                    margin-bottom: 10px;
                    -ms-interpolation-mode: bicubic;
                    max-width: 100%; 
                    }
                    body {
                    background-color: #f6f6f6;
                    font-family: sans-serif;
                    -webkit-font-smoothing: antialiased;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%; 
                    }
                    table {
                    border-collapse: separate;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    width: 100%; }
                    table td {
                        font-family: sans-serif;
                        font-size: 14px;
                        vertical-align: top; 
                    }
                    /* -------------------------------------
                        BODY & CONTAINER
                    ------------------------------------- */
                    .body {
                    background-color: #f6f6f6;
                    width: 100%; 
                    }
                    /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                    .container {
                    display: block;
                    margin: 0 auto !important;
                    /* makes it centered */
                    max-width: 580px;
                    padding: 10px;
                    width: 580px; 
                    }
                    /* This should also be a block element, so that it will fill 100% of the .container */
                    .content {
                    box-sizing: border-box;
                    display: block;
                    margin: 0 auto;
                    max-width: 580px;
                    padding: 10px; 
                    }
                    /* -------------------------------------
                        HEADER, FOOTER, MAIN
                    ------------------------------------- */
                    .main {
                    background: #ffffff;
                    border-radius: 3px;
                    width: 100%; 
                    }
                    .wrapper {
                    box-sizing: border-box;
                    padding: 20px; 
                    }
                    .content-block {
                    padding-bottom: 10px;
                    padding-top: 10px;
                    }
                    .footer {
                    clear: both;
                    margin-top: 10px;
                    text-align: center;
                    width: 100%; 
                    }
                    .footer td,
                    .footer p,
                    .footer span,
                    .footer a {
                        color: #999999;
                        font-size: 12px;
                        text-align: center; 
                    }
                    /* -------------------------------------
                        TYPOGRAPHY
                    ------------------------------------- */
                    h1,
                    h2,
                    h3,
                    h4 {
                    color: #000000;
                    font-family: sans-serif;
                    font-weight: 400;
                    line-height: 1.4;
                    margin: 0;
                    margin-bottom: 30px; 
                    }
                    h1 {
                    font-size: 35px;
                    font-weight: 300;
                    text-align: center;
                    text-transform: capitalize; 
                    }
                    p,
                    ul,
                    ol {
                    font-family: sans-serif;
                    font-size: 14px;
                    font-weight: normal;
                    margin: 0;
                    margin-bottom: 15px; 
                    }
                    p li,
                    ul li,
                    ol li {
                        list-style-position: inside;
                        margin-left: 5px; 
                    }
                    a {
                    color: #3498db;
                    text-decoration: underline; 
                    }
                    /* -------------------------------------
                        BUTTONS
                    ------------------------------------- */
                    .btn {
                    box-sizing: border-box;
                    width: 100%; }
                    .btn > tbody > tr > td {
                        padding-bottom: 15px; }
                    .btn table {
                        width: auto; 
                    }
                    .btn table td {
                        background-color: #ffffff;
                        border-radius: 5px;
                        text-align: center; 
                    }
                    .btn a {
                        background-color: #ffffff;
                        border: solid 1px #3498db;
                        border-radius: 5px;
                        box-sizing: border-box;
                        color: #3498db;
                        cursor: pointer;
                        display: inline-block;
                        font-size: 14px;
                        font-weight: bold;
                        margin: 0;
                        padding: 12px 25px;
                        text-decoration: none;
                        text-transform: capitalize; 
                    }
                    .btn-primary table td {
                    background-color: #3498db; 
                    }
                    .btn-primary a {
                    background-color: #3498db;
                    border-color: #3498db;
                    color: #ffffff; 
                    }
                    /* -------------------------------------
                        OTHER STYLES THAT MIGHT BE USEFUL
                    ------------------------------------- */
                    .last {
                    margin-bottom: 0; 
                    }
                    .first {
                    margin-top: 0; 
                    }
                    .align-center {
                    text-align: center; 
                    }
                    .align-right {
                    text-align: right; 
                    }
                    .align-left {
                    text-align: left; 
                    }
                    .clear {
                    clear: both; 
                    }
                    .mt0 {
                    margin-top: 0; 
                    }
                    .mb0 {
                    margin-bottom: 0; 
                    }
                    .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
                    max-width: 0;
                    opacity: 0;
                    overflow: hidden;
                    mso-hide: all;
                    visibility: hidden;
                    width: 0; 
                    }
                    .powered-by a {
                    text-decoration: none; 
                    }
                    hr {
                    border: 0;
                    border-bottom: 1px solid #f6f6f6;
                    margin: 20px 0; 
                    }
                    /* -------------------------------------
                        RESPONSIVE AND MOBILE FRIENDLY STYLES
                    ------------------------------------- */
                    @media only screen and (max-width: 620px) {
                    table[class=body] h1 {
                        font-size: 28px !important;
                        margin-bottom: 10px !important; 
                    }
                    table[class=body] p,
                    table[class=body] ul,
                    table[class=body] ol,
                    table[class=body] td,
                    table[class=body] span,
                    table[class=body] a {
                        font-size: 16px !important; 
                    }
                    table[class=body] .wrapper,
                    table[class=body] .article {
                        padding: 10px !important; 
                    }
                    table[class=body] .content {
                        padding: 0 !important; 
                    }
                    table[class=body] .container {
                        padding: 0 !important;
                        width: 100% !important; 
                    }
                    table[class=body] .main {
                        border-left-width: 0 !important;
                        border-radius: 0 !important;
                        border-right-width: 0 !important; 
                    }
                    table[class=body] .btn table {
                        width: 100% !important; 
                    }
                    table[class=body] .btn a {
                        width: 100% !important; 
                    }
                    table[class=body] .img-responsive {
                        height: auto !important;
                        max-width: 100% !important;
                        width: auto !important; 
                    }
                    }
                    /* -------------------------------------
                        PRESERVE THESE STYLES IN THE HEAD
                    ------------------------------------- */
                    @media all {
                    .ExternalClass {
                        width: 100%; 
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                        line-height: 100%; 
                    }
                    .apple-link a {
                        color: inherit !important;
                        font-family: inherit !important;
                        font-size: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                        text-decoration: none !important; 
                    }
                    .btn-primary table td:hover {
                        background-color: #34495e !important; 
                    }
                    .btn-primary a:hover {
                        background-color: #34495e !important;
                        border-color: #34495e !important; 
                    } 
                    }
                </style>
                </head>
                <body class="">
                <span class="preheader">Cotizacion</span>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                    <tr>
                    <td>&nbsp;</td>
                    <td class="container">
                        <div class="content">
            
                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role="presentation" class="main">
            
                            <!-- START MAIN CONTENT AREA -->
                            <tr>
                            <td class="wrapper">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                            <td style="text-align: center">
                                            <img src="'.LOGO_ONLINE.'" height="100" width="200" alt="Logo"> </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    
                                        <p>Estimado, <b> '.$VEN_CAB["NOMBRE"].'</b> dueño de :<b>'.$VEN_CAB["NombreArticulo"].'</b>, le recordamos que:  </p>

                                        <p>
                                            - Dentro de los 4 primeros meses posteriores a la compra, el cliente tiene derecho a 1 
                                            chequeo de garantia sin costo alguno en nuestros locales de servicio tecnico; en Quito:
                                            Av. Amazonas N31-61 y Calle Moreno Bellido; en Guayaquil: Piazza Ceibos Av. de los Bomberos S/N
                                            local A-38 Telefono 043812193.
                                        </p>
                                        <p>
                                        '. $customMesagge .'
                                        </p>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                        <tbody>
                                        <tr>
                                            <td align="center">
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                    <td> <a href="'.SITIOWEB_ONLINE.'" target="_blank">Visitar '.$empresaData["NomCia"].'</a> </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <p>Recuerde mantener sus datos seguros y privados.</p>
                                    <p>Muchas gracias por su confianza!</p>
                                    </td>
                                </tr>
                                </table>
                            </td>
                            </tr>
            
                        <!-- END MAIN CONTENT AREA -->
                        </table>
            
                        <!-- START FOOTER -->
                        <div class="footer">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-block">
                                <span class="apple-link">
                                    Direccion: '.$empresaData["DirCia"].'
                                </span>
                                <br> Necesitas otro requerimiento? Conctacta con nuestro equipo de asesores. '.$empresaData["TelCia"].'</a>.
                                </td>
                            </tr>
                            <tr>
                                <td class="content-block powered-by">
                                No responda a este mensaje, ha sido generado automaticamente.
                                </td>
                            </tr>
                            </table>
                        </div>
                        <!-- END FOOTER -->
            
                        <!-- END CENTERED WHITE CONTAINER -->
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    </tr>
                </table>
                </body>
            </html>
        
        ';
    }


    /* Retorna la respuesta del modelo ajax*/
    public function getInfoEmpresaController(){
        $dataBaseName = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $this->ajaxModel->getDatosEmpresaFromWINFENIX($dataBaseName);
        return $response;
    }

    /* Retorna informacion del VEN_CAB*/
    public function getVEN_CABController($IDDocument, $codProducto){
        $response = $this->ajaxModel->getVENCABByID($IDDocument, $codProducto);
        return $response;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function agendarMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->insertNewMantenimiento($data);
        $mailCliente = trim($data['Email']);
        $newCod = $response['newCod']; 
        $statusOK = $response['status']; // Comprobamos que la respuesta desde el modelo de OK
        $statusMail = array('status' => 'ok', 'mensaje' => 'No se definio correo' );
        if ($statusOK == 'ok' && !empty($mailCliente)) {
            $statusMail = $this->sendEmail($mailCliente, $newCod);
        }
        
        return array('status' => 'ok', 'mensaje' => 'Registro exitoso, codigo de mantenimiento: '. $newCod.'. ', 'email' => $statusMail);
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function agendarExtraMantenimiento($formData){
        $ajaxModel = new \models\ajaxModel();
        $mantenimiento = new \models\MantenimientosClass();
        $dataBaseName = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $codMNT = $formData->codMantenimientoModal;
        $fechaMNT = $formData->uk_dp_proxMant;
       
        $arrayMantenimiento  = $mantenimiento->getMantenimientoByCod($dataBaseName, $codMNT);
        
        $fechaHoraINI = date('Ymd H:i:s', strtotime("$fechaMNT"));
        $fechaHoraFIN = date('Ymd H:i:s', strtotime("$fechaMNT"));
        
            $data = array(
            'CodigoFac' => $arrayMantenimiento['CodigoFac'],
            'CodProducto' => $arrayMantenimiento['CodProducto'],
            'OrdenTrabajo' => NULL,
            'CantitadProd' => 1,
            'Comentario' => 'Mantenimiento programado, segun mantenimiento: '.$codMNT,
            'fechaHoraINI' => $fechaHoraINI,
            'fechaHoraFIN' => $fechaHoraFIN,
            'TipoMantenimiento' => 'MNO',
            'Tecnico' => $arrayMantenimiento['CIEncargado']
            );

        $response = $ajaxModel->insertNewMantenimiento($data);
        return $response;
    }

    /* Verifica si existe el codigo de ordenfisica en mantenimientosEQ*/
    public function isValidOrdenFisica ($formData){
        $ajaxModel = new \models\ajaxModel();
        $codEmpresa = $_SESSION["codEmpresaAUTH"];
        $response = $ajaxModel->isDisponibleOrdenFisica($formData, $codEmpresa);

        if ($response <= 0) {
            return true;
        }else{
            return false;
        }

    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
     public function anularMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->anulaMantenimientoByCod($data);
        return $response;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function anularMantenimientoExterno($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->anulaMantenimientoExternoByCod($data);
        return $response;
    }

    
    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function aprobarMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->aprobarMantenimientoByCod($data);
        return $response;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function aprobarMantenimientoExtenoByCod($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->aprobarMantenimientoExtenoByCod($data);
        return $response;
    }


    /* Realiza peticion al modelo para setear estado 3 al registro de la tabla mantenimientosEQ*/
    public function omitirMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->omitirMantenimientoByCod($data);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getTiposMantenimientos(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysTiposDOCMantenimientos();
        return $response;
    }
    
    /* Retorna la respuesta del modelo ajax*/
    public function getAllTecnicos(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysTecnicos('SBIOKAO');
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getAllTiposEquiposBy($tipoEquipo){
        $response = $this->ajaxModel->getAllTiposEquiposByModel($tipoEquipo);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getAllSupervisores(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysSupervisores('SBIOKAO');
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getAllBodegas(){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $ajaxModel->getArraysBodegas($dbEmpresa);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getSupervisoresEvaluarBy($supervisor){
        $response = $this->ajaxModel->getArraySupervisoresEvaluarBy($supervisor);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getProductoByCod($codProducto){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $ajaxModel->getArrayProducto($dbEmpresa, $codProducto);
        return $response;
    }

    /*Envia informacion al modelo para actualizar, ejecuta insert en WINFENIX, VEN_CAB y VEN_MOV */
    public function updateMantenimientoByCod($formData, $productosArray){
        date_default_timezone_set('America/Lima');
        $ajaxModel = new \models\ajaxModel();
        $VEN_CAB = new \models\venCabClass();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $tipoDOC = 'COT';
        //Actualizacion a WSSP - MantenimientosEQ
        $response_WSSP = $ajaxModel->updateMantenimientoEQ($formData);
       
        if (!empty($productosArray)) {
            
            //Obtenemos informacion de la empresa
            $datosEmpresa = $ajaxModel->getDatosEmpresaFromWINFENIX($dbEmpresa);
            $codIMPORTKAO = trim($ajaxModel->getDatosClienteWINFENIXByRUC('1790417581001', $dbEmpresa)['CODIGO']);
            $serieDocs = $ajaxModel->getDatosDocumentsWINFENIXByTypo($tipoDOC, $dbEmpresa)['Serie'];
            
            if (!$codIMPORTKAO || is_null($codIMPORTKAO)) {
                $codIMPORTKAO = '9999999999999';
            }

            //Crea mos nuevo codigo de VEN_CAB (secuencial)
            $newCodigo = $ajaxModel->getNextNumDocWINFENIX($tipoDOC, $dbEmpresa); // Recuperamos secuencial de SP de Winfenix
            $newCodigoWith0 = $ajaxModel->formatoNextNumDocWINFENIX($dbEmpresa, $newCodigo); // Asignamos formato con 0000X

            $new_cod_VENCAB = $datosEmpresa['Oficina'].$datosEmpresa['Ejercicio'].$tipoDOC.$newCodigoWith0;
            
            //Creacion y asignacion de valores a VEN_CAB
            if ($formData->product_edit_facturadoa == 1) {
                $VEN_CAB->setCliente($formData->codCliente);
                $VEN_CAB->setPorcentDescuento(0);
            }else{
                $VEN_CAB->setCliente($codIMPORTKAO);
                $VEN_CAB->setPorcentDescuento(90);
            }

            $VEN_CAB->setPcID(php_uname('n'));
            $VEN_CAB->setOficina($datosEmpresa['Oficina']);
            $VEN_CAB->setEjercicio($datosEmpresa['Ejercicio']);
            $VEN_CAB->setTipoDoc($tipoDOC);
            $VEN_CAB->setNumeroDoc($newCodigoWith0);
            $VEN_CAB->setFecha(date('Ymd'));
            
            $VEN_CAB->setBodega($formData->product_edit_bodega);
            $VEN_CAB->setDivisa('DOL');
            $VEN_CAB->setProductos($productosArray);
            $VEN_CAB->setSubtotal($VEN_CAB->calculaSubtotal());
            $VEN_CAB->setImpuesto($VEN_CAB->calculaIVA());
            $VEN_CAB->setTotal($VEN_CAB->calculaTOTAL());
            $VEN_CAB->setFormaPago('EFE');
            $VEN_CAB->setSerie($serieDocs); 
            $VEN_CAB->setSecuencia('0'.$newCodigoWith0); //Agregar 0 extra segun winfenix
            $VEN_CAB->setObservacion('MantenimientosApp #'.$formData->codMantenimiento);
            
             //Registro en VEN_CAB y MOV mantenimientosEQ
            $response_VEN_CAB = $ajaxModel->insertVEN_CAB($VEN_CAB, $dbEmpresa);

            $response_MOV_MNT = $ajaxModel->insertMOVMantenimientoEQ($formData, $new_cod_VENCAB);
            
            $arrayVEN_MOVinsets = array();

                foreach ($VEN_CAB->getProductos() as $producto) {
                    $VEN_MOV = new \models\venMovClass();
                    if ($formData->product_edit_facturadoa == 1) {
                        $VEN_MOV->setCliente($formData->codCliente);
                        
                    }else{
                        $VEN_MOV->setCliente($codIMPORTKAO);
                    }
    
                
                    $VEN_MOV->setOficina($datosEmpresa['Oficina']);
                    $VEN_MOV->setEjercicio($datosEmpresa['Ejercicio']);
                    $VEN_MOV->setTipoDoc($tipoDOC);
                    $VEN_MOV->setNumeroDoc($newCodigoWith0);
                    $VEN_MOV->setFecha(date('Ymd h:i:s'));
                    $VEN_MOV->setBodega($formData->product_edit_bodega);
                    $VEN_MOV->setCodProducto(strtoupper($producto->codigo));
                    $VEN_MOV->setCantidad($producto->cantidad);
                    $VEN_MOV->setPrecioProducto($producto->precio);
                    $VEN_MOV->setPorcentajeDescuentoProd($producto->descuento);
                    $VEN_MOV->setTipoIVA('T12');
                    $VEN_MOV->setPorcentajeIVA(12);
                    $VEN_MOV->setPrecioTOTAL($VEN_MOV->calculaPrecioTOTAL());
                    $VEN_MOV->setObservacion('');
                    
                    $response_VEN_MOV = $ajaxModel->insertVEN_MOV($VEN_MOV, $dbEmpresa);
                    
                    array_push($arrayVEN_MOVinsets, $response_VEN_MOV);
                    
                }
         
            $response_Aprobada = $this->aprobarMantenimiento($formData->codMantenimiento);
            
            return array('status' => 'OK', 
                    'mensaje'  => 'Mantenimiento Actualizado, y se registraron los repuestos.',
                    'newCodigoWith0' => $newCodigoWith0,
                    'response_WSSP' => $response_WSSP,
                    'response_VEN_CAB' => false,
                    'response_MOV_MNT' => false,
                    'arrayVEN_MOVinsets' => $arrayVEN_MOVinsets
                ); 

        }else {
            return array('status' => 'OK', 'mensaje'  => 'Actualizado, no se ingresaron repuestos, el mantenimiento continuara abierto ' ,'responses' => $response_WSSP); 
        }
       
        

        
        
    }

    /*Envia informacion al modelo para actualizar, ejecuta insert en WINFENIX, VEN_CAB y VEN_MOV */
    public function updateMantenimientoExternoByCod($formData, $productosArray){
        date_default_timezone_set('America/Lima');
        $ajaxModel = new \models\ajaxModel();
        $VEN_CAB = new \models\venCabClass();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $tipoDOC = 'COT';
        //Actualizacion a WSSP - MantenimientosEQ
        $response_WSSP = $ajaxModel->updateMantenimientoExterno($formData);
       
        if (!empty($productosArray)) {
            
            //Obtenemos informacion de la empresa
            $datosEmpresa = $ajaxModel->getDatosEmpresaFromWINFENIX($dbEmpresa);
            $codIMPORTKAO = trim($ajaxModel->getDatosClienteWINFENIXByRUC('1790417581001', $dbEmpresa)['CODIGO']);
            $serieDocs = $ajaxModel->getDatosDocumentsWINFENIXByTypo($tipoDOC, $dbEmpresa)['Serie'];
            
            if (!$codIMPORTKAO || is_null($codIMPORTKAO)) {
                $codIMPORTKAO = '9999999999999';
            }

            //Crea mos nuevo codigo de VEN_CAB (secuencial)
            $newCodigo = $ajaxModel->getNextNumDocWINFENIX($tipoDOC, $dbEmpresa); // Recuperamos secuencial de SP de Winfenix
            $newCodigoWith0 = $ajaxModel->formatoNextNumDocWINFENIX($dbEmpresa, $newCodigo); // Asignamos formato con 0000X

            $new_cod_VENCAB = $datosEmpresa['Oficina'].$datosEmpresa['Ejercicio'].$tipoDOC.$newCodigoWith0;
            
           
            $VEN_CAB->setCliente($formData->codCliente);
            $VEN_CAB->setPorcentDescuento(0);
           

            $VEN_CAB->setPcID(php_uname('n'));
            $VEN_CAB->setOficina($datosEmpresa['Oficina']);
            $VEN_CAB->setEjercicio($datosEmpresa['Ejercicio']);
            $VEN_CAB->setTipoDoc($tipoDOC);
            $VEN_CAB->setNumeroDoc($newCodigoWith0);
            $VEN_CAB->setFecha(date('Ymd'));
            
            $VEN_CAB->setBodega($formData->product_edit_bodega);
            $VEN_CAB->setDivisa('DOL');
            $VEN_CAB->setProductos($productosArray);
            $VEN_CAB->setSubtotal($VEN_CAB->calculaSubtotal());
            $VEN_CAB->setImpuesto($VEN_CAB->calculaIVA());
            $VEN_CAB->setTotal($VEN_CAB->calculaTOTAL());
            $VEN_CAB->setFormaPago('EFE');
            $VEN_CAB->setSerie($serieDocs); 
            $VEN_CAB->setSecuencia('0'.$newCodigoWith0); //Agregar 0 extra segun winfenix
            $VEN_CAB->setObservacion('MantenimientosApp #'.$formData->codMantenimiento);
            
             //Registro en VEN_CAB y MOV mantenimientosEQ
            $response_VEN_CAB = $ajaxModel->insertVEN_CAB($VEN_CAB, $dbEmpresa);

            // Registro en mov_mantenimientosEQ
            $response_MOV_MNT = $ajaxModel->insertMOVMantenimientoEQ($formData, $new_cod_VENCAB);
            
            $arrayVEN_MOVinsets = array();

                foreach ($VEN_CAB->getProductos() as $producto) {
                    $VEN_MOV = new \models\venMovClass();
                    $VEN_MOV->setCliente($formData->codCliente);
                    $VEN_MOV->setOficina($datosEmpresa['Oficina']);
                    $VEN_MOV->setEjercicio($datosEmpresa['Ejercicio']);
                    $VEN_MOV->setTipoDoc($tipoDOC);
                    $VEN_MOV->setNumeroDoc($newCodigoWith0);
                    $VEN_MOV->setFecha(date('Ymd h:i:s'));
                    $VEN_MOV->setBodega($formData->product_edit_bodega);
                    $VEN_MOV->setCodProducto(strtoupper($producto->codigo));
                    $VEN_MOV->setCantidad($producto->cantidad);
                    $VEN_MOV->setPrecioProducto($producto->precio);
                    $VEN_MOV->setPorcentajeDescuentoProd($producto->descuento);
                    $VEN_MOV->setTipoIVA('T12');
                    $VEN_MOV->setPorcentajeIVA(12);
                    $VEN_MOV->setPrecioTOTAL($VEN_MOV->calculaPrecioTOTAL());
                    $VEN_MOV->setObservacion('');
                    
                    $response_VEN_MOV = $ajaxModel->insertVEN_MOV($VEN_MOV, $dbEmpresa);
                    
                    array_push($arrayVEN_MOVinsets, $response_VEN_MOV);
                    
                }
         
            $response_Aprobada = $this->aprobarMantenimientoExtenoByCod($formData->codMantenimiento);
            
            return array('status' => 'OK', 
                    'mensaje'  => 'Mantenimiento Actualizado, y se registraron los repuestos.',
                    'newCodigoWith0' => $newCodigoWith0,
                    'response_WSSP' => $response_WSSP,
                    'response_VEN_CAB' => $response_VEN_CAB,
                    'response_MOV_MNT' => $response_MOV_MNT,
                    'arrayVEN_MOVinsets' => $arrayVEN_MOVinsets
                ); 

        }else {
            return array('status' => 'OK', 'mensaje'  => 'Actualizado, no se ingresaron repuestos, el mantenimiento continuara abierto ' ,'responses' => $response_WSSP); 
        }
       
        

        
        
    }

    /* AJAX ESTADISTICAS - Get conteo de mantenimientos */
    public function getCountMantenimientosController($codEmpresa){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $ajaxModel->getCountMantenimientos($codEmpresa);
        return $response;
    }

    /* AJAX ESTADISTICAS - Get conteo de mantenimientos */
    public function getHistoricoController($fechaINI, $fechaFIN, $codEmpresa, $tiposDocs){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
       
        $response = $ajaxModel->getHistorico($dbEmpresa, $fechaINI, $fechaFIN, $codEmpresa, $tiposDocs);
        return $response;
    }
    
    /* AJAX SUPERVISORES - Retorna todos los checklist de la DB */
    public function getCheckListActBasicasController(){

        $response = $this->ajaxModel->getCheckListActBasicasModel();
        return $response;
    }

    /* AJAX SUPERVISORES - Retorna todos los checklist de la DB */
    public function searchClienteController($value, $by){

        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $this->ajaxModel->searchClienteModel($value, $by, $dbEmpresa);
        return $response;
    }

    public function getInfoClienteController($RUC){
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $this->ajaxModel->getInfoClienteModel($RUC, $dbEmpresa);
        return $response;
    }

    public function saveMantenimientoExternoController($formDataObject){
        $response = $this->ajaxModel->persist_MantenimientoExterno($formDataObject);
        return $response;
    }
    
}
