<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `parent_` (
	`parent_id` int(11) NOT NULL auto_increment,
	`attribute` VARCHAR(255) NOT NULL, PRIMARY KEY  (`parent_id`));
*/

/**
* <b>parent_</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP5.1 MYSQL
* @see http://www.phpobjectgenerator.com/plog/tutorials/45/pdo-mysql
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=mysql&objectName=parent_&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array%2B%2528%250A%2B%2B0%2B%253D%253E%2B%2527HASMANY%2527%252C%250A%2B%2B1%2B%253D%253E%2B%2527VARCHAR%2528255%2529%2527%252C%250A%2529
*/
class parent_
{
	public $parent_Id = '';

	/**
	 * @var private array of object objects
	 */
	private $_objectList;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $attribute;
	
	public $pog_attribute_type = array(
		"parent_Id" => array("NUMERIC", "INT"),
		"object" => array("OBJECT", "HASMANY"),
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
	
	function parent_($attribute='')
	{
		$this->_objectList = array();
		$this->attribute = $attribute;
	}
	
	
	/**
	* Gets object from database
	* @param integer $parent_Id 
	* @return object $parent_
	*/

	function Get($parent_Id)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select * from `parent_` where `parent_id`= ? LIMIT 1";
			$stmt = $Database->prepare($this->pog_query);
			if ($stmt->execute(array($parent_Id)))
			{
				while ($row = $stmt->fetch())
				{
					$this->parent_Id = $row['parent_id'];
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
	* @return array $parent_List
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$parent_List = Array();
			try
			{
				$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
				$pog_query = "select `parent_id` from `parent_` where ";
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
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".parent_::Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
				$pog_query .= " order by parent_id asc $sqlLimit";
				$thisObjectName = get_class($this);
				foreach ($Database->query($pog_query) as $row)
				{
					$parent_ = new $thisObjectName();
					$parent_->Get($row['parent_id']);
					$parent_List[] = $parent_;
				}
				if ($sortBy != '')
				{
					$f = '';
					$parent_ = new $thisObjectName();
					if (isset($parent_->pog_attribute_type[$sortBy]) && ($parent_->pog_attribute_type[$sortBy][0] == "NUMERIC" || $parent_->pog_attribute_type[$sortBy][0] == "SET"))
					{
						$f = 'return $parent_1->'.$sortBy.' > $parent_2->'.$sortBy.';';
					}
					else if (isset($parent_->pog_attribute_type[$sortBy]))
					{
						$f = 'return strcmp(strtolower($parent_1->'.$sortBy.'), strtolower($parent_2->'.$sortBy.'));';
					}
					usort($parent_List, create_function('$parent_1, $parent_2', $f));
					if (!$ascending)
					{
						$parent_List = array_reverse($parent_List);
					}
					if ($limit != '')
					{
						$limitParts = explode(',', $limit);
						if (sizeof($limitParts) > 1)
						{
							return array_slice($parent_List, $limitParts[0], $limitParts[1]);
						}
						else
						{
							return array_slice($parent_List, 0, $limit);
						}
					}
				}
				return $parent_List;
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
	* @return integer $parent_Id
	*/
	function Save($deep = true)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select count(`parent_id`) as count from `parent_` where `parent_id`='$this->parent_Id' limit 1";
			foreach ($Database->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `parent_` set `attribute`=? where `parent_id`=?";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->Escape($this->attribute));
				$stmt->bindParam(2, $this->parent_Id);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `parent_` (`attribute`) values (?)";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->Escape($this->attribute));
			}
			$stmt->execute();
			if ($this->parent_Id == "")
			{
				$this->parent_Id = $Database->lastInsertId();
			}
			if ($deep)
			{
				foreach ($this->_objectList as $object)
				{
					$object->parent_Id = $this->parent_Id;
					$object->Save($deep);
				}
			}
			return $this->parent_Id;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $parent_Id
	*/
	function SaveNew($deep = false)
	{
		$this->parent_Id = '';
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
				foreach ($objectList as $object)
				{
					$object->Delete($deep);
				}
			}
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "delete from `parent_` where `parent_id` = '$this->parent_Id'";
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
					$pog_query = "delete from `parent_` where ";
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
	* Gets a list of object objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of object objects
	*/
	function GetObjectList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$object = new object();
		$fcv_array[] = array("parent_Id", "=", $this->parent_Id);
		$dbObjects = $object->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all object objects in the object List array. Any existing object will become orphan(s)
	* @return null
	*/
	function SetObjectList(&$list)
	{
		$this->_objectList = array();
		$existingObjectList = $this->GetObjectList();
		foreach ($existingObjectList as $object)
		{
			$object->parent_Id = '';
			$object->Save(false);
		}
		$this->_objectList = $list;
	}
	
	
	/**
	* Associates the object object to this one
	* @return 
	*/
	function AddObject(&$object)
	{
		$object->parent_Id = $this->parent_Id;
		$found = false;
		foreach($this->_objectList as $object2)
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