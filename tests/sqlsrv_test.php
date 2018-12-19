<?php

    try {
        $cnx = new PDO("sqlsrv:Server=196.168.1.201,1433;Database=MODELOIMPK_V7", "sfb", "sfb123");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

        echo "<p>Connected to SQL Server</p>\n";
        var_dump($cnx);

        try{
            $query = 'SELECT TOP 1 * FROM dbo.COB_CLIENTES';  

            // simple query  
            $stmt = $cnx->query( $query );  
            $row = $stmt->fetch( PDO::FETCH_ASSOC );
            var_dump($row);
        }catch(Exception $e){
           echo 'Conexion realizada, pero consulta no valida';
        }

    

