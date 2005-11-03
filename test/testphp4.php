<?
/*
	These 3 SQL queries will:
		- create a generator for your table.
		- create the table to store your object
		- create a trigger to autoincrement the object id

	CREATE GENERATOR GEN_PK_OBJECT
	CREATE TABLE OBJECT (
	OBJECTID INTEGER,
	VAR1 VARCHAR(255),
	VAR2 VARCHAR(255),
	VAR3 VARCHAR(255));

	CREATE TRIGGER BI_OBJECT FOR OBJECT
	ACTIVE BEFORE INSERT
	AS
	BEGIN
		IF(NEW.OBJECTID IS NULL) THEN
			NEW.OBJECTID = GEN_ID(GEN_PK_OBJECT, 1);
	END
*/

/**
* Object class with integrated CRUD methods.
* @author Php Object Generator
* @version 1.5 rev1
* @see http://www.phpobjectgenerator.com/plog/firebird
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=firebird&objectName=Object&attributeList=YXJyYXkgKAogIDAgPT4gJ3ZhcjEnLAogIDEgPT4gJ3ZhcjInLAogIDIgPT4gJ3ZhcjMnLAop&typeList=YXJyYXkgKAogIDAgPT4gJ1ZBUkNIQVIoMjU1KScsCiAgMSA9PiAnVkFSQ0hBUigyNTUpJywKICAyID0+ICdWQVJDSEFSKDI1NSknLAop
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
	* @return object $object
	*/

	function Get($objectId)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$sql = "select * from object where objectid='$objectId'";
			foreach ($Database->query($sql) as $row)
			{
				$this->objectId = $row['OBJECTID'];
				$this->var1 = $row['VAR1'];
				$this->var2 = $row['VAR2'];
				$this->var3 = $row['VAR3'];
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
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$sql = "select objectid from object where $field $comparator '$fieldValue'";
			foreach ($Database->query($sql) as $row)
			{
				$object = new Object();
				$object->Get($row['OBJECTID']);
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
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$Database->beginTransaction();
			$count=0;
			$sql = "select count(objectid) from object where objectid = '$this->objectId'";
			foreach ($Database->query($sql) as $row)
			{
				$count=$row['COUNT'];
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
				$stmt = $Database->prepare("insert into object (var1, var2, var3) values ( ?, ?, ?)");
				$stmt->bindParam(1, $this->var1);
				$stmt->bindParam(2, $this->var2);
				$stmt->bindParam(3, $this->var3);
			}
			$stmt->execute();
			if ($this->objectId == "")
			{
				$sql = ("select max(objectid) from object");
				foreach ($Database->query($sql) as $row)
				{
					$this->objectId = $row['MAX'];
				}
			}
			$Database->commit();
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
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
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