<?
class Object
{
	var $string = "";
	var $sql = "";
	var $objectName = "";
	var $attributeList = array();
	var $optionList = array();
	var $separator = "\n\t";
	var $pdoDriver = "";
	
	// -------------------------------------------------------------
	function Object($objectName, $attributeList = '', $optionList ='', $pdoDriver='')
	{
		$this->objectName = $objectName;
		$this->attributeList = $attributeList;
		$this->optionList = $optionList;
		$this->pdoDriver = $pdoDriver;
	}
	
	// -------------------------------------------------------------
	function BeginObject()
	{
		$this->string = "<?\n";
		$this->string .= $this->CreatePreface();
		$this->string .= "\nclass ".$this->objectName."\n{\n\t";
		$this->string.="public \$".strtolower($this->objectName)."Id;\n\t";
		foreach($this->attributeList as $attribute)
		{
			$this->string.="public $".$attribute.";\n\t";
		}
	}
	
	// -------------------------------------------------------------
	function EndObject()
	{
		$this->string .= "\n}\n?>";
	}
	
	// -------------------------------------------------------------
	function CreateConstructor()
	{
		$this->string .= "\n\t\n\tfunction ".$this->objectName."(";
		$i = 0;
		foreach($this->attributeList as $attribute)
		{
			if ($i == 0)
			{
				$this->string .= '$'.$attribute.'=\'\'';
			}
			else
			{
				$this->string .= ', $'.$attribute.'=\'\'';
			}
			$i++;
		}
		$this->string .= ")\n\t{";
		foreach($this->attributeList as $attribute)
		{
			$this->string .= "\n\t\t\$this->".$attribute." = $".$attribute.";";
		}
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateGetFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Gets object from database",array("integer \$".strtolower($this->objectName)."Id"),"object \$".$this->objectName);
		$this->string .="\n\tfunction Get(\$".strtolower($this->objectName)."Id)\n\t{";
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .="\n\t\t\t\$sql = \"select * from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id='\$".strtolower($this->objectName)."Id'\";";
		$this->string .="\n\t\t\tforeach (\$Database->query(\$sql) as \$row)";
		$this->string .="\n\t\t\t{";
		$this->string .="\n\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['".strtolower($this->objectName)."id'];";
		foreach ($this->attributeList as $attribute)
		{
			$this->string .="\n\t\t\t\t\$this->".strtolower($attribute)." = \$row['".strtolower($attribute)."'];";
		}
		$this->string .="\n\t\t\t}";
		$this->string .="\n\t\t\treturn \$this;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch (PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tprint \"Error!: \" . \$e->getMessage() . \"<br/>\";";
   		$this->string .="\n\t\t\tdie();";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateSQLQuery()
	{
		switch ($this->pdoDriver)
		{
			case "odbc":
				$this->sql .= "\tCREATE TABLE ".strtolower($this->objectName)."(\n\t".strtolower($this->objectName)."id INT IDENTITY(1,1),";
				$x=0;
				foreach ($this->attributeList as $attribute)
				{
					if ($x == (count($this->optionList)-1))
					{
						$this->sql .= "\n\t".strtolower($attribute)." ".$this->optionList[$x];
					}
					else
					{
						$this->sql .= "\n\t".strtolower($attribute)." ".$this->optionList[$x].",";
					}
					$x++;
				}
				$this->sql .= ");";
				break;
		}
	}
	
	// -------------------------------------------------------------
	function CreateSaveFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Saves the object to the database",'',"integer $".strtolower($this->objectName)."Id");
		$this->string .= "\tfunction Save()\n\t{";
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\$Database->beginTransaction();";
		$this->string .= "\n\t\t\t\$count=0;";
		$this->string .= "\n\t\t\t\$sql = \"select count(".strtolower($this->objectName)."id) as count from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id'\";";
		$this->string .= "\n\t\t\tforeach (\$Database->query(\$sql) as \$row)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$count=\$row['count'];";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tif (\$count == 1)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// update object";
		$this->string .= "\n\t\t\t\t\$stmt = \$Database->prepare(\"update ".strtolower($this->objectName)." set ";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($x == (count($this->attributeList)-1))
			{
				$this->string .= "".strtolower($attribute)."=?";
			}
			else
			{
				$this->string .= "".strtolower($attribute)."=?,";
			}
			$x++;
		}
		$this->string .= " where ".strtolower($this->objectName)."id=?\");";
		$x=1;
		foreach ($this->attributeList as $attribute)
		{
			$this->string .= "\n\t\t\t\t\$stmt->bindParam(".$x.", \$this->".$attribute.");";
			$x++;
		}
		$this->string .= "\n\t\t\t\t\$stmt->bindParam(".$x.", \$this->".strtolower($this->objectName)."Id);";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// insert object";
		$this->string .= "\n\t\t\t\t\$stmt = \$Database->prepare(\"insert into ".strtolower($this->objectName)." (";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($x == (count($this->attributeList)-1))
			{
				$this->string .= strtolower($attribute); 
			}
			else
			{
				$this->string .= strtolower($attribute).",";
			}
			$x++;
		}
		$this->string .= ") values (";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($x == (count($this->attributeList)-1))
			{
				$this->string .= "?";
			}
			else
			{
				$this->string .= "?,";
			}
			$x++;
		}
		$this->string .= ")\");";
		$x=1;
		foreach ($this->attributeList as $attribute)
		{
			$this->string .= "\n\t\t\t\t\$stmt->bindParam(".$x.", \$this->".$attribute.");";
			$x++;
		}
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$stmt->execute();";
		$this->string .= "\n\t\t\tif (\$this->".strtolower($this->objectName)."Id == \"\")";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$sql = (\"select max(".strtolower($this->objectName)."id) as max from ".strtolower($this->objectName)."\");";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$sql) as \$row)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['max'];";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$Database->commit();";
		$this->string .= "\n\t\t\treturn \$this->".strtolower($this->objectName)."Id;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch (PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tprint \"Error!: \" . \$e->getMessage() . \"<br/>\";";
   		$this->string .="\n\t\t\tdie();";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateSaveNewFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Clones the object and saves it to the database",'',"integer $".strtolower($this->objectName)."Id");
		$this->string .="\tfunction SaveNew()\n\t{";
		$this->string .= "\n\t\t\$this->".strtolower($this->objectName)."Id='';";
		$this->string .= "\n\t\treturn \$this->Save();";
		$this->string .= "\n\t}";
	}
	
	
	// -------------------------------------------------------------
	function CreateDeleteFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Deletes the object from the database",'',"integer \$affectedRows");
		$this->string .= "\tfunction Delete()\n\t{";
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\$affectedRows = \$Database->query(\"delete from object where objectid='\$this->".strtolower($this->objectName)."Id'\");";
		$this->string .= "\n\t\t\tif (\$affectedRows != null)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn \$affectedRows;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn 0;";
		$this->string .= "\n\t\t\t}";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch (PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tprint \"Error!: \" . \$e->getMessage() . \"<br/>\";";
   		$this->string .="\n\t\t\tdie();";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateComments($description='', $parameterDescriptionArray='', $returnType='')
	{
		$this->string .= "/**\n"
 		."\t* $description\n";
 		if ($parameterDescriptionArray != '')
 		{
	 		foreach ($parameterDescriptionArray as $parameter)
	 		{
	 			$this->string .= "\t* @param $parameter \n";
	 		}
 		}
	     $this->string .= "\t* @return $returnType\n"
	     ."\t*/\n";
	}
	
	// -------------------------------------------------------------
	function CreatePreface()
	{
		$this->string .= "/*\n\tThis SQL query will create the table to store your object.\n";
		$this->CreateSQLQuery();
		$this->string .= "\n".$this->sql."\n*/";
		$this->string .= "\n\n/**";
		$this->string .= "\n* ".ucwords($this->objectName)." class with integrated CRUD methods.";
		$this->string .= "\n* @author ".$GLOBALS['configuration']['author'];
		$this->string .= "\n* @version ".$GLOBALS['configuration']['versionNumber']." rev".$GLOBALS['configuration']['revisionNumber'];
		$this->string .= "\n* @see http://www.phpobjectgenerator.com/plog/odbc";
		$this->string .= "\n* @copyright ".$GLOBALS['configuration']['copyright'];
		$this->string .= "\n* @link http://phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=".$_SESSION['pdoDriver']."&objectName=".urlencode($this->objectName)."&attributeList=".urlencode(var_export($this->attributeList, true))."&typeList=".urlencode(var_export($this->optionList, true));;
		$this->string .= "\n*/";
	}
	
	// -------------------------------------------------------------
	function CreateGetAllFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("string \$field","string \$comparator","string \$fieldValue","string \$sortBy","boolean \$ascending"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tstatic function Get".$this->objectName."List(\$field,\$comparator,\$fieldValue,\$sortBy=\"\",\$ascending=true,\$optionalConditions=\"\")\n\t{\n\t\t";
		$this->string .= "\n\t\t\$".strtolower($this->objectName)."List = Array();";
		$this->string .= "\n\t\ttry";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\$sql = \"select ".strtolower($this->objectName)."id from ".strtolower($this->objectName)." where \$field \$comparator '\$fieldValue'\";";
		$this->string .= "\n\t\t\tforeach (\$Database->query(\$sql) as \$row)";
		$this->string .= "\n\t\t\t{";
      	$this->string .= "\n\t\t\t\t\$".strtolower($this->objectName)." = new ".$this->objectName."();";
		$this->string .= "\n\t\t\t\t\$".strtolower($this->objectName)."->Get(\$row['".strtolower($this->objectName)."id']);";
		$this->string .= "\n\t\t\t\t\$".strtolower($this->objectName)."List[] = \$".strtolower($this->objectName).";";
   		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tswitch(strtolower(\$sortBy))";
		$this->string .= "\n\t\t\t{";
		foreach ($this->attributeList as $attribute)
		{
			$this->string .= "\n\t\t\t\tcase strtolower(\"$attribute\"):";
			$this->string .= "\n\t\t\t\t\tusort(\$".strtolower($this->objectName)."List, array(\"".$this->objectName."\",\"Compare".$this->objectName."By".$attribute."\"));";
			$this->string .= "\n\t\t\t\tif (!\$ascending)";
			$this->string .= "\n\t\t\t\t\t{";
			$this->string .= "\n\t\t\t\t\t\t\$".strtolower($this->objectName)."List = array_reverse(\$".strtolower($this->objectName)."List);";
			$this->string .= "\n\t\t\t\t\t}";
			$this->string .= "\n\t\t\t\tbreak;";
		}
		$this->string .= "\n\t\t\t\tcase \"\":";
		$this->string .= "\n\t\t\t\tdefault:";
		$this->string .= "\n\t\t\t\tbreak;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\treturn \$".strtolower($this->objectName)."List;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch (PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tprint \"Error!: \" . \$e->getMessage() . \"<br/>\";";
   		$this->string .="\n\t\t\tdie();";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateCompareFunctions()
	{
		foreach ($this->attributeList as $attribute)
		{
			$this->string .= "\n\t$this->separator\n\t";
			$this->string .= $this->CreateComments("private function to sort an array of $this->objectName by $attribute",'',"+1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2");
			$this->string .= "\tstatic function Compare".$this->objectName."By$attribute(\$".strtolower($this->objectName)."1, \$".strtolower($this->objectName)."2)\n\t{";
			$this->string .= "\n\t\treturn strcmp(strtolower(\$".strtolower($this->objectName)."1->$attribute), strtolower(\$".strtolower($this->objectName)."2->$attribute));";
			$this->string .= "\n\t}";
		}
	}
}
?>
