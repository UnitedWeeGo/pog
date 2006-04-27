<?php
include "../../configuration.php";
include "../setup_library/class.zipfile.php";
include "../setup_library/nusoap.php";

	/**
	 * Connects to POG SOAP server defined in configuration.php and 
	 * generates new versions of all objects detected in /objects/ dir.
	 * All upgraded objects are then zipped and presented to user.
	 *
	 * @param string $path
	 */
	function UpdateAllObjects($path)
	{
		$dir = opendir($path);
		$objects = array();
		while(($file = readdir($dir)) !== false)
		{
			if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.php' && !is_dir($file) && $file != "class.database.php" && $file != "configuration.php" && $file != "setup.php")
			{
				$objects[] = $file;
			}
		}
		closedir($dir);
		$package = array();
		$package["objects"] = array();
		foreach($objects as $object)
		{
			$content = file_get_contents($path."/".$object);
			$contentParts = split("<b>",$content);
			if (isset($contentParts[1]))
			{
				$contentParts2 = split("</b>",$contentParts[1]);
			}
			if (isset($contentParts2[0]))
			{
				$className = trim($contentParts2[0]);
			}
			if (isset($className))
			{
				$objectNameList[] = $className;

				$linkParts1 = split("\*\/", $contentParts[1]);
				$linkParts2 = split("\@link", $linkParts1[0]);
				$link = $linkParts2[1];

				$client = new soapclient($GLOBALS['configuration']['soap'], true);
				$params = array('link' 	=> $link);
				$objectString = $client->call('GenerateObjectFromLink', $params);
				$package["objects"]["class.".strtolower($className).".php"] = $objectString;
			}
		}
		$zipfile = new createZip();
		$zipfile -> addPOGPackage($package);
		$zipfile -> forceDownload("pog.".time().".zip");
	}
	
	/**
	 * Checks if POG generator has been updated
	 *
	 * @return unknown
	 */
	function UpdateAvailable()
	{
		$client = new soapclient($GLOBALS['configuration']['soap'], true);
		$params = array();
		$generatorVersion = base64_decode($client->call('GetGeneratorVersion'));
		if ($generatorVersion != $GLOBALS['configuration']['versionNumber'].$GLOBALS['configuration']['revisionNumber'])
		{
			return true;
		}
		else 
		{
			return  false;
		}
	}
	
	if (UpdateAvailable())
	{
		UpdateAllObjects("../../objects/");
	}
	else 
	{
		echo "All POG objects are already up to date.";
	}
?>