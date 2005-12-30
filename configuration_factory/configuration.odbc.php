<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;

//	PDO related settings
$configuration['pdoDriver']= 'odbc';
$configuration['odbcDSN'] = "DRIVER=SQL Server;SERVER=localhost\TEST;UID=sa;PWD=pass;DATABASE=test";
//need DSN for databases other than SQL Server? go to http://www.connectionstrings.com
?>