<?php
require_once('config.php');
require_once('functions.php');
require_once('Mysql.php');
require_once('TableCreator3.php');

$mysql = new Mysql(MysqlConfig::host, MysqlConfig::user, MysqlConfig::password, MysqlConfig::database, MysqlConfig::port);

$table_creater = new TableCreator3($mysql);

$records = $table_creater->fetchData();

echo "Total Records : " . count($records);

dump($records);

