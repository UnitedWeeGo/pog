<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license 
*
* This setup file does the following: 
* 1. Checks if configuration file is present
* 2. Checks if the data in the configuration file is correct
* 3. Checks if the database and table exist
* 4. Create table if not present
* 5. Tests 5 CRUD functions and determine if everything is OK for all objects within the current directory
* 6. When all tests pass, provides an interface to the database and a way to manage objects.
*/
include_once("setup_library/setup_misc.php");
if(file_exists("../configuration.php"))
{
	include_once("../configuration.php");
}
else
{
	echo "configuration file missing<br/>";
}
if(!isset($_SESSION['diagnosticsSuccessful']) || (isset($_GET['step']) && $_GET['step']=="diagnostics"))
{
	$_SESSION['diagnosticsSuccessful'] = false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Php Object Generator Setup</title>
<meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented, CRUD" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="./setup.css" type="text/css" />
</head>
<body>
<div class="header">
<?php include "setup_library/inc.header.php";?>
</div>
<form action="./index.php" method="POST">
<?php
$errorLevel =  ini_get("error_reporting");
ini_set("error_reporting", 0);
if(count($_POST) > 0 && $_SESSION['diagnosticsSuccessful']==false)
{
?>
<div class="container">
<div class="left">
	<div class="logo2"></div>
	<div class="text"><div class="gold">What is POG Setup?</div>POG Setup is an extension of the online Php Object Generator. It is meant to help the veteran POG user and the novice alike. 
	<br/><br/>POG Setup is a 3 step process which:<br/><br/>
	1. Creates tables for your generated objects.<br/><br/>
	2. Performs diagnostics tests on all objects within your 'objects' directory.<br/><br/> 
	3. Provides a light interface to your object tables.</div>
</div>
<div class="middle">
	<div id="tabs">
		<a href="./index.php?step=diagnostics"><img src="./setup_images/tab_setup.gif"/></a>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_diagnosticresults_on.gif"/>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_manageobjects.gif"/>
	</div><div class="subtabs">&nbsp;</div><a href="./index.php?step=diagnostics"><img src="./setup_images/setup_recheck.jpg" border="0"/></a><div class="middle2">
<?php
	$type_value = InitializeTestValues();
	//perform diagnostics
	if (isset($GLOBALS['configuration']['pdoDriver']))
	{
		
	}
	else 
	{
		if(file_exists("../objects/class.database.php"))
		{
			include "../objects/class.database.php";
			//try connecting to the database
			try
			{
				$database = new DatabaseConnection();
				//success
				//scan for generated objects.
				$dir = opendir('../objects/');  
				$objects = array();  
				while(($file = readdir($dir)) !== false)  
				{  
					if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.php' && !is_dir($file) && $file != "class.database.php" && $file != "configuration.php" && $file != "setup.php")  
					{  
						$objects[] = $file;  
					}  
				}  
				closedir($dir);
				$objectNameList = array();
				$errors = 0;
				$diagnostics = "";
				$_SESSION['links'] = array();
				foreach($objects as $object)
				{
					$content = file_get_contents("../objects/".$object);
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
						$diagnostics .= "TESTING $className...\n";
						$objectNameList[] = $className;
												
						//get sql
						$sqlParts = split(";",$contentParts[0]);
						$sqlPart = split("CREATE",$sqlParts[0]);
						$sql = "CREATE ".$sqlPart[1].";";

						$linkParts1 = split("\*\/", $contentParts[1]);
						$linkParts2 = split("\@link", $linkParts1[0]);
						$link = $linkParts2[1]; 

						include("../objects/{$object}");
						eval('$instance = new '.$className.'();');
						
						$attributeList = array_keys(get_object_vars($instance));
						
      					foreach($attributeList as $attribute)
  						{ 							
  							if (isset($instance->pog_attribute_type[$attribute]))
  							{
	  							if (isset($type_value[$instance->pog_attribute_type[$attribute]]))
	  							{
	 								$instance->{$attribute} = $type_value[$instance->pog_attribute_type[$attribute]];
	  							}
	  							else
	  							{
	  								$instance->{$attribute} = "1";
	  							}
  							}
  						}
      					//Test Save()
      					try
      					{
      						$instanceId = $instance->Save();
	      					if(!$instanceId)
	      					{
	      						$diagnostics .= "ERROR: Save() could not be performed\n";
	      						$diagnostics .= $instance->pog_query."\n";
	      						$errors++;
	      					}
	      					else
	      					{
	      						$diagnostics .=  "Testing Save()....OK\n";
	      					}
      					}
      					catch(Exception $e)
      					{
      						if(substr($e->getMessage(),0,4) == "1146")
      						{
      							//table doesn't exist
      							//try to create table
      							$database = new DatabaseConnection();
      							try 
      							{
      								$database->Query($sql);
      								$diagnostics .= "Created Table $className successfully\n";
      							}
      							catch (Exception $e)
      							{
      								$diagnostics .= "Could not create table.";
      							}
      							$instanceId = $instance->Save();
		      					if(!$instanceId)
		      					{
		      						$diagnostics .= "ERROR: Save() could not be performed\n";
		      						$diagnostics .= $instance->pog_query."\n";
		      						$errors++;
		      					}
		      					else
		      					{
		      						$diagnostics .= "Testing Save()....OK\n";
		      					}
      						}
      					}
      					
      					//Test SaveNew()
      					if(!$instance->SaveNew())
      					{
      						$diagnostics .= "ERROR: SaveNew() could not be performed\n";
      						$diagnostics .= $instance->pog_query."\n";
      						$errors++;
      					}
      					else
      					{
      						$diagnostics .= "Testing SaveNew()....OK\n";
      					}
      					
      					//Test GetList();
      					//GetList() implicitly tests Get()
      					//Multiple Conditions, 
      					$instanceList = $instance->GetList(array(array(strtolower($className)."Id",">",0)));
      					if($instanceList == null)
      					{
      						$diagnostics .= "ERROR: GetList() could not be performed\n";
      						$diagnostics .= $instance->pog_query."\n";
      						$errors++;
      					}
      					else 
      					{
      						$diagnostics .= "Testing Get()....OK\n";
      						$diagnostics .= "Testing GetList()....OK\n";
      						$oldCount = count($instanceList);
      						$instanceList = $instance->GetList(array(array(strtolower($className)."Id", ">=",$instanceId), array(strtolower($className)."Id", "<=", $instanceId+1)), $className."Id", false, 2);
      						foreach ($instanceList as $instance)
      						{
      							$attributeList = array_keys(get_object_vars($instance));
      							foreach ($attributeList as $attribute)
      							{
			      					if (isset($instance->pog_attribute_type[$attribute]))
		  							{
			  							if (isset($type_value[$instance->pog_attribute_type[$attribute]]))
			  							{
		      								if ($instance->{$attribute} != $type_value[$instance->pog_attribute_type[$attribute]])
		      								{
		      									$diagnostics .= "WARNING: Failed to retrieve attribute `$attribute`. Expecting `".$type_value[$instance->pog_attribute_type[$attribute]]."`; found `".$instance->{$attribute}."`. Check that column `$attribute` in the `$className` table is of type `".$instance->pog_attribute_type[$attribute]."`\n";
		      								}
			  							}
		  							}
      							}
      							$instance->Delete();
      						}
      						$instanceList = $instance->GetList(array(array(strtolower($className)."Id",">",0)));
      						if ($instanceList == null)
      						{
      							$instanceList = array();
      						}
      						$newCount = count($instanceList);
      						if($oldCount-2 == $newCount)
      						{
      							$diagnostics .= "Testing Delete()....OK\n";
      						}
      						else
      						{
      							$diagnostics .= "ERROR: Delete() could not be performed\n";
      							$diagnostics .= $instance->pog_query."\n";
      							$errors++;
      						}
      					}
      					if ($errors == 0)
						{
							$diagnostics .= $className."....OK\n-----\n";
							$_SESSION['links'][$className] = $link;
						}
   						$contentParts2 = null;
						$className = null;
					}
					
				}
				$diagnostics .= "\nFOUND & CHECKED ".count($objectNameList)." OBJECT(S)\n";
				$_SESSION['fileNames'] = serialize($objects);
				$_SESSION['objectNameList'] = serialize($objectNameList);
				echo "<textarea>$diagnostics</textarea></div>";
				if ($errors == 0)
				{
					$_SESSION['diagnosticsSuccessful'] = true;
					echo '<input type="image" src="./setup_images/setup_proceed.gif" name="submit"/>';
				}
				else
				{
					$diagnostics .= "FOUND $errors ERROR(S)\n";
					//echo "<input type='submit' name='submit' value='Retry'/>";
				}
				
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
		{
			echo "database wrapper (class.database.php) missing<br/>";
		}
	}
$_POST = null;
?>
</div></div>
<?php
}
else if($_SESSION['diagnosticsSuccessful'] == true)
{
?>
<div class="container">
	<div class="left">
		<div class="logo3"></div>
		<div class="text"><div class="gold">What is POG Setup?</div>POG Setup is an extension of the online Php Object Generator. It is meant to help the veteran POG user and the novice alike. 
		<br/><br/>POG Setup is a 3 step process which:<br/><br/>
		1. Creates tables for your generated objects.<br/><br/>
		2. Performs diagnostics tests on all objects within your 'objects' directory.<br/><br/> 
		3. Provides a light interface to your object tables.</div>
	</div>
<div class="middle33">
	<div id="tabs3">
		<a href="./index.php?step=diagnostics"><img src="./setup_images/tab_setup.gif"/></a>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_diagnosticresults.gif"/>
		<img src="./setup_images/tab_separator.gif"/>
		<a href="./index.php"><img src="./setup_images/tab_manageobjects_on.gif"/></a>
	</div><div class="subtabs">
<?php
	//provide interface to the database
	if(file_exists("configuration.php"))
	{
		include "../configuration.php";
	}
	if(file_exists("../objects/class.database.php"))
	{
		include "../objects/class.database.php";
	}
	
	$fileNames = unserialize($_SESSION['fileNames']);
	foreach($fileNames as $filename)
	{
		include("../objects/{$filename}");
	}
	$objectNameList = unserialize($_SESSION['objectNameList']);
	if (isset($_GET['objectName']))
	{
		$_SESSION['objectName'] = $_GET['objectName'];
	}
	$objectName = (isset($_SESSION['objectName'])?$_SESSION['objectName']:$objectNameList[0]);
	
	?>
	<div id="header">
  	<ul>
  	<li id='inactive'>My Tables:</li>
	<?php
	if (!isset($_SESSION['objectName']))
	{
		$_SESSION['objectName'] = $objectNameList[0];
	}
	for($i=0; $i<count($objectNameList); $i++)
	{
		echo "<li ".($_SESSION['objectName']==$objectNameList[$i]?"id='current'":'')."><a href='./index.php?objectName=".$objectNameList[$i]."'>".$objectNameList[$i]."</a></li>";
		//echo "<a href='./index.php?objectName=".$objectNameList[$i]."'".(isset($_SESSION['objectName']) && $_SESSION['objectName']==$objectNameList[$i]?"class='activetab'":(!isset($_SESSION['objectName'])&&$i==0?"class='activetab'":"inactivetab")).">".$objectNameList[$i]."</a> ";
	}
	?>
	</ul>
	</div></div><div class="toolbar"><a href="<?php echo $_SESSION['links'][$_SESSION['objectName']]?>" target="_blank"><img src="./setup_images/setup_regenerate.jpg" border="0"/></a><input type='image' src='./setup_images/setup_deleteall.jpg' alt='delete all' name='thrashall' value='thrashall'/></a></div><div class="middle3">
	<?php
	//is there an action to perform?

	$keys = array_keys($_POST);
	foreach ($keys as $key)
	{
		if (substr($key, 0, 3) == "add")
		{
			eval('$instance = new '.$objectName.'();');
			foreach ($_POST as $attribute)
			{
				if ($attribute != "add")
				{
					$instance->{key($_POST)} = "$attribute";
					next($_POST);
				}
			}
			$instance->Save();
			break;
		}
		else if (substr($key, 0, 6) == "delete")
		{
			eval('$instance = new '.$objectName.'();');
			$instanceId = substr($key, 6);
			$instance->Get($instanceId);
			$instance->Delete();
			break;
		}
		else if (substr($key, 0, 6) == "update")
		{
			eval('$instance = new '.$objectName.'();');
			$instanceIdParts = explode("_", $key);
			$instanceId = substr($instanceIdParts[0], 6); // very important. when using images as submit button, "_x" & "_y" is automatically added by the browser. 
			$instance->Get($instanceId);
			foreach ($keys as $key2)
			{
				$keyParts = explode("_", $key2);
				if (count($keyParts) > 1 && $keyParts[1]==$instanceId)
				{
					$instance->{$keyParts[0]} = $_POST[$key2];
				}
			}
			$instance->Save();
			break;
		}
		else if (substr($key, 0, 9) == "thrashall")
		{
			eval('$instance = new '.$objectName.'();');
			$instanceId = strtolower(get_class($instance))."Id";
			$instanceList = $instance->GetList(array(array($instanceId, ">", "0")));
			foreach ($instanceList as $instance)
			{
				$instance->Delete();
			}
		}
	}
	eval('$instance = new '.$objectName.'();');
	$attributeList = array_keys(get_object_vars($instance));
	$instanceList = $instance->GetList(array(array(strtolower($objectName)."Id",">",0)));
	$table =  "<table border='0'><tr>";
	$x = 0;
	foreach($attributeList as $attribute)
	{
		if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
		{
			if ($x == 0)
			{
				$table .= "<td width='75'>$attribute</td>";
			}
			else
			{
				$table .= "<td>$attribute</td>";
			}
			$x++;
		}
	}
	$table .= "<td></td></tr>";
	$table .= "<tr>";
	foreach($attributeList as $attribute)
	{
		if ($attribute == strtolower($objectName).'Id')
		{
			$table .= "<td width='75'>";
			$table .= "</td>";
		}
		else if ($attribute != "pog_attribute_type" && $attribute!= "pog_query" && $attribute!= strtolower($objectName).'Id')
		{
			$table .= "<td>";
			$table .= ConvertAttributeToHtml($attribute,$instance->pog_attribute_type[$attribute]);
			$table .= "</td>";
		}
	}
	$table .= "<td><input type='image' src='./setup_images/button_add.gif' alt='add' name='add' value='add'/></td></tr>";
	if ($instanceList != null)
	{
		foreach($instanceList as $instance)
		{
			$table .= "<tr>";
			$x = 0;
			foreach($attributeList as $attribute)
			{
				if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
				{
					if ($x == 0)
					{
						$table .= "<td class='id'>".$instance->{$attribute}."</td>";
					}
					else
					{
						$table .= "<td>";
						if (isset($instance->pog_attribute_type[$attribute]))
						{
							$table .= ConvertAttributeToHtml($attribute, $instance->pog_attribute_type[$attribute], $instance->{$attribute}, $instance->{$attributeList[0]});
						}
						else 
						{
							$table .= $instance->{$attribute};
						}
						$table .= "</td>";
					}
					$x++;
				}
			}
			$table .= "<td><input type='image' src='./setup_images/button_update.gif' alt='update' value='update' name='update".$instance->{$attributeList[0]}."'/> <input type='image'  src='./setup_images/button_delete.gif' alt='delete' name='delete".$instance->{$attributeList[0]}."' value='delete'/></td></tr>";
		}
	}
	$table .= "</table>";
	echo $table;
	$_SESSION['fileNames'] = serialize($fileNames);
	$_SESSION['objectNameList'] = serialize($objectNameList);
?>
</div><div class="bottom3"><img src="./setup_images/setup_bottom3.jpg"/></div></div></div>
<?php
}
else 
{
	//welcome screen
?>
<div class="container">
	<div class="left">
		<div class="logo"></div>
		<div class="text"><div class="gold">What is POG Setup?</div>POG Setup is an extension of the online Php Object Generator. It is meant to help the veteran POG user and the novice alike. 
		<br/><br/>POG Setup is a 3 step process which:<br/><br/>
		1. Creates tables for your generated objects.<br/><br/>
		2. Performs diagnostics tests on all objects within your 'objects' directory.<br/><br/> 
		3. Provides a light interface to your object tables.</div>
	</div>
	<div class="middle">
		<div id="tabs">
			<img src="./setup_images/tab_setup_on.gif" height="20px" width="70px"/>
			<img src="./setup_images/tab_separator.gif" height="20px" width="17px"/>
			<img src="./setup_images/tab_diagnosticresults.gif" height="20px" width="137px"/>
			<img src="./setup_images/tab_separator.gif" height="20px" width="17px"/>
			<img src="./setup_images/tab_manageobjects.gif" height="20px" width="129px"/>
		</div>
		<div id="nifty">
			<div style="height:500px">
			<img src="./setup_images/setup_welcome.jpg" height="47px" width="617px"/>
			<div class="col1"><img src="./setup_images/pog_setup_closed.jpg"/><div class="gold">What is POG?</div>POG generates PHP objects with integrated CRUD methods to dramatically accelerate web application development in PHP. <br/>
			<br/>POG allows developers to easily map object attributes onto columns of a database table without having to write SQL queries. More information at:</div>
			<div class="col2"><img src="./setup_images/pog_setup_open.jpg"/><div class="gold">What is POG Setup?</div>You've generated one or more objects using Php Object Generator ... Now what?<br/>
			<br/>POG SETUP is an answer to this question and takes the POG experience one step further. The Setup process automates <b>table creation</b>, <b>unit testing</b> and provides a light <b>scaffolding</b> environment.</div>
			<div class="col3">
			<div class="gold">If you are ready to get POG'd up, click on thebutton below to proceed. Doing this will:</div>
			<br/>1. Establish a database connection.<br/>
			2. Create table(s) for your objec(s), if required.<br/>
			3. Perform diagnostics tests on your object(s).<br/>
			4. Provide you with the test results.<br/><input type="image" src="./setup_images/setup_pogmeup.gif" name="submit"/></div>
			</div>
			<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
		</div>
	</div>
</div>
<?php	
}
ini_set("error_reporting", $errorLevel);
?>
</form>
<div class="footer">
<?php include "setup_library/inc.footer.php";?>
</div>
</body>
</html>