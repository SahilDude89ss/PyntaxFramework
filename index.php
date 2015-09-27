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


$bean = \Pyntax\PyntaxDAO::getBean('notes');

$htmlFactory = new \Pyntax\Html\HtmlFactory();
//$htmlFactory->scanDirForFiles("./third-party/AdminLTE-2.3.0/dist/css",\Pyntax\Html\HtmlFactory::FileTypeOption_CSS);
//$htmlFactory->scanDirForFiles("./third-party/AdminLTE-2.3.0/dist/js",\Pyntax\Html\HtmlFactory::FileTypeOption_JS);

$htmlFactory->addFile(array(
    '/third-party/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css',
    'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
    'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
    '/third-party/AdminLTE-2.3.0/dist/css/AdminLTE.min.css',
    '/third-party/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css',

    '/pyntaxjs/lib/pickadatejs/lib/themes/default.css',
    '/pyntaxjs/lib/pickadatejs/lib/themes/default.date.css',
    '/pyntaxjs/lib/pickadatejs/lib/themes/default.time.css'


), \Pyntax\Html\HtmlFactory::FileTypeOption_CSS, \Pyntax\Html\HtmlFactory::FilePlacementOption_Header);

$htmlFactory->addFile(array(
    '/pyntaxjs/lib/jquery/jquery.min.js',

//    '/pyntaxjs/lib/backbone/backbone.min.js',
//    '/pyntaxjs/lib/backbone/underscore.min.js',
//    '/pyntaxjs/lib/handlebars/handlebars.js',

    '/pyntaxjs/lib/pickadatejs/lib/legacy.js',
    '/pyntaxjs/lib/pickadatejs/lib/picker.js',
    '/pyntaxjs/lib/pickadatejs/lib/picker.date.js',
    '/pyntaxjs/lib/pickadatejs/lib/picker.time.js',

    '/pyntaxjs/src/app.js',
    '/pyntaxjs/src/core/form/form.js'

), \Pyntax\Html\HtmlFactory::FileTypeOption_JS, \Pyntax\Html\HtmlFactory::FilePlacementOption_Header);

?>

<html>
<head>
    <?php $htmlFactory->printCSSFiles(\Pyntax\Html\HtmlFactory::FilePlacementOption_Header);
    $htmlFactory->printJSFiles(\Pyntax\Html\HtmlFactory::FilePlacementOption_Header); ?>
</head>
<body>
<h1>Example  <span style="font-size: 18px">- <?php echo ucfirst($bean->getName()) ?></span></h1>
<?php //echo $htmlFactory->createForm($bean); ?>
<?php echo \Pyntax\PyntaxDAO::generateForm($bean); ?>
<?php echo $htmlFactory->createTable($bean); ?>
</body>
</html>