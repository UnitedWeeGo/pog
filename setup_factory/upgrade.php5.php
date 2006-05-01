<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
*
* This upgrade file does the following:
* 1. Checks if there is a new version of POG
* 2. If there is, it reads generates newer versions of all objects in the object directory,
* zip then and present them to the user to 'download'
*/
include "../../configuration.php";
include "class.zipfile.php";

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
		$i = 0;
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
				$client = new SoapClient($GLOBALS['configuration']['soap']) ;
				if ($i == 0)
				{
					$package = unserialize($client->GeneratePackageFromLink($link));
				}
				else
				{
					$objectString = $client->GenerateObjectFromLink($link);
					$package["objects"]["class.".strtolower($className).".php"] = $objectString;
				}
			}
			$i++;
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
		$client = new SoapClient($GLOBALS['configuration']['soap']);
		$generatorVersion = base64_decode($client -> GetGeneratorVersion());
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
		echo "<script>
			alert('All POG objects are already up to date');
			window.close();
		</script>";
	}
?>