<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once('vendor/autoload.php');
\Pyntax\Pyntax::start(dirname(__FILE__)."/config");

\Pyntax\Config\Config::write('database', array(
    'database' => 'sugarcrm_amsa_crmuat',
    'server' => 'localhost',
    'password' => '',
));


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

$startTime = microtime();

$bean = \Pyntax\Pyntax::getBean('users');
$bean->find(2);

$htmlFactory = \Pyntax\Pyntax::loadHtmlFactory();
?>

<html>
<head>
    <?php $htmlFactory->printCSSFiles(\Pyntax\Html\HtmlFactory::FilePlacementOption_Header);
    $htmlFactory->printJSFiles(\Pyntax\Html\HtmlFactory::FilePlacementOption_Header); ?>
</head>
<body>
<h1>Example  <span style="font-size: 18px">- <?php echo ucfirst($bean->getName()) ?></span></h1>
<?php echo \Pyntax\Pyntax::generateForm($bean); ?>
<?php echo $htmlFactory->createTable($bean); ?>

<h3><?php  echo microtime() - $startTime ?></h3>
</body>
</html>