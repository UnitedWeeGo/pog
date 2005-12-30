<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

//PDO related settings
$configuration['pdoDriver']= 'sqlite';
$configuration['sqliteDatabase']= 'C:\code\pog\test.db';
?>