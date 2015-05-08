<?php

$pyntax_confing['database'] = array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'port' => '3306'
);

$pyntax_confing['cache'] = array(
    'Redis' => array(
        'enable' =>  true
    )
);

return $pyntax_confing;