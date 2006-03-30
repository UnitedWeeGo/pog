<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

$configuration['pdoDriver']	= 'mysql';
$configuration['db_encoding'] = 1;

// edit the information below to match your database settings

$configuration['db']	= 'test';		//	<- database name
$configuration['host'] 	= 'localhost';	//	<- database host
$configuration['user'] 	= 'root';		//	<- database user
$configuration['pass']	= 'pass';		//	<- database password
$configuration['port']	= '3306';		//	<- database port
?>