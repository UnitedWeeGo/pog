<?
//	Php Object Generator v.1.0
//	http://www.phpobjectgenerator.com

//	This SQL query will create the table to store your object.
/*
	CREATE TABLE `abc` (abcid int(11) auto_increment,
	`one` int,
	`two` varchar(255), PRIMARY KEY  (`abcid`));
*/
class abc
{
	public $abcId;
	public $one;
	public $two;
	
	
	function abc($one='', $two='')
	{
		$this->one = $one;
		$this->two = $two;
	}
	
	
	/**
	* Gets the object from the database
	* @param abcId 
	* @return object
	*/
	function Get($abcId)
	{
		$Database = new DatabaseConnection();
		$query = "select * from `abc` where `abcid`='".$abcId."' LIMIT 1";
		$Database->Query($query);
		$this->abcId = $Database->Result(0,"abcid");
		$this->one = $Database->Unescape($Database->Result(0,"one"));
		$this->two = $Database->Unescape($Database->Result(0,"two"));
		return $this;
	}
	
	
	/**
	* Gets all objects from the database
	* @return array of objects
	*/
	static function GetabcList($field,$comparator,$fieldValue)
	{
		
		$abcList = Array();
		$Database = new DatabaseConnection();
		$query = "select abcId from abc where `".$field."`".$comparator."'".$Database->Escape($fieldValue)."'";
		$Database->Query($query);
		for ($i=0; $i < $Database->Rows(); $i++)
		{
			$abc = new abc();
			$abc->Get($Database->Result($i,"abcId"));
			$abcList[] = $abc;
		}
		return $abcList;
	}
	
	
	/**
	* Saves the object to the database
	* @return nothing
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$query = "select * from `abc` where `abcid`='".$this->abcId."' LIMIT 1";
		$Database->Query($query);
		if ($Database->Rows() > 0)
		{
			$query = "update `abc` set 
			`one`='".$Database->Escape($this->one)."', 
			`two`='".$Database->Escape($this->two)."' where `abcid`='".$this->abcId."'";
		}
		else
		{
			$query = "insert into `abc` (`one`, `two` ) values (
			'".$Database->Escape($this->one)."', 
			'".$Database->Escape($this->two)."' )";
		}
		$Database->InsertOrUpdate($query);
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return nothing
	*/
	function SaveNew()
	{
		$this->abcId='';
		$this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return nothing
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$query = "delete from `abc` where `abcid`='".$this->abcId."'";
		$Database->Query($query);
	}
}
?>