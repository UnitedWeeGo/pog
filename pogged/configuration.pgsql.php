<?
session_start();
global $configuration;

//	PDO related settings
$configuration['pdoDriver']= 'pgsql';
$configuration['db'] = 'template1';
$configuration['host'] = 'localhost';
$configuration['user'] = 'root';
$configuration['pass'] = 'pass';
?>