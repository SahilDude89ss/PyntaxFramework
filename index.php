<?php
require_once('vendor/autoload.php');

$clientBean = \Pyntax\PyntaxDAO::getBean('clients');
$clientBean->users_id = 4;
$clientBean->first_name = "Dhanoop";
$clientBean->last_name = "Karunakarun";
$clientBean->website = "dk.com";
$clientBean->save();

$clientBean->website = "http://hello.world.com";

$clientBean->save();