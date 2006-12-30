<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;
$configuration['soap'] = "http://beta.phpobjectgenerator.com/services/soap.php?wsdl";
$configuration['homepage'] = "http://beta.phpobjectgenerator.com";
$configuration['revisionNumber'] = "";
$configuration['versionNumber'] = "2.3";

$configuration['pdoDriver']	= 'mysql';
$configuration['setup_password'] = '';
$configuration['db_encoding'] = 1;

// edit the information below to match your database settings

$configuration['db']	= '';		//	<- database name
$configuration['host'] 	= 'localhost';	//	<- database host
$configuration['user'] 	= '';		//	<- database user
$configuration['pass']	= '';		//	<- database password
$configuration['port']	= '3306';		//	<- database port
?>