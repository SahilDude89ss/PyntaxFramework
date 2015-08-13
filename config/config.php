<?php
$pyntax_config['database'] = array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'simplemanager_db_v3',
);

$pyntax_config['cache'] = array(
    'Redis' => array(
        'enable' =>  true
    )
);

return $pyntax_config;