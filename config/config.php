<?php
$pyntax_config['database'] = array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'port' => '3306'
);

$pyntax_config['cache'] = array(
    'Redis' => array(
        'enable' =>  true
    )
);

return $pyntax_config;