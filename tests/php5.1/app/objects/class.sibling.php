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
* @version POG 3.0 / PHP5.1 MYSQL
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
			$connection = Database::Connect();
			$this->pog_query = "select * from `sibling` where `siblingid`= ? LIMIT 1";
			$stmt = $connection->prepare($this->pog_query);
			if ($stmt->execute(array($siblingId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->siblingId = $row['siblingid'];
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
	* @return array $siblingList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$pog_query = "select * from `sibling` ";
		try
		{
			$siblingList = Array();
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
				$sortBy = "siblingid";
			}
			$pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
			$thisObjectName = get_class($this);
			foreach ($connection->query($pog_query) as $row)
			{
				$sibling = new $thisObjectName();
				$sibling->siblingId = $row['siblingid'];
				$sibling->attribute = POG_Base::Unescape($row['attribute']);
				$siblingList[] = $sibling;
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		return $siblingList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $siblingId
	*/
	function Save($deep = true)
	{
		try
		{
			$connection = Database::Connect();
			$this->pog_query = "select count(`siblingid`) as count from `sibling` where `siblingid`='$this->siblingId' limit 1";
			foreach ($connection->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `sibling` set `attribute`=? where `siblingid`=?";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, POG_Base::Escape($this->attribute));
				$stmt->bindParam(2, $this->siblingId);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `sibling` (`attribute`) values (?)";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, POG_Base::Escape($this->attribute));
			}
			$stmt->execute();
			if ($this->siblingId == "")
			{
				$this->siblingId = $connection->lastInsertId();
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
	function Delete($deep = false, $across = false)
	{
		try
		{
			if ($deep)
			{
			}
			if ($across)
			{
				$objectList = $this->GetObjectList();
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			else
			{
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
			}
			$connection = Database::Connect();
			$this->pog_query = "delete from `sibling` where `siblingid` = '$this->siblingId'";
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
	function DeleteList($fcv_array, $deep = false, $across = false)
	{
		if (sizeof($fcv_array) > 0)
		{
			if ($deep || $across)
			{
				$objectList = $this->GetList($fcv_array);
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			else
			{
				try
				{
					$connection = Database::Connect();
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
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		try
		{
			$connection = Database::Connect();
			$object = new object();
			$objectList = Array();
			$this->pog_query = "select distinct * from `object` a INNER JOIN `objectsiblingmap` m ON m.objectid = a.objectid where m.siblingid = '$this->siblingId' ";
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
							if ($GLOBALS['configuration']['db_encoding'] == 1)
							{
								$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
								$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
							}
							else
							{
								$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".POG_Base::Escape($fcv_array[$i][2])."'";
								$this->pog_query .= "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
							}
						}
						else
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
				}
			}
			if ($sortBy != '')
			{
				if (isset($object->pog_attribute_type[$sortBy]) && $object->pog_attribute_type[$sortBy][0] != 'NUMERIC' && $object->pog_attribute_type[$sortBy][0] != 'SET')
				{
					if ($GLOBALS['configuration']['db_encoding'] == 1)
					{
						$sortBy = "BASE64_DECODE(a.$sortBy) ";
					}
					else
					{
						$sortBy = "a.$sortBy ";
					}
				}
				else
				{
					$sortBy = "a.$sortBy ";
				}
			}
			else
			{
				$sortBy = "a.objectid";
			}
			$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
			$connection->Query($this->pog_query);
			foreach ($connection->query($this->pog_query) as $row)
			{
				$object = new object();
				foreach ($object->pog_attribute_type as $attribute_name => $attrubute_type)
				{
					if ($attrubute_type[1] != "HASMANY" && $attrubute_type[1] != "JOIN")
					{
						if ($attrubute_type[1] == "BELONGSTO")
						{
							$object->{strtolower($attribute_name).'Id'} = $row[strtolower($attribute_name).'id'];
							continue;
						}
						$object->{$attribute_name} = POG_Base::Unescape($row[strtolower($attribute_name)]);
					}
				}
				$objectList[] = $object;
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
}
?>