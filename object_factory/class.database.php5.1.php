<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version 3.0 / PHP5.1
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	private static $connection = null;
	
	private function Database()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$driver = $GLOBALS['configuration']['pdoDriver'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		if (!isset($this->connection))
		{
			$this->connection = new PDO($driver.':host='.$serverName.';port='.$databasePort.';dbname='.$databaseName, $databaseUser, $databasePassword);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		if (!$this->connection)
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
		self::$connection = $database->connection;
		return $database->connection;
	}

	public static function Reader($query)
	{
		try
		{
			$result = self::$connection->Query($query);
		}
		catch(PDOException $e)
		{
			return false;
		}
		return $result;
	}

	public static function ReaderPrepared($query, $bindings)
	{
		try
		{
			$r = self::$connection->prepare($query);
			$r->execute($bindings);			
			$result = $r;
		}
		catch(PDOException $e)
		{
			return false;
		}
		return $result;
	}

	public static function Read($result)
	{
		try
		{
			return $result->fetch();
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	public static function NonQuery($query)
	{
		try
		{
			$r = self::$connection->query($query);
			if ($r === false)
			{
				return 0;
			}
			return $r->rowCount();
		}
		catch (PDOException $e)
		{
			return false;
		}

	}

	public static function Query($query)
	{
		try
		{
			$i = 0;
			$r = self::$connection->query($query);
			foreach ($r as $row)
			{
				$i++;
			}
			return  $i;
		}
		catch (PDOException $e)
		{
			error_log($e->getMessage());
			return false;
		}
	}

	public static function InsertOrUpdate($query)
	{
		try
		{
			$r = self::$connection->query($query);
			return self::$connection->lastInsertId();
		}
		catch (PDOException $e)
		{
			error_log($e->getMessage());
			return false;
		}
	}

	public static function Quote($value)
	{
		if(is_null(self::$connection))
		{
			self::Connect();
		}
		return self::$connection->quote($value); 
	}

	public static function InsertOrUpdatePrepared($query, $bindings)
	{
		try
		{
			$r = self::$connection->prepare($query);
			$r->execute($bindings);
			return self::$connection->lastInsertId();
		}
		catch (PDOException $e)
		{
			error_log($e->getMessage());
			return false;
		}
	}

}
?>