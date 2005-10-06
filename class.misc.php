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
		if ($type=="VARCHAR(255)" 
		|| $type=="TINYINT" 
		|| $type=="TEXT"
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
		|| $type=="BLOB"
		|| $type=="MEDIUMBLOB"
		|| $type=="MEDIUMTEXT"
		|| $type=="LONGBLOB"
		|| $type=="LONGTEXT"
		|| $type=="BINARY")
			return true;
		else
			return false;
	}
}
?>