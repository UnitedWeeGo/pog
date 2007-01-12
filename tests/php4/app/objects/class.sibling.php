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
* @version POG 2.6.1 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=sibling&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27JOIN%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.objectsiblingmap.php');
class sibling
{
	var $siblingId = '';

	/**
	 * @var private array of object objects
	 */
	var $_objectList;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $attribute;
	
	var $pog_attribute_type = array(
		"siblingId" => array("NUMERIC", "INT"),
		"object" => array("OBJECT", "JOIN"),
		"attribute" => array("TEXT", "VARCHAR", "255"),
		);
	var $pog_query;
	
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
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `sibling` where `siblingid`='".intval($siblingId)."' LIMIT 1";
		$Database->Query($this->pog_query);
		$this->siblingId = $Database->Result(0, "siblingid");
		$this->attribute = $Database->Unescape($Database->Result(0, "attribute"));
		return $this;
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
			$Database = new DatabaseConnection();
			$this->pog_query = "select siblingid from `sibling` where ";
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
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]) && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
					{
						$this->pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$this->pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			$this->pog_query .= " order by siblingid asc $sqlLimit";
			$Database->Query($this->pog_query);
			$thisObjectName = get_class($this);
			for ($i=0; $i < $Database->Rows(); $i++)
			{
				$sibling = new $thisObjectName();
				$sibling->Get($Database->Result($i, "siblingid"));
				$siblingList[] = $sibling;
			}
			if ($sortBy != '')
			{
				$f = '';
				$sibling = new sibling();
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
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $siblingId
	*/
	function Save($deep = true)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `siblingid` from `sibling` where `siblingid`='".$this->siblingId."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `sibling` set 
			`attribute`='".$Database->Escape($this->attribute)."' where `siblingid`='".$this->siblingId."'";
		}
		else
		{
			$this->pog_query = "insert into `sibling` (`attribute` ) values (
			'".$Database->Escape($this->attribute)."' )";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->siblingId == "")
		{
			$this->siblingId = $Database->GetCurrentId();
		}
		if ($deep)
		{
			foreach (array_keys($this->_objectList) as $key)
			{
				$object =& $this->_objectList[$key];
				$object->Save();
				$map = new objectsiblingMap();
				$map->AddMapping($this, $object);
			}
		}
		return $this->siblingId;
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
	* @return boolean
	*/
	function Delete($deep = false)
	{
		if ($deep)
		{
			$objectList = $this->GetObjectList();
			$map = new objectsiblingMap();
			$map->RemoveMapping($this, null);
			foreach ($objectList as $object)
			{
				$object->Delete($deep);
			}
		}
		else
		{
			$map = new objectsiblingMap();
			$map->RemoveMapping($this, null);
		}
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `sibling` where `siblingid`='".$this->siblingId."'";
		return $Database->Query($this->pog_query);
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
				$Database = new DatabaseConnection();
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
							$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
						}
						else
						{
							$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
						}
					}
				}
				return $Database->Query($pog_query);
			}
		}
	}
	
	
	/**
	* Creates mappings between this and all objects in the object List array. Any existing mapping will become orphan(s)
	* @return null
	*/
	function SetObjectList($objectList)
	{
		$map = new objectsiblingMap();
		$map->RemoveMapping($this, null);
		$this->_objectList =& $objectList;
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
		$Database = new DatabaseConnection();
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
						$this->pog_query .= "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
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
		for($i=0; $i < $Database->Rows(); $i++)
		{
			$object = new object();
			$object->Get($Database->Result($i, "objectid"));
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
	
	
	/**
	* Associates the object object to this one
	* @return 
	*/
	function AddObject($object)
	{
		if (is_a($object, "object"))
		{
			foreach (array_keys($object->_siblingList) as $key)
			{
				$otherSibling =& $object->_siblingList[$key];
				if ($otherSibling === $this)
				{
					return false;
				}
			}
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
				$this->_objectList[] =& $object;
			}
		}
	}
}
?>