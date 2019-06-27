<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }   

?>


<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- header main -->
      <?php include 'sis_modules/header_main.php'?>
    <!-- header main end -->

    <!-- main sidebar -->
      <?php include 'sis_modules/sidebar_main.php'?>
    <!-- main sidebar end -->

    <!-- ribbon options bar -->
    <div id="top_bar">
        <div class="md-top-bar">
            <div class="uk-width-large-8-10 uk-container-center">
                <div class="uk-clearfix">
                    <div class="md-top-bar-actions-left">
                        
                        <div class="md-btn-group">
                            <a data-uk-modal="{target:'#modal_advancedSearch'}" class="md-btn md-btn-flat md-btn-small md-btn-wave" data-uk-tooltip="{pos:'bottom'}" title="Filtros de Busqueda"><i class="material-icons">note_add</i>Busqueda Avanzada</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_content_inner">
            <h3 class="heading_b uk-margin-bottom">Historico de mantenimientos internos</h3>
            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Filtros de busqueda</h3>
                    <div class="uk-grid" data-uk-grid-margin="">
                        <div class="uk-width-large-3-10 uk-row-first">
                            <div class="uk-input-group">
                                <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                <div class="md-input-wrapper md-input-filled">
                                  <label for="uk_dp_start">Fecha Inicial</label>
                                  <input class="md-input label-fixed" type="date" id="uk_dp_start" value="<?php echo date("Y-m-d")?>">
                                  <span class="md-input-bar"></span></div>

                            </div>
                        </div>
                        <div class="uk-width-medium-3-10">
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
                                <label>Tipos</label>
                                <select id="select_tiposDoc" data-md-selectize>
                                        <option value="ALL">Todos</option>
                                        <option value="PND">Pendientes</option>
                                        <option value="ANUL">Aulados/Omitidos</option>
                                </select>
                                </span></div>
                        </div>

                        <div class="uk-width-medium-2-10">
                            <a id="btn_search" class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light md-btn-block">
                                <i class="uk-icon-search"></i> Buscar
                            </a>
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-toolbar">
                        <div class="md-card-head-menu" data-uk-dropdown="{pos:'bottom-right'}" aria-haspopup="true" aria-expanded="false">
                            <i class="md-icon material-icons"></i>
                            <div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" aria-hidden="true" style="min-width: 160px; top: 32px; left: 0px;" tabindex="">
                                <ul class="uk-nav">
                                    <li><a class="showInformePDF"><i class="material-icons">picture_as_pdf</i> Imprimir PDF</a></li>
                                    <li><a class="showInformeExcel"><i class="material-icons">print</i> Generar Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <h3 class="md-card-toolbar-heading-text large">
                            Resultados
                        </h3>

                    </div>
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table uk-table-nowrap table_check uk-table-hover">
                            <thead>
                            <tr>
                                <th class="uk-width-1-10">#</th>
                                <th class="uk-width-2-10">ID Factura Venta</th>
                                <th class="uk-width-1-10">ID Mant.</th>
                                <th class="uk-width-1-10">Mant Fisico.</th>
                                <th class="uk-width-2-10">Cliente</th>
                                <th class="uk-width-1-10">Equipo</th>
                                <th class="uk-width-1-10">Fecha Programada</th>
                                <th class="uk-width-1-10">Num Cotizacion.</th>
                                <th class="uk-width-1-10">Num Fact Cotizacion.</th>
                                <th class="uk-width-1-10">Estado</th>
                            
                            </tr>
                            </thead>
                            <tbody id="tbodyresults">

                                <!-- AJAX result response aqui -->
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>    
        </div>
    </div>

    <div id="modal_advancedSearch" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <h2 class="heading_a"><i class="material-icons">search</i> Busqueda Avanzada </h2>
                <p>Indique filtros de busqueda.</p>
                <div class="uk-row">
                    <form action="" class="uk-form-stacked">
                        <div class="uk-form-row">
                            <label for="advanced_cedula">Cedula o RUC del cliente</label>
                            <input id="advanced_cedula" class="md-input" type="text" value="">
                        </div>
                    </form>
                    
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button type="button" class="md-btn md-btn-flat uk-modal-close">Cancelar</button>
                    <button id="btn_search_advanced" type="button" class="md-btn md-btn-flat md-btn-flat-primary">Buscar</button>
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

<script src="<?php echo ROOT_PATH; ?>assets/js/pages/mantenimientosHistorico.js"></script>

<!--  theme color functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>

</body>