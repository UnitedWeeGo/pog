<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `objectsiblingmap` (
	`objectid` int(11) NOT NULL,
	`siblingid` int(11) NOT NULL,INDEX(`objectid`, `siblingid`));
*/

/**
* <b>objectsiblingMap</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP5.1 MYSQL
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
class objectsiblingMap
{
	public $objectId = '';

	public $siblingId = '';

	public $pog_attribute_type = array(
		"objectId" => array("NUMERIC", "INT"),
		"siblingId" => array("NUMERIC", "INT"));
		public $pog_query;
	
	
	/**
	* Creates a mapping between the two objects
	* @param object $object 
	* @param sibling $otherObject 
	* @return 
	*/
	function AddMapping(&$object, $otherObject)
	{
		if ($object instanceof object && $object->objectId != '')
		{
			$this->objectId = $object->objectId;
			$this->siblingId = $otherObject->siblingId;
			return $this->Save();
		}
		else if ($object instanceof sibling && $object->siblingId != '')
		{
			$this->siblingId = $object->siblingId;
			$this->objectId = $otherObject->objectId;
			return $this->Save();
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	* Removes the mapping between the two objects
	* @param Object $object 
	* @param Object $object2 
	* @return 
	*/
	function RemoveMapping(&$object, &$otherObject = null)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			if ($object instanceof object)
			{
				$this->pog_query = "delete from `objectsiblingmap` where `objectid` = '".$object->objectId."'";
				if ($otherObject != null && $otherObject instanceof sibling)
				{
					$this->pog_query .= " and `siblingid` = '".$otherObject->siblingId."'";
				}
			}
			else if ($object instanceof sibling)
			{
				$this->pog_query = "delete from `objectsiblingmap` where `siblingid` = '".$object->siblingId."'";
				if ($otherObject != null && $otherObject instanceof object)
				{
					$this->pog_query .= " and `objectid` = '".$otherObject->objectId."'";
				}
			}
			$Database->Query($this->pog_query);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Physically saves the mapping to the database
	* @return 
	*/
	function Save()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select count(`objectid`) as count from `objectsiblingmap` where `objectid`='".$this->objectId."' AND `siblingid`='".$this->siblingId."' LIMIT 1";
			foreach ($Database->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows == 0)
			{
				$this->pog_query = "insert into `objectsiblingmap` (`objectid`, `siblingid`) values ('".$this->objectId."', '".$this->siblingId."')";
				$Database->query($this->pog_query);
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
}
?>