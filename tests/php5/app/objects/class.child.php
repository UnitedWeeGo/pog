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
* @version POG 2.6 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=child&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27BELONGSTO%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
class child
{
	public $childId = '';

	/**
	 * @var INT(11)
	 */
	public $objectId;
	
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
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `child` where `childid`='".intval($childId)."' LIMIT 1";
		$Database->Query($this->pog_query);
		$this->childId = $Database->Result(0, "childid");
		$this->objectId = $Database->Result(0, "objectid");
		$this->attribute = $Database->Unescape($Database->Result(0, "attribute"));
		return $this;
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
			$Database = new DatabaseConnection();
			$pog_query = "select childid from `child` where ";
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
			$pog_query .= " order by childid asc $sqlLimit";
			$Database->Query($pog_query);
			$thisObjectName = get_class($this);
			for($i=0; $i < $Database->Rows(); $i++)
			{
				$child = new $thisObjectName();
				$child->Get($Database->Result($i, "childid"));
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
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $childId
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `childid` from `child` where `childid`='".$this->childId."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `child` set 
			`objectid`='".$this->objectId."', 
			`attribute`='".$Database->Escape($this->attribute)."' where `childid`='".$this->childId."'";
		}
		else
		{
			$this->pog_query = "insert into `child` (`objectid`, `attribute` ) values (
			'".$this->objectId."', 
			'".$Database->Escape($this->attribute)."' )";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->childId == "")
		{
			$this->childId = $Database->GetCurrentId();
		}
		return $this->childId;
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
	* @return boolean
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `child` where `childid`='".$this->childId."'";
		return $Database->Query($this->pog_query);
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
			$Database = new DatabaseConnection();
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