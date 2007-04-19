<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version &versionNumber&revisionNumber / &language
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	public $connection;

	private function Database()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		$this->connection = mysql_connect ($serverName.":".$databasePort, $databaseUser, $databasePassword);
		if ($this->connection)
		{
			if (!mysql_select_db ($databaseName))
			{
				throw new Exception('I cannot find the specified database "'.$databaseName.'". Please edit configuration.php.');
			}
		}
		else
		{
			throw new Exception('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		}
	}

	public static function Connect()
	{
		static $database = null;
		if (!isset($database))
		{
			$database = new Database();
		}
		return $database->connection;
	}

	public static function Query($query, $connection)
	{
		$result = mysql_query($query, $connection);
		if (!$result)
		{
			throw new Exception(mysql_errno().":".mysql_error().';'.$query);
		}
		return $result;
	}

	public static function Rows($result)
	{
		if ($result != false)
		{
			return mysql_num_rows($result);
		}
		return null;
	}

	public static function InsertOrUpdate($query, $connection)
	{
		$result = mysql_query($query, $connection);
		return (mysql_affected_rows() > 0);
	}

	public static function GetCurrentId($connection)
	{
		return intval(mysql_insert_id($connection));
	}
}
?>
