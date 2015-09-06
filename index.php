<?php
error_reporting(E_ALL);
ini_set('display_errors', true);


require_once('vendor/autoload.php');

\Pyntax\PyntaxDAO::run();

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


//$clientBean = \Pyntax\PyntaxDAO::getBean('clients')->find(8);

//$tableFactory = new \Pyntax\Html\Table\TableFactory();
//$tableFactory->generateTable($clientBean, array('AND' => array(
//    'first_name' => 'Sahil',
//    'OR' => array(
//        'last_name' => 'Sharma'
//    )
//)));


//echo $tableFactory->generateTable($clientBean);


//$elementFactory = new Pyntax\Html\Element\ElementFactory();
//echo $elementFactory->generateElementHtml('a' , array(
//    'href' => 'http://www.google.com/',
//    'style' => array(
//        'padding-top' => '100px',
//        'padding-left' => '200px'
//    )
//), "Go To google");

//echo $elementFactory->generateElementHtml('input', array(
//    'type' => 'text',
//), "Sahil Sharma", false);

//$formFactory = new \Pyntax\Html\Form\FormFactory();
//echo $formFactory->generateForm($clientBean);


//$elementFactory = new \Pyntax\Html\Element\ElementFactory();
//
//if($clientBean instanceof \Pyntax\DAO\Bean\BeanInterface) {
//    foreach($clientBean->getColumnDefinition() as $_column) {
//        echo $elementFactory->generateElementByColumn($clientBean, $_column);
//    }
//}
//$clientBean = \Pyntax\PyntaxDAO::getBean('clients')->find(8);
//$attachmentBean = \Pyntax\PyntaxDAO::getBean('attachments');
//
//$tableFactory = new \Pyntax\Html\Table\TableFactory();
//echo $tableFactory->generateTable($attachmentBean);
//
//
//$formFactory = new \Pyntax\Html\Form\FormFactory();
//echo $formFactory->generateForm($attachmentBean);

$bean = Pyntax\PyntaxDAO::getBean('employees');

$tableFactory = new \Pyntax\Html\Table\TableFactory();
echo $tableFactory->generateTable($bean);

echo "<hr/>";
$formFactory = new \Pyntax\Html\Form\FormFactory();
echo $formFactory->generateForm($bean);