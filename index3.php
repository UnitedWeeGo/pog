<?
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
<?
if (isset($_SESSION['objectString']))
{
	$_GET = null;
	$client = new soapclient($GLOBALS['configuration']['soap']);
	$params = array(
		    'objectName' 	=> $_SESSION['objectName'],
		    'attributeList' => $_SESSION['attributeList'],
		    'typeList'      => $_SESSION['typeList'],
		    'language'      => $_SESSION['language'],
		    'wrapper'       => $_SESSION['wrapper'],
		    'pdoDriver'     => $_SESSION['pdoDriver']
		);
	$package = $client->call('GeneratePackage', $params);
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