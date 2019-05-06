<?php namespace models;
require_once 'conexion.php';


/* LOS MODELOS del MVC retornaran unicamente arrays PHP sin serializar*/

class supervisoresModel  {
    
    private $instanciaDB;
    private $db;
    
    public function __construct() {
        $this->instanciaDB = new \models\conexion();
        $this->db = $this->instanciaDB->getInstanciaCNX();
    }

    public function persist_ActividadesBasicas_CAB($formDataObject, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $codigoDOC = 'CHK'; //Codigo para checklist
        $sth = $this->db->prepare("exec sp_genera_codCheckActBasicasSup_CAB ?");
        $sth->bindParam(1, $codigoDOC);
        $sth->execute();
        $arraycodigo = $sth->fetch();
        $newCod = $arraycodigo[0]; // Codgigo MNT obtenido por SP

        $usuarioActivo = trim($_SESSION["usuarioRUC"]); //Cedula del usuario logeado
        $codEmpresa =  trim($_SESSION["codEmpresaAUTH"]); //Codigo de la empresa seleccionada en login

        $fechaActual = date('Ym01');

        $query = "
            INSERT INTO 
                dbo.checkActBasicasSup_CAB 
            VALUES 
                ('$newCod','$fechaActual','$usuarioActivo','$formDataObject->supervisor','$formDataObject->semana','$codEmpresa','$formDataObject->bodega','0')
        ";

        try{
            $stmt = $this->db->prepare($query); 
            $stmt->execute();
            return array('status' => 'ok', 'mensaje' => 'Se ha registrado correctamente '.$newCod, 'newCod' => $newCod); 
            
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

        
    }


    public function persist_ActividadesBasicas_MOV($arrayCheckListObjects, $newCod, $dataBaseName='KAO_wssp'){
        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX();

        $contadorAgregdos = 0;
        

            foreach ($arrayCheckListObjects as $chekItem) {

                $query = "
                    INSERT INTO 
                        dbo.checkActBasicasSup_MOV
                    VALUES ('$newCod','$chekItem->codigo','$chekItem->checked','$chekItem->comentario')
                ";
                try{
                    $stmt = $this->db->prepare($query); 
                    $stmt->execute();
                    $contadorAgregdos++;
                    
                }catch(PDOException $exception){
                    return array('status' => 'error', 'mensaje' => $exception->getMessage() );
                }
        
            }
        return array('status' => 'ok', 'mensaje' => 'Items registrados '.$contadorAgregdos);
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
}



   
    
