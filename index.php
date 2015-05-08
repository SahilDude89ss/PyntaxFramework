<?php
require_once('vendor/autoload.php');

$pdo = new PDO('mysql:host=localhost;dbname=world_db;charset=utf8', 'root', '');
//$columnFactory = new ColumnFactory();
//$schema = new MysqlSchema($pdo, $columnFactory);

//$table = $schema->fetchTableList();
//var_dump($table);

//$columns = $schema->fetchTableCols('accounts');
//var_dump(count($columns));


//$cacheAdapter = new \Pyntax\Cache\Adapter\RedisAdapter();
//$cacheAdapter->connect('localhost');
//$cacheAdapter->set('DB_TABLE_LIST',serialize($table));
//
//var_dump(unserialize($cacheAdapter->get('DB_TABLE_LIST')));

/*
select
  TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
from INFORMATION_SCHEMA.KEY_COLUMN_USAGE
where
  REFERENCED_TABLE_NAME = '<table>';
 */


//$table = new \Pyntax\DAO\SqlSchema\Table('City',$pdo, new \Pyntax\DAO\Sql\QueryBuilder());
$beanFactory = new \Pyntax\DAO\Bean\BeanFactory($pdo, new \Pyntax\DAO\Sql\QueryBuilder());
$bean =  $beanFactory->createNewBean('City');
//$r = $bean->get(1);

$r = $bean->select(array(),true,2);

//echo $bean->ID.": ".$bean->Name.": ".$bean->Population.": ".$bean->CountryCode."\n";
var_dump($r);

