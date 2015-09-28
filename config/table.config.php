<?php
Pyntax\Config\Config::writeConfig('table', array(
    'table' => array(
        'class' => 'table table-bordered table-hover',
        'id' => 'example2'
    ),
    'recordLimitOnOnePage' => 100,
    'dataTable' => false,
    'beans' => array(
        'accounts' => array(
            'recordLimitOnOnePage' => 3,
        )
    )
));
