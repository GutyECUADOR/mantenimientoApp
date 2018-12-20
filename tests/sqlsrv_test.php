<?php

    function ejecuta(){
        try {
            $cnx = new PDO("sqlsrv:Server=localhost,1433;Database=SCKINSMAN_V7", "sa", "adminguty");
            $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            die("Error connecting to SQL Server: " . $e->getMessage());
        }

            echo "<p>Connected to SQL Server</p>\n";
        
           
            $gestion = 'VEN';
            $ofi = '99';
            $eje = '';
            $tipo = 'C02';
            $codigo = '';

        try{
            $stmt = $cnx->prepare("exec SP_CONTADOR ?, ?, ?, ?, ?"); 
            $stmt->bindValue(1, $gestion); 
            $stmt->bindValue(2, $ofi); 
            $stmt->bindValue(3, $eje); 
            $stmt->bindValue(4, $tipo); 
            $stmt->bindValue(5, $codigo); 
            $stmt->execute();
            
            
            $newCodLimpio = $stmt->fetch(\PDO::FETCH_ASSOC);
            $newCodLimpio =  $newCodLimpio['NExtID'];

            return $newCodLimpio;

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
    }
    
    $result = ejecuta();
    var_dump($result);
    

