<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

//	PDO related settings
$configuration['pdoDriver']= 'mysql';
$configuration['db'] = 'test';
$configuration['host'] = 'localhost';
$configuration['user'] = 'root';
$configuration['pass'] = 'pass';
?>