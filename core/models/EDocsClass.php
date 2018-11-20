<?php namespace models;
require_once 'conexion.php';

class EDocsClass {

    private $instancia_cnx;
    
    public function __construct() {
        $instanciaDB = new \models\conexion();
        $instanciaLista = $instanciaDB->getInstanciaCNX();
        $this->instancia_cnx = $instanciaLista;
    }
    
    function getInstancia_cnx() {
        return $this->instancia_cnx;
    }

    function getInfoUserActive($RUC){
        $query = ("SELECT ruc, nombre, password FROM `tbl_cliente` WHERE ruc='$RUC' LIMIT 1");
        $stmt = $this->instancia_cnx->query($query);
        $stmt->execute();
        $resultset = $stmt->fetch();
        return $resultset;
    }
    
    function getAllDocumentsByRUC($RUC, $tipoDOC='FV', $fecha_INI, $fecha_FIN){
        $query = ("SELECT A.*, B.nombre as ClienteN FROM tbl_transaccion as A INNER JOIN tbl_cliente as B ON A.ruc = B.ruc WHERE A.ruc = '$RUC'  AND A.tipo='$tipoDOC' AND A.fecha BETWEEN '$fecha_INI' AND '$fecha_FIN' LIMIT 100");
        $stmt = $this->instancia_cnx->query($query);
        $stmt->execute();
        $resultset = $stmt->fetchAll();
        return $resultset;
    }

    function updatePassword($RUC, $nuevaPassword){
        $query = "UPDATE `tbl_cliente` SET `password` = :password WHERE ruc = :ruc"; 
        $stmt = $this->instancia_cnx->prepare($query); 
        $stmt->bindParam(':ruc', $RUC); 
        $stmt->bindParam(':password', $nuevaPassword); 
         
        if ($stmt->execute()) {
            return true;
        }else{
            return false;
        }
        
    }
    
    function getDateNow() { 
      return date('Y-m-d');
    }

    function showIncorrectUpdate($mensaje){
        echo "
        <script>
        $(document).ready(function() {
            new PNotify({
                title: 'Estimado Usuario',
                type: 'warning',
                delay: 3000,
                text: '$mensaje',
                nonblock: {
                    nonblock: false
                },
                styling: 'bootstrap3'
            
            });
        
        });

        </script>";
    }

    function showCorrectUpdate($mensaje){
        
        echo "
        <script>
        $(document).ready(function() {
            new PNotify({
                title: 'Estimado Usuario',
                type: 'success',
                delay: 3000,
                text: '$mensaje',
                nonblock: {
                    nonblock: false
                },
                styling: 'bootstrap3'
                
            });
        
        });

        </script>";
    }
    
    function getTypeDocument($codDocument) {
        if ($codDocument=='FV')
        {
        $tdocument = "Factura";
        }
        elseif ($codDocument=='NC') 
        {
        $tdocument = "Nota de Crédito";    
        }
        elseif ($codDocument=='RT') 
        {
        $tdocument = "Retenciones";    
        }
        elseif ($codDocument=='GR') 
        {
        $tdocument = "Guía de Remisión";    
        }
        else
        {
        $tdocument = "SIN IDENTIFICAR";    
        }  
       return $tdocument;
    }
    
}