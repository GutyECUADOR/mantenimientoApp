<?php
     if (!isset($_SESSION["usuarioRUC"])){
        header("Location:index.php?&action=login");  
    }

    $supervisoresRepository = new models\SupervisoresRepositoryClass();
    $arraylistReportesSupervisores = $supervisoresRepository->getListActBasicasSup();

    
    
?>

<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- header main -->
      <?php include 'sis_modules/header_main.php'?>
  
    <!-- main sidebar -->
      <?php include 'sis_modules/sidebar_main.php'?>
    <!-- main sidebar end -->

    

    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_content_inner">

            <input id="txt_hidden_sessionEvaluador" name="txt_hidden_sessionEvaluador" type="hidden" value="<?php echo trim($_SESSION["usuarioRUC"])?>">

            <h3 class="heading_a uk-margin-bottom">Lista de supervisores evaluados</h3>
           
            <div class="md-card-list-wrapper">
                <div class="uk-container-center">
                    <div class="md-card-list">
                        
                        <ul class="">

                                <?php
                                    if (is_array($arraylistReportesSupervisores)) {
                                    
                                    foreach ($arraylistReportesSupervisores as $item) {
                                      
                                ?>
                                    <!-- Inicio item -->

                                <li>
                                    <div class="md-card-list-item-menu" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                                        <a href="#" class="md-icon material-icons">&#xE5D4;</a>
                                        <div class="uk-dropdown uk-dropdown-small">
                                            <ul class="uk-nav">
                                                <li><a href="?&action=detailListActBasicasSup&cedula=<?php echo trim($item['Cedula'])?>&evaluador=<?php echo trim($item['Evaluador'])?>&fecha=<?php echo trim($item['fecha'])?>"><i class="material-icons">playlist_add_check</i> Ver detalle</a></li>
                                                <!-- <li><a href="#"><i class="material-icons">print</i> Imprimir Informe</a></li> -->
                                            </ul>
                                        </div>
                                    </div>
                                    <span class="md-card-list-item-date">Mes: <?php echo date('Y-m', strtotime($item['fecha']))?></span>
                                    
                                    <div class="md-card-list-item-avatar-wrapper">
                                        <span class="md-card-list-item-avatar md-bg-grey">KAO</span>
                                    </div>
                                    
                                    <div class="md-card-list-item-subject">
                                        <span>Evaluado por <?php echo $item['NombreEvaluador']?> a <?php echo $item['NombreEvaluado']?></span>
                                    </div>
                                    
                                    
                                </li>
                                
                                <!-- FIN item -->

                    
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
                        </ul>
                    
                    
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

<!-- altair common functions/helpers -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/tableListActBasicasSup.js"></script>



</body>
