<?php
require_once('vendor/autoload.php');

$clientBean = \Pyntax\PyntaxDAO::getBean('clients');
$clientBean->users_id = 4;
$clientBean->first_name = "Sahil";
$clientBean->last_name = "Sharma";
$clientBean->website = "pyntax.net";
$clientBean->save();

$clientBean->website = "http://hello.world.com";

$clientBean->save();