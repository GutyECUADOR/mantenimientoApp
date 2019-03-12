<?php
date_default_timezone_set('America/Lima');
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './src/Exception.php';
require './src/PHPMailer.php';
require './src/SMTP.php';

//Load Composer's autoloader
//require 'vendor/autoload.php';
$correoCliente = 'monicachiluiza@kaosportcenter.com';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output 0->off 2->debug
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'kaomantenimiento@hotmail.com';                 // SMTP username
    $mail->Password = 'kaomnt2019$$';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('mantenimiento@kaosportcenter.com', 'Administrador KAO');
    $mail->addAddress($correoCliente, 'Cliente KAO');     // Add a recipient
    $mail->addAddress('soporteweb@sudcompu.net', 'Sistemas');
    /* $mail->addAddress('ellen@example.com');               // Name is optional
    
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com'); */

    /* //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
 */
    //Content
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'KAO Sport - Mantenimiento de Equipos';
    $mail->Body    = file_get_contents('card_mantenimiento.php');
  
    $mail->send();
    $detalleMail = 'Correo ha sido enviado a : '. $correoCliente;
    echo $detalleMail;

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

        file_put_contents('./logs/logOK.txt', $log, FILE_APPEND );

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
        file_put_contents('./logs/logError.txt', $log, FILE_APPEND);
        echo 'Error al enviar el correo. Mailer Error: ', $mail->ErrorInfo;
    
}