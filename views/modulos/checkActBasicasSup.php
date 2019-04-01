<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- header main -->
      <?php include 'sis_modules/header_main.php'?>
  
    <!-- main sidebar -->
      <?php include 'sis_modules/sidebar_main.php'?>
    <!-- main sidebar end -->

    

    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        
        <div id="page_content_inner">

            <div class="uk-width-medium-8-10 uk-container-center">
                <div class="md-card md-card-single">
                    <div class="md-card-toolbar">
                        <div class="md-card-toolbar-actions">
                            <i class="md-icon material-icons"></i>
                        </div>
                        <h3 class="md-card-toolbar-heading-text large">
                            Actividades basicas a cumplir
                        </h3>

                    </div>

                    

                    <form action="" id="formActividadesBasicas" >
                        <div class="md-card-content" id="todo_list" style="padding-bottom: 50px;">

                            <h2 class="heading_list">Informacion Solicitante: {{ usuarioIdentificado | capitalize }}</h2>

                            <div class="uk-grid" data-uk-grid-margin> 
                                <div class="uk-input-group uk-width-medium-1-3">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-user"></i></span>
                                    <label>Cedula del supervisor: </label>
                                    <input type="number" v-model="txtCIRUC" v-on:keyup="searchCIRUC" name="txtCIRUC" class="md-input label-fixed" placeholder="170000000000" />
                                </div>

                                <div class="uk-input-group uk-width-medium-1-3">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-cog"></i></span>
                                    <label class="uk-form-label">Empresa: </label>
                                    <select id="selectEmpresa" name="selectEmpresa" class="md-input" data-uk-tooltip="{pos:'top'}" title="Seleccione empresa">
                                            <option value="" disabled selected hidden>Seleccione por favor</option>
                                            <option value="a">MODELO</option>
                                    </select>
                                </div>

                                <div class="uk-input-group uk-width-medium-1-3">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-cog"></i></span>
                                    <label class="uk-form-label">Bodega: </label>
                                    <select id="selectBodega" name="selectBodega" class="md-input" data-uk-tooltip="{pos:'top'}" title="Seleccione bodega">
                                            <option value="" disabled selected hidden>Seleccione por favor</option>
                                            <option value="a">PRUEBA</option>
                                    </select>
                                </div>

                                

                            </div>

                            <h2 class="heading_list">Semana, Hora de Ingreso/Salida: </h2>
                            <div class="uk-grid" data-uk-grid-margin>

                                <div class="uk-input-group uk-width-medium-1-3">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <label class="uk-form-label">Semana: </label>
                                    <select id="selectSemana" name="selectSemana" class="md-input" data-uk-tooltip="{pos:'top'}" title="Seleccione semana">
                                            <option value="" disabled selected hidden>Seleccione por favor</option>
                                            <option value="a">SEMANA 1</option>
                                            <option value="b">SEMANA 2</option>
                                            <option value="c">SEMANA 3</option>
                                            <option value="c">SEMANA 4</option>
                                    </select>
                                </div>

                                <div class="uk-input-group uk-width-medium-1-3">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                    <label> Hora de Inicio</label>
                                    <input id="txt_horaINI" name="txt_horaINI" class="md-input label-fixed" type="time" data-uk-timepicker>
                                    
                                </div>
                                
                                <div class="uk-input-group uk-width-medium-1-3">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                    <label>Hora de finalizacion</label>
                                    <input id="txt_horaFIN" name="txt_horaFIN" class="md-input label-fixed" type="time" data-uk-timepicker>
                                
                                </div>
                            </div>

                            <h2 class="heading_list">Detalle: </h2>
                            <div>
                                <!-- Checklists -->
                                <ul class="md-list md-list-addon uk-margin-small-bottom uk-nestable" data-uk-nestable="{ maxDepth:2,handleClass:'md-list-content'}">
                                    
                                    <li>
                                        <div class="md-list-addon-element">
                                            <input type="checkbox" name="chklist" data-md-icheck />
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading"> Revision de CheckList </span>
                                            <span class="uk-text-small uk-text-muted">Se ha revisado los checklist de los locales.</span>
                                        </div>
                                        <div class="md-input-wrapper md-input-filled">
                                            <input type="text" class="md-input" placeholder="Comentario del item">
                                            <span class="md-input-bar"></span>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="md-list-addon-element">
                                            <input type="checkbox" name="chklist" data-md-icheck />
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading"> Revision de Couching de ventas</span>
                                            <span class="uk-text-small uk-text-muted">Se ha dado revision al trabajo del personal de ventas.</span>
                                        </div>
                                        <div class="md-input-wrapper md-input-filled">
                                            <input type="text" class="md-input" placeholder="Comentario del item">
                                            <span class="md-input-bar"></span>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="md-list-addon-element">
                                            <input type="checkbox" data-md-icheck />
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading"> Revision de Couching de ventas</span>
                                            <span class="uk-text-small uk-text-muted">Se ha dado revision al trabajo del personal de ventas.</span>
                                        </div>
                                        <div class="md-input-wrapper md-input-filled">
                                            <input type="text" class="md-input" placeholder="Comentario del item">
                                            <span class="md-input-bar"></span>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="md-list-addon-element">
                                            <input type="checkbox" data-md-icheck />
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading"> Revision de Couching de ventas</span>
                                            <span class="uk-text-small uk-text-muted">Se ha dado revision al trabajo del personal de ventas.</span>
                                        </div>
                                        <div class="md-input-wrapper md-input-filled">
                                            <input type="text" class="md-input" placeholder="Comentario del item">
                                            <span class="md-input-bar"></span>
                                        </div>
                                    </li>
                                </ul>
                                
                                </div>

                        </div>
                    </form>
                    
                </div>
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

<!-- altair common functions/helpers -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/checkListSupervisoresBasicas.js"></script>



</body>
