<?
/*
	This SQL query will create the table to store your object.


	CREATE TABLE feedback (
	feedbackid INTEGER PRIMARY KEY,
	name TEXT,
	email TEXT,
	comments TEXT);
*/

/**
* Feedback class with integrated CRUD methods.
* @author Php Object Generator
* @version 1.5 rev2
* @see http://www.phpobjectgenerator.com/plog/tutorials/38/pdo-sqlite
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=sqlite&objectName=Feedback&attributeList=array+%28%0A++0+%3D%3E+%27name%27%2C%0A++1+%3D%3E+%27email%27%2C%0A++2+%3D%3E+%27comments%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27TEXT%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A%29
*/
class Feedback
{
	public $feedbackId;
	public $name;
	public $email;
	public $comments;
	
	
	function Feedback($name='', $email='', $comments='')
	{
		$this->name = $name;
		$this->email = $email;
		$this->comments = $comments;
	}
	
	
	/**
	* Gets object from database
	* @param integer $feedbackId 
	* @return object $Feedback
	*/

	function Get($feedbackId)
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$stmt = $Database->prepare("select * from feedback where feedbackid= ? LIMIT 1");
			if ($stmt->execute(array($feedbackId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->feedbackId = $row['feedbackid'];
					$this->name = $row['name'];
					$this->email = $row['email'];
					$this->comments = $row['comments'];
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
	* @return array $feedbackList
	*/
	static function GetFeedbackList($field,$comparator,$fieldValue,$sortBy="",$ascending=true,$optionalConditions="")
	{
		
		$feedbackList = Array();
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$sql = "select feedbackid from feedback where $field $comparator '$fieldValue'";
			foreach ($Database->query($sql) as $row)
			{
				$feedback = new Feedback();
				$feedback->Get($row['feedbackid']);
				$feedbackList[] = $feedback;
			}
			switch(strtolower($sortBy))
			{
				case strtolower("name"):
					usort($feedbackList, array("Feedback","CompareFeedbackByname"));
				if (!$ascending)
					{
						$feedbackList = array_reverse($feedbackList);
					}
				break;
				case strtolower("email"):
					usort($feedbackList, array("Feedback","CompareFeedbackByemail"));
				if (!$ascending)
					{
						$feedbackList = array_reverse($feedbackList);
					}
				break;
				case strtolower("comments"):
					usort($feedbackList, array("Feedback","CompareFeedbackBycomments"));
				if (!$ascending)
					{
						$feedbackList = array_reverse($feedbackList);
					}
				break;
				case "":
				default:
				break;
			}
			return $feedbackList;
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $feedbackId
	*/
	function Save()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$count=0;
			$sql = "select count(feedbackid) as count from feedback where feedbackid = '$this->feedbackId'";
			foreach ($Database->query($sql) as $row)
			{
				$count=$row['count'];
			}
			if ($count == 1)
			{
				// update object
				$stmt = $Database->prepare("update feedback set name=?,email=?,comments=? where feedbackid=?");
				$stmt->bindParam(1, $this->name);
				$stmt->bindParam(2, $this->email);
				$stmt->bindParam(3, $this->comments);
				$stmt->bindParam(4, $this->feedbackId);
			}
			else
			{
				// insert object
				$stmt = $Database->prepare("insert into feedback (name,email,comments) values (?,?,?)");
				$stmt->bindParam(1, $this->name);
				$stmt->bindParam(2, $this->email);
				$stmt->bindParam(3, $this->comments);
			}
			$stmt->execute();
			if ($this->feedbackId == "")
			{
				$this->feedbackId = $Database->lastInsertId();
			}
			return $this->feedbackId;
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $feedbackId
	*/
	function SaveNew()
	{
		$this->feedbackId='';
		return $this->Save();
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
			$affectedRows = $Database->query("delete from object where objectid='$this->feedbackId'");
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
	* private function to sort an array of Feedback by name
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareFeedbackByname($feedback1, $feedback2)
	{
		return strcmp(strtolower($feedback1->name), strtolower($feedback2->name));
	}
	
	
	/**
	* private function to sort an array of Feedback by email
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareFeedbackByemail($feedback1, $feedback2)
	{
		return strcmp(strtolower($feedback1->email), strtolower($feedback2->email));
	}
	
	
	/**
	* private function to sort an array of Feedback by comments
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareFeedbackBycomments($feedback1, $feedback2)
	{
		return strcmp(strtolower($feedback1->comments), strtolower($feedback2->comments));
	}
}
?>