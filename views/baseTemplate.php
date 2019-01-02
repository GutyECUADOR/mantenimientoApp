<?php 

?>
<!DOCTYPE html>
<html lang="es">

  <head>
      <meta charset="UTF-8">

      <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Remove Tap Highlight on Windows Phone IE -->
      <meta name="msapplication-tap-highlight" content="no" />

      <link rel="icon" type="image/png" href="<?php echo ROOT_PATH; ?>assets/img/favicon-16x16.png" sizes="16x16">
      <link rel="icon" type="image/png" href="<?php echo ROOT_PATH; ?>assets/img/favicon-32x32.png" sizes="32x32">

      <title>KAO WebApp</title>

      <!-- additional styles for plugins -->
      <!-- jquery ui -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/skins/jquery-ui/material/jquery-ui.min.css">
      <!-- jTable -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/skins/jtable/jtable.min.css">

      <!-- uikit -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

      <!-- flag icons -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/icons/flags/flags.min.css" media="all">

      <!-- style switcher -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/css/style_switcher.min.css" media="all">

      <!-- altair admin -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/css/main.min.css" media="all">

      <!-- themes -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/css/themes/themes_combined.min.css" media="all">

      <!-- matchMedia polyfill for testing media queries in JS -->
      <!--[if lte IE 9]>
          <script type="text/javascript" src="bower_components/matchMedia/matchMedia.js"></script>
          <script type="text/javascript" src="bower_components/matchMedia/matchMedia.addListener.js"></script>
          <link rel="stylesheet" href="assets/css/ie.css" media="all">
      <![endif]-->

     

  </head>

  <body>
    <div>
      <div>
        
        <?php
            $inicioController = new controllers\mainController();
            $inicioController->actionCatcherController();
        ?>
      </div>
    </div>

   

      <!-- page specific plugins -->
        <!-- d3 -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/d3/d3.min.js"></script>
        <!-- metrics graphics (charts) -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
        <!-- chartist (charts) -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/chartist/dist/chartist.min.js"></script>
        <!-- maplace (google maps)
        <script src="http://maps.google.com/maps/api/js"></script> -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/maplace-js/dist/maplace.min.js"></script>
        <!-- peity (small charts) -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/peity/jquery.peity.min.js"></script>
        <!-- easy-pie-chart (circular statistics) -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <!-- countUp -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/countUp.js/dist/countUp.min.js"></script>
        <!-- handlebars.js -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/handlebars/handlebars.min.js"></script>
        <script src="<?php echo ROOT_PATH; ?>assets/js/custom/handlebars_helpers.min.js"></script>
        <!-- CLNDR -->
        <script src="<?php echo ROOT_PATH; ?>assets/bower_components/clndr/clndr.min.js"></script>

       
  
  </body>
</html>


