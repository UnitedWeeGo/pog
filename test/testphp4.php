<?
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `object` (
	`objectid` int(11) auto_increment,
	`var1` VARCHAR(255),
	`var2` VARCHAR(255),
	`var3` VARCHAR(255), PRIMARY KEY  (`objectid`));
*/

/**
* Object class with integrated CRUD methods.
* @author Php Object Generator
* @version 1.5 rev3
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=Object&attributeList=array+%28%0A++0+%3D%3E+%27var1%27%2C%0A++1+%3D%3E+%27var2%27%2C%0A++2+%3D%3E+%27var3%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
class Object
{
	var $objectId;
	var $var1;
	var $var2;
	var $var3;
	
	
	function Object($var1='', $var2='', $var3='')
	{
		$this->var1 = $var1;
		$this->var2 = $var2;
		$this->var3 = $var3;
	}
	
	
	/**
	* Gets object from database
	* @param integer $objectId 
	* @return object $Object
	*/
	function Get($objectId)
	{
		$Database = new DatabaseConnection();
		$query = "select * from `object` where `objectid`='".$objectId."' LIMIT 1";
		$Database->Query($query);
		$this->objectId = $Database->Result(0,"objectid");
		$this->var1 = $Database->Unescape($Database->Result(0,"var1"));
		$this->var2 = $Database->Unescape($Database->Result(0,"var2"));
		$this->var3 = $Database->Unescape($Database->Result(0,"var3"));
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param string $field 
	* @param string $comparator 
	* @param string $fieldValue 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @return array $objectList
	*/
	function GetObjectList($field,$comparator,$fieldValue,$sortBy="",$ascending=true)
	{
		
		$objectList = Array();
		$Database = new DatabaseConnection();
		$query = "select objectid from object where `".$field."`".$comparator."'".$Database->Escape($fieldValue)."'";
		$Database->Query($query);
		for ($i=0; $i < $Database->Rows(); $i++)
		{
			$object = new Object();
			$object->Get($Database->Result($i,"objectid"));
			$objectList[] = $object;
		}
		switch(strtolower($sortBy))
		{
			case strtolower("var1"):
				usort($objectList, array("Object","CompareObjectByvar1"));
				if (!$ascending)
				{
					$objectList = array_reverse($objectList);
				}
			break;
			case strtolower("var2"):
				usort($objectList, array("Object","CompareObjectByvar2"));
				if (!$ascending)
				{
					$objectList = array_reverse($objectList);
				}
			break;
			case strtolower("var3"):
				usort($objectList, array("Object","CompareObjectByvar3"));
				if (!$ascending)
				{
					$objectList = array_reverse($objectList);
				}
			break;
			case "":
			default:
			break;
		}
		return $objectList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $objectId
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$query = "select objectid from `object` where `objectid`='".$this->objectId."' LIMIT 1";
		$Database->Query($query);
		if ($Database->Rows() > 0)
		{
			$query = "update `object` set 
			`var1`='".$Database->Escape($this->var1)."', 
			`var2`='".$Database->Escape($this->var2)."', 
			`var3`='".$Database->Escape($this->var3)."' where `objectid`='".$this->objectId."'";
		}
		else
		{
			$query = "insert into `object` (`var1`, `var2`, `var3` ) values (
			'".$Database->Escape($this->var1)."', 
			'".$Database->Escape($this->var2)."', 
			'".$Database->Escape($this->var3)."' )";
		}
		$Database->InsertOrUpdate($query);
		if ($this->objectId == "")
		{
			$this->objectId = $Database->GetCurrentId();
		}
		return $this->objectId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $objectId
	*/
	function SaveNew()
	{
		$this->objectId='';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$query = "delete from `object` where `objectid`='".$this->objectId."'";
		return $Database->Query($query);
	}
	
	
	/**
	* private function to sort an array of Object by var1
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function CompareObjectByvar1($object1, $object2)
	{
		return strcmp(strtolower($object1->var1), strtolower($object2->var1));
	}
	
	
	/**
	* private function to sort an array of Object by var2
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function CompareObjectByvar2($object1, $object2)
	{
		return strcmp(strtolower($object1->var2), strtolower($object2->var2));
	}
	
	
	/**
	* private function to sort an array of Object by var3
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function CompareObjectByvar3($object1, $object2)
	{
		return strcmp(strtolower($object1->var3), strtolower($object2->var3));
	}
}
?>