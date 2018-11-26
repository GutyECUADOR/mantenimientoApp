<?php namespace models;
require_once 'conexion.php';


/* LOS MODELOS del MVC retornaran unicamente arrays PHP sin serializar*/

class ajaxModel  {
    
    private $instanciaDB;
    private $db;
    
    public function __construct() {
        $this->instanciaDB = new \models\conexion();
        $this->db = $this->instanciaDB->getInstanciaCNX();
    }

    /*
        Funcion que retorna array con todos equipos que NO poseen un mantenimiento 
        en la tabla dbo.MantenimientosEQ, asegurar nombre de la base de datos a la que conectar.
    */

    public function getArraysMantenimientosEQ($dataBaseName='KAO_wssp', $pageSize, $fechaInicial, $fechaFinal) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP ".$pageSize."
            Compra.FECHA as FechaCompra,
            Compra.ID as CodigoFac,
            Compra.TIPO as TipoDocumento,
            TIPOSDOC.TIPODOC as TipoDoc,
            Compra.CANTIDAD as CantitadProd,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            FinGarantia = dateadd(day,Producto.GarantiaCli,Compra.FECHA),
            DiasGarantiarestantes = DATEDIFF(day, GETDATE () ,dateadd(day,Producto.GarantiaCli,Compra.FECHA)),
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as NombreBodega,
            Compra.ID + Compra.CODIGO as IDCompra 
            
        FROM dbo.VEN_MOV as Compra
            INNER JOIN dbo.VEN_TIPOS as TIPOSDOC on TIPOSDOC.CODIGO = Compra.TIPO
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA = Bodega.CODIGO
        
        WHERE 
            Compra.ID COLLATE Modern_Spanish_CI_AS NOT IN( SELECT codFactura FROM KAO_wssp.dbo.mantenimientosEQ AS MANTENI  WHERE Compra.CODIGO = MANTENI.codEquipo COLLATE Modern_Spanish_CI_AS AND MANTENI.estado IN('0','1','3'))
            AND Compra.TIPO IN (SELECT CODIGO FROM VEN_TIPOS WHERE TIPODOC IN ('F', 'D'))
            AND Compra.ANULADO = '0'
			AND Producto.GarantiaCli != '0'
            AND fecha BETWEEN '".$fechaInicial."' AND '".$fechaFinal."' 
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


    public function insertNewMantenimiento($data, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codigoDOC = 'MNT'; //Codigo para Mantenimientos MNT
        $sth = $this->db->prepare("exec sp_genera_codMantenimiento ?");
        $sth->bindParam(1, $codigoDOC);
        $sth->execute();
        $arraycodigo = $sth->fetch();
        $newCod = $arraycodigo[0]; // Codgigo MNT obtenido por SP

        $usuarioActivo = trim($_SESSION["usuarioRUC"]); //Cedula del usuario logeado
        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $codFactura = $data['CodigoFac'];
        $codProducto = $data['CodProducto'];
        $OrdenTrabajo = $data['OrdenTrabajo'];
        $CantitadProd = $data['CantitadProd'];
        $Comentario = $data['Comentario'];
        $fechaHoraINI = $data['fechaHoraINI'];
        $fechaHoraFIN = $data['fechaHoraFIN'];
        $TipoMantenimiento = $data['TipoMantenimiento'];
        $Tecnico = $data['Tecnico'];
        

        $query = "
        INSERT INTO 
            dbo.mantenimientosEQ 
        VALUES 
            ('$newCod','$TipoMantenimiento','$OrdenTrabajo','$codFactura','$codProducto','$codEmpresa','$fechaHoraINI','$fechaHoraFIN',$CantitadProd,'$Comentario','$Tecnico',0);
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
        
    }
    
