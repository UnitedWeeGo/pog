<?
session_start();
global $configuration;

//Path related settings
$configuration['path'] = ''; //absolute path to root of web application

//Database related settings
$configuration['db'] = 'TEST'; //database name
$configuration['host'] = 'localhost'; //database host 
$configuration['user'] = 'sa'; //database user
$configuration['pass'] = 'multipass'; //database password

//Data Encoding/Decoding settings
$configuration['encode/decode'] = true;

//PDO related settings
$configuration['pdoDriver']= 'dblib';
$configuration['sqliteDatabase']= 'C:\Inetpub\wwwroot\pog\test.db';
?>