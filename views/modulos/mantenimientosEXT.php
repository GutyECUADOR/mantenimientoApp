<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }   

    /* CREACION DE INSTANCIA USADA PARA ESTA VISTA*/  
    $codEmpresa = trim($_SESSION["empresaAUTH"]);  // Nombre de la db asiganda en el login
    $mantenimientos = new models\MantenimientosClass();

    if (isset($_GET['fechaINI']) || isset($_GET['fechaFIN'])) {
       $fechaINI = $_GET['fechaINI'];
       $fechaFIN = $_GET['fechaFIN'];
    }else{
        $fechaINI = $mantenimientos->first_month_day(); //$mantenimientos->getPrimerDiaMes()['StartOfMonth'];
        $fechaFIN = $mantenimientos->last_month_day(); //$mantenimientos->getUltimoDiaMes()['EndOfMonth'];
    }

    $primerDiaMesSPAM = new DateTime($fechaINI);
    $primerDiaMesSPAM = date_format($primerDiaMesSPAM, "Y-m-d");

    $ultimoDiaMesSPAM = new DateTime($fechaFIN);
    $ultimoDiaMesSPAM = date_format($ultimoDiaMesSPAM, "Y-m-d");

    $arrayMantenimientos = $mantenimientos->getMantenimientosExternosAgendados($codEmpresa, 100, $fechaINI, $fechaFIN); //Devuelve array de mantenimientos
    $dateNow = $mantenimientos->getDateNow(); //Fecha actual determina si la tarjeta esta valida o no

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

        <h3 class="heading_a uk-margin-bottom">Equipos externos agendados : <?php echo $primerDiaMesSPAM .' hasta '. $ultimoDiaMesSPAM?></h3>

            <form action="" method="get">
                <input type="hidden" name="action" value="mantenimientosEXT">
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_a">Filtros de busqueda</h3>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-large-3-10 uk-row-first">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper md-input-filled">
                                        <label for="uk_dp_start">Fecha Inicial</label>
                                        <input class="md-input label-fixed" type="date" id="uk_dp_start" name="fechaINI" value="<?php echo $fechaINI?>" required>
                                        <span class="md-input-bar"></span></div>

                                </div>
                            </div>
                            <div class="uk-width-medium-3-10">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper md-input-filled">
                                        <label for="uk_dp_end">Fecha Final</label>
                                        <input class="md-input label-fixed" type="date" id="uk_dp_end" name="fechaFIN" value="<?php echo $fechaFIN?>" required>
                                        <span class="md-input-bar"></span></div>

                                </div>
                            </div>

                            <div class="uk-width-medium-2-10">
                                <div class="md-input-wrapper md-input-filled">
                                    <label>Tipos</label>
                                    <select id="select_tiposDoc" data-md-selectize disabled>
                                        <option value="ALL">Todos</option>
                                        <option value="PND">Pendientes</option>
                                        <option value="ANUL">Aulados/Omitidos</option>
                                    </select>
                                    </span></div>
                            </div>

                            <div class="uk-width-medium-2-10">
                                <button type="submit" href="#" class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light md-btn-block"><i class="uk-icon-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            </br>

            <ul id="products_sort" class="uk-subnav uk-subnav-pill">
                <li data-uk-sort="product-name:asc"><a href="#">Acendente</a></li>
                <li data-uk-sort="product-name:desc"><a href="#">Descendente</a></li>
            </ul>

            <div class="uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 hierarchical_show" data-uk-grid="{gutter: 20, controls: '#products_sort'}">
                
                <?php
                    if (is_array($arrayMantenimientos)) {
                    
                    foreach ($arrayMantenimientos as $equipo) {
                        $dateCard = new DateTime($equipo['fechaPrometida']);
                        $fechaTarjeta = date_format($dateCard, "Y-m-d");
                        $isInTime = ($fechaTarjeta >= $dateNow ) ? true : false;
                    
                ?>
                    <!-- Inicio card -->
                    <div data-product-name="<?php echo $equipo['NombreCliente']?>">
                        <div class="md-card md-card-hover-img">
                            <div class="md-card">
                                <?php $colorCard = ($isInTime) ? 'md-bg-light-blue-600' : 'md-bg-red-800' ?>
                                <div class="md-card-head <?php echo $colorCard?>">
                                    <div class="md-card-head-menu" data-uk-dropdown="{pos:'bottom-right'}">
                                        <i class="md-icon material-icons md-icon-light"></i>
                                        <div class="uk-dropdown uk-dropdown-small">
                                            <ul class="uk-nav">
                                                <li><a href="#" class="showInforme" data-mantenimiento="<?php echo trim($equipo['codMantExt'])?>">Ver hoja de informe</a></li>
                                                <li><a href="?&action=editMantenimientoExt&codOrden=<?php echo trim($equipo['codMantExt'])?>" class="addRepuestos" data-mantenimiento="<?php echo $equipo['codMantExt']?>" disabled>Abrir Orden</a></li>
                                                <li><a href="#" class="uk-text-danger anularCita" data-mantenimiento="<?php echo trim($equipo['codMantExt'])?>">Anular Cita</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="uk-text-center">
                                        <img class="md-card-head-avatar" src="assets/img/avatars/avatar_11.png" alt="">
                                    </div>
                                    <h3 class="md-card-head-text uk-text-center md-color-white">
                                        <!-- Titulo del card-->
                                        <?php 
                                            echo $equipo['codMantExt'];
                                            echo "</br><small>".$equipo['serieModelo']."</small>";
                                        ?>
                                    </h3>
                                </div>
                                <div class="md-card-content" style="min-height: 215px;">
                                    <ul class="md-list md-list-addon">
                                        
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon uk-icon-user"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo substr($equipo['NombreCliente'], 0, 20)?></span>
                                                <span class="uk-text-small uk-text-muted">Cliente</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">
                                                    <?php 
                                                        $retVal = empty(trim($equipo['Email'])) ? '(Vacio)' : $equipo['Email'] ;
                                                        echo $retVal;
                                                    ?> </span>
                                                <span class="uk-text-small uk-text-muted">Email</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['Telefono']?></span>
                                                <span class="uk-text-small uk-text-muted">Teléfono</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">build</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['Encargado']?></span>
                                                <span class="uk-text-small uk-text-muted">Técnico Asignado</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon uk-icon-calendar-check-o"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">
                                                    <?php 
                                                     $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                                                     $dateTime = new DateTime($equipo['fechaPrometida']);
                                                     $dia =  date_format($dateTime, "w");
                                                     $hora = date_format($dateTime, "Y-m-d");
                                                     echo $dias[$dia].", ".$hora."</br>";
                                                    ?>
                                                    
                                                </span>
                                                <span class="uk-text-small uk-text-muted">Fecha de entrega</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon uk-icon-map-marker"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['Direccion']?></span>
                                                <span class="uk-text-small uk-text-muted">Dirección</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN card -->

                    
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

<!-- Scripts de la página -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/mantenimientosEXT.js"></script>

<!--  theme color functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>

</body>