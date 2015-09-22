<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once('vendor/autoload.php');
\Pyntax\PyntaxDAO::start();

//include_once("third-party/AdminLTE-2.3.0/pages/forms/PyntaxDAO-General.php"); die;
//include_once("third-party/AdminLTE-2.3.0/pages/tables/PyntaxDAO-Table.php");
//$clientBean = \Pyntax\PyntaxDAO::getBean('clients');

//if(!empty($_POST)) {
//    var_dump($_POST); die;
//}

//$clientBean->users_id = 4;
//$clientBean->first_name = "Sahil";
//$clientBean->last_name = "Sharma";
//$clientBean->website = "pyntax.net";
//$clientBean->save();
//
//$clientBean->website = "http://hello.world.com";
//
//$clientBean->save();
//
//$r = $clientBean->find(array('AND' => array(
//    'first_name' => 'Sahil',
//    'OR' => array(
//        'last_name' => 'Sharma'
//    )
//)), true);
//
//var_dump($r); die;


//$clientBean = \Pyntax\PyntaxDAO::getBean('clients');
//$tableFactory = new \Pyntax\Html\Table\TableFactory();
////$tableFactory->generateTable($clientBean, array('AND' => array(
////    'first_name' => 'Sahil',
////    'OR' => array(
////        'last_name' => 'Sharma'
////    )
////)));
//
//
//echo $tableFactory->generateTable($clientBean);




$attachmentBean = \Pyntax\PyntaxDAO::getBean('users');
//
//$elementFactory = new \Pyntax\Html\Element\ElementFactory();
//if($attachmentBean instanceof \Pyntax\DAO\Bean\BeanInterface) {
//    foreach($attachmentBean->getColumnDefinition() as $_column) {
//        echo $elementFactory->generateElementByColumn($attachmentBean, $_column);
//    }
//}

$htmlFactory = new \Pyntax\Html\HtmlFactory();
echo $htmlFactory->createForm($attachmentBean);
echo $htmlFactory->createTable($attachmentBean);