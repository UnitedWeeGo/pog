<?
class Misc
{
	var $string;
	var $objectList = Array();
	var $attributeList;
	var $optionList;
	var $separator = "\n\t// -------------------------------------------------------------";
	
	// -------------------------------------------------------------
	function Misc($objectList, $attributeList = '', $optionList ='')
	{
		$this->objectList = $objectList;
		$this->attributeList = $attributeList;
		$this->optionList = $optionList;
	}
	
	// -------------------------------------------------------------
	function CreateGetAllFunction()
	{
		foreach ($this->objectList as $object)
		{
			$this->string .= "\n\t".$this->separator."\n\tfunction Get".$object."List()\n\t{\n\t\t";
			$this->string .= "\n\t\t\$".strtolower($object)."List = Array();";
			$this->string .= "\n\t\t\$Database = new DatabaseConnection();";
			$this->string .= "\n\t\t\$query = \"select id from $object\";";
			$this->string .= "\n\t\t\$Database->Query(\$query);";
			$this->string .= "\n\t\tfor (\$i=0; \$i < \$Database->Rows(); \$i++)";
			$this->string .= "\n\t\t{";
			$this->string .= "\n\t\t\t\$".strtolower($object)." = new $object();";
			$this->string .= "\n\t\t\t\$".strtolower($object)."->Get(\$Database->Result(\$i,\"id\"));";
			$this->string .= "\n\t\t\t\$".strtolower($object)."List[] = $".$object.";";
			$this->string .= "\n\t\t}";
			$this->string .= "\n\t\treturn \$".strtolower($object)."List;";
			$this->string .= "\n\t}";
		}
	}
	
	// -------------------------------------------------------------
	function CreateGetVariableFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\tfunction GetVariable(\$variableName)\n\t{\n\t\t";
		$this->string .= "if (isset(\$_GET[\$variableName]))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn \$_GET[\$variableName];";
		$this->string .= "\n\t\t}";
		$this->string .= "if (isset(\$_POST[\$variableName]))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn \$_POST[\$variableName];";
		$this->string .= "\n\t\t}";
		$this->string .= "if (isset(\$_SESSION[\$variableName]))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn \$_SESSION[\$variableName];";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn null;";
		$this->string .= "\n\t}";
	}
	
	function TypeIsKnown($type)
	{
		if ($type=="VARCHAR(255)"	//mysql
		|| $type=="TINYINT" 
		|| $type=="TEXT"
		|| $type=="INT"
		|| $type=="DATE"
		|| $type=="SMALLINT"
		|| $type=="MEDIUMINT"
		|| $type=="BIGINT"
		|| $type=="FLOAT"
		|| $type=="DOUBLE"
		|| $type=="DECIMAL"
		|| $type=="DATETIME"
		|| $type=="TIMESTAMP"
		|| $type=="TIME"
		|| $type=="YEAR"
		|| $type=="CHAR(255)"
		|| $type=="TINYBLOB"
		|| $type=="TINYTEXT"
		|| $type=="BLOB"	//firebird
		|| $type=="MEDIUMBLOB"
		|| $type=="MEDIUMTEXT"
		|| $type=="LONGBLOB"
		|| $type=="LONGTEXT"
		|| $type=="BINARY"
		|| $type=="BLOB"
		|| $type=="CHAR"
		|| $type=="CHAR(1)"
		|| $type=="INT64"
		|| $type=="INTEGER"
		|| $type=="NUMERIC"
		|| $type=="BIGSERIAL"	//postgresql
		|| $type=="BIT"
		|| $type=="BOOLEAN"
		|| $type=="BOX"
		|| $type=="BYTEA"
		|| $type=="CIRCLE"
		|| $type=="DOUBLE PRECISION"
		|| $type=="INET"
		|| $type=="LINE"
		|| $type=="LSEG"
		|| $type=="MACADDR"
		|| $type=="MONEY"
		|| $type=="OID"
		|| $type=="PATH"
		|| $type=="POINT"
		|| $type=="REAL"
		|| $type=="SERIAL"
		|| $type=="MONEY"
		|| $type=="IMAGE"	//odbc
		|| $type=="NCHAR"
		|| $type=="NTEXT"
		|| $type=="NVARCHAR"
		|| $type=="SMALLDATETIME"
		|| $type=="SMALLINT"
		|| $type=="SMALLMONEY"
		|| $type=="UNIQUEIDENTIFIER"
		|| $type=="VARBINARY"	
		)
			return true;
		else
			return false;
	}
	
	// -------------------------------------------------------------
	function GetVariable($variableName)
	{
		if (isset($_GET[$variableName]))
		{
			return $_GET[$variableName];
		}
		if (isset($_POST[$variableName]))
		{
			return $_POST[$variableName];
		}
		if (isset($_SESSION[$variableName]))
		{
			return $_SESSION[$variableName];
		}
		return null;
	}
	
	// -------------------------------------------------------------
	function TypeIsNumeric($type)
	{
		if ($type=="TINYINT"	//mysql
		|| $type=="INT"
		|| $type=="DATE"
		|| $type=="SMALLINT"
		|| $type=="MEDIUMINT"
		|| $type=="BIGINT"
		|| $type=="FLOAT"
		|| $type=="DOUBLE"
		|| $type=="DECIMAL"
		|| $type=="TIMESTAMP"
		|| $type=="TIME"
		|| $type=="YEAR"
		|| $type=="INT64"
		|| $type=="INTEGER"
		|| $type=="NUMERIC"
		|| $type=="BIGSERIAL"	//postgresql
		|| $type=="DOUBLE PRECISION"
		|| $type=="MONEY"
		|| $type=="OID"
		|| $type=="REAL"
		|| $type=="SERIAL"
		|| $type=="MONEY"
		|| $type=="SMALLINT"
		|| $type=="SMALLMONEY"
		|| $type=="UNIQUEIDENTIFIER"
		)
			return true;
		else
			return false;
	}
}
?>