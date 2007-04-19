<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `object` (
	`objectid` int(11) NOT NULL auto_increment,
	`attribute` VARCHAR(255) NOT NULL,
	`parent_id` int(11) NOT NULL,
	`attribute2` VARCHAR(255) NOT NULL, INDEX(`parent_id`), PRIMARY KEY  (`objectid`));
*/

/**
* <b>object</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0 / PHP5.1 MYSQL
* @see http://www.phpobjectgenerator.com/plog/tutorials/45/pdo-mysql
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=mysql&objectName=object&attributeList=array+%28%0A++0+%3D%3E+%27attribute%27%2C%0A++1+%3D%3E+%27child%27%2C%0A++2+%3D%3E+%27parent_%27%2C%0A++3+%3D%3E+%27sibling%27%2C%0A++4+%3D%3E+%27attribute2%27%2C%0A%29&typeList=array%2B%2528%250A%2B%2B0%2B%253D%253E%2B%2527VARCHAR%2528255%2529%2527%252C%250A%2B%2B1%2B%253D%253E%2B%2527HASMANY%2527%252C%250A%2B%2B2%2B%253D%253E%2B%2527BELONGSTO%2527%252C%250A%2B%2B3%2B%253D%253E%2B%2527JOIN%2527%252C%250A%2B%2B4%2B%253D%253E%2B%2527VARCHAR%2528255%2529%2527%252C%250A%2529
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

	/**
	 * @var VARCHAR(255)
	 */
	public $attribute2;

	public $pog_attribute_type = array(
		"objectId" => array("NUMERIC", "INT"),
		"attribute" => array("TEXT", "VARCHAR", "255"),
		"child" => array("OBJECT", "HASMANY"),
		"parent_" => array("OBJECT", "BELONGSTO"),
		"sibling" => array("OBJECT", "JOIN"),
		"attribute2" => array("TEXT", "VARCHAR", "255"),
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

	function object($attribute='', $attribute2='')
	{
		$this->attribute = $attribute;
		$this->_childList = array();
		$this->_siblingList = array();
		$this->attribute2 = $attribute2;
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
			$connection = Database::Connect();
			$this->pog_query = "select * from `object` where `objectid`= ? LIMIT 1";
			$stmt = $connection->prepare($this->pog_query);
			if ($stmt->execute(array($objectId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->objectId = $row['objectid'];
					$this->attribute = POG_Base::Unescape($row['attribute']);
					$this->parent_Id = $row['parent_id'];
					$this->attribute2 = POG_Base::Unescape($row['attribute2']);
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
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$pog_query = "select * from `object` ";
		try
		{
			$objectList = Array();
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
				$sortBy = "objectid";
			}
			$pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
			$thisObjectName = get_class($this);
			foreach ($connection->query($pog_query) as $row)
			{
				$object = new $thisObjectName();
				$object->objectId = $row['objectid'];
				$object->attribute = POG_Base::Unescape($row['attribute']);
				$object->parent_Id = $row['parent_id'];
				$object->attribute2 = POG_Base::Unescape($row['attribute2']);
				$objectList[] = $object;
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		return $objectList;
	}


	/**
	* Saves the object to the database
	* @return integer $objectId
	*/
	function Save($deep = true)
	{
		try
		{
			$connection = Database::Connect();
			$this->pog_query = "select count(`objectid`) as count from `object` where `objectid`='$this->objectId' limit 1";
			foreach ($connection->query($this->pog_query) as $row)
			{
				$rows = $row["count"];
				break;
			}
			if ($rows > 0)
			{
				// update object
				$this->pog_query = "update `object` set `attribute`=?, `parent_id`=?, `attribute2`=? where `objectid`=?";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, POG_Base::Escape($this->attribute));
				$stmt->bindParam(2, $this->parent_Id);
				$stmt->bindParam(3, POG_Base::Escape($this->attribute2));
				$stmt->bindParam(4, $this->objectId);
			}
			else
			{
				// insert object
				$this->pog_query = "insert into `object` (`attribute`, `parent_id`, `attribute2`) values (?, ?, ?)";
				$stmt = $connection->prepare($this->pog_query);
				$stmt->bindParam(1, POG_Base::Escape($this->attribute));
				$stmt->bindParam(2, $this->parent_Id);
				$stmt->bindParam(3, POG_Base::Escape($this->attribute2));
			}
			$stmt->execute();
			if ($this->objectId == "")
			{
				$this->objectId = $connection->lastInsertId();
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
	function Delete($deep = false, $across = false)
	{
		try
		{
			if ($deep)
			{
				$childList = $this->GetChildList();
				foreach ($childList as $child)
				{
					$child->Delete($deep, $across);
				}
			}
			if ($across)
			{
				$siblingList = $this->GetSiblingList();
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
				foreach ($siblingList as $sibling)
				{
					$sibling->Delete($deep, $across);
				}
			}
			else
			{
				$map = new objectsiblingMap();
				$map->RemoveMapping($this);
			}
			$connection = Database::Connect();
			$this->pog_query = "delete from `object` where `objectid` = '$this->objectId'";
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
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		try
		{
			$connection = Database::Connect();
			$sibling = new sibling();
			$siblingList = Array();
			$this->pog_query = "select distinct * from `sibling` a INNER JOIN `objectsiblingmap` m ON m.siblingid = a.siblingid where m.objectid = '$this->objectId' ";
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
				if (isset($sibling->pog_attribute_type[$sortBy]) && $sibling->pog_attribute_type[$sortBy][0] != 'NUMERIC' && $sibling->pog_attribute_type[$sortBy][0] != 'SET')
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
				$sortBy = "a.siblingid";
			}
			$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
			$connection->Query($this->pog_query);
			foreach ($connection->query($this->pog_query) as $row)
			{
				$sibling = new sibling();
				foreach ($sibling->pog_attribute_type as $attribute_name => $attrubute_type)
				{
					if ($attrubute_type[1] != "HASMANY" && $attrubute_type[1] != "JOIN")
					{
						if ($attrubute_type[1] == "BELONGSTO")
						{
							$sibling->{strtolower($attribute_name).'Id'} = $row[strtolower($attribute_name).'id'];
							continue;
						}
						$sibling->{$attribute_name} = POG_Base::Unescape($row[strtolower($attribute_name)]);
					}
				}
				$siblingList[] = $sibling;
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
}
?>