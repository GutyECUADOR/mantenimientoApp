<?php
    session_start();
    require_once '../core/models/MantenimientosClass.php';
    require_once '../config/global.php';
    require_once '../libs/mpdf/mpdf.php';

   
    //Seteo de PDF
        $fecha = date ("Y-n-j");
        
        $name_doc = 'mantenimientoEQ_'.$doc_sinespacios.'.pdf';
        $css = file_get_contents('style.css');
        $destino = 'I';
    
        $codigoMNT = filter_input(INPUT_GET,'codigoMNT'); 
        $empresa_select = '002';
        $doc_sinespacios = str_replace(" ", "", $codigoMNT); //Quitar espacios en blanco

        $codEmpresa = trim($_SESSION["empresaAUTH"]);  // Nombre de la db asiganda en el login
        $mantenimientos = new models\MantenimientosClass();
        $arrayMantenimiento = $mantenimientos->getDataMantenimiento($codEmpresa, $codigoMNT); //Devuelve array de mantenimientos
        
        $dateTimeINI = new DateTime($arrayMantenimiento[0]['fechaInicio']);
        $horaINI = date_format($dateTimeINI, "H:i");

        $dateTimeFIN = new DateTime($arrayMantenimiento[0]['fechaFin']);
        $horaFIN = date_format($dateTimeFIN, "H:i");
     
    $html = '
      
    <body>
    <header class="clearfix">
      <div id="logo">
        <img src="logo.png" height="60" width="110">
      </div>
      <h1>INFORME - MANTENIMIENTO DE EQUIPOS</h1>
      <div id="contenedor_info">
            <div id="company" class="clearfix">
              <div>KAO Sport Center</div>
              <div>Av. de los Shyris y Naciones Unidas Edificio Nuñez Vela<br /> Quito, Ecuador</div>
              <div>(593-2)-2550005</div>
              <div><a href="mailto:info@kaosport.com">info@kaosport.com</a></div>
            </div>
            <div id="datos1">
              <div><span>FACTURA: </span> '. $arrayMantenimiento[0]['CodigoFac'].' </div>
              <div><span>COD. MANTENIMIENTO: </span>'. $codigoMNT .'</div>
              <div><span>CLIENTE: </span> '. $arrayMantenimiento[0]['NombreCliente'] .'</div>
              <div><span>TÈCNICO KAO: </span> '. $arrayMantenimiento[0]['Encargado'] .'</div>
              <div><span>DIRECCION: </span> '. $arrayMantenimiento[0]['Direccion'] .'  </div>
              <div><span>TIEMPO ESTIMADO: </span> De '. $horaINI .' a  '. $horaFIN .'</div>
            </div>
      </div>    
    </header>
    <main>
      <div>
        <table>
            <thead>
                <tr>
                  <th class="title-row">Equipos</th>
                 
              </tr>
            </thead>
            <tboby>
            <tr>
                <td class="service">'. $arrayMantenimiento[0]['Producto'] .'</td>
            </tr>
                                     
            </tbody>
        </table>
      </div>

      <div>
        <table>
            <thead>
                <tr>
                  <th class="title-row" colspan="2">ACTIVIDADES DE MANTENIMIENTO PREVENTIVO </th>
                 
              </tr>
            </thead>
            <tboby>
            <tr>
                <td class="service">Verificación de estado de funcionamiento equipo</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>
            <tr>
                <td class="service">Limpieza de estructura interna y externa del equipo </td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>

            <tr>
              <td class="service">Limpieza de sensores</td>
              <td class="service">
                <input type="checkbox" height="50" width="50">Si
                <input type="checkbox" height="50" width="50">No
                <input type="checkbox" height="50" width="50">No aplica
              </td>
            </tr>

            <tr>
                <td class="service">Limpieza del mecanismo interno</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>
            <tr>
                <td class="service">Limpieza de panel y revisión de funcionamiento</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>
            <tr>
                <td class="service">Limpieza de las cubiertas plásticas</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>
            <tr>
                <td class="service">Ajuste interno </td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>

            <tr>
                <td class="service">Ajuste externo</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>

            <tr>
                <td class="service">Lubricación y calibración</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>

            <tr>
                <td class="service">Encendido, pruebas de funcionamiento.</td>
                <td class="service">
                  <input type="checkbox" height="50" width="50">Si
                  <input type="checkbox" height="50" width="50">No
                  <input type="checkbox" height="50" width="50">No aplica
                </td>
            </tr>

            
                                     
            </tbody>
        </table>
      </div>

      <div id="cont_firmas">
        <div id="firmasola">Firma de Cliente</div>
      </div>

    </main>

        
      </body>


        ';
            
  
    $mpdf = new mPDF('c','A4');
    $mpdf->WriteHTML($css,1);
    $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->WriteHTML($html);
    $mpdf->SetTitle("Reporte Generado");
    $mpdf->Output($name_doc, $destino);
    
        
       