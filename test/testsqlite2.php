<?
//	POG 1.0 rev23 (http://www.phpobjectgenerator.com)
//	Feel free to use the code for personal & commercial purposes. (Offered under the BSD license)

//	This SQL query will create the table to store your object.
/*
CREATE TABLE myobject (myobjectid INTEGER PRIMARY KEY, var1 NUMERIC, var2 TEXT, var3 TEXT);
*/

//	This URL will repopulate the object & attributes for you.
/*
	http://phpobjectgenerator.com/?language=php5&wrapper=pdo&pdoDriver=sqlite&objectName=MyObject&attributeList=YXJyYXkgKAogIDAgPT4gJ3ZhcjEnLAogIDEgPT4gJ3ZhcjInLAogIDIgPT4gJ3ZhcjMnLAop&typeList=YXJyYXkgKAogIDAgPT4gJ05VTUVSSUMnLAogIDEgPT4gJ1RFWFQnLAogIDIgPT4gJ1RFWFQnLAop
*/
class MyObject
{
	public $myobjectId;
	public $var1;
	public $var2;
	public $var3;
	
	
	function MyObject($var1='', $var2='', $var3='')
	{
		$this->var1 = $var1;
		$this->var2 = $var2;
		$this->var3 = $var3;
	}
	
	
	/**
	* Gets object from database
	* @param integer $myobjectId 
	* @return object $MyObject
	*/

	function Get($myobjectId)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$stmt = $Database->prepare("select * from myobject where myobjectid= ? LIMIT 1");
			if ($stmt->execute(array($myobjectId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->myobjectId = $row['myobjectid'];
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
	* @return array $myobjectList
	*/
	static function GetMyObjectList($field,$comparator,$fieldValue,$sortBy="",$ascending=true,$optionalConditions="")
	{
		
		$myobjectList = Array();
		try
		{
			$Database = new PDO('sqlite:test.db');
			$sql = ("select myobjectid from myobject where $field $comparator '$fieldValue'");
			foreach ($Database->query($sql) as $row)
			{
				$myobject = new MyObject();
				$myobject->Get($row['myobjectid']);
				$myobjectList[] = $myobject;
			}
			switch(strtolower($sortBy))
			{
				case strtolower("var1"):
					usort($myobjectList, array("MyObject","CompareMyObjectByvar1"));
				if (!$ascending)
					{
						$myobjectList = array_reverse($myobjectList);
					}
				break;
				case strtolower("var2"):
					usort($myobjectList, array("MyObject","CompareMyObjectByvar2"));
				if (!$ascending)
					{
						$myobjectList = array_reverse($myobjectList);
					}
				break;
				case strtolower("var3"):
					usort($myobjectList, array("MyObject","CompareMyObjectByvar3"));
				if (!$ascending)
					{
						$myobjectList = array_reverse($myobjectList);
					}
				break;
				case "":
				default:
				break;
			}
			return $myobjectList;
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
			$Database = new PDO('sqlite:test.db');
			$sql = "select count(myobjectid) as count from myobject where myobjectid = '$this->myobjectId'";
			foreach ($Database->query($sql) as $row)
			{
				$count=$row['count'];
			}
			if ($count == 1)
			{
				// update object
				$stmt = $Database->prepare("update myobject set var1=?,var2=?,var3=? where rowid=?");
				$stmt->bindParam(1, $this->var1);
				$stmt->bindParam(2, $this->var2);
				$stmt->bindParam(3, $this->var3);
				$stmt->bindParam(4, $this->myobjectId);
			}
			else
			{
				// insert object
				$stmt = $Database->query("insert into myobject ('var1', 'var2', 'var3') values('100', 'var2', 'var3')");
				$stmt->bindParam(1, $this->var1);
				$stmt->bindParam(2, $this->var2);
				$stmt->bindParam(3, $this->var3);
			}
			$stmt->execute();
			if ($this->myobjectId == "")
			{
				$this->myobjectId = $Database->lastInsertId();
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
		$this->myobjectId='';
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
			$Database = new PDO('sqlite:test.db');
			echo "delete from myobject where myobjectid='$this->myobjectId'";
			$Database->query("delete from myobject where myobjectid='$this->myobjectId'");
			$affectedRows = 0;
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
	* private function to sort an array of MyObject by var1
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareMyObjectByvar1($myobject1, $myobject2)
	{
		return strcmp(strtolower($myobject1->var1), strtolower($myobject2->var1));
	}
	
	
	/**
	* private function to sort an array of MyObject by var2
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareMyObjectByvar2($myobject1, $myobject2)
	{
		return strcmp(strtolower($myobject1->var2), strtolower($myobject2->var2));
	}
	
	
	/**
	* private function to sort an array of MyObject by var3
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareMyObjectByvar3($myobject1, $myobject2)
	{
		return strcmp(strtolower($myobject1->var3), strtolower($myobject2->var3));
	}
}
?>