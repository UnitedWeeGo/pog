<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;
$configuration['soap'] = "&soap";
$configuration['revisionNumber'] = "&revisionNumber";
$configuration['versionNumber'] = "&versionNumber";

$configuration['pdoDriver']= 'sqlite';
$configuration['setup_password'] = '';
$configuration['db_encoding'] = &db_encoding;

// edit the information below to match your database settings

$configuration['sqliteDatabase']= 'C:\code\pog\test.db';
?>