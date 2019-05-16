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
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table uk-table-nowrap table_check uk-table-hover">
                            <thead>
                            <tr>
                                <th class="uk-width-1-10">#</th>
                                <th class="uk-width-2-10">ID Factura</th>
                                <th class="uk-width-1-10">ID Mant.</th>
                                <th class="uk-width-1-10">Mant Fisico.</th>
                                <th class="uk-width-2-10">Cliente</th>
                                <th class="uk-width-1-10">Equipo</th>
                                <th class="uk-width-1-10">Fecha Programada</th>
                                <th class="uk-width-1-10">Num Rel.</th>
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