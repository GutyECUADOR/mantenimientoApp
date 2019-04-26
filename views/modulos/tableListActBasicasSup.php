<?php
    $supervisoresRepository = new models\SupervisoresRepositoryClass();
    $arraylistReportesSupervisores = $supervisoresRepository->getListActBasicasSup();

    $chkBySupervisor = $supervisoresRepository->getChkCABBySupervisor('0400882940','2019-04-01');
    var_dump($chkBySupervisor[0]['items']);
    
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

            <h3 class="heading_a uk-margin-bottom">Lista de supervisores evaluados</h3>

            <div class="md-card-list-wrapper" id="mailbox">
                <div class="uk-width-large-8-10 uk-container-center">
                    <div class="md-card-list">
                        <!-- <div class="md-card-list-header heading_list">TODOS</div> -->
                        <div class="md-card-list-header md-card-list-header-combined heading_list" style="display: none">All Messages</div>
                        <ul class="hierarchical_slide">

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
                                                <li><a href="#"><i class="material-icons">&#xE149;</i> Imprimir Informe</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <span class="md-card-list-item-date">Mes: <?php echo $item['fecha']?></span>
                                    <!-- <div class="md-card-list-item-select">
                                        <input type="checkbox" data-md-icheck />
                                    </div> -->
                                    <div class="md-card-list-item-avatar-wrapper">
                                        <span class="md-card-list-item-avatar md-bg-grey">KAO</span>
                                    </div>
                                    <div class="md-card-list-item-sender">
                                        <span><?php echo substr($item['Cedula'], 0, 20)?></span>
                                    </div>
                                    <div class="md-card-list-item-subject">
                                        <span><?php echo $item['Nombre']?></span>
                                    </div>
                                    <div class="md-card-list-item-content-wrapper">
                                        <div class="md-card-list-item-content">
                                            <!-- Resumen Here -->
                                            <div class="uk-grid" data-uk-grid-margin="">
                                                <div class="uk-width-medium-1-1">
                                                    <div class="uk-overflow-container">
                                                        <table class="uk-table uk-table-hover uk-table-nowrap uk-table-align-vertical">
                                                            <thead>
                                                                <tr>
                                                                    <!-- Inicio de las filas -->
                                                                    <th class="uk-width-1-10"><b>Items de evaluacion \ Semanas</b></th>
                                                                    <?php
                                                                        foreach ($chkBySupervisor as $checkListCAB) {
                                                                        
                                                                    ?>
                                                                
                                                                    <th class="uk-text-center md-bg-grey-100 uk-text-small"><?php echo $checkListCAB['codChecklist']?></th>
                                                                   
                                                                    <?php } 
                                                                        
                                                                    ?> <!-- End for each -->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <!-- <td class="md-bg-grey-100 uk-text-small">TEST</td>
                                                                    <td class="uk-text-center"><i class="md-list-addon-icon material-icons uk-text-success">check</i></td>
                                                                    <td class="uk-text-center"><i class="md-list-addon-icon material-icons uk-text-success">check</i></td>
                                                                    <td class="uk-text-center"><i class="md-list-addon-icon material-icons uk-text-danger">clear</i></td>
                                                                    <td class="uk-text-center"><i class="md-list-addon-icon material-icons uk-text-success">check</i></td>
                                                                    <td class="uk-text-center">60/80</td> -->
                                                                </tr>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Resumen -->

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
