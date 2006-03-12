<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();
include "./include/configuration.php";
include "./include/class.zipfile.php";
include "./services/nusoap.php";
?>
<?php
if (isset($_SESSION['objectString']))
{
	$_GET = null;
	$client = new soapclient($GLOBALS['configuration']['soap'], true);
	$attributeList = unserialize($_SESSION['attributeList']);
	$typeList = unserialize($_SESSION['typeList']);
	$params = array(
		    'objectName' 	=> $_SESSION['objectName'],
		    'attributeList' => $attributeList,
		    'typeList'      => $typeList,
		    'language'      => $_SESSION['language'],
		    'wrapper'       => $_SESSION['wrapper'],
		    'pdoDriver'     => $_SESSION['pdoDriver']
		);
	$package = unserialize($client->call('GeneratePackage', $params));
	$zipfile = new createZip();
	$zipfile -> addPOGPackage($package);
	$zipfile -> forceDownload("pog.".time().".zip");
	$_POST = null;
}
else
{
	header("Location:/");
}
?>
