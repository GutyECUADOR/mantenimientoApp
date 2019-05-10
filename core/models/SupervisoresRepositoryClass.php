<?php namespace models;
require_once 'conexion.php';

class SupervisoresRepositoryClass {

    private $instanciaDB;
    private $db;
    
    public function __construct() {
        $this->instanciaDB = new \models\conexion();
        $this->db = $this->instanciaDB->getInstanciaCNX();
    }

    public function getListActBasicasSup($dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT 
            CAB.supervisor as Cedula,
            SBIO.Apellido + SBIO.Nombre as NombreEvaluado,
            CAB.fechaCreacion as fecha,
            CAB.evaluador as Evaluador,
            SBIO2.Apellido + SBIO2.Nombre as NombreEvaluador
                    
        FROM
            dbo.checkActBasicasSup_CAB as CAB
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on SBIO.Cedula = CAB.supervisor
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO2 on SBIO2.Cedula = CAB.evaluador
                            
        GROUP BY 
            CAB.fechaCreacion,
            CAB.supervisor,
            CAB.evaluador,
            SBIO.Apellido,
            SBIO.Nombre,
            SBIO2.Apellido,
            SBIO2.Nombre

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
      
            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC );
                    
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }


    public function getChkCABBySupervisor($supervisor, $evaluador, $fechaMes, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
                --This parameter will hold the dynamically created SQL script
                DECLARE   @SQLQuery AS NVARCHAR(MAX)
                
                --This parameter will hold the Pivoted Column values
                DECLARE   @PivotColumns AS NVARCHAR(MAX)
                DECLARE @supervisor AS varchar(13) = '$supervisor';
                DECLARE @evaluador AS varchar(13) = '$evaluador';
                DECLARE @fechaMES AS varchar(13) = '$fechaMes';
                DECLARE @count as INT;
                
                SELECT   @PivotColumns= COALESCE(@PivotColumns + ',','') + QUOTENAME(codChecklist)
                FROM dbo.checkActBasicasSup_CAB WHERE fechaCreacion = @fechaMES and supervisor = @supervisor and evaluador = @evaluador
                
                /* UNCOMMENT TO SEE THE NEW COLUMN NAMES THAT WILL BE CREATED */
                --SELECT   @PivotColumns
                
                --LIST ALL FILEDS EXCEPT PIVOT COLUMN
                
                SET   @SQLQuery =
                '
                SELECT supervisor, codCheckItem, Titulo,' +   @PivotColumns + '
                    FROM 
                        (
                        SELECT 
                            MOV.codCAB, 
                            CAB.supervisor, 
                            MOV.codCheckItem,
                            BANCO.Titulo,
                            MOV.checked
                        FROM dbo.checkActBasicasSup_CAB as CAB
                            INNER JOIN dbo.checkActBasicasSup_MOV as MOV on CAB.codChecklist = MOV.codCAB
                            INNER JOIN dbo.checkActBasicasSup_Banco as BANCO on BANCO.Codigo = MOV.codCheckItem
                        WHERE supervisor = '+@supervisor+' and evaluador = '+@evaluador+'
                        ) AS ResultSet
                        PIVOT(
                        MAX(checked)
                        FOR codCAB in ('+ @PivotColumns +')
                        )as Pivote
                '
                
                --Execute dynamic query
                EXEC sp_executesql @SQLQuery
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        if($stmt->execute()){
            return $stmt->fetchAll();
                
        }else{
            $resulset = false;
        }
    }

    public function getCurrentCodsCheckList($supervisor, $fechaMes, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT codChecklist FROM dbo.checkActBasicasSup_CAB WHERE supervisor = '$supervisor' and fechaCreacion = '$fechaMes'
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
        if($stmt->execute()){
            return $stmt->fetchAll( \PDO::FETCH_ASSOC );
                
        }else{
            $resulset = false;
        }
    }


    public function getChkMOVBySupervisor($codCheckCAB, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
                MOV.*,
                BANCO.Titulo,
                BANCO.Descripcion
            FROM 
                dbo.checkActBasicasSup_MOV as MOV
                INNER JOIN dbo.checkActBasicasSup_Banco as BANCO on BANCO.Codigo = MOV.codCheckItem
            WHERE MOV.codCAB = '$codCheckCAB'

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
      
            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC );
                    
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    public function getResumenTableListActBasicasSup($dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
                CAB.supervisor,
                CAB.codChecklist,
                CAB.fechaCreacion,
                CAB.semana,
                MOV.codCheckItem,
                BANCO.Titulo,
                MOV.checked,
                MOV.comentario
                
            FROM dbo.checkActBasicasSup_CAB AS CAB
            INNER JOIN dbo.checkActBasicasSup_MOV as MOV ON CAB.codChecklist = MOV.codCAB
            INNER JOIN dbo.checkActBasicasSup_Banco as BANCO on BANCO.Codigo = MOV.codCheckItem
            
            GROUP BY 
            
                CAB.supervisor,
                CAB.codChecklist,
                CAB.fechaCreacion,
                CAB.semana,
                MOV.codCheckItem,
                BANCO.Titulo,
                MOV.checked,
                MOV.comentario

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
      
            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC );
                    
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    public function showIconCheched($codCheckItem, $checkedValue){
        if ($checkedValue == 1) {
            return '<i class="md-list-addon-icon material-icons uk-text-success" data-codCheck="'.trim($codCheckItem).'" data-codCheckValue="1">check</i>';
        }elseif ($checkedValue == 0) {
            return '<i class="md-list-addon-icon material-icons uk-text-danger" data-codCheckValue="0">clear</i>';
        }else{
            return 'error';
        }
    }
    
}