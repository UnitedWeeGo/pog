<?
class POG_Base
{
	/**
	 * Overloading
	 */
	function __call($method, $arguments)
	{
		include_once("plugins/IPlugin.php");
		include_once("plugins/plugin.".strtolower($method).".php");
		$argumentString = '';
		foreach ($arguments as $arg)
		{
			$value = str_replace('\'', '"', var_export($arg, true));
			$argumentString .= $value.',';
		}
		$argumentString = trim($argumentString, ',');
		eval('$plugin = new $method('.$argumentString.');');
		return $plugin->Execute($this);
	}

	/**
	 * constructor
	 *
	 * @return POG_Base
	 */
	function POG_Base()
	{
	}


	/**
	 * Gets
	 */

	/**
	 * Gets table name associated with of POG Object
	 *
	 * @param object $pog_object
	 * @return string
	 */
	function GetTableName($pog_object)
	{
		return strtolower(get_class($pog_object));
	}

	/**
	 * Gets the non-child/non-parent and non-sibling attributes of the POG Object
	 *
	 * @param object $pog_object
	 * @return array
	 */
	function GetAttributes($pog_object)
	{
		$columns = array();
		foreach ($pog_object->pog_attribute_type as $attribute => $attribute_type)
		{
			$columns[] = $attribute;
		}
		return $columns;
	}

	/**
	 * Enter description here...
	 *
	 */
	function GetParentClasses()
	{

	}

	/**
	 * Enter description here...
	 *
	 */
	function GetChildClasses()
	{

	}

	/**
	 * Enter description here...
	 *
	 */
	function GetSiblingClasses()
	{

	}

	/**
	 * Returns true if pog_object is a child of other_object. If other_object is '', returns whether pog_object is a child of any other object
	 *
	 */
	function IsChildOf($object_instance)
	{

	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $pog_object
	 * @param unknown_type $other_object
	 */
	function IsParentOf($object_instance)
	{

	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $pog_object
	 * @param unknown_type $other_object
	 */
	function IsSiblingOf($object_instance)
	{

	}

	/**
	 * Returns all objects in the /objects/ directory
	 *
	 */
	function GetAllObjects()
	{
		$dir = opendir('../objects/');
		$objects = array();
		while(($file = readdir($dir)) !== false)
		{
			if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.php' && !is_dir($file) && $file != "class.database.php")
			{
				include_once("../objects/{$file}");
				$objectNameParts = explode('.', $file);
				$instance = new $objectNameParts[1]();
				$objects[] = $instance;
			}
		}
		closedir($dir);
		return $objects;
	}

	///////////////////////////
	// Data manipulation
	///////////////////////////

	/**
	* This function will try to encode $text to base64, except when $text is a number. This allows us to Escape all data before they're inserted in the database, regardless of attribute type.
	* @param string $text
	* @return string encoded to base64
	*/
	function Escape($text)
	{
		if ($GLOBALS['configuration']['db_encoding'] && !is_numeric($text))
		{
			return base64_encode($text);
		}
		return mysql_real_escape_string($text);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $text
	 * @return unknown
	 */
	function Unescape($text)
	{
		if ($GLOBALS['configuration']['db_encoding'] && !is_numeric($text))
		{
			return base64_decode($text);
		}
		return stripcslashes($text);
	}


	////////////////////////////////
	// Table -> Object Mapping
	////////////////////////////////

	/**
	 * Executes $query against database and returns the result set as an array of POG objects
	 *
	 * @param string $query. SQL query to execute against database
	 * @param string $objectClass. POG Object type to return
	 * @param bool $lazy. If true, will also load all children/sibling
	 */
	function FetchObjects($query, $objectClass, $lazy = true)
	{
		$databaseConnection = Database::Connect();
		$result = Database::Query($query, $databaseConnection);
		$objectList = $this->CreateObjects($result, $objectClass, $lazy);
		return $objectList;
	}

	function CreateObjects($mysql_result, $objectClass, $lazyLoad = true)
	{
		$objectList = array();
		while ($row = mysql_fetch_assoc($mysql_result))
		{
			$pog_object = new $objectClass();
			$this->PopulateObjectAttributes($row, $pog_object);
			$objectList[] = $pog_object;
		}
		return $objectList;
	}

	function PopulateObjectAttributes($fetched_row, $pog_object)
	{
 		foreach ($this->GetAttributes($pog_object) as $column)
		{
			$pog_object->{$column} = $this->Unescape($fetched_row[strtolower($column)]);
		}
		return $pog_object;
	}

	//misc
 	function IsColumn($value)
	{
		if (strlen($value) > 2)
		{
			if (substr($value, 0, 1) == '`' && substr($value, strlen($value) - 1, 1) == '`')
			{
				return true;
			}
			return false;
		}
		return false;
	}
}
?>