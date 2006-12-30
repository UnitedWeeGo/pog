<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `object` (
	`objectid` int(11) NOT NULL auto_increment,
	`attribute` VARCHAR(255) NOT NULL,
	`parent_id` int(11) NOT NULL, INDEX(`parent_id`), PRIMARY KEY  (`objectid`));
*/

/**
* <b>object</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP5.1 MYSQL
* @see http://www.phpobjectgenerator.com/plog/tutorials/45/pdo-mysql
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=mysql&objectName=object&attributeList=array+%28%0A++0+%3D%3E+%27attribute%27%2C%0A++1+%3D%3E+%27child%27%2C%0A++2+%3D%3E+%27parent_%27%2C%0A++3+%3D%3E+%27sibling%27%2C%0A%29&typeList=array%2B%2528%250A%2B%2B0%2B%253D%253E%2B%2527VARCHAR%2528255%2529%2527%252C%250A%2B%2B1%2B%253D%253E%2B%2527HASMANY%2527%252C%250A%2B%2B2%2B%253D%253E%2B%2527BELONGSTO%2527%252C%250A%2B%2B3%2B%253D%253E%2B%2527JOIN%2527%252C%250A%2529
*/
include_once('class.objectsiblingmap.php');
class object
{
	public $objectId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $attribute;
	
	/**
	 * @var private array of child objects
	 */
	private $_childList;
	
	/**
	 * @var INT(11)
	 */
	public $parent_Id = '';
	
	/**
	 * @var private array of sibling objects
	 */
	private $_siblingList;
	
	public $pog_attribute_type = array(
		"objectId" => array("NUMERIC", "INT"),
		"attribute" => array("TEXT", "VARCHAR", "255"),
		"child" => array("OBJECT", "HASMANY"),
		"parent_" => array("OBJECT", "BELONGSTO"),
		"sibling" => array("OBJECT", "JOIN"),
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
	
	function object($attribute='')
	{
		$this->attribute = $attribute;
		$this->_childList = array();
		$this->_siblingList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $objectId 
	* @return object $object
	*/

	function Get($objectId)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select * from `object` where `objectid`= ? LIMIT 1";
			$stmt = $Database->prepare($this->pog_query);
			if ($stmt->execute(array($objectId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->objectId = $row['objectid'];
					$this->attribute = $this->Unescape($row['attribute']);
					$this->parent_Id = $row['parent_id'];
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
	* @return array $objectList
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$objectList = Array();
			try
			{
				$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
				$pog_query = "select `objectid` from `object` where ";
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
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".object::Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
				$pog_query .= " order by objectid asc $sqlLimit";
				$thisObjectName = get_class($this);
				foreach ($Database->query($pog_query) as $row)
				{
					$object = new $thisObjectName();
					$object->Get($row['objectid']);
					$objectList[] = $object;
				}
				if ($sortBy != '')
				{
					$f = '';
					$object = new $thisObjectName();
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
		}
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $objectId
	*/
	function Save($deep = true)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select count(`objectid`) as count from `object` where `objectid`='$this->objectId' limit 1";
			foreach ($Database->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `object` set `attribute`=?, `parent_id`=? where `objectid`=?";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->Escape($this->attribute));
				$stmt->bindParam(2, $this->parent_Id);
				$stmt->bindParam(3, $this->objectId);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `object` (`attribute`, `parent_id`) values (?, ?)";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->Escape($this->attribute));
				$stmt->bindParam(2, $this->parent_Id);
			}
			$stmt->execute();
			if ($this->objectId == "")
			{
				$this->objectId = $Database->lastInsertId();
			}
			if ($deep)
			{
				foreach ($this->_childList as $child)
				{
					$child->objectId = $this->objectId;
					$child->Save($deep);
				}
				foreach ($this->_siblingList as $sibling)
				{
					$sibling->Save();
					$map = new objectsiblingMap();
					$map->AddMapping($this, $sibling);
				}
			}
			return $this->objectId;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $objectId
	*/
	function SaveNew($deep = false)
	{
		$this->objectId = '';
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
				$childList = $this->GetChildList();
				foreach ($childList as $child)
				{
					$child->Delete($deep);
				}
				$siblingList = $this->GetSiblingList();
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
				foreach ($siblingList as $sibling)
				{
					$sibling->Delete($deep);
				}
			}
			else
			{
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
			}
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "delete from `object` where `objectid` = '$this->objectId'";
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
					$pog_query = "delete from `object` where ";
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
	* Gets a list of child objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of child objects
	*/
	function GetChildList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$child = new child();
		$fcv_array[] = array("objectId", "=", $this->objectId);
		$dbObjects = $child->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all child objects in the child List array. Any existing child will become orphan(s)
	* @return null
	*/
	function SetChildList(&$list)
	{
		$this->_childList = array();
		$existingChildList = $this->GetChildList();
		foreach ($existingChildList as $child)
		{
			$child->objectId = '';
			$child->Save(false);
		}
		$this->_childList = $list;
	}
	
	
	/**
	* Associates the child object to this one
	* @return 
	*/
	function AddChild(&$child)
	{
		$child->objectId = $this->objectId;
		$found = false;
		foreach($this->_childList as $child2)
		{
			if ($child->childId > 0 && $child->childId == $child2->childId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_childList[] = $child;
		}
	}
	
	
	/**
	* Associates the parent_ object to this one
	* @return boolean
	*/
	function GetParent_()
	{
		$parent_ = new parent_();
		return $parent_->Get($this->parent_Id);
	}
	
	
	/**
	* Associates the parent_ object to this one
	* @return 
	*/
	function SetParent_(&$parent_)
	{
		$this->parent_Id = $parent_->parent_Id;
	}
	
	
	/**
	* Creates mappings between this and all objects in the sibling List array. Any existing mapping will become orphan(s)
	* @return null
	*/
	function SetSiblingList(&$siblingList)
	{
		$map = new objectsiblingMap();
		$map->RemoveMapping($this);
		$this->_siblingList = $siblingList;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $objectList
	*/
	function GetSiblingList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$sibling = new sibling();
			$siblingList = Array();
			$this->pog_query = "select distinct(a.siblingid) from `sibling` a INNER JOIN `objectsiblingmap` m ON m.siblingid = a.siblingid where m.objectid = '$this->objectId' ";
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
						if (isset($sibling->pog_attribute_type[$fcv_array[$i][0]]) && $sibling->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $sibling->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
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
			$this->pog_query .= " order by m.siblingid asc $sqlLimit";
			$Database->Query($this->pog_query);
			foreach ($Database->query($this->pog_query) as $row)
			{
				$sibling = new sibling();
				$sibling->Get($row['siblingid']);
				$siblingList[] = $sibling;
			}
			if ($sortBy != '')
			{
				$f = '';
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
		return null;
	}
	
	
	/**
	* Associates the sibling object to this one
	* @return 
	*/
	function AddSibling(&$sibling)
	{
		if ($sibling instanceof sibling)
		{
			if (in_array($this, $sibling->objectList, true))
			{
				return false;
			}
			else
			{
				$found = false;
				foreach ($this->_siblingList as $sibling2)
				{
					if ($sibling->siblingId > 0 && $sibling->siblingId == $sibling2->siblingId)
					{
						$found = true;
						break;
					}
				}
				if (!$found)
				{
					$this->_siblingList[] = $sibling;
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