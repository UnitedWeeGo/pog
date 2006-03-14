<?php
include "./setup_library/xPandMenu.php";
include "./setup_library/setup_misc.php";
if(file_exists("../configuration.php"))
{
	include "../configuration.php";
}

if(file_exists("../objects/class.database.php"))
{
	include "../objects/class.database.php";
}

$objectName = $_REQUEST['objectname'];

//include all classes (possible relations)
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
foreach ($objects as $object)
{
	include("../objects/{$object}");
}

eval ('$instance = new '.$objectName.'();');
$attributeList = array_keys(get_object_vars($instance));
$noOfExternalAttributes = sizeof($attributeList) - 3;

// get object id to perform action. required for Delete() and Update()
$objectId = $_REQUEST['objectid'];

// get the ids of all open nodes before action is performed
$openNodes = explode('-', $_REQUEST['opennodes']);

// get action to perform
$action = $_GET['action'];

$currentNode = -1;
if (isset($_GET['currentnode']))
{
	// get the node id on which the action is performed. required for Delete() and Update()
	$currentNode = $_GET['currentnode'];
	$currentNodeParts = explode('Xnode', $currentNode);
	if (isset($currentNodeParts[1]))
	{
		$currentNode = $currentNodeParts[1];
	}
}
$root = new XMenu();

foreach ($openNodes as $openNode)
{
	$openNodeParts = explode('Xtree', $openNode);
	$noParts = sizeof($openNodeParts);

	// all open nodes when action is initiated
	if ($noParts > 0 && is_numeric($openNodeParts[$noParts - 1]))
	{
		// initialize all open nodes
		$root->visibleNodes[] = $openNodeParts[$noParts - 1];
	}
}

