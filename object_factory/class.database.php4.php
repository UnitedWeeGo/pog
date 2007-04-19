<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version versionNumber / PHP4
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	var $connection;

	function Database()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		$this->connection = mysql_connect ($serverName.":".$databasePort, $databaseUser, $databasePassword) or die ('I cannot connect to the database. Please edit configuration.php');
		mysql_select_db($databaseName) or die ('I cannot find the specified database "'.$databaseName.'". Please edit configuration.php');
	}

	function Connect()
	{
		static $database = null;
		if (!isset($database))
		{
			$database = new Database();
		}
		return $database->connection;
	}

	function Query($query, $connection)
	{
		$result = mysql_query($query, $connection);
		return $result;
	}

	function Rows($result)
	{
		if ($result != false)
		{
			return mysql_num_rows($result);
		}
		return null;
	}

	function InsertOrUpdate($query, $connection)
	{
		$result = mysql_query($query, $connection);
		return (mysql_affected_rows() > 0);
	}

	function GetCurrentId($connection)
	{
		return intval(mysql_insert_id($connection));
	}
}
?>
