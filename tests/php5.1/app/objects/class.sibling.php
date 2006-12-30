<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `sibling` (
	`siblingid` int(11) NOT NULL auto_increment,
	`attribute` VARCHAR(255) NOT NULL, PRIMARY KEY  (`siblingid`));
*/

/**
* <b>sibling</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP5.1 MYSQL
* @see http://www.phpobjectgenerator.com/plog/tutorials/45/pdo-mysql
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=mysql&objectName=sibling&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array%2B%2528%250A%2B%2B0%2B%253D%253E%2B%2527JOIN%2527%252C%250A%2B%2B1%2B%253D%253E%2B%2527VARCHAR%2528255%2529%2527%252C%250A%2529
*/
include_once('class.objectsiblingmap.php');
class sibling
{
	public $siblingId = '';

	/**
	 * @var private array of object objects
	 */
	private $_objectList;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $attribute;
	
	public $pog_attribute_type = array(
		"siblingId" => array("NUMERIC", "INT"),
		"object" => array("OBJECT", "JOIN"),
		"attribute" => array("TEXT", "VARCHAR", "255"),
		);
	public $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	public function __get($attribute)
	{
		if (isset($this->{"_".$attribute}))
		{
			return $this->{"_".$attribute};
		}
		else
		{
			return false;
		}
	}
	
	function sibling($attribute='')
	{
		$this->_objectList = array();
		$this->attribute = $attribute;
	}
	
	
	/**
	* Gets object from database
	* @param integer $siblingId 
	* @return object $sibling
	*/

