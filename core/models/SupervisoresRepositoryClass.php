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
                CAB.fechaCreacion as fecha,
                SBIO.Apellido + SBIO.Nombre as Nombre
            
            FROM
                dbo.checkActBasicasSup_CAB as CAB
                INNER JOIN SBIOKAO.dbo.Empleados as SBIO on SBIO.Cedula = CAB.supervisor
            
            GROUP BY 
                CAB.fechaCreacion,
                CAB.supervisor,
                SBIO.Apellido,
                sbio.Nombre

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
      
            if($stmt->execute()){
                return $stmt->fetchAll( \PDO::FETCH_ASSOC );
                    
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }


    public function getChkCABBySupervisor($supervisor, $fechaMes, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT 
            CAB.codChecklist,
            CAB.fechaCreacion,
            CAB.semana
        FROM 
        dbo.checkActBasicasSup_CAB AS CAB
        
        WHERE 
            CAB.supervisor = '$supervisor'
            AND fechaCreacion = '$fechaMes'

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    
                    // Agregar a cada row una key que lleva el detalle
                    $codigoCheckList = $row['codChecklist'];
                    $row['items'] = $this->getChkMOVBySupervisor($codigoCheckList);
                    array_push($arrayResultados, $row);
                }
                
                return $arrayResultados;

                
            }else{
                $resulset = false;
            }

   
    }


    public function getChkMOVBySupervisor($codCheckCAB, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
       
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT * 
            FROM 
                dbo.checkActBasicasSup_MOV as MOV
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

    
}