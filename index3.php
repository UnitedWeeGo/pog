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
include "./include/class.misc.php";
include "./include/misc.php";

if (isset($_SESSION['objectString']))
{
	$_GET = null;

	$zipfile = new createZip;
	$filename = "pog.".time();

	//append PDO driver settings if PDO
	if (strtoupper($_SESSION['wrapper']) == "PDO")
	{
		$data = file_get_contents("./configuration_factory/configuration.".$_SESSION['pdoDriver'].".php");
	}
	else
	{
		$data = file_get_contents("./configuration_factory/configuration.php");
	}
	$zipfile -> addFile($data, "configuration.php");


	//read database file if not using PDO
	$zipfile -> addDirectory("objects/");
	if (strtoupper($_SESSION['wrapper']) != "PDO")
	{
		if ($_SESSION['language'] == "php4")
		{
			$data = file_get_contents("./object_factory/class.database.php4.php");
		}
		else
		{
			$data = file_get_contents("./object_factory/class.database.php5.php");
		}
		$zipfile -> addFile($data, "objects/class.database.php");
	}
	$zipfile -> addFile($_SESSION['objectString'], "objects/class.".strtolower($_SESSION['objectName']).".php");

	//adding setup files
	if (strtoupper($_SESSION['wrapper']) == "PDO")
	{
		$data = file_get_contents("./setup_factory/setup.pdo.php");
	}
	else
	{
		if ($_SESSION['language'] == "php4")
		{
			$data = file_get_contents("./setup_factory/setup.php4.php");
		}
		else
		{
			$data = file_get_contents("./setup_factory/setup.php");
		}
	}
	$zipfile -> addDirectory("setup/");
	$zipfile -> addFile($data, "setup/index.php");
	$data = file_get_contents("./setup_factory/rpc.php");
	$zipfile -> addFile($data, "setup/rpc.php");
	$zipfile -> addDirectory("setup/setup_images/");
	$data = file_get_contents("./setup_factory/setup_files/setup.css");
	$zipfile -> addFile($data, "setup/setup.css");
	$zipfile -> addDirectory("setup/setup_library/");
	$data = file_get_contents("./setup_factory/setup_files/setup_misc.php");
	$zipfile -> addFile($data, "setup/setup_library/setup_misc.php");
	$data = file_get_contents("./setup_factory/setup_files/inc.header.php");
	$zipfile -> addFile($data, "setup/setup_library/inc.header.php");
	$data = file_get_contents("./setup_factory/setup_files/inc.footer.php");
	$zipfile -> addFile($data, "setup/setup_library/inc.footer.php");
	$data = file_get_contents("./setup_factory/setup_files/xPandMenu.php");
	$zipfile -> addFile($data, "setup/setup_library/xPandMenu.php");
	$data = file_get_contents("./setup_factory/setup_files/xPandMenu.css");
	$zipfile -> addFile($data, "setup/setup_library/xPandMenu.css");
	$data = file_get_contents("./setup_factory/setup_files/xPandMenu.js");
	$zipfile -> addFile($data, "setup/setup_library/xPandMenu.js");

	//read all image files
	$dir = opendir('./setup_factory/setup_files/setup_images/');
	while(($file = readdir($dir)) !== false)
	{
		if (substr(strtolower($file), strlen($file) - 4) === '.gif' || substr(strtolower($file), strlen($file) - 4) === '.jpg')
		{
			$data = file_get_contents("./setup_factory/setup_files/setup_images/$file");
			$zipfile -> addFile($data, "setup/setup_images/$file");
		}
	}
	closedir($dir);

	//force download
	$zipfile->forceDownload($filename.".zip");
	?>
<?
$_POST = null;
}
else
{
	header("Location:/");
}
?>