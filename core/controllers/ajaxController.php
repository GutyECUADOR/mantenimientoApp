<?php namespace controllers;

class ajaxController  {
 
    /* Devuelve array en el formato requerido para el plugin JTable */
    public function getAllEquiposSinMantenimiento($fechaInicio, $fechaFinal, $startIndex, $pageSize) {

        $ajaxModel = new \models\ajaxModel();
        //Respuesta de informacion de VEN_MOV
        $dbEmpresa = trim($_SESSION["empresaAUTH"]);
        $arrayEquipos = $ajaxModel->getArraysMantenimientosEQ($dbEmpresa, $pageSize, $fechaInicio, $fechaFinal);
        $arrayUTF8 = array();
        foreach ($arrayEquipos as $equipo) {
           
            /* Recuperamos filas para validar UTF-8 */
            $CodigoFac= iconv("iso-8859-1", "UTF-8",  $equipo['CodigoFac']);
            $TipoDocumento= iconv("iso-8859-1", "UTF-8",  $equipo['TipoDoc']);
            $FechaCompra= $equipo['FechaCompra'];
            $FechaCompraSPAN = date("Y-m-d", strtotime($FechaCompra));
            $NombreCliente= trim(iconv("iso-8859-1", "UTF-8", $equipo['NombreCliente']));
            $Telefono = trim(iconv("iso-8859-1", "UTF-8", $equipo['Telefono']));
            $CodProducto = trim(iconv("iso-8859-1", "UTF-8", $equipo['CodProducto']));
            $Producto = trim(iconv("iso-8859-1", "UTF-8", $equipo['Producto']));
            $DiasGarantia = trim(iconv("iso-8859-1", "UTF-8", $equipo['DiasGarantiarestantes']));
            $CantitadProd = trim(iconv("iso-8859-1", "UTF-8", $equipo['CantitadProd']));
            $Direccion = trim(iconv("iso-8859-1", "UTF-8", $equipo['Direccion']));
            $Email = trim(iconv("iso-8859-1", "UTF-8", $equipo['Email']));
            $NombreBodega = trim(iconv("iso-8859-1", "UTF-8", $equipo['NombreBodega']));

            $rowdata = ([
                "CodigoFac"=>$CodigoFac, 
                "TipoDocumento"=>$TipoDocumento, 
                "FechaCompra"=>$FechaCompraSPAN, 
                "CodProducto"=>$CodProducto, 
                "Producto"=>$Producto,
                "DiasGarantia"=>$DiasGarantia,
                "CantitadProd"=>$CantitadProd,
                "NombreCliente"=>$NombreCliente, 
                "Telefono"=>$Telefono, 
                "Direccion"=>$Direccion,
                "Email"=>$Email,
                "NombreBodega"=>$NombreBodega
            ]);

            array_push($arrayUTF8, $rowdata);
        }

        return $arrayUTF8;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function agendarMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->insertNewMantenimiento($data);
        return $response;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
     public function anularMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->anulaMantenimientoByCod($data);
        return $response;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function aprobarMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->aprobarMantenimientoByCod($data);
        return $response;
    }


    /* Realiza peticion al modelo para setear estado 3 al registro de la tabla mantenimientosEQ*/
    public function omitirMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->omitirMantenimientoByCod($data);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getTiposMantenimientos(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysTiposDOCMantenimientos();
        return $response;
    }
    
    /* Retorna la respuesta del modelo ajax*/
    public function getAllTecnicos(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysTecnicos('SBIOKAO');
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getProductoByCod($codProducto){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = trim($_SESSION["empresaAUTH"]);
        $response = $ajaxModel->getArrayProducto($dbEmpresa, $codProducto);
        return $response;
    }

    /*Envia informacion al modelo para actualizar, ejecuta insert en WINFENIX, VEN_CAB y VEN_MOV */
    public function updateMantenimientoByCod($formData){
        date_default_timezone_set('America/Lima');
        $ajaxModel = new \models\ajaxModel();
        $VEN_CAB = new \models\venCabClass();
        $dbEmpresa = trim($_SESSION["empresaAUTH"]);

        //Actualizacion a WSSP - MantenimientosEQ
        $response_WSSP = $ajaxModel->updateMantenimientoEQ($formData);

        //Obtenemos informacion de la empresa
        $datosEmpresa = $ajaxModel->getDatosEmpresaFromWINFENIX($dbEmpresa);

        //Creamos nuevo codigo de VEN_CAB (secuencial)
        $newCodigo = $ajaxModel->getNextNumDocWINFENIX('C02', $dbEmpresa); // Recuperamos secuencial de SP de Winfenix
        $newCodigoWith0 = $ajaxModel->formatoNextNumDocWINFENIX($dbEmpresa, $newCodigo); // Asignamos formato con 0000X
      
         /*Creacion y asignacion de valores a VEN_CAB*/
       
        $VEN_CAB->setPcID(php_uname('n'));
        $VEN_CAB->setOficina($datosEmpresa['Oficina']);
        $VEN_CAB->setEjercicio($datosEmpresa['Ejercicio']);
        $VEN_CAB->setTipoDoc('C02');
        $VEN_CAB->setNumeroDoc($newCodigoWith0);
        $VEN_CAB->setFecha(date('Ymd h:i:s'));
        $VEN_CAB->setBodega('FAL');
        $VEN_CAB->setDivisa('DOL');
        $VEN_CAB->setSubtotal(100);
        $VEN_CAB->setImpuesto(12);
        $VEN_CAB->setTotal(112);
        $VEN_CAB->setFormaPago('EFE');
        $VEN_CAB->setSerie('001005');
        $VEN_CAB->setSecuencia('00002050');
        $VEN_CAB->setObservacion('TEST OBJETOS');
        $VEN_CAB->setProductos(NULL);
        
        //$newIDWinFenix = $datosEmpresa['Oficina'].$datosEmpresa['Ejercicio'].$tipoDOC.$newCodigoWith0;
        
        //Registro en VEN_CAB
        $response_VEN_CAB = $ajaxModel->insertVEN_CAB($VEN_CAB, $dbEmpresa);

        if($response_WSSP && $response_VEN_CAB){
            return true;
        }else{
            return false;
        }
        
        
    }
    
    
}
