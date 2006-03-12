<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

$configuration['pdoDriver']= 'dblib';

// edit the information below to match your database settings

$configuration['db']	= 'TEST'; 		//database name
$configuration['host'] 	= 'localhost'; 	//database host
$configuration['user'] 	= 'sa'; 		//database user
$configuration['pass'] 	= 'pass'; 		//database password
?>
