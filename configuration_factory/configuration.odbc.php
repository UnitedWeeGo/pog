<?php
/**
 * http://www.connectionstrings.com
 */
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

$configuration['pdoDriver']= 'odbc';

// edit the information below to match your database settings

$configuration['odbcDSN'] = "DRIVER=SQL Server;SERVER=localhost\TEST;UID=sa;PWD=pass;DATABASE=test";
?>