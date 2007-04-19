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
* @version POG 3.0 / PHP5.1 MYSQL
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
			$connection = Database::Connect();
			$this->pog_query = "select * from `parent_` where `parent_id`= ? LIMIT 1";
			$stmt = $connection->prepare($this->pog_query);
			if ($stmt->execute(array($parent_Id)))
			{
				while ($row = $stmt->fetch())
				{
					$this->parent_Id = $row['parent_id'];
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
	* @return array $parent_List
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$pog_query = "select * from `parent_` ";
		try
		{
			$parent_List = Array();
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
				$sortBy = "parent_id";
			}
			$pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
			$thisObjectName = get_class($this);
			foreach ($connection->query($pog_query) as $row)
			{
				$parent_ = new $thisObjectName();
				$parent_->parent_Id = $row['parent_id'];
				$parent_->attribute = POG_Base::Unescape($row['attribute']);
				$parent_List[] = $parent_;
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		return $parent_List;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $parent_Id
	*/
	function Save($deep = true)
	{
		try
		{
			$connection = Database::Connect();
			$this->pog_query = "select count(`parent_id`) as count from `parent_` where `parent_id`='$this->parent_Id' limit 1";
			foreach ($connection->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `parent_` set `attribute`=? where `parent_id`=?";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, POG_Base::Escape($this->attribute));
				$stmt->bindParam(2, $this->parent_Id);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `parent_` (`attribute`) values (?)";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, POG_Base::Escape($this->attribute));
			}
			$stmt->execute();
			if ($this->parent_Id == "")
			{
				$this->parent_Id = $connection->lastInsertId();
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
	function Delete($deep = false, $across = false)
	{
		try
		{
			if ($deep)
			{
				$objectList = $this->GetObjectList();
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			$connection = Database::Connect();
			$this->pog_query = "delete from `parent_` where `parent_id` = '$this->parent_Id'";
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
}
?>