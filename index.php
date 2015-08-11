<?php
require_once('vendor/autoload.php');

$pdo = new PDO('mysql:host=localhost;dbname=simplemanager_db_v3;charset=utf8', 'root', '');
////$columnFactory = new ColumnFactory();
////$schema = new MysqlSchema($pdo, $columnFactory);
//
////$table = $schema->fetchTableList();
////var_dump($table);
//
////$columns = $schema->fetchTableCols('accounts');
////var_dump(count($columns));
//
//
////$cacheAdapter = new \Pyntax\Cache\Adapter\RedisAdapter();
////$cacheAdapter->connect('localhost');
////$cacheAdapter->set('DB_TABLE_LIST',serialize($table));
////
////var_dump(unserialize($cacheAdapter->get('DB_TABLE_LIST')));
//
///*
//select
//  TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
//from INFORMATION_SCHEMA.KEY_COLUMN_USAGE
//where
//  REFERENCED_TABLE_NAME = '<table>';
// */
//
//
////$table = new \Pyntax\DAO\SqlSchema\Table('City',$pdo, new \Pyntax\DAO\Sql\QueryBuilder());
//$beanFactory = new \Pyntax\DAO\Bean\BeanFactory($pdo, new \Pyntax\DAO\Sql\QueryBuilder());
//$bean =  $beanFactory->createNewBean('City');
////$r = $bean->get(1);
//
//$r = $bean->select(array(),true,2);
//
////echo $bean->ID.": ".$bean->Name.": ".$bean->Population.": ".$bean->CountryCode."\n";
//var_dump($r);

$beanFactory = new \Pyntax\DAO\Bean\BeanFactory(new \Pyntax\DAO\Adapter\MySqlAdapter($pdo));

//$bean = $beanFactory->getBean('clients');
//$bean->find(2);
//$bean->website = "http://www.wwe.com";
//$bean->save();
//

$clientBean = $beanFactory->getBean('clients');

//$clientBean->find(3);
//echo $clientBean->first_name."\n";
//$clientBean->website = "http://pyntax.net";
//$clientBean->save();

//$clientBean = $beanFactory->getBean('clients');
//$clientBean->first_name = "Warren";
//$clientBean->last_name = "Hastings";
//$clientBean->email = 'SahilDude89ss@gmail.com';
//$clientBean->users_id = 4;
//$kid = $clientBean->save();

$r = $clientBean->find(25);
var_dump($clientBean->delete());
