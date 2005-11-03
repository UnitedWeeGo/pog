<?
session_start();
global $configuration;

//	PDO related settings
$configuration['pdoDriver']= 'odbc';
$configuration['odbcDSN'] = "DRIVER=SQL Server;SERVER=localhost\TEST;UID=sa;PWD=pass;DATABASE=test";
?>