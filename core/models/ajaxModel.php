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

    public function getArraysMantenimientosEQ($dataBaseName='KAO_wssp', $pageSize, $fechaInicial, $fechaFinal, $codigoBodega) {

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
            Cliente.RUC as RUCCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.CODIGO as CodigoCodega,
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
            AND Cliente.RUC NOT IN('1792190851001', '1790417581001', '0992720301001', '1792585155001', '1706388335001')
            AND Compra.ANULADO = '0'
			AND Producto.GarantiaCli != '0'
            AND fecha BETWEEN '".$fechaInicial."' AND '".$fechaFinal."' 
            AND Bodega.CODIGO = '$codigoBodega'
        ORDER BY Producto, NombreCliente ASC

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

       /*  $usuarioActivo = trim('9999999999'); //Cedula del usuario logeado
        $codEmpresa =  trim('004'); //Codigo de la empresa seleccionada en login */

        $usuarioActivo = trim($_SESSION["usuarioRUC"]); //Cedula del usuario logeado
        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $codFactura = $data['CodigoFac'];
        $codProducto = $data['CodProducto'];
        $OrdenTrabajo = str_pad(abs($data['OrdenTrabajo']), 8, "0", STR_PAD_LEFT);
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

        try{
            $stmt = $this->db->prepare($query); 
            $stmt->execute();
            return array('status' => 'ok', 'mensaje' => 'Agregado registro a WSSP mantenimientos'.$newCod , 'newCod' => $newCod ); 
            
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
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

    public function anulaMantenimientoExternoByCod($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $query = "
            UPDATE 
                dbo.mantExternosEQ_CAB 
            SET 
                estado = '2'
            WHERE 
                codMantExt = '$codMNT' 
                AND empresa ='$codEmpresa'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    /*Retorna string con el numero de columnas encontradas*/
    public function isDisponibleOrdenFisica($formData, $codEmpresa, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        $codMNT = $formData->codMantenimiento;
        $codOrdenFisica = str_pad(abs($formData->product_ordenFisica), 8, "0", STR_PAD_LEFT); 

        $query = "
            SELECT count(*) as totalRows FROM dbo.mantenimientosEQ WHERE codOrdenFisica = '$codOrdenFisica' AND codOrdenFisica IN 
            (SELECT codOrdenFisica FROM dbo.mantenimientosEQ WHERE codMantenimiento != '$codMNT' AND codEmpresa='$codEmpresa'  )

        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            $number_of_rows = $stmt->fetchColumn(); 
            return $number_of_rows;
        }else{
            return false;
        }
    }

    /*Retorna string con el numero de columnas encontradas*/
    public function getFacturaEquipoByCodMNT($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
       
        $query = "
            SELECT codFactura, codEquipo FROM dbo.mantenimientosEQ WHERE codMantenimiento = '$codMNT'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
           
            return $stmt->fetchAll( \PDO::FETCH_ASSOC );;
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

    public function aprobarMantenimientoExtenoByCod($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login
        $fechaActual = date('Ymd');

        $query = "
            UPDATE 
                dbo.mantExternosEQ_CAB 
            SET 
                estado = '1',
                fechaEntrega = '$fechaActual'
            WHERE 
                codMantExt = '$codMNT' 
                AND empresa ='$codEmpresa'
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
        $comentario = $data['RazonAnulacion'];
        $fechaHoraINI = date('Ymd');
        $fechaHoraFIN =  date('Ymd');

        $query = "
        INSERT INTO 
            dbo.mantenimientosEQ 
        VALUES 
            ('$newCod','OMT','0','$codFactura','$codProducto','$codEmpresa','$fechaHoraINI','$fechaHoraFIN',0,'$comentario','NA',3);
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function getMantenimientoByCodMNTmodel($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "
            SELECT
                CAB.*,
                MOV.*
            
            FROM dbo.mantenimientosEQ  as CAB
                LEFT JOIN dbo.mov_mantenimientosEQ as MOV on MOV.codMantenimiento = CAB.codMantenimiento 
            WHERE CAB.codMantenimiento = '$codMNT'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return $stmt->fetch( \PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }


    public function getMantenimientoByCodMNTmodelExt($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "
            SELECT
                CAB.*,
                MOV.*
                        
            FROM dbo.mantExternosEQ_CAB  as CAB
                LEFT JOIN dbo.mov_mantenimientosEQ as MOV on MOV.codMantenimiento = CAB.codMantExt 
            WHERE CAB.codMantExt = '$codMNT'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return $stmt->fetch( \PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    public function getMantenimientoByCodMNTExtmodel($codMNT, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "
        SELECT
            CAB.*,
            MOV.*
                    
        FROM dbo.mantExternosEQ_CAB  as CAB
            LEFT JOIN dbo.mov_mantenimientosEQ as MOV on MOV.codMantenimiento = CAB.codMantExt 
        WHERE CAB.codMantExt = '$codMNT'
        ";

        $stmt = $this->db->prepare($query); 
        if($stmt->execute()){
            return $stmt->fetch( \PDO::FETCH_ASSOC);
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

    public function getAllTiposEquiposByModel($tipoEquipo='BICI', $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT codigo as Value, Descripcion as DisplayText FROM dbo.mantTiposEquipos WHERE tipo = '$tipoEquipo' ORDER BY Descripcion";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC);
                
            }else{
                $resulset = false;
            }
        return $resulset;  
    }

    public function getArraysSupervisores($dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT Cedula as Value , (Nombre + Apellido) as DisplayText FROM dbo.Empleados WHERE CodDpto = 'SUP'";  // Final del Query SQL 

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

    public function getArraysBodegas($dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT CODIGO as Value, NOMBRE as DisplayText FROM INV_BODEGAS";  // Final del Query SQL 

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

    public function getArraySupervisoresEvaluarBy($supervisorEvaluador, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT ASIGNADOS.evaluado as Value,
                (SBIO.Apellido + SBIO.Nombre) as DisplayText 
        FROM
            dbo.SUP_evasignados as ASIGNADOS
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on SBIO.Cedula = ASIGNADOS.evaluado
        WHERE ASIGNADOS.supervisor = '$supervisorEvaluador'
 
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC );
                  
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

    public function getDefaultBodegaByCedulaModel($cedulaUsuario, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP 1
            u.Codigo as UsuarioWinfenix,
            u.Nombre as NombreUsuario,
            u.UserID as Cedula,
            XASIGNA.DATO as BodegaDefecto
        FROM 
            dbo.XASIGDATOUSER AS XASIGNA
            
        INNER JOIN dbo.USUARIOS as u ON XASIGNA.USERCOD = u.Codigo
        
        WHERE 
            u.UserID = '$cedulaUsuario' 
            and XASIGNA.GESTION = 'VEN' 
            AND XASIGNA.DATO LIKE 'C%' 
            AND XASIGNA.DATO IN (SELECT Codigo FROM VEN_TIPOS WHERE TIPODOC= 'C' )
        
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                return $stmt->fetch( \PDO::FETCH_ASSOC );
                
            }else{
                return $resulset = false;
            }
        
    }
    
    /* Actualiza tabla mantenimientosEQ con la informacion que llega del formulario editMantenimiento*/
    public function updateMantenimientoEQ($formData, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa = trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login
        $codMNT = $formData->codMantenimiento;
        $codOrdenFisica = str_pad($formData->product_ordenFisica, 8, "0", STR_PAD_LEFT);
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


        try{
            $stmt = $this->db->prepare($query); 
            $stmt->execute();
            
            return array('status' => 'ok', 'mensaje' => $codMNT. ' actualizada' ); 
            
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => 'Error en mantenimientosEQ' . $exception->getMessage() );
        }


        
    }

    /* Actualiza tabla mantenimientosEQ con la informacion que llega del formulario editMantenimiento*/
    public function updateMantenimientoExterno($formData, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa = trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login
        $codMNT = $formData->codMantenimiento;
        $codOrdenFisica = str_pad($formData->product_ordenFisica, 8, "0", STR_PAD_LEFT);
       
        $query = "
        UPDATE 
            DBO.mantExternosEQ_CAB
        SET 
            codOrdenFisica = '$codOrdenFisica'
        WHERE 
            codMantExt = '$codMNT' 
            AND empresa ='$codEmpresa'
        ";


        try{
            $stmt = $this->db->prepare($query); 
            $stmt->execute();
            
            return array('status' => 'ok', 'mensaje' => $codMNT. ' actualizada' ); 
            
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => 'Error en mantenimientosEQ' . $exception->getMessage() );
        }


        
    }

    /* Actualiza tabla mantenimientosEQ con la informacion que llega del formulario editMantenimiento*/
    public function insertMOVMantenimientoEQ($formData, $codVENCAB, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codEmpresa = trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login
        $codMNT = $formData->codMantenimiento;
        $codOrdenFisica = $formData->product_ordenFisica;
     
        $query = "
            INSERT INTO mov_mantenimientosEQ VALUES ('$codMNT','$codVENCAB','$codEmpresa');
        ";

        
        try{
            $stmt = $this->db->prepare($query); 
            $stmt->execute();
            return array('status' => 'ok', 'mensaje' => 'Agregado registro a mov_mantenimientosEQ' ); 
            
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

    }

    public function getVENCABByID($IDDocument, $codProducto, $dataBaseName='KAO_wssp') {
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        $query = " 
        SELECT 
            VEN_CAB.ID,
            VEN_CAB.NUMERO,
            VEN_CAB.TIPO,
            VEN_CAB.FECHA,
            VEN_CAB.CLIENTE,
            RTRIM(LTRIM(REPLACE(CLIENTE.NOMBRE, NCHAR(0x00A0), ''))) as NOMBRE,
            CLIENTE.RUC,
            CLIENTE.DIRECCION1,
            CLIENTE.TELEFONO1,
            RTRIM(LTRIM(REPLACE(CLIENTE.EMAIL, NCHAR(0x00A0), ''))) as EMAIL,
            VENDEDOR.CODIGO as CodigoVendedor,
            VENDEDOR.NOMBRE as VendedorName,
            ARTICULO.Codigo as CodigoArticulo,
            ARTICULO.Nombre as NombreArticulo
        FROM 
            dbo.VEN_CAB 
            INNER JOIN dbo.VEN_MOV as MOV on MOV.ID = VEN_CAB.ID
            INNER JOIN dbo.COB_CLIENTES as CLIENTE on CLIENTE.CODIGO = VEN_CAB.CLIENTE
            LEFT JOIN dbo.COB_VENDEDORES as VENDEDOR on VENDEDOR.CODIGO = VEN_CAB.CODVEN
            INNER JOIN dbo.INV_ARTICULOS as ARTICULO on ARTICULO.Codigo = MOV.CODIGO
            
        WHERE VEN_CAB.ID='$IDDocument' and MOV.CODIGO = '$codProducto'

        ";  // Final del Query SQL 
        try{
            $stmt = $this->db->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }

    public function getVENCABByIDWithOutProducto($IDDocument) {
        $query = " 
        SELECT 
            RTRIM(LTRIM(REPLACE(CLIENTE.NOMBRE, NCHAR(0x00A0), ''))) as NOMBRE,
            CLIENTE.RUC,
            CLIENTE.DIRECCION1,
            CLIENTE.TELEFONO1,
            RTRIM(LTRIM(REPLACE(CLIENTE.EMAIL, NCHAR(0x00A0), ''))) as EMAIL,
            VENDEDOR.CODIGO as CodigoVendedor,
            VENDEDOR.NOMBRE as VendedorName,
            VEN_CAB.*
        FROM 
            dbo.VEN_CAB 
            INNER JOIN dbo.COB_CLIENTES as CLIENTE on CLIENTE.CODIGO = VEN_CAB.CLIENTE
            LEFT JOIN dbo.COB_VENDEDORES as VENDEDOR on VENDEDOR.CODIGO = VEN_CAB.CODVEN	
        WHERE ID='$IDDocument'

        ";  // Final del Query SQL 
        try{
            $stmt = $this->db->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }

    public function getVENMOVByID($IDDocument) {

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT
             ARTICULO.Nombre,
             VEN_MOV.*
             
         FROM 
             dbo.VEN_MOV
             INNER JOIN dbo.INV_ARTICULOS as ARTICULO ON ARTICULO.Codigo = VEN_MOV.CODIGO
         WHERE 
             ID = '$IDDocument'
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

    /*Retorna array con informacion de la empresa que se indique*/
    public function getDatosEmpresaFromWINFENIX ($dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "SELECT NomCia, Oficina, Ejercicio, DirCia, TelCia, RucCia, Ciudad  FROM dbo.DatosEmpresa";
        $stmt = $this->db->prepare($query); 

        try{
            $stmt->execute();
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

    }
    
    /*Retorna array asociativo con informacion del cliente que se indique*/
    public function getDatosClienteWINFENIXByRUC ($clienteRUC, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "SELECT * FROM COB_CLIENTES WHERE RUC = '$clienteRUC'";
        $stmt = $this->db->prepare($query); 

        try{
            $stmt->execute();
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
    }

    /*Retorna array asociativo con informacion del cliente que se indique*/
    public function getDatosDocumentsWINFENIXByTypo ($tipoDOC, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $query = "SELECT CODIGO, NOMBRE, Serie FROM dbo.VEN_TIPOS WHERE CODIGO = '$tipoDOC'";
        $stmt = $this->db->prepare($query); 

        if($stmt->execute()){
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }else{
            return false;
        }
    }

    /*Retorna el siguiente secuencial del tipo de documento que se le indiqie - Winfenix*/
    public function getNextNumDocWINFENIX ($tipoDoc, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $gestion = 'VEN';
        $ofi = '99';
        $eje = '';
        $tipo = $tipoDoc;
        $codigo = '';

        try{
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

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

        
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

    public function insertVEN_CAB($VEN_CAB_obj, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        //$queryExample = "exec dbo.SP_VENGRACAB 'I','ADMINWSSP','TESTOK','99', '2014', 'C02', '00001721','','20181126','00054818','FAL','DOL','1.00','0.00','10','0.00','0.00','0.00','0.00','0.00','10','0.00','2','0.00','12','CON','0','1','0','S','0','1','0','0','','','999',' ',' ','PRUEBAS','001005','00002050','','','','','0.00','0.00','0.00','','','','','','','','','','0','P','','','','','','0','','','','','0','2','0.00','0.00','0.00','0','999999999 ','0','','','','','','EFE','','','','','20181126','',''";
        $VEN_CAB = new \models\venCabClass();
        $VEN_CAB = $VEN_CAB_obj;
        
        $query = "exec dbo.SP_VENGRACAB 'I','ADMINWSSP','$VEN_CAB->pcID','$VEN_CAB->oficina', '$VEN_CAB->ejercicio', '$VEN_CAB->tipoDoc', '$VEN_CAB->numeroDoc','','$VEN_CAB->fecha','$VEN_CAB->cliente','$VEN_CAB->bodega','$VEN_CAB->divisa','1.00','0.00','$VEN_CAB->subtotal','0.00','0.00','0.00','0.00','0.00','$VEN_CAB->subtotal','0.00','$VEN_CAB->impuesto','0.00','$VEN_CAB->total','CON','0','1','0','S','0','1','0','0','','','999',' ',' ','$VEN_CAB->observacion','$VEN_CAB->serie','$VEN_CAB->secuencia','','','','','0.00','0.00','0.00','','','','','','','','','','0','P','','','','','','0','','','','','0','2','0.00','0.00','0.00','0','999999999 ','0','','','','','','$VEN_CAB->formaPago','','','','','$VEN_CAB->fecha','',''";
        
        try{
            $rowsAfected = $this->db->exec($query);
           return array('status' => 'ok', 'mensaje' => $rowsAfected. ' fila afectada(s)' ); //true;
           
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

        
    }

    public function insertVEN_MOV($VEN_MOV_obj, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        
        $VEN_MOV = new \models\venMovClass();
        $VEN_MOV = $VEN_MOV_obj;

        $query = "exec dbo.SP_VENGRAMOV 'I','$VEN_MOV->oficina','$VEN_MOV->ejercicio','$VEN_MOV->tipoDoc','$VEN_MOV->numeroDoc','$VEN_MOV->fecha','$VEN_MOV->cliente','$VEN_MOV->bodega','S','0','0','$VEN_MOV->codProducto','UND','$VEN_MOV->cantidad','A','$VEN_MOV->precioProducto','$VEN_MOV->porcentajeDescuentoProd','$VEN_MOV->porcentajeIVA','$VEN_MOV->precioTOTAL','$VEN_MOV->fecha','','0.00','0.0000000','0','1.01.11','','1','1','104','0.0000','0.0000','0','','0','$VEN_MOV->tipoIVA' ";

        $stmt = $this->db->prepare($query); 
       
        try{
            $rowsAfected = $this->db->exec($query);
           return array('status' => 'ok', 'mensaje' => $rowsAfected. ' fila afectada(s)' ); //true;
           
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }


    }


    /*
       - Realiza conteo de mantenimientos pendientes 
    */
    public function getCountMantenimientos($codEmpresa, $dataBaseName='KAO_wssp') {
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
                MantPendientes = (SELECT COUNT (*) FROM dbo.mantenimientosEQ WHERE codEmpresa = '$codEmpresa' AND estado = 0),
                PorcentMantFinalizados = (SELECT COUNT( * ) FROM dbo.mantenimientosEQ  WHERE estado != 0 AND codEmpresa = '$codEmpresa') * 100 / (SELECT COUNT( * ) FROM dbo.mantenimientosEQ WHERE codEmpresa = '$codEmpresa') 
        
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){

                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
               
                return $arrayResultados;
            }else{
                return false;
                
            }
        return $resulset;  

   
    }


    /*
       - Retorna todos los mantenimientos de la tabla 
    */
    public function getHistorico($dataBaseName='KAO_wssp', $fechaINI, $fechaFIN, $codEmpresa, $tiposDocs) {
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        
        $tiposDOC = $this->getFiltroTiposDoc($tiposDocs);
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
                Compra.ID as CodigoFac,
                Mant.codMantenimiento as CodMNT,
                Mant.codEquipo as CodProducto,
                Cliente.NOMBRE as Cliente,
                Mant.tipo as TipoMant,
                Mant.fechaInicio as FechaINI,
                Mant.estado as Estado
            
            FROM
                dbo.VEN_CAB as Compra
                INNER JOIN KAO_wssp.dbo.mantenimientosEQ as Mant ON Mant.codFactura COLLATE Modern_Spanish_CI_AS = Compra.ID
                INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
                
            WHERE 
                codEmpresa = '$codEmpresa'
                AND Mant.fechaInicio BETWEEN '$fechaINI' AND '$fechaFIN'
                ".$tiposDOC."
            ORDER BY CodMNT ASC
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){

                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
               
                return $arrayResultados;
            }else{
                return false;
                
            }
        return $resulset;  

   
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

    /*
       - Retorna todos los mantenimientos de la tabla 
    */
    public function getCheckListActBasicasModel($dataBaseName='KAO_wssp') {
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        
        //Query de consulta con parametros para bindear si es necesario.
            $query = "
                SELECT * FROM dbo.checkActBasicasSup_Banco
            ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        $arrayResultados = array();

        if($stmt->execute()){
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);  
        }else{
            return false;
        }
       

    }

    /*
       - Retorna todos los mantenimientos de la tabla 
    */
    public function searchClienteModel($value, $by, $dataBaseName='KAO_wssp') {
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        
       

        //Query de consulta con parametros para bindear si es necesario.
            $query = "
            SELECT TOP 10 RUC, NOMBRE, CODIGO, TIPOPRECIO FROM dbo.COB_CLIENTES WHERE $by LIKE '$value%'
            ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        if($stmt->execute()){
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);  
        }else{
            return false;
        }
    }

    public function getInfoClienteModel($RUC, $dataBaseName) {
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();
        

        //Query de consulta con parametros para bindear si es necesario.
        $query = " 
            
        SELECT 
            CLIENTE.CODIGO, 
            RTRIM(CLIENTE.NOMBRE) as NOMBRE, 
            RTRIM(CLIENTE.EMPRESA) as EMPRESA, 
            RTRIM(CLIENTE.RUC) as RUC, 
            RTRIM(CLIENTE.EMAIL) as EMAIL, 
            RTRIM(CLIENTE.FECHAALTA) as FECHAALTA, 
            RTRIM(CLIENTE.DIRECCION1) as DIRECCION, 
            RTRIM(CLIENTE.TELEFONO1) as TELEFONO, 
            RTRIM(VENDEDOR.CODIGO) as VENDEDOR,
            RTRIM(VENDEDOR.NOMBRE) as VENDEDORNAME,
            RTRIM(CLIENTE.LIMITECRED) as LIMITECRED, 
            RTRIM(CLIENTE.FPAGO) as FPAGO, 
            RTRIM(CLIENTE.DIASPAGO) as DIASPAGO, 
            RTRIM(CLIENTE.TIPOPRECIO) as TIPOPRECIO 
        FROM 
            dbo.COB_CLIENTES as CLIENTE INNER JOIN
            dbo.COB_VENDEDORES as VENDEDOR ON VENDEDOR.CODIGO = CLIENTE.VENDEDOR
        WHERE 
            RUC='$RUC'";  // Final del Query SQL 

        try{
            $stmt = $this->db->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }

    public function persist_MantenimientoExterno($formDataObject, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codigoDOC = 'MNE'; //Codigo para checklist
        $sth = $this->db->prepare("exec sp_genera_codMantenimientoExt ?");
        $sth->bindParam(1, $codigoDOC);
        $sth->execute();
        $arraycodigo = $sth->fetch();
        $newCod = $arraycodigo[0]; // Codgigo MNT obtenido por SP

        $usuarioActivo = trim($_SESSION["usuarioRUC"]); //Cedula del usuario logeado
        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $fechaActual = date('Ymd');
        $fechaPrometida = date('Ymd H:i:s', strtotime("$formDataObject->fechaPrometida"));
        $cliente = $formDataObject->cliente;

        $query = "
            INSERT INTO 
                dbo.mantExternosEQ_CAB
            VALUES
                ('$newCod', '0' , '$usuarioActivo', '$codEmpresa','$fechaActual','$fechaPrometida', null,'$cliente->RUC','$formDataObject->tecnico','$formDataObject->tipoEquipo','$formDataObject->tipoMantenimiento','$formDataObject->serieModelo','$formDataObject->comentario', 0) 
        ";

        try{
            $stmt = $this->db->prepare($query); 
            $stmt->execute();
            return array('status' => 'ok', 'mensaje' => 'Se ha registrado correctamente '.$newCod, 'newCod' => $newCod); 
            
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

        
    }
}



   
    
