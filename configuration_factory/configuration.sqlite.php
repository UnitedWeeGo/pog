<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

$configuration['pdoDriver']= 'sqlite';
$configuration['db_encoding'] = 1;

// edit the information below to match your database settings

$configuration['sqliteDatabase']= 'C:\code\pog\test.db';
?>