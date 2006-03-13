<?php
include_once("nusoap.php");

$server = new soap_server();
$server -> configureWSDL('pogwsdl', 'urn:pogwsdl');
$server -> wsdl -> addComplexType(
    'StringArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:string')
    ),
    'xsd:string'
);


$server -> register('GetGeneratorVersion',
					array(),
					array('return' => 'xsd:string'),
					'urn:pogwsdl',
					'urn:pogwsdl#GetGeneratorVersion',
					'rpc',
					'encoded',
					'Fetches the current POG version. Can be used to detect for upgrades.'
					);
$server -> register('GenerateObject',
					array('objectName' => 'xsd:string',
						'attributeList' => 'tns:StringArray',
						'typeList' => 'tns:StringArray',
						'language' => 'xsd:string',
						'wrapper' => 'xsd:string',
						'pdoDriver' => 'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:pogwsdl',
					'urn:pogwsdl#GenerateObject',
					'rpc',
					'encoded',
					'Generates the appropriate object from supplied attributeList, typeList etc.'
					);
$server -> register('GenerateObjectFromLink',
					array('link' => 'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:pogwsdl',
					'urn:pogwsdl#GenerateObjectFromLink',
					'rpc',
					'encoded',
					'Generates the appropriate object from `proprietary format` of @link'
					);
$server -> register('GenerateConfiguration',
					array('wrapper' => 'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:pogwsdl',
					'urn:pogwsdl#GenerateConfiguration',
					'rpc',
					'encoded',
					'Generates the appropriate configuration file'
					);
$server -> register('GeneratePackage',
					array('objectName' => 'xsd:string',
						'attributeList' => 'tns:StringArray',
						'typeList' => 'tns:StringArray',
						'language' => 'xsd:string',
						'wrapper' => 'xsd:string',
						'pdoDriver' => 'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:pogwsdl',
					'urn:pogwsdl#GeneratePackage',
					'rpc',
					'encoded',
					'Generates a pog package which is essentially a multi-D array with folder names as keys and file contents as values. The package can be delivered across the network, modified, and then finally zipped when the time is right.'
					);

/**
 * Protects the web service from possible 'attacks'
 *
 */
function Shelter()
{
	//to do later

	// examples:
	// 1) log each attempt to use the service with the IP and Method-called
	// 2) count each attempts over from an IP and auto close any over a certain number
	//   a) daily maximum
	//   b) monthly maximum
	//   c) lifetime maximum
	// 3) check for banned IP addresses or ranges
	// 4) use a membership model - i.e. sign-in

	// MS: I think that 1, 2b, and 3 are applicable to this service
}

/**
 * Fetches the current POG version. Can be used to detect for upgrades
 *
 * @return base64 encoded string
 */
function GetGeneratorVersion()
{
	require("../include/configuration.php");
	return base64_encode($GLOBALS['configuration']['versionNumber']." ".$GLOBALS['configuration']['revisionNumber']);
}

/**
 * Generates the appropriate object from supplied attributeList, typeList etc.
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
	require ("../include/configuration.php");
	require ("../include/class.misc.php");

	//added these so that POG would still generate something even if invalid variables are passed
	//this is so that users see something being generated even if they don't fill in the object fields
	if ($objectName == null)
	{
		$objectName = '';
	}
	if ($attributeList == null)
	{
		$attributeList = array();
	}
	if ($typeList == null)
	{
		$attributeList = array();
	}
	if ($language == null)
	{
		$language = '';
	}
	if ($wrapper == null)
	{
		$wrapper = '';
	}
	if ($pdoDriver == null)
	{
		$pdoDriver = '';
	}

	if (strtoupper($wrapper) == "PDO")
	{
		require "../object_factory/class.object".$language.strtolower($wrapper).$pdoDriver.".php";
	}
	else
	{

		if  ($language == "php4")
		{
			require "../object_factory/class.objectphp4pogmysql.php";
		}
		else
		{
			require "../object_factory/class.objectphp5pogmysql.php";
		}
	}
	$object = new Object($objectName,$attributeList,$typeList,$pdoDriver);
	$object->BeginObject();
	$object->CreateConstructor();
	$object->CreateGetFunction();
	$object->CreateGetAllFunction();
	$object->CreateSaveFunction(in_array("HASMANY", $typeList));
	$object->CreateSaveNewFunction();
	$object->CreateDeleteFunction(in_array("HASMANY", $typeList));
	
	$i = 0;
	foreach ($typeList as $type)
	{
		if ($type == "HASMANY")
		{
			$object->CreateGetChildrenFunction($attributeList[$i]);
			$object->CreateAddChildFunction($attributeList[$i]);
		}
		else if ($type == "BELONGSTO")
		{
			$object->CreateGetParentFunction($attributeList[$i]);
			$object->CreateSetParentFunction($attributeList[$i]);
		}
		$i++;
	}
	
	if(strtoupper($wrapper) == "PDO")
	{
		$object->CreateEscapeFunction();
		$object->CreateUnescapeFunction();
	}
	$object->EndObject();
	return base64_encode($object->string);
}

/**
 * Generates the appropriate object from `proprietary format` of @link
 * An @link looks like this: http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=alliever&attributeList=array (  0 => 'firstName',  1 => 'lastName',  2 => 'description',  3 => 'gender',  4 => 'Country',  5 => 'over18',)&typeList=array (  0 => 'VARCHAR(255)',  1 => 'VARCHAR(255)',  2 => 'TEXT',  3 => 'enum(\\\'male\\\',\\\'female\\\')',  4 => 'enum(\\\'Mauritius\\\', \\\'Canada\\\', \\\'Singapore\\\')',  5 => 'enum(\\\'yes\\\')',)
 * @param (urlencoded)string $link
 * @return base64 encoded string
 */
function GenerateObjectFromLink($link)
{
	$link = explode('?', $link);

	$linkParts = explode('&', $link[1]);
	for ($i = 0; $i < sizeof($linkParts); $i++)
	{
		$arguments = split('[^ ]=', $linkParts[$i]);

		eval ("\$".$arguments[0]." =". stripcslashes(urldecode($arguments[1])).";");
	}
	for($i=0; $i<sizeof($typeLis); $i++)
	{
		$typeLis[$i] = stripcslashes($typeLis[$i]);
	}
	$string = GenerateObject($objectNam, $attributeLis, $typeLis, $languag, $wrappe, $pdoDrive);
	return $string;
}

/**
 * Generates the appropriate configuration file
 *
 * @param string $wrapper
 * @return base64 encoded string
 */
function GenerateConfiguration($wrapper = null, $pdoDriver = null)
{
	if (strtoupper($wrapper) == "PDO")
	{
		$data = file_get_contents("../configuration_factory/configuration.".strtolower($pdoDriver).".php");
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
	$package["configuration.php"] = GenerateConfiguration($wrapper, $pdoDriver);



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

	return serialize($package);
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
