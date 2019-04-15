<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }   

    $ajaxController = new controllers\ajaxController();
    $arrayTecnicos = $ajaxController->getAllTecnicos();

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

                            <h2 class="heading_b">2. Tipo de Trabajo</h2>
                            <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
                                <div class="uk-width-medium-2-3">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <input type="radio" name="sm" id="sm_regular" data-md-icheck />
                                            <label for="sm_regular" class="inline-label">Mantenimiento Regular</label>
                                        </div>
                                    </div>
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <input type="radio" name="sm" id="sm_express" data-md-icheck />
                                            <label for="sm_express" class="inline-label">Mantenimiento Completo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <p class="uk-text-muted"><a href="#modal_shipping" data-uk-modal>Mostrar el detalle de los tipos de mantenimientos</a></p>
                                    <div class="uk-modal" id="modal_shipping">
                                        <div class="uk-modal-dialog">
                                            <button type="button" class="uk-modal-close uk-close"></button>
                                            <div class="uk-modal-header">
                                                <h3 class="uk-modal-title">Shipping Info</h3>
                                            </div>
                                            <p>With customers all around the world, we are happy to send our products to
                                                anywhere that has a letterbox. P.O. Boxes in the U.S. and Canada can be sent
                                                to with the regular shipping option.</p>
                                            <p>While we always dispatch an order within 2 working days, we can’t directly
                                                control the delivery times beyond our end.</p>
                                            <p>As a general rule, assume that regular post will take 3–8 working days for
                                                Australia, North America and the UK, and 6–28 working days for the rest of
                                                the world. Express post is generally 1–3 working days for Australia, North
                                                America and the UK, and 2–8 working days for other International. ​</p>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="uk-grid-divider">

                            <h2 class="heading_b">3. Fecha y Tecnico</h2>
                            <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
                                <div class="uk-width-medium-2-3">
                                
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-1-3 uk-row-first">
                                            <div class="md-input-wrapper"><label>Fecha Entrega</label>
                                            <input type="text" class="md-input" id="uk_dp_fecha" name="uk_dp_fecha"><span class="md-input-bar "></span></div>
                                            
                                        </div>
                                        <div class="uk-width-2-3">
                                        
                                            <select id="product_edit_tecnico" name="product_edit_tecnico" data-md-selectize>
                                                <?php
                                                     foreach ($arrayTecnicos as $opcion) {
                                                        echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                     }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                        
                                   
                                
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <strong>Fecha prevista</strong><br>
                                    <p class="uk-text-muted">Indique una fecha prevista para la entrega del equipo y asigne un tecnico</p>
                                   
                                </div>
                            </div>
                            <hr class="uk-grid-divider">

                            <h2 class="heading_b">4. Descripcion especifica</h2>
                            <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
                                <div class="uk-width-medium-2-3">
                                    <textarea cols="30" rows="4" class="md-input autosized" placeholder="Maximo 200 caracteres" style="overflow-x: hidden; overflow-wrap: break-word; height: 145px;"></textarea>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <strong>Detalles especificos</strong><br>
                                    <p class="uk-text-muted">Inique en esta seccion los detalles tecnicos extra del trabajo que no se den por entendido. O hayan sido hechos por el cliente.</p>
                                </div>
                            </div>
                            <hr class="uk-grid-divider">

                            <div class="uk-grid uk-margin-large-top" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <button class="md-btn md-btn-primary" type="button">Guardar</button>
                                    <button class="md-btn md-btn-danger" type="button">Cancelar</button>
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