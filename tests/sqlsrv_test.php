<?php

    try {
        $cnx = new PDO("sqlsrv:Server=S1-W202,1433;Database=SBIOKAO", "sfb", "Sud2017$");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

        echo "<p>Connected to SQL Server</p>\n";
        var_dump($cnx);

        try{
            $query = 'SELECT TOP 1 * FROM dbo.Empleados';  

            // simple query  
            $stmt = $cnx->query( $query );  
            $row = $stmt->fetch( PDO::FETCH_ASSOC );
            var_dump($row);
        }catch(Exception $e){
           echo 'Conexion realizada, pero consulta no valida';
        }

    

