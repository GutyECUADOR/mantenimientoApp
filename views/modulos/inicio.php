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

    <!-- secondary sidebar -->
    <?php include 'sis_modules/secondary_sidebar.php'?>
    <!-- secondary sidebar end -->



    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_content_inner">

        <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler" data-uk-sortable="" data-uk-grid-margin="">
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right">
                                <svg class="peity" height="28" width="48">
                                    <rect data-value="5" fill="#d84315" x="1.3714285714285717" y="12.444444444444443" width="4.114285714285715" height="15.555555555555557"></rect>
                                    <rect data-value="3" fill="#d84315" x="8.228571428571428" y="18.666666666666668" width="4.114285714285716" height="9.333333333333332"></rect>
                                    <rect data-value="9" fill="#d84315" x="15.085714285714287" y="0" width="4.1142857142857086" height="28"></rect>
                                    <rect data-value="6" fill="#d84315" x="21.942857142857147" y="9.333333333333336" width="4.114285714285707" height="18.666666666666664"></rect>
                                    <rect data-value="5" fill="#d84315" x="28.800000000000004" y="12.444444444444443" width="4.114285714285707" height="15.555555555555557"></rect>
                                    <rect data-value="9" fill="#d84315" x="35.65714285714286" y="0" width="4.114285714285707" height="28"></rect>
                                    <rect data-value="7" fill="#d84315" x="42.51428571428572" y="6.222222222222221" width="4.114285714285707" height="21.77777777777778"></rect>
                                </svg>
                            </div>
                            <span class="uk-text-muted uk-text-small">Mant. Pendientes</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe" id="countUpMeMantenimientos"> 0 </span></h2>
                        </div>
                    </div>
                </div>

                <div style="">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_orders peity_data" style="display: none;">64/100</span><svg class="peity" height="24" width="24"><path d="M 12 0 A 12 12 0 1 1 2.753841086690528 19.649087876984275 L 7.376920543345264 15.824543938492138 A 6 6 0 1 0 12 6" data-value="64" fill="#8bc34a"></path><path d="M 2.753841086690528 19.649087876984275 A 12 12 0 0 1 11.999999999999998 0 L 11.999999999999998 6 A 6 6 0 0 0 7.376920543345264 15.824543938492138" data-value="36" fill="#eee"></path></svg></div>
                            <span class="uk-text-muted uk-text-small">Ordenes Completadas (%)</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe" id='countUpMePorcentFinish' > 0 </span>%</h2>
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

    <!--  dashbord functions -->
    <script src="<?php echo ROOT_PATH; ?>assets/js/pages/dashboard.js"></script>

    <!--  theme color functions -->
    <script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>

    <script>
        $(function() {
            const switcherdeTema = $("#switcher_theme_sis");

            // Funcion de botton switcher theme claro/oscuro
            switcherdeTema.change( function(){
              if( $(this).is(':checked')){
                console.log('Definiendo tema oscuro.');
                var this_theme = 'app_theme_dark';
                $html
                    .removeClass('app_theme_a app_theme_b app_theme_c app_theme_d app_theme_e app_theme_f app_theme_g app_theme_h app_theme_i app_theme_dark')
                    .addClass(this_theme);

                if(this_theme == '') {
                    localStorage.removeItem('altair_theme');
                    $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.material.min.css');
                } else {
                    localStorage.setItem("altair_theme", this_theme);
                    if(this_theme == 'app_theme_dark') {
                        $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.materialblack.min.css')
                    } else {
                        $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.material.min.css');
                    }
                }

              }else{
                console.log('Definiendo tema claro');
                var this_theme = '';
                $html
                    .removeClass('app_theme_a app_theme_b app_theme_c app_theme_d app_theme_e app_theme_f app_theme_g app_theme_h app_theme_i app_theme_dark')
                    .addClass(this_theme);

                if(this_theme == '') {
                    localStorage.removeItem('altair_theme');
                    $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.material.min.css');
                } else {
                    localStorage.setItem("altair_theme", this_theme);
                    if(this_theme == 'app_theme_dark') {
                        $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.materialblack.min.css')
                    } else {
                        $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.material.min.css');
                    }
                }
              }
              
            }); 


        });
    </script>
</body>
