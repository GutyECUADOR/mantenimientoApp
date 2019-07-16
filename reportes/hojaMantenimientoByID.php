<?php
  use Mpdf\Mpdf;

    session_start();
    require_once '../vendor/autoload.php';

    //Seteo de PDF
        $fecha = date ("Y-n-j");
      
        $css = file_get_contents('style.css');
        $destino = 'I';
    
        $codigoMNT = filter_input(INPUT_GET,'codigoMNT');
        $codEmpresa = trim($_SESSION["empresaAUTH"]);  // Nombre de la db asiganda en el login 

        $doc_sinespacios = str_replace(" ", "", $codigoMNT); //Quitar espacios en blanco
        $name_doc = 'mantenimiento_'.$doc_sinespacios.'.pdf';

        $mantenimientos = new models\MantenimientosClass();
        $arrayMantenimiento = $mantenimientos->getDataMantenimiento($codEmpresa, $codigoMNT); //Devuelve array de mantenimientos
        
        $dateTimeINI = new DateTime($arrayMantenimiento[0]['fechaInicio']);
        $horaINI = date_format($dateTimeINI, "H:i");

        $dateTimeFIN = new DateTime($arrayMantenimiento[0]['fechaFin']);
        $horaFIN = date_format($dateTimeFIN, "H:i");

        $ajaxController = new \controllers\ajaxController();
        $empresaData = $ajaxController->getInfoEmpresaController();
     
    $html = '
      
    <body>
      <header class="clearfix">
        <div id="logo">
          <img src="../assets/img/logo_dark.png" height="30px">
        </div>
        <h1>MANTENIMIENTO DE EQUIPOS - ORDEN DE TRABAJO</h1>
        <div id="contenedor_info">
              <div id="company" class="clearfix">
                <div>'. $empresaData["NomCia"] .'</div>
                <div>'.$empresaData["DirCia"].'</div>
                <div>'.$empresaData["TelCia"].'</div>
                <div><a href="mailto:info@kaosport.com">info@kaosport.com</a></div>
              </div>
              <div id="datos1">
                <div><span>FACTURA: </span> '. $arrayMantenimiento[0]['CodigoFac'].' </div>
                <div><span>COD. MANTENIMIENTO: </span>'. $codigoMNT .'</div>
                <div><span>CLIENTE: </span> '. $arrayMantenimiento[0]['NombreCliente'] .'</div>
                <div><span>TÈCNICO KAO: </span> '. $arrayMantenimiento[0]['Encargado'] .'</div>
                <div><span>DIRECCION: </span> '. $arrayMantenimiento[0]['Direccion'] .'  </div>
              </div>
        </div>    
      </header>
      <main>
        <div>
          <table>
              <thead>
                  <tr>
                    <th class="title-row" colspan="2" style="font-size:12px;">EQUIPO</th>
                </tr>
              </thead>

              <tboby>
              <tr>
                  <td class="service rownormal" colspan="2" style="font-size:20px;">'. $arrayMantenimiento[0]['Producto'] .'</td>
              </tr>

              <tr>
                <td class="service rownormal"><img src="../assets/img/bicicleta.png" height="250px"></td>
                <td class="service rownormal"><img src="../assets/img/multifuerza.jpg" height="250px"></td>
              </tr>

              <tr>
                <td class="service rownormal"><img src="../assets/img/estatica.jpg" height="250px"></td>
                <td class="service rownormal"><img src="../assets/img/caminadora.png" height="250px"></td>
              </tr>

              <tr>
                  <th class="title-row" colspan="2" style="font-size:12px;"> OBSERVACIONES </th>
              </tr>

              <tr>
                  <td class="service rownormal" colspan="2" style="font-size:12px;">
                    '. 
                    $arrayMantenimiento[0]['comentario'] 
                    
                    .'
                  </td>
              </tr>

              <tr>
                  <th class="title-row" colspan="2" style="font-size:12px;"> CONDICIONES DE RETIRO Y ENTREGA </th>
              </tr>

              <tr>
                  <td colspan="2" style="font-size:12px;  text-align: left;">
                    <ul>
                      <li>Si la garantia de la bicicleta no esta vigente, el costo de la revision sera asumido por el cliente.</li>
                      <li>La presente autorizacion expresa que: Siendo el propietario o actuando como representante de la misma, estoy en condiciones de autorizar los servicios anotados, asi como el reembolso de las piezas que fueren necesarias para la ejecucion de los mismos.</li>
                      <li>La empresa queda facultada para retener la bicicleta o mercaderia mientras este pendiente la cancelacion de la factura.</li>
                      <li>El cliente proveera las facilidades necesarias para el retiro y entrega de la bicicleta o equipo.</li>
                      <li>Por motivos de logistica y por servirles mejor no se podra ofrecer una hora exacta. </li>
                      <li>Se entregara la bicicleta o equipo reparado, probada por el tecnicopara satisfaccion del cliente. </li>
                      <li>El mantenimiento debera ser realizado por el personal de la empresa, encaso de ser revisado por terceras personas la empresa no se responsabiliza</li>
                      <li><strong>Tiene un lapso de tres meses laborables para retirar la bicicleta a partir de la fecha de recepcion. Pasado este periodo el local no se responsabiliza por la bicicleta olvidada</strong></li>
                    </ul>  
                  </td>
              </tr>

              
                                      
              </tbody>
          </table>
        </div>

      

        <div id="cont_firmas">
          <div id="firma1">Firma de Cliente</div>
          <div id="firma2">Firma Servicio Tecnico</div>
        </div>

      </main>

        
    </body>


        ';
            
  
    $mpdf = new mPDF();
    $mpdf->WriteHTML($css,1);
    $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->WriteHTML($html);
    $mpdf->SetTitle("Reporte Generado");
    $mpdf->Output($name_doc, $destino);
    
        
       