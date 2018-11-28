
<?php

$data = '[{"codigo":"MK-5N","nombre":"BALON FUTBOL CUERO SINT PU #5 NEGRO","cantidad":"1","precio":"40.09","descuento":"0"},{"codigo":"MK-5A","nombre":"BALON FUTBOL CUERO SINT PU #5 AZUL","cantidad":"1","precio":"40.09","descuento":"0"}]';
$descodificada = json_decode($data);

var_dump($descodificada);

foreach ($descodificada as $producto) {

    echo $producto->codigo;

}