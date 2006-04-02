<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

$configuration['pdoDriver']= 'pgsql';
$configuration['db_encoding'] = &db_encoding;

// edit the information below to match your database settings

$configuration['db'] 	= 'template1';	//	database name
$configuration['host'] 	= 'localhost';	//	database host
$configuration['user'] 	= 'root';		//	database user
$configuration['pass'] 	= 'pass';		//	database password
?>