// perform requested action
switch($action)
{
    case 'Add':
    	eval ('$instance = new '.$objectName.'();');
    	$attributeList = array_keys(get_object_vars($instance));
    	foreach($attributeList as $attribute)
		{
			if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
			{
				if (isset($_GET[$attribute]))
				{
					$instance->{$attribute} = $_GET[$attribute];
				}
			}
		}
		if ($instance->Save())
		{
			for ($i = 0; $i < sizeof($root->visibleNodes); $i++)
			{
				if ($root->visibleNodes[$i] > ($noOfExternalAttributes + 2))
				{
					$root->visibleNodes[$i] += ($noOfExternalAttributes + 1);
				}
			}
		}
    	RefreshTree($objectName, $root);
    break;
    case 'GetList':
		RefreshTree($objectName, $root);
    break;
    case 'Delete':
    	eval ('$instance = new '.$objectName.'();');
    	$instance->Get($objectId);
    	$instance->Delete();
    	for ($i = 0; $i < sizeof($root->visibleNodes); $i++)
		{
			if ($root->visibleNodes[$i] > ($noOfExternalAttributes + 2))
			{
				if (intval($root->visibleNodes[$i]) == intval($openNodeParts[$noParts - 1]))
				{
					$root->visibleNodes[$i] = null;
				}
				else if ($root->visibleNodes[$i] > $currentNode)
				{
					$root->visibleNodes[$i] -= ($noOfExternalAttributes + 1);
				}
			}
		}
    	RefreshTree($objectName, $root);
    break;
    case 'Update':
    	eval ('$instance = new '.$objectName.'();');
    	$instance->Get($objectId);
    	$attributeList = array_keys(get_object_vars($instance));
    	foreach($attributeList as $attribute)
		{
			if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
			{
				if (isset($_GET[$attribute]))
				{
					$instance->{$attribute} = $_GET[$attribute];
				}
			}
		}
    	$instance->Save();
    	RefreshTree($objectName, $root);
    break;
 }

 /**
  * Refreshes the tree after an operation while preserving node statuses
  *
  * @param unknown_type $objectName
  * @param unknown_type $root
  */
 function RefreshTree($objectName, $root)
 {
 		$js = "new Array(";
 		eval ('$instance = new '.$objectName.'();');
		$attributeList = array_keys(get_object_vars($instance));
		$instanceList = $instance->GetList(array(array(strtolower($objectName)."Id",">",0,)), strtolower($objectName)."Id", false);
		$x = 0;
		$masterNode = &$root->addItem(new XNode("<span style='color:#998D05'>".$objectName."</span>&nbsp;<span style='font-weight:normal'>{Dimensions:[".sizeof($instanceList)."]}</span>", false, "setup_images/folderopen.gif","setup_images/folderclose.gif"));
		$node = &$masterNode->addItem(new XNode("<span style='color:#998D05'>ADD RECORD</span>", false,"setup_images/folderclose.gif","setup_images/folderopen.gif"));
		foreach($attributeList as $attribute)
		{
			if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
			{
				if ($x != 0)
				{
					$js .= '"'.$attribute.'"';
					if ($x != sizeof($attributeList)-3)
					{
						$js .= ",";
					}
					$thisValue = ConvertAttributeToHtml($attribute, $instance->pog_attribute_type[strtolower($attribute)], $instance->{$attribute}, $instance->{$attributeList[0]});
					$subnode = &$node->addItem(new XNode("<br/><span style='color:#998D05'>".$attribute."</span>&nbsp;<span style='font-weight:normal;color:#ADA8B2;'>{".$instance->pog_attribute_type[strtolower($attribute)][1]."}</span><br/>".$thisValue."<br/>", false,'',"setup_images/folderopen.gif"));
				}
			}
			$x++;
		}
		$js .= ")";
		$subnode = &$node->addItem(new XNode("<br/><a href='#' onclick='javascript:sndReq(\"Add\", getOpenNodes(), \"$objectName\", \"".$instance->{strtolower($objectName).'Id'}."\", this.parentNode.parentNode.parentNode.parentNode.id, $js);return false;'><img src='./setup_images/button_add.gif' border='0'/></a>", false,'',"folderopen.gif"));

		if ($instanceList != null)
		{
			foreach($instanceList as $instance)
			{
				$className = get_class($instance);
				$node = &$masterNode->addItem(new XNode("<span style='color:#0BAA9D'>[".$instance->{strtolower($className)."Id"}."]</span>  <a href='#' onclick='javascript:sndReq(\"Delete\", getOpenNodes(), \"$objectName\", \"".$instance->{strtolower($objectName).'Id'}."\", this.parentNode.parentNode.parentNode.parentNode.id, $js);return false;'><img src=\"./setup_images/button_delete.gif\" border=\"0\"/></a>", false,"setup_images/folderclose.gif","setup_images/folderopen.gif"));
				$x = 0;
				foreach($attributeList as $attribute)
				{
					if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
					{
						if ($x == 0)
						{
							$table = "<div class='cell'>".$instance->{$attribute}."</div>";
						}
						else
						{
							$thisValue = ConvertAttributeToHtml($attribute, $instance->pog_attribute_type[strtolower($attribute)], $instance->{$attribute}, $instance->{$attributeList[0]});
							$subnode = &$node->addItem(new XNode("<br/>".$attribute."<span style='font-weight:normal;color:#ADA8B2;'>{".$instance->pog_attribute_type[strtolower($attribute)][1]."}</span><br/>".$thisValue."<br/>", false,'',"setup_images/folderopen.gif"));
						}
						$x++;
					}
				}
				$subnode = &$node->addItem(new XNode("<br/><a href='#' onclick='javascript:sndReq(\"Update\", getOpenNodes(), \"$objectName\", \"".$instance->{strtolower($objectName).'Id'}."\", this.parentNode.parentNode.parentNode.parentNode.id, $js);return false;'><img src='./setup_images/button_update.gif' border='0'/></a>", false,'',"folderopen.gif"));
			}
		}
		$menu_html_code = $root->generateTree();
		$table = "<div id='container'>".$menu_html_code."</div>";
		echo $table;
 }
?>