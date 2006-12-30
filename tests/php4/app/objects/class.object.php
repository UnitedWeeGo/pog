<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `object` (
	`objectid` int(11) NOT NULL auto_increment,
	`parent_id` int(11) NOT NULL,
	`attribute` VARCHAR(255) NOT NULL, INDEX(`parent_id`), PRIMARY KEY  (`objectid`));
*/

/**
* <b>object</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=object&attributeList=array+%28%0A++0+%3D%3E+%27child%27%2C%0A++1+%3D%3E+%27parent_%27%2C%0A++2+%3D%3E+%27attribute%27%2C%0A++3+%3D%3E+%27sibling%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27HASMANY%27%2C%0A++1+%3D%3E+%27BELONGSTO%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27JOIN%27%2C%0A%29
*/
include_once('class.objectsiblingmap.php');
class object
{
	var $objectId = '';

	/**
	 * @var private array of child objects
	 */
	var $_childList;
	
	/**
	 * @var INT(11)
	 */
	var $parent_Id;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $attribute;
	
	/**
	 * @var private array of sibling objects
	 */
	var $_siblingList;
	
	var $pog_attribute_type = array(
		"objectId" => array("NUMERIC", "INT"),
		"child" => array("OBJECT", "HASMANY"),
		"parent_" => array("OBJECT", "BELONGSTO"),
		"attribute" => array("TEXT", "VARCHAR", "255"),
		"sibling" => array("OBJECT", "JOIN"),
		);
	var $pog_query;
	
	function object($attribute='')
	{
		$this->_childList = array();
		$this->attribute = $attribute;
		$this->_siblingList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $objectId 
	* @return object $object
	*/
	function Get($objectId)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `object` where `objectid`='".intval($objectId)."' LIMIT 1";
		$Database->Query($this->pog_query);
		$this->objectId = $Database->Result(0, "objectid");
		$this->parent_Id = $Database->Result(0, "parent_id");
		$this->attribute = $Database->Unescape($Database->Result(0, "attribute"));
		return $this;
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
			$Database = new DatabaseConnection();
			$this->pog_query = "select objectid from `object` where ";
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
			$this->pog_query .= " order by objectid asc $sqlLimit";
			$Database->Query($this->pog_query);
			$thisObjectName = get_class($this);
			for ($i=0; $i < $Database->Rows(); $i++)
			{
				$object = new $thisObjectName();
				$object->Get($Database->Result($i, "objectid"));
				$objectList[] = $object;
			}
			if ($sortBy != '')
			{
				$f = '';
				$object = new object();
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
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $objectId
	*/
	function Save($deep = true)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `objectid` from `object` where `objectid`='".$this->objectId."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `object` set 
			`parent_id`='".$this->parent_Id."', 
			`attribute`='".$Database->Escape($this->attribute)."'where `objectid`='".$this->objectId."'";
		}
		else
		{
			$this->pog_query = "insert into `object` (`parent_id`, `attribute`) values (
			'".$this->parent_Id."', 
			'".$Database->Escape($this->attribute)."')";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->objectId == "")
		{
			$this->objectId = $Database->GetCurrentId();
		}
		if ($deep)
		{
			foreach (array_keys($this->_childList) as $key)
			{
				$child =& $this->_childList[$key];
				$child->objectId = $this->objectId;
				$child->Save($deep);
			}
			foreach (array_keys($this->_siblingList) as $key)
			{
				$sibling =& $this->_siblingList[$key];
				$sibling->Save();
				$map = new objectsiblingMap();
				$map->AddMapping($this, $sibling);
			}
		}
		return $this->objectId;
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
	* @return boolean
	*/
	function Delete($deep = false)
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
			$map->RemoveMapping($this, null);
			foreach ($siblingList as $sibling)
			{
				$sibling->Delete($deep);
			}
		}
		else
		{
			$map = new objectsiblingMap();
			$map->RemoveMapping($this, null);
		}
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `object` where `objectid`='".$this->objectId."'";
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
		$this->_childList =& $list;
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
			$this->_childList[] =& $child;
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
		$map->RemoveMapping($this, null);
		$this->_siblingList =& $siblingList;
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
		$Database = new DatabaseConnection();
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
						$this->pog_query .= "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
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
		for($i=0; $i < $Database->Rows(); $i++)
		{
			$sibling = new sibling();
			$sibling->Get($Database->Result($i, "siblingid"));
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
	
	
	/**
	* Associates the sibling object to this one
	* @return 
	*/
	function AddSibling(&$sibling)
	{
		if (is_a($sibling, "sibling"))
		{
			foreach (array_keys($sibling->_objectList) as $key)
			{
				$otherObject =& $sibling->_objectList[$key];
				if ($otherObject === $this)
				{
					return false;
				}
			}
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
				$this->_siblingList[] =& $sibling;
			}
		}
	}
}
?>