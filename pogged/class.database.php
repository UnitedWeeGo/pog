<?php
 Class DatabaseConnection 
{
	var $connection;
	var $databaseName;
	var $result;

	// -------------------------------------------------------------
	function DatabaseConnection() 
	{
		$this->databaseName = $GLOBALS['configuration']['db']; 
		$serverName = $GLOBALS['configuration']['host'];		
		$databaseUser = $GLOBALS['configuration']['user'];		
		$databasePassword = $GLOBALS['configuration']['pass'];	

		$this->connection = mysql_connect ($serverName, $databaseUser, $databasePassword) or die ('I cannot connect to the database.');
		mysql_select_db ($this->databaseName);
	}
	
	// -------------------------------------------------------------
	function Close()
	{
		mysql_close($this->connection);
	}

	// -------------------------------------------------------------
	function GetConnection() {
		return $this->connection;
	}

	// -------------------------------------------------------------
	function Query($query) 
	{	
		$this->result = mysql_query($query,$this->connection);
		if (!$this->result) {
			die('Invalid query: '.mysql_error());
		}
		return $this->result;
	}

	// -------------------------------------------------------------
	function Rows() 
	{
		if ($this->result != false)
		{
			return mysql_num_rows($this->result);
		}
		return null;
	}
	
	// -------------------------------------------------------------
	function AffectedRows() 
	{
		return mysql_affected_rows();
	}

	// -------------------------------------------------------------
	function Result($row,$name) 
	{
		if ($this->Rows() > 0)
		{
			return mysql_result($this->result,$row,$name);
		}
		return null;
	}
	

	// -------------------------------------------------------------
	function InsertOrUpdate($query) 
	{
		$this->result = mysql_query($query,$this->connection);
		return ($this->AffectedRows() > 0);
	}
	
	/**
	* This function will always try to encode $text to base64, except when $text is a number. This allows us to Escape all data before they're inserted in the database, regardless of attribute type.
	* @param string $text
	* @return string encoded to base64
	*/
	function Escape($text) 
	{
		if (!ctype_digit($text))
		{
			return base64_encode($text);
		}
		return $text;
	}
	
	// -------------------------------------------------------------
	function Unescape($text)
	{
		if (!ctype_digit($text))
		{
			return base64_decode($text);
		}
		return $text;
	}
	
	// -------------------------------------------------------------
	function GetCurrentId()
	{
		return mysql_insert_id($this->connection);
	}
}
Class Comparator 
{
	const Equal = "=";
	const NotEqual = "<>";
	const GreaterThan = ">";
	const GreaterThanEqual = ">=";
	const LessThan = "<";
	const LessThanEqaul = "<=";
}
?>