    public function anulaMantenimientoByCod($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $query = "
            UPDATE 
                dbo.mantenimientosEQ 
            SET 
                estado = '2'
            WHERE 
                codMantenimiento = '$codMNT' 
                AND codEmpresa ='$codEmpresa'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    public function aprobarMantenimientoByCod($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $query = "
            UPDATE 
                dbo.mantenimientosEQ 
            SET 
                estado = '1'
            WHERE 
                codMantenimiento = '$codMNT' 
                AND codEmpresa ='$codEmpresa'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function omitirMantenimientoByCod($data, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codigoDOC = 'MNT'; //Codigo para Mantenimientos MNT
        $sth = $this->db->prepare("exec sp_genera_codMantenimiento ?");
        $sth->bindParam(1, $codigoDOC);
        $sth->execute();
        $arraycodigo = $sth->fetch();
        $newCod = $arraycodigo[0]; // Codgigo MNT obtenido por SP

        $usuarioActivo = trim($_SESSION["usuarioRUC"]); //Cedula del usuario logeado
        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $codFactura = $data['CodigoFac'];
        $codProducto = $data['CodProducto'];
        $fechaHoraINI = date('Ynd');
        $fechaHoraFIN =  date('Ynd');

        $query = "
        INSERT INTO 
            dbo.mantenimientosEQ 
        VALUES 
            ('$newCod','OMT','0','$codFactura','$codProducto','$codEmpresa','$fechaHoraINI','$fechaHoraFIN',0,'No requiere','NA',3);
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function getArraysTiposDOCMantenimientos($dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = " SELECT Descripcion as DisplayText, Codigo as Value FROM dbo.tiposDOC ";  // Final del Query SQL 

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

    public function getArraysTecnicos($dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT Cedula as Value , (Nombre + Apellido) as DisplayText FROM dbo.Empleados WHERE CodDpto = 'TEC'";  // Final del Query SQL 

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

    public function getArrayProducto($dataBaseName='KAO_wssp', $codProducto) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT TOP 1 * FROM INV_ARTICULOS WHERE Codigo = '$codProducto'";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }
    
    /* Actualiza tabla mantenimientosEQ con la informacion que llega del formulario editMantenimiento*/
    public function updateMantenimientoEQ($formData, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa = trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login
        $codMNT = $formData->codMantenimiento;
        $codOrdenFisica = $formData->product_ordenFisica;
        $responsable = $formData->product_edit_tecnico;
        $fechaInicio = date('Ymd H:i:s', strtotime("$formData->uk_dp_fecha"));
        $fechaFinal = date('Ymd H:i:s', strtotime("$formData->uk_dp_fecha"));

        $query = "
        UPDATE 
            DBO.mantenimientosEQ
        SET 
            codOrdenFisica = '$codOrdenFisica',
            responsable = '$responsable',
            fechaInicio = '$fechaInicio',
            fechaFin = '$fechaFinal'
        WHERE 
            codMantenimiento = '$codMNT' 
            AND codEmpresa ='$codEmpresa'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    /*Retorna array con informacion de la empresa que se indique*/
    public function getDatosEmpresaFromWINFENIX ($dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "SELECT NomCia, Oficina, Ejercicio FROM dbo.DatosEmpresa";
        $stmt = $this->db->prepare($query); 

        if($stmt->execute()){
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }else{
            return false;
        }
    }

    /*Retorna el siguiente secuencial del tipo de documento que se le indiqie - Winfenix*/
    public function getNextNumDocWINFENIX ($dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $gestion = 'VEN';
        $ofi = '99';
        $eje = '';
        $tipo = 'PEM';
        $codigo = '';

        $stmt = $this->db->prepare("exec SP_CONTADOR ?, ?, ?, ?, ?"); 
        $stmt->bindValue(1, $gestion); 
        $stmt->bindValue(2, $ofi); 
        $stmt->bindValue(3, $eje); 
        $stmt->bindValue(4, $tipo); 
        $stmt->bindValue(5, $codigo); 

        $stmt->execute();
        $stmt->nextRowset(); 
        
        $newCodLimpio = $stmt->fetch(\PDO::FETCH_ASSOC);
        $newCodLimpio =  $newCodLimpio['NExtID'];

        return $newCodLimpio;
    }

    /*Retorna el secuencial de WinFenix en formato 0000XXXX - Winfenix*/
    public function formatoNextNumDocWINFENIX ($dataBaseName='KAO_wssp', $secuencialWinfenix){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $newCod = $this->db->query("select RIGHT('00000000' + Ltrim(Rtrim('$secuencialWinfenix')),8) as newcod");
        $codigoConFormato = $newCod->fetch(\PDO::FETCH_ASSOC);
        $codigoConFormato = $codigoConFormato['newcod'];
        return $codigoConFormato;
    }

    public function insertVEN_CAB($formData, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $oficinaEmpresa = '99';
        $ejercicioEmpresa = '2017';
        $cod_tipodoc = 'C02';
        $cod_sp_with0 = '00001717'; /*DEFINE FK clave unica*/
        $cod_cliente = '00001823';
        $facturaRef = '';
        $bodega = 'B02'; 
        $iva = '0';
        $subtotal = '0';
        $total = '0';

        $pcID = php_uname('n'); // Obtiene el nombre del PC
        $fecha_now_SQL = date("Ymd");  //Elimina del formato -, para evitar error
        $observa_valep = 'Generado por mantenimientosApp';
        $serie_valep='001005';

        $query = "exec dbo.SP_VENGRACAB 'I','ADMINWSSP','$pcID', '$oficinaEmpresa' , '$ejercicioEmpresa' , '$cod_tipodoc', '$cod_sp_with0','$facturaRef','$fecha_now_SQL','00001823','B02','DOL','1.00','0.00','10','0.00','0.00','0.00','0.00','0.00','10','0.00','2','0.00','12','CON','0','1','0','S','0','1','0','0','','','999',' ',' ','PRUEBAS','001005','00002050','','','','','0.00','0.00','0.00','','','','','','','','','','0','P','','','','','','0','','','','','0','2','0.00','0.00','0.00','0','999999999 ','0','','','','','','EFE','','','','','20181121','','',''";
        
        $rowsAfected = $this->db->exec($query);
        $sth->bindParam(1, $name);
        if($rowsAfected == 1){
            return true;
        }else{
            return false;
        }
    }

    public function insertVEN_MOV($formData, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        

        $query = "
        
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

}



   
    
