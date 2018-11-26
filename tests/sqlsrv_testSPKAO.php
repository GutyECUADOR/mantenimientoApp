<?php

    try {
        $cnx = new PDO("sqlsrv:Server=S1-W202,1433;Database=FALVAREZ_V7", "sfb", "Sud2017$");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

        $oficinaEmpresa = '99'; // From DB
        $ejercicioEmpresa = '2014';  // From DB
        $cod_tipodoc = 'C02'; // Estatico
        $cod_sp_with0 = '00001716'; /*DEFINE FK clave unica*/
        $cod_cliente = '00054818';
        $facturaRef = '';
        $bodega = 'FAL'; 
        $iva = '0';
        $subtotal = '0';
        $total = '0';

        $pcID = php_uname('n'); // Obtiene el nombre del PC
       
      
        $fecha_now_SQL = date("Ymd");  //Elimina del formato -, para evitar error
        $observa_valep = 'Generado por mantenimientosApp';
        $serie_valep='001005';

        $query = "exec dbo.SP_VENGRACAB 'I','ADMINWSSP','$pcID','$oficinaEmpresa', '$ejercicioEmpresa' , '$cod_tipodoc', '$cod_sp_with0','','$fecha_now_SQL','$cod_cliente','$bodega','DOL','1.00','0.00','10','0.00','0.00','0.00','0.00','0.00','10','0.00','2','0.00','12','CON','0','1','0','S','0','1','0','0','','','999',' ',' ','mantenimientosApp','001005','00002050','','','','','0.00','0.00','0.00','','','','','','','','','','0','P','','','','','','0','','','','','0','2','0.00','0.00','0.00','0','999999999 ','0','','','','','','EFE','','','','','$fecha_now_SQL','','' ";
        
        try{

            $rowsAfected = $cnx->exec($query);
            $cnx->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
           
            echo '';
            var_dump( $rowsAfected ) ;
           
            
        }catch(Exception $e){
           echo $e;
        }

    

