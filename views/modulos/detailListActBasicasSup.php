<?php
    if (!isset($_SESSION["usuarioRUC"]) || !isset($_GET["cedula"]) || !isset($_GET["evaluador"]) || !isset($_GET["fecha"]) ){
           header("Location:index.php?&action=login");  
        }   
    
    $cedula = $_GET["cedula"]; // Cedula del evaluado
    $cedulaEvaluador = trim($_GET["evaluador"]);
    $fechaMes = $_GET["fecha"];

    $supervisoresRepository = new models\SupervisoresRepositoryClass();
    $arrayCheckLists = $supervisoresRepository->getChkCABBySupervisor($cedula, $cedulaEvaluador, $fechaMes); 
   
     
    //var_dump($arrayCheckLists);

   
?>

<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- header main -->
      <?php include 'sis_modules/header_main.php'?>
    <!-- header main end -->

    <!-- main sidebar -->
      <?php include 'sis_modules/sidebar_main.php'?>
    <!-- main sidebar end -->

    <!-- secondary sidebar -->
    <?php include 'sis_modules/secondary_sidebar.php'?>
    <!-- secondary sidebar end -->


    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_content_inner">

            <h3 class="heading_a uk-margin-bottom">Evaluaciones realizadas por supervisor inmediato: </h3>

            <div class="uk-grid" data-uk-grid-margin="">
                    
                    <div class="uk-width-medium-1-1">
                        <div class="md-card">
                            <div class="md-card-content">
                                <div class="uk-overflow-container">
                                    <table class="uk-table uk-table-hover uk-table-nowrap uk-table-align-vertical">
                                        <thead>
                                            <tr>
                                                
                                                <th class="uk-width-1-10">Items / Semanas (CheckList)</th>
                                                <th class="uk-text-center md-bg-grey-100 uk-text-small"> SEMANA 1 </th>
                                                <th class="uk-text-center md-bg-grey-100 uk-text-small"> SEMANA 2 </th>
                                                <th class="uk-text-center md-bg-grey-100 uk-text-small"> SEMANA 3 </th>
                                                <th class="uk-text-center md-bg-grey-100 uk-text-small"> SEMANA 4 </th>
                                                <th class="uk-text-center md-bg-grey-100 uk-text-small"> TOTAL </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (is_array($arrayCheckLists)) {
                                                
                                                foreach ($arrayCheckLists as $checkList) {
                                           
                                                
                                            ?>
                                                <tr>        
                                                    <td class="md-bg-grey-100 uk-text-small"><span class="codcheckItem" data-codCheckItem="<?php echo trim($checkList['codCheckItem'])?>"></span><?php echo $checkList['codCheckItem'].' - '. $checkList['Titulo']?></td>  
                                                    <td class="uk-text-center">
                                                        <?php 
                                                        if (isset($checkList[3])) {
                                                            echo $supervisoresRepository->showIconCheched($checkList[1],$checkList[3]);
                                                        }
                                                        
                                                        ?>
                                                    </td> 
                                                    <td class="uk-text-center">
                                                        <?php 
                                                        if (isset($checkList[4])) {
                                                            echo $supervisoresRepository->showIconCheched($checkList[1],$checkList[4]);
                                                        }
                                                        
                                                        ?>
                                                    </td> 
                                                    <td class="uk-text-center">
                                                        <?php 
                                                        if (isset($checkList[5])) {
                                                            echo $supervisoresRepository->showIconCheched($checkList[1],$checkList[5]);
                                                        }
                                                        
                                                        ?>
                                                    </td> 
                                                    <td class="uk-text-center">
                                                        <?php 
                                                        if (isset($checkList[6])) {
                                                            echo $supervisoresRepository->showIconCheched($checkList[1],$checkList[6]);
                                                        }
                                                        
                                                        ?>
                                                    </td> 
                                                    <td class="uk-text-center"> <span id="<?php echo 'total_'.trim($checkList[1]) ?>">calculando...</span> </td>  
                                                </tr>
                                                   
                                                
                                            <?php
                                                    
                                                    
                                                } // FIN del ciclo for

                                                
                                                }else{
                                                    echo '
                                                        <script>
                                                            alert("No se pudo establecer conexion con la tabla requerida o existe un error en la sintaxis SQL.");
                                                        </script>
                                                    ';
                                                }
                                                // FIN DEL IF
                                            ?>
                                            <tr>
                                                <td class="md-bg-grey-100 uk-text-small uk-text-center" colspan="5">TOTAL GENERAL</th>
                                                <td class="uk-text-center" id="totalGeneralShow"> Error </th>
                                            </tr>
                                            <tr>
                                                <td class="md-bg-grey-100 uk-text-small uk-text-center" colspan="5">TOTAL GENERAL SOBRE CANT. ITEMS</th>
                                                <td class="uk-text-center" id="totalGeneralEquivalenteShow"> Error </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

           
        </div>
    
       


    </div>

  
<!-- google web fonts -->
<script>
    WebFontConfig = {
        google: {
            families: [
                'Source+Code+Pro:400,700:latin',
                'Roboto:400,300,500,700,400italic:latin'
            ]
        }
    };
    (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
        '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();
</script>

<!-- common functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/common.min.js"></script>
<!-- uikit functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/uikit_custom.js"></script>
<!-- altair common functions/helpers -->
<script src="<?php echo ROOT_PATH; ?>assets/js/altair_admin_common.min.js"></script>

<!-- scripts de la pagina -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/detailListActBasicasSup.js"></script>

</body>