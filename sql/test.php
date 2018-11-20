
<?php
$fechaINIup = '2018-11-06';
$horaInicio = '10:30';
$fechaHoraINI = date('Ymd H:i:s', strtotime("$fechaINIup $horaInicio"));
     
      

echo $fechaHoraINI;