<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version versionNumber / PHP5.1
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	public $connection;

	private function Database()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$driver = $GLOBALS['configuration']['pdoDriver'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		$this->connection = new PDO($driver.':host='.$serverName.';port='.$databasePort.';dbname='.$databaseName, $databaseUser, $databasePassword);
		if (!$this->connection)
		{
			throw new Exception('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		}
	}

	public static function Connect()
	{
		if (!isset($database))
		{
			static $database = null;
			$database = new Database();
		}
		return $database->connection;
	}

	public static function Disconnect()
	{
		if (isset($database))
		{
			$database = null;
		}
	}

	public static function Query($query, $connection)
	{
		try
		{
			$result = $connection->Query($query);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		return $result;
	}

	public static function Rows($result)
	{
		return $result->rowCount();
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
