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
                            <a href="?&action=mantenimientosEXT/nuevo" class="md-btn md-btn-flat md-btn-small md-btn-wave" data-uk-tooltip="{pos:'bottom'}" title="Nuevo Mantenimiento"><i class="material-icons">note_add</i>Nuevo</a>
                        </div>
                        <div class="uk-button-dropdown" data-uk-dropdown="{mode: 'click'}" style="padding-top: 4px;">
                            <button class="md-btn md-btn-flat md-btn-small md-btn-wave" data-uk-tooltip="{pos:'top'}" title="Archivo"><i class="material-icons">&#xE2C7;</i> <i class="material-icons">&#xE313;</i></button>
                            <div class="uk-dropdown">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="#">Forward</a></li>
                                    <li><a href="#">Reply</a></li>
                                    <li><a href="#">Offers</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="#">Trash</a></li>
                                    <li><a href="#">Spam</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end ribbon options bar -->

    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom">Equipos externos</h3>


            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Rango de Fechas</h3>
                    <div class="uk-grid" data-uk-grid-margin="">
                        <div class="uk-width-large-1-3 uk-width-1-1 uk-row-first">
                            <div class="uk-input-group">
                                <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                <div class="md-input-wrapper md-input-filled">
                                <label for="uk_dp_start">Fecha Inicial</label>
                                <input class="md-input label-fixed" type="date" id="uk_dp_start" value="<?php echo date("Y-m-d")?>">
                                <span class="md-input-bar"></span></div>

                            </div>
                        </div>
                        <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-grid-margin uk-row-first">
                            <div class="uk-input-group">
                                <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                <div class="md-input-wrapper md-input-filled">
                                <label for="uk_dp_end">Fecha Final</label>
                                <input class="md-input label-fixed" type="date" id="uk_dp_end" value="<?php echo date("Y-m-d")?>">
                                <span class="md-input-bar"></span></div>

                            </div>
                        </div>

                        <div class="uk-width-medium-1-3">
                            <a id="btn_searchEquipos" class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light md-btn-block">
                                <i class="uk-icon-search"></i> Buscar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <div>    
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


</body>