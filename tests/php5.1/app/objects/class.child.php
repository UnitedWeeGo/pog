<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `child` (
	`childid` int(11) NOT NULL auto_increment,
	`objectid` int(11) NOT NULL,
	`attribute` VARCHAR(255) NOT NULL, INDEX(`objectid`), PRIMARY KEY  (`childid`));
*/

/**
* <b>child</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP5.1 MYSQL
* @see http://www.phpobjectgenerator.com/plog/tutorials/45/pdo-mysql
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=mysql&objectName=child&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array%2B%2528%250A%2B%2B0%2B%253D%253E%2B%2527BELONGSTO%2527%252C%250A%2B%2B1%2B%253D%253E%2B%2527VARCHAR%2528255%2529%2527%252C%250A%2529
*/
class child
{
	public $childId = '';

	/**
	 * @var INT(11)
	 */
	public $objectId = '';
	
	/**
	 * @var VARCHAR(255)
	 */
	public $attribute;
	
	public $pog_attribute_type = array(
		"childId" => array("NUMERIC", "INT"),
		"object" => array("OBJECT", "BELONGSTO"),
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
	
	function child($attribute='')
	{
		$this->attribute = $attribute;
	}
	
	
	/**
	* Gets object from database
	* @param integer $childId 
	* @return object $child
	*/

	function Get($childId)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select * from `child` where `childid`= ? LIMIT 1";
			$stmt = $Database->prepare($this->pog_query);
			if ($stmt->execute(array($childId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->childId = $row['childid'];
					$this->objectId = $row['objectid'];
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
	* @return array $childList
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$childList = Array();
			try
			{
				$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
				$pog_query = "select `childid` from `child` where ";
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
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".child::Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
				$pog_query .= " order by childid asc $sqlLimit";
				$thisObjectName = get_class($this);
				foreach ($Database->query($pog_query) as $row)
				{
					$child = new $thisObjectName();
					$child->Get($row['childid']);
					$childList[] = $child;
				}
				if ($sortBy != '')
				{
					$f = '';
					$child = new $thisObjectName();
					if (isset($child->pog_attribute_type[$sortBy]) && ($child->pog_attribute_type[$sortBy][0] == "NUMERIC" || $child->pog_attribute_type[$sortBy][0] == "SET"))
					{
						$f = 'return $child1->'.$sortBy.' > $child2->'.$sortBy.';';
					}
					else if (isset($child->pog_attribute_type[$sortBy]))
					{
						$f = 'return strcmp(strtolower($child1->'.$sortBy.'), strtolower($child2->'.$sortBy.'));';
					}
					usort($childList, create_function('$child1, $child2', $f));
					if (!$ascending)
					{
						$childList = array_reverse($childList);
					}
					if ($limit != '')
					{
						$limitParts = explode(',', $limit);
						if (sizeof($limitParts) > 1)
						{
							return array_slice($childList, $limitParts[0], $limitParts[1]);
						}
						else
						{
							return array_slice($childList, 0, $limit);
						}
					}
				}
				return $childList;
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
	* @return integer $childId
	*/
	function Save()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "select count(`childid`) as count from `child` where `childid`='$this->childId' limit 1";
			foreach ($Database->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `child` set `objectid`=?, `attribute`=? where `childid`=?";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->objectId);
				$stmt->bindParam(2, $this->Escape($this->attribute));
				$stmt->bindParam(3, $this->childId);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `child` (`objectid`, `attribute`) values (?, ?)";
				$stmt = $Database->prepare($this->pog_query);
				$stmt->bindParam(1, $this->objectId);
				$stmt->bindParam(2, $this->Escape($this->attribute));
			}
			$stmt->execute();
			if ($this->childId == "")
			{
				$this->childId = $Database->lastInsertId();
			}
			return $this->childId;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $childId
	*/
	function SaveNew()
	{
		$this->childId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return integer $affectedRows
	*/
	function Delete()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$this->pog_query = "delete from `child` where `childid` = '$this->childId'";
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
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			try
			{
				$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';port='.$GLOBALS['configuration']['port'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
				$pog_query = "delete from `child` where ";
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
	
	
	/**
	* Associates the object object to this one
	* @return boolean
	*/
	function GetObject()
	{
		$object = new object();
		return $object->Get($this->objectId);
	}
	
	
	/**
	* Associates the object object to this one
	* @return 
	*/
	function SetObject(&$object)
	{
		$this->objectId = $object->objectId;
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