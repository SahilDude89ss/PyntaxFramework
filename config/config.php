<?php
Pyntax\Config\Config::writeConfig('database', array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'simplemanager_db_v3'
));

Pyntax\Config\Config::writeConfig('orm', array(
    'load_related_beans' => true,
    'beans' => array(
        'clients' => array(
            'visible_columns' => array(
                'list' => array(
                    'title', 'first_name', 'last_name', 'email'
                )
            )
        )
    )
));

Pyntax\Config\Config::writeConfig('table_config', array(
    'table' => array(
        'class' => 'table table-bordered table-hover',
        'id' => 'example2'
    ),
    'dataTable' => false
));

Pyntax\Config\Config::writeConfig('form_config', array(
    'Bean' => array(
        'clients' => array(
            'user_id' => array(
                'class' => 'selectQuery',
                'id' => 'sltUniqueID',
                'template' => array(
                    "<select id='@_id_'>@_user_id_</select>"
                )
            )
        )
    )
));
