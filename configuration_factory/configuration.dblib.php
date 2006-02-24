<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

//Database related settings
$configuration['db'] = 'TEST'; //database name
$configuration['host'] = 'localhost'; //database host 
$configuration['user'] = 'sa'; //database user
$configuration['pass'] = 'pass'; //database password

//PDO related settings
$configuration['pdoDriver']= 'dblib';
$configuration['sqliteDatabase']= 'C:\Inetpub\wwwroot\pog\test.db';
?>
