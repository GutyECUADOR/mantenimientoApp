<?php namespace models;
require_once 'conexion.php';

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
    public function getMantenimientosAgendados($dataBaseName='KAO_wssp', $cantidad=1) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

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
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE Modern_Spanish_CI_AS = WSSP.codFactura
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP2 on Compra.CODIGO COLLATE Modern_Spanish_CI_AS  = WSSP.codEquipo  
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable = SBIO.Cedula

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
            SBIO.Apellido + SBIO.Nombre as Encargado,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE Modern_Spanish_CI_AS = WSSP.codFactura
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP2 on Compra.CODIGO COLLATE Modern_Spanish_CI_AS  = WSSP.codEquipo  
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable = SBIO.Cedula

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
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin
            
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
            SBIO.Apellido as Encargado,
            WSSP.fechaInicio,
            WSSP.fechaFin
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE Modern_Spanish_CI_AS = WSSP.codFactura
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
            WSSP.fechaInicio,
            WSSP.fechaFin
	


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


    function getDateNow() { 
      return date('Y-m-d');
    }

    
}