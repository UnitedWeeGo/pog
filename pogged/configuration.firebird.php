<?
session_start();
global $configuration;

//	PDO related settings
//	Note: If you're wondering why the DSN for Firebird is different from the other PDO DSN, the link below explains why
//	http://groups.google.com/group/mailing.www.php-dev/browse_thread/thread/6e0f0cea5cfb106a/83647b79c1867e06%2383647b79c1867e06?sa=X&oi=groupsr&start=0&num=3

$configuration['pdoDriver']= 'firebird';
$configuration['db'] = 'localhost:C:\Inetpub\wwwroot\pog\TEST.fdb'; //path to firebird database. server must have write access to this.
$configuration['user'] = 'SYSDBA'; //database user
$configuration['pass'] = 'masterkey'; //database password
?>