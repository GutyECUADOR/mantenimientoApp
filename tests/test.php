
<?php

require('../core/models/venCabClass.php');

$VEN_CAB = new \models\VenCabClass(
    'EQ-Progra',
    '99',
    '2014'
);

echo $VEN_CAB->getCode();