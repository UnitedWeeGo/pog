<?php
include_once("../include/configuration.php");
include_once("../include/class.misc.php");
include_once("../include/misc.php");
include_once("nusoap.php");

$server = new soap_server;
$server -> register('GetGeneratorVersion');
$server -> register('GenerateObject');
$server -> register('GenerateConfiguration');
$server -> register('GeneratePackage');

/**
 * Protects the web service from possible 'attacks'
 *
 */
function Shelter()
{
	//to do later
}

/**
 * Fetches the current POG version. Can be used to detect for upgrades
 *
 * @return base64 encoded string
 */
function GetGeneratorVersion()
{
	include("../include/configuration.php");
	return base64_encode($GLOBALS['configuration']['versionNumber']." ".$GLOBALS['configuration']['revisionNumber']);
}

/**
 * Generates the appropriate object
 *
 * @param string $objectName
 * @param array $attributeList
 * @param array $typeList
 * @param string $language
 * @param string $wrapper
 * @param string $pdoDriver
 * @return base64 encoded string
 */
function GenerateObject($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver)
{
	if (strlen($objectName) > 0 &&
		sizeof($attributeList) > 0 &&
		sizeof($typeList) > 0 &&
		strlen($language) > 0 &&
		strlen($wrapper) > 0 &&
		strlen($pdoDriver) > 0)
		{
			if (strtoupper($wrapper) == "PDO")
			{
				eval("include \"../object_factory/class.object".$language.strtolower($wrapper).$pdoDriver.".php\";");
			}
			else
			{
				if  ($language == "php4")
				{
					eval("include \"../object_factory/class.objectphp4pogmysql.php\";");
				}
				else
				{
					eval("include \"../object_factory/class.objectphp5pogmysql.php\";");
				}
			}
			$object = new Object($objectName,$attributeList,$typeList,$pdoDriver);

			$object->BeginObject();
			$object->CreateConstructor();
			$object->CreateGetFunction();
			$object->CreateGetAllFunction();
			$object->CreateSaveFunction();
			$object->CreateSaveNewFunction();
			$object->CreateDeleteFunction();
			if(strtoupper($wrapper) == "PDO")
			{
				$object->CreateEscapeFunction();
				$object->CreateUnescapeFunction();
			}
			$object->EndObject();
			return base64_encode($object->string);
		}
}

/**
 * Generates the appropriate configuration file
 *
 * @param string $wrapper
 * @return base64 encoded string
 */
function GenerateConfiguration($wrapper = null)
{
	if (strtoupper($wrapper) == "PDO")
	{
		$data = file_get_contents("../configuration_factory/configuration.".strtolower($wrapper).".php");
	}
	else
	{
		$data = file_get_contents("../configuration_factory/configuration.php");
	}
	return base64_encode($data);
}

/**
 * Generates a pog 'package' which is essentially a multi-D array with folder names as keys and file contents as values.
 * The package can be delivered across the network, modified, and then finally zipped when the time is right.
 *
 * @param string $objectName
 * @param array $attributeList
 * @param array $typeList
 * @param string $language
 * @param string $wrapper
 * @param string $pdoDriver
 */
function GeneratePackage($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver = null)
{
	$package = array();
	$package["objects"] = array();
	$package["setup"] = array();
	$package["setup"]["setup_images"] = array();
	$package["setup"]["setup_library"] = array();

	//generate configuration file
	$package["configuration.php"] = GenerateConfiguration($wrapper);

	//generate objects
	if (strtoupper($wrapper) != "PDO")
	{
		if (strtolower($language) == "php4")
		{
			$data = file_get_contents("../object_factory/class.database.php4.php");
		}
		else
		{
			$data = file_get_contents("../object_factory/class.database.php5.php");
		}
		$package["objects"]["class.database.php"] = base64_encode($data);
	}
	$package["objects"]["class.".strtolower($objectName).".php"] =  GenerateObject($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver);

	//generate setup
	if (strtoupper($wrapper) == "PDO")
	{
		$data = file_get_contents("../setup_factory/setup.pdo.php");
	}
	else
	{
		if ($language == "php4")
		{
			$data = file_get_contents("../setup_factory/setup.php4.php");
		}
		else
		{
			$data = file_get_contents("../setup_factory/setup.php");
		}
	}
	$package["setup"]["index.php"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/rpc.php");
	$package["setup"]["rpc.php"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/setup.css");
	$package["setup"]["setup.css"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/setup_misc.php");
	$package["setup"]["setup_library"]["setup_misc.php"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/inc.header.php");
	$package["setup"]["setup_library"]["inc.header.php"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/inc.footer.php");
	$package["setup"]["setup_library"]["inc.footer.php"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/xPandMenu.php");
	$package["setup"]["setup_library"]["xPandMenu.php"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/xPandMenu.css");
	$package["setup"]["setup_library"]["xPandMenu.css"] = base64_encode($data);

	$data = file_get_contents("../setup_factory/setup_files/xPandMenu.js");
	$package["setup"]["setup_library"]["xPandMenu.js"] = base64_encode($data);

	//read all setup image files
	$dir = opendir('../setup_factory/setup_files/setup_images/');
	while(($file = readdir($dir)) !== false)
	{
		if (substr(strtolower($file), strlen($file) - 4) === '.gif' || substr(strtolower($file), strlen($file) - 4) === '.jpg')
		{
			$data = file_get_contents("../setup_factory/setup_files/setup_images/$file");
			$package["setup"]["setup_images"][$file] = base64_encode($data);
		}
	}
	closedir($dir);

	return $package;
}

$server->service($HTTP_RAW_POST_DATA);
?>