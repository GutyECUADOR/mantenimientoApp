<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }  
        
        $ajaxController = new controllers\ajaxController();
        $arrayBodegas = $ajaxController->getAllBodegas();

        
?>

<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- header main -->
      <?php include 'sis_modules/header_main.php'?>
    <!-- header main end -->

    <!-- main sidebar -->
      <?php include 'sis_modules/sidebar_main.php'?>
    <!-- main sidebar end -->

    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom">Busqueda de equipos sin mentanimiento asignado</h3>


            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Rango de Fechas</h3>
                    <div class="uk-grid" data-uk-grid-margin="">
                        <div class="uk-width-large-3-10 uk-width-1-1 uk-row-first">
                            <div class="uk-input-group">
                                <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                <div class="md-input-wrapper md-input-filled">
                                  <label for="uk_dp_start">Fecha Inicial</label>
                                  <input class="md-input label-fixed" type="date" id="uk_dp_start" value="<?php echo date("Y-m-d")?>">
                                  <span class="md-input-bar"></span></div>

                            </div>
                        </div>
                        <div class="uk-width-large-3-10 uk-width-medium-1-1 uk-grid-margin uk-row-first">
                            <div class="uk-input-group">
                                <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                <div class="md-input-wrapper md-input-filled">
                                  <label for="uk_dp_end">Fecha Final</label>
                                  <input class="md-input label-fixed" type="date" id="uk_dp_end" value="<?php echo date("Y-m-d")?>">
                                  <span class="md-input-bar"></span></div>

                            </div>
                        </div>

                        <div class="uk-width-medium-2-10">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Locales/Bodegas</label>
                                <select id="select_bodegas" data-md-selectize>
                                    <?php
                                        foreach ($arrayBodegas as $opcion) {
                                            echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                        }
                                    ?>
                                </select>
                                </span></div>
                        </div>

                        <div class="uk-width-medium-2-10">
                            <a id="btn_searchEquipos" class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light md-btn-block">
                                <i class="uk-icon-search"></i> Buscar
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="md-card">
                <div class="md-card-content">
                            
                    <div id="equipos_table_crud"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_razonOmitir" class="uk-modal">
            <div class="uk-modal-dialog">
                <h2 class="heading_a">Razon de anulacion <i class="material-icons">&#xE8FD;</i></h2>
                <p>Indique la razon para proceder a la anulacion del agendamiento.</p>
                <div class="uk-overflow-container">
                    <form action="" class="uk-form-stacked">
                        <div class="uk-form-row">
                            <select id="select_razonAnulacion" class="md-input">
                                <option value="INFOIN">Informacion de contacto del cliente es incorrecta</option>
                                <option value="NODESE">El cliente no muestra interes en el servicio</option>
                                <option value="VINTER">Venta a intermediarios</option>
                                <option value="NOTACR">Nota de credito</option>
                               
                            </select>
                        </div>
                    </form>
                    
                   
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button type="button" id="btnAnulaOmite" class="md-btn md-btn-flat md-btn-flat-primary">Registrar</button>
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


    <!-- page specific plugins -->
    <!-- jquery ui -->
    <script src="<?php echo ROOT_PATH; ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- jTable -->
    <script src="<?php echo ROOT_PATH; ?>assets/bower_components/jtable/lib/jquery.jtable.js"></script>

    <!--  crud table functions -->
    <script src="<?php echo ROOT_PATH; ?>assets/js/pages/mantenimientosEQ.js"></script>

    <!--  traduccion es crud table  -->
    <script src="<?php echo ROOT_PATH; ?>assets/js/pages/jquery.jtable.es.js"></script>

    <!--  theme color functions -->
    <script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>
</body>