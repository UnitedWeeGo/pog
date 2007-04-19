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
* @version POG 3.0 / PHP5.1 MYSQL
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
			$connection = Database::Connect();
			$this->pog_query = "select * from `child` where `childid`= ? LIMIT 1";
			$stmt = $connection->prepare($this->pog_query);
			if ($stmt->execute(array($childId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->childId = $row['childid'];
					$this->objectId = $row['objectid'];
					$this->attribute = POG_Base::Unescape($row['attribute']);
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
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$pog_query = "select * from `child` ";
		try
		{
			$childList = Array();
			$pog_query .= " where ";
			if (sizeof($fcv_array) > 0)
			{
				$connection = Database::Connect();
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
							if ($GLOBALS['configuration']['db_encoding'] == 1)
							{
								$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
								$pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
							}
							else
							{
								$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".POG_Base::Escape($fcv_array[$i][2])."'";
								$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
							}
						}
						else
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
							$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
				}
			}
			if ($sortBy != '')
			{
				if (isset($this->pog_attribute_type[$sortBy]) && $this->pog_attribute_type[$sortBy][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy][0] != 'SET')
				{
					if ($GLOBALS['configuration']['db_encoding'] == 1)
					{
						$sortBy = "BASE64_DECODE($sortBy) ";
					}
					else
					{
						$sortBy = "$sortBy ";
					}
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "childid";
			}
			$pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
			$thisObjectName = get_class($this);
			foreach ($connection->query($pog_query) as $row)
			{
				$child = new $thisObjectName();
				$child->childId = $row['childid'];
				$child->objectId = $row['objectid'];
				$child->attribute = POG_Base::Unescape($row['attribute']);
				$childList[] = $child;
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		return $childList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $childId
	*/
	function Save()
	{
		try
		{
			$connection = Database::Connect();
			$this->pog_query = "select count(`childid`) as count from `child` where `childid`='$this->childId' limit 1";
			foreach ($connection->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `child` set `objectid`=?, `attribute`=? where `childid`=?";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, $this->objectId);
				$stmt->bindParam(2, POG_Base::Escape($this->attribute));
				$stmt->bindParam(3, $this->childId);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `child` (`objectid`, `attribute`) values (?, ?)";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, $this->objectId);
				$stmt->bindParam(2, POG_Base::Escape($this->attribute));
			}
			$stmt->execute();
			if ($this->childId == "")
			{
				$this->childId = $connection->lastInsertId();
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
			$connection = Database::Connect();
			$this->pog_query = "delete from `child` where `childid` = '$this->childId'";
			$affectedRows = $connection->query($this->pog_query);
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
				$connection = Database::Connect();
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
							$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".POG_Base::Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
				return $connection->Query($pog_query);
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
}
?>