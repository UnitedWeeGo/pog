<?
//	Php Object Generator v.1.0
//	http://www.phpobjectgenerator.com

//	This SQL query will create the table to store your object.
/*
	CREATE TABLE `one` (oneid int(11) auto_increment,
	`abc` int,
	`def` int, PRIMARY KEY  (`oneid`));
*/
class one
{
	public $oneId;
	public $abc;
	public $def;
	
	
	function one($abc='', $def='')
	{
		$this->abc = $abc;
		$this->def = $def;
	}
	
	
	/**
	* Gets the object from the database
	* @param oneId 
	* @return object
	*/
	function Get($oneId)
	{
		$Database = new DatabaseConnection();
		$query = "select * from `one` where `oneid`='".$oneId."' LIMIT 1";
		$Database->Query($query);
		$this->oneId = $Database->Result(0,"oneid");
		$this->abc = $Database->Unescape($Database->Result(0,"abc"));
		$this->def = $Database->Unescape($Database->Result(0,"def"));
		return $this;
	}
	
	
	/**
	* Gets all objects from the database
	* @return array of objects
	*/
	static function GetoneList($field,$comparator,$fieldValue)
	{
		
		$oneList = Array();
		$Database = new DatabaseConnection();
		$query = "select oneId from one where `".$field."`".$comparator."'".$Database->Escape($fieldValue)."'";
		$Database->Query($query);
		for ($i=0; $i < $Database->Rows(); $i++)
		{
			$one = new one();
			$one->Get($Database->Result($i,"oneId"));
			$oneList[] = $one;
		}
		return $oneList;
	}
	
	
	/**
	* Saves the object to the database
	* @return nothing
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$query = "select * from `one` where `oneid`='".$this->oneId."' LIMIT 1";
		$Database->Query($query);
		if ($Database->Rows() > 0)
		{
			$query = "update `one` set 
			`abc`='".$Database->Escape($this->abc)."', 
			`def`='".$Database->Escape($this->def)."' where `oneid`='".$this->oneId."'";
		}
		else
		{
			$query = "insert into `one` (`abc`, `def` ) values (
			'".$Database->Escape($this->abc)."', 
			'".$Database->Escape($this->def)."' )";
		}
		$Database->InsertOrUpdate($query);
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return nothing
	*/
	function SaveNew()
	{
		$this->oneId='';
		$this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return nothing
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$query = "delete from `one` where `oneid`='".$this->oneId."'";
		$Database->Query($query);
	}
}
?>