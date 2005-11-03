<?
/*
	This SQL query will create the table to store your object.


	CREATE TABLE object (
	objectid INTEGER PRIMARY KEY,
	var1 INTEGER,
	var2 TEXT,
	var3 TEXT);
*/

/**
* Object class with integrated CRUD methods.
* @author Php Object Generator
* @version 1.5 rev1
* @see http://www.phpobjectgenerator.com/plog/sqlite
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=sqlite&objectName=Object&attributeList=YXJyYXkgKAogIDAgPT4gJ3ZhcjEnLAogIDEgPT4gJ3ZhcjInLAogIDIgPT4gJ3ZhcjMnLAop&typeList=YXJyYXkgKAogIDAgPT4gJ0lOVEVHRVInLAogIDEgPT4gJ1RFWFQnLAogIDIgPT4gJ1RFWFQnLAop
*/
class Object
{
	public $objectId;
	public $var1;
	public $var2;
	public $var3;
	
	
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
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$stmt = $Database->prepare("select * from object where objectid= ? LIMIT 1");
			if ($stmt->execute(array($objectId)))
			{
				while ($row = $stmt->fetch())
				{
				$this->objectId = $row['objectid'];
					$this->var1 = $row['var1'];
					$this->var2 = $row['var2'];
					$this->var3 = $row['var3'];
				}
			}
			return $this;
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
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
	static function GetObjectList($field,$comparator,$fieldValue,$sortBy="",$ascending=true,$optionalConditions="")
	{
		
		$objectList = Array();
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$sql = "select objectid from object where $field $comparator '$fieldValue'";
			foreach ($Database->query($sql) as $row)
			{
				$object = new Object();
				$object->Get($row['objectid']);
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
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	
	/**
	* Saves the object to the database
	* @return nothing
	*/
	function Save()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$count=0;
			$sql = "select count(objectid) as count from object where objectid = '$this->objectId'";
			foreach ($Database->query($sql) as $row)
			{
				$count=$row['count'];
			}
			if ($count == 1)
			{
				// update object
				$stmt = $Database->prepare("update object set var1=?,var2=?,var3=? where objectid=?");
				$stmt->bindParam(1, $this->var1);
				$stmt->bindParam(2, $this->var2);
				$stmt->bindParam(3, $this->var3);
				$stmt->bindParam(4, $this->objectId);
			}
			else
			{
				// insert object
				$stmt = $Database->prepare("insert into object (var1,var2,var3) values (?,?,?)");
				$stmt->bindParam(1, $this->var1);
				$stmt->bindParam(2, $this->var2);
				$stmt->bindParam(3, $this->var3);
			}
			$stmt->execute();
			if ($this->objectId == "")
			{
				$this->objectId = $Database->lastInsertId();
			}
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return nothing
	*/
	function SaveNew()
	{
		$this->objectId='';
		$this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return integer $affectedRows
	*/
	function Delete()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$affectedRows = $Database->query("delete from object where objectid='$this->objectId'");
			if ($affectedRows != null)
			{
				return $affectedRows;
			}
			else
			{
				return 0;
			}
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	
	/**
	* private function to sort an array of Object by var1
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareObjectByvar1($object1, $object2)
	{
		return strcmp(strtolower($object1->var1), strtolower($object2->var1));
	}
	
	
	/**
	* private function to sort an array of Object by var2
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareObjectByvar2($object1, $object2)
	{
		return strcmp(strtolower($object1->var2), strtolower($object2->var2));
	}
	
	
	/**
	* private function to sort an array of Object by var3
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareObjectByvar3($object1, $object2)
	{
		return strcmp(strtolower($object1->var3), strtolower($object2->var3));
	}
}
?>