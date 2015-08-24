<?php
Pyntax\Config\Config::writeConfig('database', array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'simplemanager_db_v3'
));

Pyntax\Config\Config::writeConfig('table_config', array(
    'table' => array(
        'class' => 'table table-bordered'
    ),
    'dataTable' => false
));