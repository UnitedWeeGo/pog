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
* @version POG 2.6 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=parent_&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27HASMANY%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
class parent_
{
	public $parent_Id = '';

	/**
	 * @var private array of object objects
	 */
	private $_objectList = array();
	
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
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `parent_` where `parent_id`='".intval($parent_Id)."' LIMIT 1";
		$Database->Query($this->pog_query);
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
	* @return array $parent_List
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$parent_List = Array();
			$Database = new DatabaseConnection();
			$pog_query = "select parent_id from `parent_` where ";
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
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			$pog_query .= " order by parent_id asc $sqlLimit";
			$Database->Query($pog_query);
			$thisObjectName = get_class($this);
			for($i=0; $i < $Database->Rows(); $i++)
			{
				$parent_ = new $thisObjectName();
				$parent_->Get($Database->Result($i, "parent_id"));
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
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $parent_Id
	*/
	function Save($deep = true)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `parent_id` from `parent_` where `parent_id`='".$this->parent_Id."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `parent_` set 
			`attribute`='".$Database->Escape($this->attribute)."' where `parent_id`='".$this->parent_Id."'";
		}
		else
		{
			$this->pog_query = "insert into `parent_` (`attribute` ) values (
			'".$Database->Escape($this->attribute)."' )";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->parent_Id == "")
		{
			$this->parent_Id = $Database->GetCurrentId();
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
	* @return boolean
	*/
	function Delete($deep = false)
	{
		if ($deep)
		{
			$objectList = $this->GetObjectList();
			foreach ($objectList as $object)
			{
				$object->Delete($deep);
			}
		}
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `parent_` where `parent_id`='".$this->parent_Id."'";
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