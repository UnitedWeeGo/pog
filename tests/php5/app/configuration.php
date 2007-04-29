<?php
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;
$configuration['soap'] = "http://localhost/pog_dev/services/soap.php?wsdl";
$configuration['homepage'] = "http://localhost/pog_dev";
$configuration['revisionNumber'] = "";
$configuration['versionNumber'] = "2.9";

$configuration['setup_password'] = '';

//db_encoding=1 is highly recommended unless you know what you're doing
$configuration['db_encoding'] = 0;

// edit the information below to match your database settings

$configuration['db']	= 'evaa'; 		//	database name
$configuration['host']	= 'localhost';	//	database host
$configuration['user']	= 'root';		//	database user
$configuration['pass']	= 'givvinus';		//	database password
$configuration['port'] 	= '3306';		//	database port
?>