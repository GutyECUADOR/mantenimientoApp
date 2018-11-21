<?php

    try {
        $cnx = new PDO("sqlsrv:Server=S1-W202,1433;Database=KAO_wssp", "sfb", "Sud2017$");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

        $gestion = 'VEN';
        $ofi = '99';
        $eje = '';
        $tipo = 'PEM';
        $codigo = '';

        $query = "exec SP_CONTADOR VEN, 99, '', PEM, ''";

        try{

            $stmt = $cnx->prepare("exec sp_genera_codMantenimiento ?");
            $cnx->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $value = 'MNT';
            $stmt->bindValue(1, $value, \PDO::PARAM_STR); 

            // call the stored procedure
            $res->nextRowset (); 
            $stmt->execute();

            var_dump($stmt->fetch());
            
        }catch(Exception $e){
           echo $e;
        }

    

