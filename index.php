<?php
require_once('vendor/autoload.php');

//$clientBean = \Pyntax\PyntaxDAO::getBean('clients');

//$clientBean->users_id = 4;
//$clientBean->first_name = "Sahil";
//$clientBean->last_name = "Sharma";
//$clientBean->website = "pyntax.net";
//$clientBean->save();

//$clientBean->website = "http://hello.world.com";

//$clientBean->save();

//$r = $clientBean->find(array('AND' => array(
//    'first_name' => 'Sahil',
//    'OR' => array(
//        'last_name' => 'Sharma'
//    )
//)), true);
//
//var_dump($r); die;

//var_dump($clientBean->getDisplayColumns());

//$anchorTag = \Pyntax\PyntaxDAO::generateHtmlElement('a', 'Sahil SHARMA');
//echo $anchorTag->generateHtml();

$clientBean = \Pyntax\PyntaxDAO::getBean('clients');

$tableFactory = new Pyntax\Table\TableFactory;
$tableFactory->generateTable($clientBean, array('AND' => array(
    'first_name' => 'Sahil',
    'OR' => array(
        'last_name' => 'Sharma'
    )
)));