	function Get($siblingId)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select * from `sibling` where `siblingid`= ? LIMIT 1";
			$stmt = $Database->prepare($this->pog_query);
			if ($stmt->execute(array($siblingId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->siblingId = $row['siblingid'];
					$this->attribute = $this->Unescape($row['attribute']);
				}
			}
			return $this;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $siblingList
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$siblingList = Array();
			try
			{
				$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
				$pog_query = "select `siblingid` from `sibling` where ";
				for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
				{
					if (sizeof($fcv_array[$i]) == 1)
					{
						$pog_query .= " ".$fcv_array[$i][0]." ";
						continue;
					}
					else
					{
						if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
						{
							$pog_query .= " AND ";
						}
						if (isset($this->pog_attribute_type[$fcv_array[$i][0]]) && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
						{
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".sibling::Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
				$pog_query .= " order by siblingid asc $sqlLimit";
				$thisObjectName = get_class($this);
				foreach ($Database->query($pog_query) as $row)
				{
					$sibling = new $thisObjectName();
					$sibling->Get($row['siblingid']);
					$siblingList[] = $sibling;
				}
				if ($sortBy != '')
				{
					$f = '';
					$sibling = new $thisObjectName();
					if (isset($sibling->pog_attribute_type[$sortBy]) && ($sibling->pog_attribute_type[$sortBy][0] == "NUMERIC" || $sibling->pog_attribute_type[$sortBy][0] == "SET"))
					{
						$f = 'return $sibling1->'.$sortBy.' > $sibling2->'.$sortBy.';';
					}
					else if (isset($sibling->pog_attribute_type[$sortBy]))
					{
						$f = 'return strcmp(strtolower($sibling1->'.$sortBy.'), strtolower($sibling2->'.$sortBy.'));';
					}
					usort($siblingList, create_function('$sibling1, $sibling2', $f));
					if (!$ascending)
					{
						$siblingList = array_reverse($siblingList);
					}
					if ($limit != '')
					{
						$limitParts = explode(',', $limit);
						if (sizeof($limitParts) > 1)
						{
							return array_slice($siblingList, $limitParts[0], $limitParts[1]);
						}
						else
						{
							return array_slice($siblingList, 0, $limit);
						}
					}
				}
				return $siblingList;
			}
			catch(PDOException $e)
			{
				throw new Exception($e->getMessage());
			}
		}
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $siblingId
	*/
	function Save($deep = true)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select count(`siblingid`) as count from `sibling` where `siblingid`='$this->siblingId' limit 1";
			foreach ($Database->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `sibling` set `attribute`=? where `siblingid`=?";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->Escape($this->attribute));
				$stmt->bindParam(2, $this->siblingId);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `sibling` (`attribute`) values (?)";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->Escape($this->attribute));
			}
			$stmt->execute();
			if ($this->siblingId == "")
			{
				$this->siblingId = $Database->lastInsertId();
			}
			if ($deep)
			{
				foreach ($this->_objectList as $object)
				{
					$object->Save();
					$map = new objectsiblingMap();
					$map->AddMapping($this, $object);
				}
			}
			return $this->siblingId;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $siblingId
	*/
	function SaveNew($deep = false)
	{
		$this->siblingId = '';
		return $this->Save($deep);
	}
	
	
	/**
	* Deletes the object from the database
	* @return integer $affectedRows
	*/
	function Delete($deep = false)
	{
		try
		{
			if ($deep)
			{
				$objectList = $this->GetObjectList();
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
				foreach ($objectList as $object)
				{
					$object->Delete($deep);
				}
			}
			else
			{
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
			}
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "delete from `sibling` where `siblingid` = '$this->siblingId'";
			$affectedRows = $Database->query($this->pog_query);
			if ($affectedRows != null)
			{
				return $affectedRows;
			}
			else
			{
				return 0;
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array, $deep = false)
	{
		if (sizeof($fcv_array) > 0)
		{
			if ($deep)
			{
				$objectList = $this->GetList($fcv_array);
				foreach ($objectList as $object)
				{
					$object->Delete($deep);
				}
			}
			else
			{
				try
				{
					$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
					$pog_query = "delete from `sibling` where ";
					for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
					{
						if (sizeof($fcv_array[$i]) == 1)
						{
							$pog_query .= " ".$fcv_array[$i][0]." ";
							continue;
						}
						else
						{
							if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
							{
								$pog_query .= " AND ";
							}
							if (isset($this->pog_attribute_type[$fcv_array[$i][0]]) && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
							{
								$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
							}
							else
							{
								$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
							}
						}
					}
					return $Database->Query($pog_query);
				}
				catch(PDOException $e)
				{
					throw new Exception($e->getMessage());
				}
			}
		}
	}
	
	
	/**
	* Creates mappings between this and all objects in the object List array. Any existing mapping will become orphan(s)
	* @return null
	*/
	function SetObjectList(&$objectList)
	{
		$map = new objectsiblingMap();
		$map->RemoveMapping($this);
		$this->_objectList = $objectList;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $siblingList
	*/
	function GetObjectList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$object = new object();
			$objectList = Array();
			$this->pog_query = "select distinct(a.objectid) from `object` a INNER JOIN `objectsiblingmap` m ON m.objectid = a.objectid where m.siblingid = '$this->siblingId' ";
			if (sizeof($fcv_array) > 0)
			{
				$this->pog_query .= " AND ";
				for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
				{
					if (sizeof($fcv_array[$i]) == 1)
					{
						$this->pog_query .= " ".$fcv_array[$i][0]." ";
						continue;
					}
					else
					{
						if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
						{
							$this->pog_query .= " AND ";
						}
						if (isset($object->pog_attribute_type[$fcv_array[$i][0]]) && $object->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $object->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
						{
							$this->pog_query .= "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$this->pog_query .= "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
			}
			$this->pog_query .= " order by m.objectid asc $sqlLimit";
			$Database->Query($this->pog_query);
			foreach ($Database->query($this->pog_query) as $row)
			{
				$object = new object();
				$object->Get($row['objectid']);
				$objectList[] = $object;
			}
			if ($sortBy != '')
			{
				$f = '';
				if (isset($object->pog_attribute_type[$sortBy]) && ($object->pog_attribute_type[$sortBy][0] == "NUMERIC" || $object->pog_attribute_type[$sortBy][0] == "SET"))
				{
					$f = 'return $object1->'.$sortBy.' > $object2->'.$sortBy.';';
				}
				else if (isset($object->pog_attribute_type[$sortBy]))
				{
					$f = 'return strcmp(strtolower($object1->'.$sortBy.'), strtolower($object2->'.$sortBy.'));';
				}
				usort($objectList, create_function('$object1, $object2', $f));
				if (!$ascending)
				{
					$objectList = array_reverse($objectList);
				}
				if ($limit != '')
				{
					$limitParts = explode(',', $limit);
					if (sizeof($limitParts) > 1)
					{
						return array_slice($objectList, $limitParts[0], $limitParts[1]);
					}
					else
					{
						return array_slice($objectList, 0, $limit);
					}
				}
			}
			return $objectList;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		return null;
	}
	
	
	/**
	* Associates the object object to this one
	* @return 
	*/
	function AddObject(&$object)
	{
		if ($object instanceof object)
		{
			if (in_array($this, $object->siblingList, true))
			{
				return false;
			}
			else
			{
				$found = false;
				foreach ($this->_objectList as $object2)
				{
					if ($object->objectId > 0 && $object->objectId == $object2->objectId)
					{
						$found = true;
						break;
					}
				}
				if (!$found)
				{
					$this->_objectList[] = $object;
				}
			}
		}
	}
	
	
	/**
	* This function will always try to encode $text to base64, except when $text is a number. This allows us to Escape all data before they're inserted in the database, regardless of attribute type.
	* @param string $text 
	* @return base64_encoded $text
	*/
	function Escape($text)
	{
		if ($GLOBALS['configuration']['db_encoding'] && !is_numeric($text))
		{
			return base64_encode($text);
		}
		return mysql_escape_string($text);
	}
	
	
	function Unescape($text)
	{
		if ($GLOBALS['configuration']['db_encoding'] && !is_numeric($text))
		{
			return base64_decode($text);
		}
		return stripcslashes($text);
	}
}
?>