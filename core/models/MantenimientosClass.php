<?php namespace models;
require_once 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MantenimientosClass {

    private $instanciaDB;
    private $db;
    
    public function __construct() {
        $this->instanciaDB = new \models\conexion();
        $this->db = $this->instanciaDB->getInstanciaCNX();
    }

    /*
        Recupera los registros de la tabla mantenimientosEQ en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getMantenimientosAgendados($dataBaseName='KAO_wssp', $cantidad=1, $fechaINI, $fechaFIN) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        /* $fechaINI = $this->first_month_day(); //$this->getPrimerDiaMes()['StartOfMonth'];
        $fechaFIN = $this->last_month_day(); //$this->getUltimoDiaMes()['EndOfMonth']; */
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP $cantidad
			Compra.ID as CodigoFac,
            Compra.FECHA as FechaCompra,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as Bodega,
            SBIO.Apellido + SBIO.Nombre as Encargado,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE COLLATE DATABASE_DEFAULT = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO COLLATE DATABASE_DEFAULT = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA COLLATE DATABASE_DEFAULT = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE DATABASE_DEFAULT = WSSP.codFactura 
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP2 on Compra.CODIGO COLLATE DATABASE_DEFAULT  = WSSP.codEquipo  
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable COLLATE DATABASE_DEFAULT = SBIO.Cedula


        WHERE 
            WSSP.codEmpresa = '$codEmpresa'
            AND WSSP.estado = '0'
            
        GROUP BY 
            Producto.Codigo,
            Compra.ID,
            Compra.FECHA,
            Producto.NOMBRE,
            Compra.CLIENTE,
            Cliente.NOMBRE,
            Cliente.TELEFONO1,
            Cliente.DIRECCION1,
            Cliente.EMAIL,
            Bodega.NOMBRE,
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
	        WSSP.fechaFin
            
        ORDER BY NombreCliente ASC 

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
                return $arrayResultados;
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera los registros de la tabla mantenimientosEQExternos en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getMantenimientosExternosAgendados($dataBaseName='KAO_wssp', $cantidad=1, $fechaINI, $fechaFIN) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        $query = "
            SELECT 
                Cliente.NOMBRE as NombreCliente,
                Cliente.TELEFONO1 as Telefono,
                Cliente.DIRECCION1 as Direccion,
                Cliente.EMAIL as Email,
                WSSP.*,
                SBIO.Apellido + SBIO.Nombre as Encargado

            FROM 
                dbo.COB_CLIENTES as Cliente
            INNER JOIN KAO_wssp.dbo.mantExternosEQ_CAB as WSSP on Cliente.RUC COLLATE DATABASE_DEFAULT = WSSP.cliente 
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.tecnico COLLATE DATABASE_DEFAULT = SBIO.Cedula

            WHERE 
                WSSP.empresa = '$codEmpresa'
                AND WSSP.estado = '0'
            
            ORDER BY NombreCliente ASC 


        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 

            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera los registros de la tabla mantenimientosEQ en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getMantenimientosHistorico($fechaINI, $fechaFIN, $tiposDocs, $rucCliente='', $cantidad=1000, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        $filtroDOC = $this->getFiltroTiposDoc($tiposDocs);
        $filtroRUC = $this->getFiltroRUC($rucCliente);
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP $cantidad 
            Compra.ID as CodigoFac,
            Mant.codMantenimiento as CodMNT,
            Mant.codOrdenFisica as CodOrdenFisica,
            Mant.codEquipo as CodProducto,
            Mant.responsable as rucResponsable,
            SBIO.Apellido + SBIO.Nombre as nombreResponsable,
            Producto.Nombre as NombreProducto,
            Cliente.NOMBRE as Cliente,
            Cliente.RUC,
            Mant.tipo as TipoMant,
            Mant.fechaInicio as FechaINI,
            CAB.NUMREL as NUMREL,
            Mant.comentario as Comentario,
            Mant.estado as Estado,
            MOV_MNT.codVENCAB as numRELCOT,
            cobro.ID as facturaCOT,
            cobro.TOTAL as totalFactura       
                                    
        FROM
            dbo.VEN_CAB as Compra
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as Mant ON Mant.codFactura COLLATE Modern_Spanish_CI_AS = Compra.ID
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Producto.Codigo COLLATE Modern_Spanish_CI_AS = Mant.codEquipo
            LEFT JOIN dbo.VEN_CAB as CAB on CAB.ID = Compra.ID
            LEFT JOIN KAO_wssp.dbo.mov_mantenimientosEQ as MOV_MNT on MOV_MNT.codMantenimiento = Mant.codMantenimiento
            LEFT JOIN dbo.VEN_CAB as cobro on cobro.NUMREL COLLATE Modern_Spanish_CI_AS = MOV_MNT.codVENCAB
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on SBIO.Cedula = Mant.responsable      
        WHERE 
            Mant.codEmpresa = '$codEmpresa'
            AND Mant.fechaInicio BETWEEN '$fechaINI' AND '$fechaFIN'   
            ".$filtroDOC."
            ".$filtroRUC."
            

        ORDER BY CodMNT DESC

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
      

            if($stmt->execute()){
                $resulset = $stmt->fetchAll( \PDO::FETCH_ASSOC );
                
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera los registros de la tabla mantenimientosEQ en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getMantenimientosHistoricoEXT($fechaINI, $fechaFIN, $tiposDocs, $rucCliente='', $cantidad=1000, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        $filtroDOC = $this->getFiltroTiposDoc($tiposDocs);
        $filtroRUC = $this->getFiltroRUC($rucCliente);

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP $cantidad 
            cliente.RUC,
            cliente.NOMBRE  as ClienteName,
            Mant.*,
            SBIO.Apellido + SBIO.Nombre as nombreTecnico,
            MOV_MNT.codVENCAB as numRELCOT,
            cobro.ID as facturaCOT,
            cobro.TOTAL as totalFac
        FROM 
            dbo.COB_CLIENTES as Cliente
            INNER JOIN KAO_wssp.dbo.mantExternosEQ_CAB as Mant  on Mant.cliente COLLATE Modern_Spanish_CI_AS = Cliente.RUC
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO ON SBIO.Cedula = Mant.tecnico
            LEFT JOIN KAO_wssp.dbo.mov_mantenimientosEQ as MOV_MNT on MOV_MNT.codMantenimiento = Mant.codMantExt
            LEFT JOIN dbo.VEN_CAB as cobro on cobro.NUMREL COLLATE Modern_Spanish_CI_AS = MOV_MNT.codVENCAB
        WHERE 
            Mant.empresa = '$codEmpresa' 
            AND fechaCreacion BETWEEN '$fechaINI' AND '$fechaFIN'
            ".$filtroDOC."
            ".$filtroRUC."
        
        ORDER BY Mant.codMantExt DESC
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetchAll( \PDO::FETCH_ASSOC );
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera el registro con el codigo indicado tabla mantenimientosEQ en KAO_wssp
    */
    public function getMantenimientoByCod($dataBaseName='KAO_wssp', $codMantenimiento) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP 1
            Compra.ID as CodigoFac,
            Compra.FECHA as FechaCompra,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as Bodega,
            SBIO.Cedula as CIEncargado,
            SBIO.Nombre + SBIO.Apellido as Encargado,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin,
            WSSP.comentario,
            WSSP.estado
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE COLLATE DATABASE_DEFAULT = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO COLLATE DATABASE_DEFAULT = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA COLLATE DATABASE_DEFAULT = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE DATABASE_DEFAULT = WSSP.codFactura
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP2 on Compra.CODIGO COLLATE DATABASE_DEFAULT  = WSSP.codEquipo  
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable COLLATE DATABASE_DEFAULT = SBIO.Cedula

        WHERE 
            WSSP.codEmpresa = '$codEmpresa'
            AND WSSP.codMantenimiento = '$codMantenimiento'

        GROUP BY 
            Producto.Codigo,
            Compra.ID,
            Compra.FECHA,
            Producto.NOMBRE,
            Compra.CLIENTE,
            Cliente.NOMBRE,
            Cliente.TELEFONO1,
            Cliente.DIRECCION1,
            Cliente.EMAIL,
            Bodega.NOMBRE,
            SBIO.Cedula,
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin,
            WSSP.comentario,
            WSSP.estado
        ORDER BY NombreCliente ASC 

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera el registro con el codigo indicado tabla mantenimientosEQ en KAO_wssp
    */
    public function getMantenimientoExternoByCod($dataBaseName='KAO_wssp', $codMantenimiento) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT 
            Cliente.NOMBRE as NombreCliente,
            Cliente.RUC as RUC,
            Cliente.CODIGO as CodCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            WSSP.*,
            SBIO.Apellido + SBIO.Nombre as Encargado
        
        FROM 
            dbo.COB_CLIENTES as Cliente
        INNER JOIN KAO_wssp.dbo.mantExternosEQ_CAB as WSSP on Cliente.RUC COLLATE DATABASE_DEFAULT = WSSP.cliente 
        INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.tecnico COLLATE DATABASE_DEFAULT = SBIO.Cedula
        
        WHERE 
            WSSP.empresa = '$codEmpresa'
            AND WSSP.codMantExt = '$codMantenimiento'
            
        ORDER BY NombreCliente ASC 

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera el registro en VEN_MOD de los productos asignados al ID de factura y eligo de mantenimiento
    */
    public function getRepuestosOfMantenimientoByCod($codMantenimiento, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        
        SELECT
            VEN_CAB.ID,
            VEN_CAB.FECHA,
            VEN_CAB.CLIENTE as codCliente,
            CLIENTE.NOMBRE as facturadoA,
            CLIENTE.RUC,
            VEN_CAB.BODEGA as codBodega,
            BODEGA.NOMBRE  as bodegaName,
            VEN_CAB.TOTAL 
        FROM 
            dbo.VEN_CAB as VEN_CAB
            INNER JOIN dbo.COB_CLIENTES as CLIENTE on CLIENTE.CODIGO = VEN_CAB.CLIENTE
            INNER JOIN dbo.INV_BODEGAS as BODEGA on BODEGA.CODIGO = VEN_CAB.BODEGA

        WHERE 
            ID COLLATE Modern_Spanish_CI_AS IN (SELECT codVENCAB FROM KAO_wssp.dbo.mov_mantenimientosEQ WHERE codMantenimiento = '$codMantenimiento')
            ORDER BY VEN_CAB.ID
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
                return $arrayResultados;
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    
    /* Retorna el nombre array con la clave NameDatabase y Codigo para el nombre de la DB, para ser usada en la conexion*/ 
    public function getCodeDBByName($nombreDB){
        $query = "SELECT TOP 1 NameDatabase, Codigo FROM SBIOKAO.dbo.Empresas_WF WHERE NameDatabase = :NameDatabase"; 
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':NameDatabase', $nombreDB); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  
    }

    public function last_month_day() { 
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
   
        return date('Ymd', mktime(0,0,0, $month, $day, $year));
    }
   
    /** Actual month first day **/
    public function first_month_day() {
        $month = date('m');
        $year = date('Y');
        return date('Ymd', mktime(0,0,0, $month, 1, $year));
    }

    public function getDescStatus($codigo) {
        
        switch ($codigo) {
            case 0:
            return 'Pendiente';
            break;
            
            case 1:
            return 'Finalizada';
            break;

            case 2:
            return 'Anulada';
            break;
            
            case 3:
            return 'Omitida';
            break;

            default:
            return 'No difinida';
            
            break;
        }
       
    }

    public function getDescTipoMant($codigo) {
        
        switch ($codigo) {
            case 'MNO':
            return 'Mant. Orden de Serivcios';
            break;
            
            case 'OMT':
            return 'Mant. Omitido';
            break;

            case 'MNC':
            return 'Mant. Correctivo';
            break;
            
            case 'MNP':
            return 'Mant. Preventivo';
            break;

            default:
            return 'No difinida';
            
            break;
        }
       
    }

    public function pipeFormatDate($fecha) {
        return date('Y-m-d', strtotime($fecha));
    }

    public function getColorBadge($codigo) {
        
        switch ($codigo) {
            case 0:
            return 'uk-badge-primary';
            break;
            
            case 1:
            return 'uk-badge-success';
            break;

            case 2:
            return 'uk-badge-danger';
            break;
            
            case 3:
            return 'uk-badge-warning';
            break;

            default:
            return '';
            
            break;
        }
       
    }

    public function getDataMantenimiento($dataBaseName='KAO_wssp', $codMantenimiento=null) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP 1
            Compra.ID as CodigoFac,
            WSSP.codMantenimiento as CodMNT,
            Compra.FECHA as FechaCompra,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as Bodega,
            SBIO.Apellido + SBIO.Nombre as Encargado,
            WSSP.fechaInicio,
            WSSP.fechaFin,
            WSSP.comentario
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE Modern_Spanish_CI_AS = WSSP.codFactura
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Producto.CODIGO COLLATE Modern_Spanish_CI_AS = WSSP.codEquipo 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA = Bodega.CODIGO
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable = SBIO.Cedula
        
        WHERE 
            WSSP.estado = '0'
            AND WSSP.codMantenimiento = '$codMantenimiento'
        GROUP BY 
            Producto.Codigo,
            WSSP.codMantenimiento,
            Compra.ID,
            Compra.FECHA,
            Producto.NOMBRE,
            Compra.CLIENTE,
            Cliente.NOMBRE,
            Cliente.TELEFONO1,
            Cliente.DIRECCION1,
            Cliente.EMAIL,
            Bodega.NOMBRE,
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.fechaInicio,
            WSSP.fechaFin,
            WSSP.comentario
	


        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
                return $arrayResultados;
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    public function generaInformeMantInternosPDF($fechaINI, $fechaFIN, $tiposDocs, $ruc, $codEmpresa, $outputMode = 'S'){

        $equiposHistorico = $this->getMantenimientosHistorico($fechaINI, $fechaFIN, $tiposDocs, $ruc, $cantidad=1000, $codEmpresa);
        
         $html = '
             
             <div style="width: 100%;">
         
                 <div style="float: right; width: 100%;">
                     <div id="informacion">
                        
                         <h4>REPORTE - MANTENIMIENTO DE EQUIPOS INTERNOS</h4>
                         <h4>EMPRESA:  '.$codEmpresa.'</h4>
                         <h4>FECHA: '.$fechaINI.'- '.$fechaFIN.'</h4>
                        
                     </div>
                 </div>
         
                
             </div>
         
             
             <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
                 <thead>
                     <tr>
                         <td width="3%">#</td>
                         <td width="13%">Factura.</td>
                         <td width="8%">Cod Mant.</td>
                         <td width="7%">Mant. Fisico</td>
                         <td width="10%">Cod. Equipo</td>
                         <td width="15%">Equipo</td>
                         <td width="15%">Cliente</td>
                         <td width="8%">Fecha Agendada.</td>
                         <td width="8%">Tipo</td>
                         <td width="7%">Estado</td>
                         <td width="15%">Comentario</td>
                     </tr>
                 </thead>
             <tbody>
         
             <!-- ITEMS HERE -->
             ';

                $cont = 1;
                 foreach($equiposHistorico as $row){
                    
                     $html .= '
         
                     <tr>
                         <td align="center">'.$cont.'</td>
                         <td>'.$row["CodigoFac"].'</td>
                         <td>'.$row["CodMNT"].'</td>
                         <td>'.$row["CodOrdenFisica"].'</td>
                         <td>'.$row["CodProducto"].'</td>
                         <td>'.$row["NombreProducto"].'</td>
                         <td>'.$row["Cliente"].'</td>
                         <td>'.$this->pipeFormatDate($row["FechaINI"]).'</td>
                         <td>'.$this->getDescTipoMant(trim($row["TipoMant"])).'</td>
                         <td>'.$this->getDescStatus($row["Estado"]).'</td>
                         <td>'.$row["Comentario"].'</td>
                       
                     </tr>';
                     $cont++;
                     }
                
             $html .= ' 
             
             
         
             <!-- END ITEMS HERE -->
                
         
             </tbody>
             </table>
 
             
         
             
         ';
 
         //==============================================================
         //==============================================================
         //==============================================================
 
         /* require_once '../../../vendor/autoload.php'; */
         $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
 
         // LOAD a stylesheet
         $stylesheet = file_get_contents('../../../assets/css/reportesStyles.css');
         
         $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
 
         $mpdf->WriteHTML($html);
         
         return $mpdf->Output('doc.pdf', $outputMode);
 
         //==============================================================
         //==============================================================
         //==============================================================
 
    }

    public function generaInformeMantExternosPDF($fechaINI, $fechaFIN, $tiposDocs, $ruc, $codEmpresa, $outputMode = 'S'){

        $equiposHistorico = $this->getMantenimientosHistoricoEXT($fechaINI, $fechaFIN, $tiposDocs, $ruc, $cantidad=1000, $codEmpresa);
        
         $html = '
             
             <div style="width: 100%;">
         
                 <div style="float: right; width: 100%;">
                     <div id="informacion">
                        
                         <h4>REPORTE - MANTENIMIENTO DE EQUIPOS EXTERNOS</h4>
                         <h4>EMPRESA:  '.$codEmpresa.'</h4>
                         <h4>FECHA: '.$fechaINI.'- '.$fechaFIN.'</h4>
                        
                     </div>
                 </div>
         
                
             </div>
         
             
             <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
                 <thead>
                     <tr>
                         <td width="5%">#</td>
                         <td width="10%">CI/RUC.</td>
                         <td width="15%">Cliente.</td>
                         <td width="15%">Equipo</td>
                         <td width="8%">Cod. Mant</td>
                         <td width="8%">Cod. Mant Fisico</td>
                         <td width="10%">Fecha Agendada</td>
                         <td width="10%">Fecha Entrega</td>
                         <td width="15%">comentario</td>
                         <td width="7%">Estado</td>
                     </tr>
                 </thead>
             <tbody>
         
             <!-- ITEMS HERE -->
             ';

                $cont = 1;
                 foreach($equiposHistorico as $row){
                    
                     $html .= '
         
                     <tr>
                         <td align="center">'.$cont.'</td>
                         <td>'.$row["RUC"].'</td>
                         <td>'.$row["ClienteName"].'</td>
                         <td>'.$row["serieModelo"].'</td>
                         <td>'.$row["codMantExt"].'</td>
                         <td>'.$row["codOrdenFisica"].'</td>
                         <td>'.$this->pipeFormatDate($row["fechaPrometida"]).'</td>
                         <td>'.$this->pipeFormatDate($row["fechaEntrega"]).'</td>
                         <td>'.$row["comentario"].'</td>
                         <td>'.$this->getDescStatus($row["estado"]).'</td>
                       
                     </tr>';
                     $cont++;
                     }
                
             $html .= ' 
             
             
         
             <!-- END ITEMS HERE -->
                
         
             </tbody>
             </table>
 
             
         
             
         ';
 
         //==============================================================
         //==============================================================
         //==============================================================
 
         /* require_once '../../../vendor/autoload.php'; */
         $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
 
         // LOAD a stylesheet
         $stylesheet = file_get_contents('../../../assets/css/reportesStyles.css');
         
         $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
 
         $mpdf->WriteHTML($html);
         
         return $mpdf->Output('doc.pdf', $outputMode);
 
         //==============================================================
         //==============================================================
         //==============================================================
 
    }

    public function generaInformeMantInternosExcel($fechaINI, $fechaFIN, $tiposDocs, $ruc, $codEmpresa){

        $equiposHistorico = $this->getMantenimientosHistorico($fechaINI, $fechaFIN, $tiposDocs, $ruc, $cantidad=1000, $codEmpresa);
        
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setCellValue('A1', '#')
            ->setCellValue('B1', 'Factura')
            ->setCellValue('C1', 'Cod. Mantenimiento')
            ->setCellValue('D1', 'Mant. Fisico')
            ->setCellValue('E1', 'Cod. Equipo')
            ->setCellValue('F1', 'Equipo')
            ->setCellValue('G1', 'Cliente')
            ->setCellValue('H1', 'Fecha Agendada')
            ->setCellValue('I1', 'Tipo')
            ->setCellValue('J1', 'Estado')
            ->setCellValue('K1', 'Tecnico')
            ->setCellValue('L1', 'Comentario');

            $cont = 2;
            foreach($equiposHistorico as $row){

                $worksheet->setCellValue('A'.$cont, '')
                ->setCellValue('B'.$cont, $row['CodigoFac'])
                ->setCellValue('C'.$cont, $row['CodMNT'])
                ->setCellValue('D'.$cont, $row['CodOrdenFisica'])
                ->setCellValue('E'.$cont, $row['CodProducto'])
                ->setCellValue('F'.$cont, $row['NombreProducto'])
                ->setCellValue('G'.$cont, $row['Cliente'])
                ->setCellValue('H'.$cont, $this->pipeFormatDate($row["FechaINI"]))
                ->setCellValue('I'.$cont, $this->getDescTipoMant(trim($row["TipoMant"])))
                ->setCellValue('J'.$cont, $this->getDescStatus($row["Estado"]))
                ->setCellValue('K'.$cont, $row['nombreResponsable'])
                ->setCellValue('L'.$cont, $row["Comentario"]);
              
                $cont++;
            }

       

        return $spreadsheet;
 
    }

    public function generaInformeMantExternosExcel($fechaINI, $fechaFIN, $tiposDocs, $ruc, $codEmpresa){

        $equiposHistorico = $this->getMantenimientosHistoricoEXT($fechaINI, $fechaFIN, $tiposDocs, $ruc, $cantidad=1000, $codEmpresa);
        
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setCellValue('A1', 'CI/RUC')
            ->setCellValue('B1', 'Cliente')
            ->setCellValue('C1', 'Equipo')
            ->setCellValue('D1', 'Cod. Mantenimiento')
            ->setCellValue('E1', 'Cod. Mant. Fisico')
            ->setCellValue('F1', 'Fecha Creacion')
            ->setCellValue('G1', 'Fecha Prometida')
            ->setCellValue('H1', 'Fecha Entrega')
            ->setCellValue('I1', 'Num. Cotizacion')
            ->setCellValue('J1', 'Num Factura Cotizacion')
            ->setCellValue('K1', 'Valor Factura')
            ->setCellValue('L1', 'Comentario')
            ->setCellValue('M1', 'Tecnico')
            ->setCellValue('N1', 'Estado');
         
            $cont = 2;
            foreach($equiposHistorico as $row){

                $worksheet->setCellValue('A'.$cont, $row['RUC'])
                ->setCellValue('B'.$cont, $row['ClienteName'])
                ->setCellValue('C'.$cont, $row['serieModelo'])
                ->setCellValue('D'.$cont, $row['codMantExt'])
                ->setCellValue('E'.$cont, $row['codOrdenFisica'])
                ->setCellValue('F'.$cont, $this->pipeFormatDate($row["fechaCreacion"]))
                ->setCellValue('G'.$cont, $this->pipeFormatDate($row["fechaPrometida"]))
                ->setCellValue('H'.$cont, $this->pipeFormatDate($row["fechaEntrega"]))
                ->setCellValue('I'.$cont, $row['numRELCOT'])
                ->setCellValue('J'.$cont, $row['facturaCOT'])
                ->setCellValue('K'.$cont, $row['totalFac'])
                ->setCellValue('L'.$cont, $row['comentario'])
                ->setCellValue('M'.$cont, $row['nombreTecnico'])
                ->setCellValue('N'.$cont, $this->getDescStatus($row["estado"]));
               
                $cont++;
            }

       

        return $spreadsheet;
 
    }

    function getDateNow() { 
      return date('Y-m-d');
    }

    private function getFiltroTiposDoc($tipoDOC){
        switch ($tipoDOC) {
            case 'ALL':
                return 'AND Mant.estado IN(0,1,2,3)';
                break;
            case 'PND':
                return 'AND Mant.estado IN(0)';
                break;

            case 'ANUL':
                return 'AND Mant.estado IN(2,3)';
                break;    
            
            default:
                return 'AND Mant.estado IN(0,1,2,3)';
                break;
        }
    }

    private function getFiltroRUC($RUC){
      if (!empty($RUC)) {
        return "AND Cliente.RUC LIKE '$RUC%'";
      }else{
          return '';
      }
    }


    
}