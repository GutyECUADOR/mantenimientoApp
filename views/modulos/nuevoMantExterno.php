<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }   

    $ajaxController = new controllers\ajaxController();
    $arrayTecnicos = $ajaxController->getAllTecnicos();
    $arrayBicis = $ajaxController->getAllTiposEquiposBy('BICI');
    $arrayEquipos = $ajaxController->getAllTiposEquiposBy('EQUI');
    $arrayMantenimientosBici = $ajaxController->getAllTiposEquiposBy('MANTB');
    $arrayMantenimientosEquipos = $ajaxController->getAllTiposEquiposBy('MANTE');
    $arrayBodegas = $ajaxController->getAllBodegas();

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

            <div class="uk-grid">
                <div class="uk-width-medium-4-5 uk-container-center">
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <div class="md-card-head-menu" data-uk-dropdown="{pos:'bottom-right'}" aria-haspopup="true" aria-expanded="false">
                                <i class="md-icon material-icons"></i>
                                <div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" aria-hidden="true" style="min-width: 160px; top: 32px; left: 0px;" tabindex="">
                                    <ul class="uk-nav">
                                        <li><a href="#">Nuevo</a></li>
                                    </ul>
                                </div>
                            </div>
                            <h3 class="md-card-toolbar-heading-text large">
                                Registro de mantenimiento externo
                            </h3>

                        </div>

                        <div class="md-card-content large-padding">

                            <h2 class="heading_b">1. Cliente</h2>
                            <div class="uk-grid uk-grid-divider uk-margin-medium-bottom" data-uk-grid-margin>
                                <div class="uk-width-medium-2-3">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <label>Cedula/RUC <span class="uk-badge uk-badge-danger uk-badge-notification">Obligatorio</span> </label>
                                            <input type="text" id="inputRUC" class="md-input label-fixed" required />
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <label>Telefono</label>
                                            <input type="text" id="inputTelefono" class="md-input label-fixed" />
                                        </div>
                                    </div>
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <label>Nombre del cliente</label>
                                            <input type="text" id="inputNombre" class="md-input label-fixed" />
                                        </div>
                                    </div>
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <label>Email</label>
                                            <input type="text" id="inputCorreo" class="md-input label-fixed" />
                                        </div>
                                    </div>
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-1-1">
                                            <label>Direccion</label>
                                            <input type="text" id="inputDireccion" class="md-input label-fixed" />
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <strong>Informacion del cliente</strong><br>
                                    <p class="uk-text-muted">Indique el cliente al que sera facturado el trabajo, indique su numero de Cedula o RUC; este debe estar previamente registrado en Winfenix. O haga clic en el boton buscar cliente.</p>
                                    <button class="md-btn btn-block md-btn-success md-btn-block" data-uk-modal="{target:'#modal_AgendarNuevo'}" type="button">Buscar cliente</button>
                                </div>
                            </div>
                            <hr class="uk-grid-divider">

                            <h2 class="heading_b">2. Identificacion del equipo</h2>
                            <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
                                
                                <div class="uk-width-medium-2-4">
                                    <select id="select_tipoEquipo" class="md-input" data-uk-tooltip="{pos:'top'}" title="Tipo de equipo">
                                        <option value="" disabled="" selected="" hidden="">Seleccione el tipo de equipo</option>
                                        <optgroup label="Bicicletas">
                                            <?php
                                                foreach ($arrayBicis as $opcion) {
                                                echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                }
                                            ?>
                                        </optgroup>
                                        <optgroup label="Equipos">
                                            <?php
                                                foreach ($arrayEquipos as $opcion) {
                                                echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                }
                                            ?>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="uk-width-medium-2-4">
                                    <select id="select_tipoMantenimiento" class="md-input" data-uk-tooltip="{pos:'top'}" title="Tipo de mantenimiento">
                                        <option value="" disabled="" selected="" hidden="">Seleccione el tipo de mantenimiento</option>
                                        <optgroup label="Bicicletas">
                                            <?php
                                                foreach ($arrayMantenimientosBici as $opcion) {
                                                echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                }
                                            ?>
                                        
                                        </optgroup>
                                        <optgroup label="Equipos">
                                            <?php
                                                foreach ($arrayMantenimientosEquipos as $opcion) {
                                                echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                }
                                            ?>
                                        </optgroup>
                                    </select>
                                </div>

                                

                            </div>

                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-commenting"></i></span>
                                        <div class="md-input-wrapper md-input-filled">
                                        <label>SERIE / MODELO</label>
                                            <input type="text" id="inputSerieModelo" class="md-input label-fixed" placeholder="Escriba aqui..." maxlength="75" />
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>

                            <h2 class="heading_b">3. Fecha y Tecnico</h2>
                            <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
                                
                                <div class="uk-width-medium-2-4">
                                    
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <div class="md-input-wrapper ">
                                            <div class="md-input-wrapper md-input-filled"><label>Fecha Entrega</label>
                                            <input type="text" class="md-input label-fixed" id="uk_dp_fecha" name="uk_dp_fecha" placeholder="Click aqui..."><span class="md-input-bar "></span>
                                        </div>
                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-2-4">
                                
                                    <select id="select_tecnico" name="select_tecnico" class="md-input" data-uk-tooltip="{pos:'top'}" title="Seleccione un tecnico">
                                        <option value="" disabled="" selected="" hidden="">Seleccione un tecnico</option>
                                        <?php
                                                foreach ($arrayTecnicos as $opcion) {
                                                echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                }
                                        ?>
                                        
                                    </select>
                                </div>

                                <div class="uk-width-medium-4-4">
                                
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-database"></i></span>

                                        <select id="select_bodega" name="select_bodega" class="md-input" data-uk-tooltip="{pos:'top'}" title="Seleccione un local">
                                            <option value="" disabled="" selected="" hidden="">Seleccione un local/bodega</option>
                                            <?php
                                                    foreach ($arrayBodegas as $opcion) {
                                                    echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                    }
                                            ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                    
                               
                            </div>
                            
                            <h2 class="heading_b">4. Descripcion especifica</h2>
                            <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
                                <div class="uk-width-medium-3-3">
                                    <textarea id="inputComentario" cols="30" rows="4" class="md-input autosized" placeholder="Escriba aqui, maximo 200 caracteres" style="overflow-x: hidden; overflow-wrap: break-word; height: 145px;"></textarea>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODALS WINDOWS -->

    <div class="uk-modal" id="modal_AgendarNuevo">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Busqueda de clientes - Winfenix <i class="material-icons">search</i></h3>
            </div>
            
                <div class="uk-grid">
                    
                    <div class="uk-width-medium-2-3">
                        <div class="uk-input-group">
                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-user"></i></span>
                            <div class="md-input-wrapper md-input-filled">
                                <label>Cedula, RUC o Nombre del Cliente</label>
                                <input class="md-input label-fixed" id="terminoBusquedaModalCliente" type="text">
                                <span class="md-input-bar "></span>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-medium-1-3">
                        <div class="uk-input-group">
                            <span class="uk-input-group-addon" style="padding:0px"><i class="uk-input-group-icon uk-icon-cog"></i></span>
                            <select id="tipoBusquedaModalCliente" class="md-input" data-uk-tooltip="{pos:'top'}" title="Filtro de busqueda">
                              <option value="cedula">CEDULA / RUC</option>
                                <option value="nombre" selected>NOMBRE</option>
                            </select>
                        </div>
                    </div>
                    

                    <div class="uk-width-medium-1-1">
                        <br><h4>Resultados:</h4>
                        <ul id="resultadosBusquedaClientes" class="md-list md-list-addon md-list-right">
                            <!-- Render items here -->
                        </ul>
                    </div>
                    
                   
                </div>
            
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Cerrar</button>
                <button id="searchClienteModal_Button" type="button" class="md-btn md-btn-flat md-btn-flat-primary">Buscar</button>
            </div>
        </div>
    </div>

    <!-- FAB Button -->  
    <div class="md-fab-wrapper">
        <a class="md-fab md-fab-accent" id="save_form_submit">
            <i class="material-icons">save</i>
        </a>
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
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/nuevoMantExterno.js"></script>

<!--  theme color functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>

</body>