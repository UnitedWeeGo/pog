<?
class Object
{
	var $string = "";
	var $sql = "";
	var $objectName = "";
	var $attributeList = array();
	var $typeList = array();
	var $separator = "\n\t";
	var $pdoDriver = "";
	
	// -------------------------------------------------------------
	function Object($objectName, $attributeList = '', $typeList ='', $pdoDriver='')
	{
		$this->objectName = $objectName;
		$this->attributeList = $attributeList;
		$this->typeList = $typeList;
		$this->pdoDriver = $pdoDriver;
	}
	
	// -------------------------------------------------------------
	function BeginObject()
	{
		$this->string = "<?php\n";
		$this->string .= $this->CreatePreface();
		$this->string .= "\nclass ".$this->objectName."\n{\n\t";
		$this->string.="public \$".strtolower($this->objectName)."Id;\n\t";
		foreach ($this->attributeList as $attribute)
		{
			$this->string.="public $".$attribute.";\n\t";
		}
		//	create attribute => type array map
		//	needed for setup
		$this->string .= "public \$pog_attribute_type = array(\n\t\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{ 
			$this->string .= "\"".$attribute."\" => \"".$this->typeList[$x]."\",\n\t\t";
			$x++;
		}
		$this->string .= ");\n\t";
		$this->string .= "public \$pog_query;";
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
		foreach ($this->attributeList as $attribute)
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
		foreach ($this->attributeList as $attribute)
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
		$this->string .="\n\t\t\t\$this->pog_query = \"select * from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id='\$".strtolower($this->objectName)."Id'\";";
		$this->string .="\n\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .="\n\t\t\t{";
		$this->string .="\n\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['".strtolower($this->objectName)."id'];";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date")
			{
				$this->string .="\n\t\t\t\t\$this->".$attribute." = \$row['".strtolower($attribute)."'];";
			}
			else
			{
				$this->string .="\n\t\t\t\t\$this->".$attribute." = \$this->Unescape(\$row['".strtolower($attribute)."']);";
			}
			
		}
		$this->string .="\n\t\t\t}";
		$this->string .="\n\t\t\treturn \$this;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tthrow new Exception(\$e->getMessage());";
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
					if ($x == (count($this->typeList)-1))
					{
						$this->sql .= "\n\t".strtolower($attribute)." ".$this->typeList[$x];
					}
					else
					{
						$this->sql .= "\n\t".strtolower($attribute)." ".$this->typeList[$x].",";
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
		$this->string .= "\n\t\t\t\$count = 0;";
		$this->string .= "\n\t\t\t\$this->pog_query = \"select count(".strtolower($this->objectName)."id) as count from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id'\";";
		$this->string .= "\n\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$count = \$row['count'];";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tif (\$count == 1)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// update object";
		$this->string .= "\n\t\t\t\t\$this->pog_query = \"update ".strtolower($this->objectName)." set ";
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
		$this->string .= " where ".strtolower($this->objectName)."id=?\";";
		$this->string .= "\n\t\t\t\t\$stmt = \$Database->prepare(\$this->pog_query);";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date")
			{
				$this->string .= "\n\t\t\t\t\$stmt->bindParam(".($x+1).", \$$attribute);";
				$this->string .= "\n\t\t\t\t\$$attribute = \$this->".$attribute;
			}
			else
			{
				$this->string .= "\n\t\t\t\t\$stmt->bindParam(".($x+1).", \$$attribute);";
				$this->string .= "\n\t\t\t\t\$$attribute = \$this->Escape(\$this->".$attribute.");";
			}
			$x++;
		}
		$this->string .= "\n\t\t\t\t\$stmt->bindParam(".($x+1).", $".strtolower($this->objectName)."Id);";
		$this->string .= "\n\t\t\t\t\$".strtolower($this->objectName)."Id = \$this->".strtolower($this->objectName)."Id;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// insert object";
		$this->string .= "\n\t\t\t\t\$this->pog_query = \"insert into ".strtolower($this->objectName)." (";
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
		$this->string .= ")\";";
		$this->string .= "\n\t\t\t\t\$stmt = \$Database->prepare(\$this->pog_query);";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date")
			{
				$this->string .= "\n\t\t\t\t\$stmt->bindParam(".($x+1).", \$$attribute);";
				$this->string .= "\n\t\t\t\t\$$attribute = \$this->".$attribute;
			}
			else
			{
				$this->string .= "\n\t\t\t\t\$stmt->bindParam(".($x+1).", \$$attribute);";
				$this->string .= "\n\t\t\t\t\$$attribute = \$this->Escape(\$this->".$attribute.");";
			}
			$x++;
		}
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$stmt->execute();";
		$this->string .= "\n\t\t\tif (\$this->".strtolower($this->objectName)."Id == \"\")";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->pog_query = (\"select max(".strtolower($this->objectName)."id) as max from ".strtolower($this->objectName)."\");";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['max'];";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$Database->commit();";
		$this->string .= "\n\t\t\treturn \$this->".strtolower($this->objectName)."Id;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tthrow new Exception(\$e->getMessage());";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateSaveNewFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Clones the object and saves it to the database",'',"integer $".strtolower($this->objectName)."Id");
		$this->string .="\tfunction SaveNew()\n\t{";
		$this->string .= "\n\t\t\$this->".strtolower($this->objectName)."Id = '';";
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
		$this->string .= "\n\t\t\t\$this->pog_query = \"delete from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id'\";";
		$this->string .= "\n\t\t\t\$affectedRows = \$Database->query(\$this->pog_query);";
		$this->string .= "\n\t\t\tif (\$affectedRows != null)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn \$affectedRows;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn 0;";
		$this->string .= "\n\t\t\t}";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tthrow new Exception(\$e->getMessage());";
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
		$this->string .= "\n* <b>".ucwords($this->objectName)."</b> class with integrated CRUD methods.";
		$this->string .= "\n* @author ".$GLOBALS['configuration']['author'];
		$this->string .= "\n* @version ".$GLOBALS['configuration']['versionNumber']." rev".$GLOBALS['configuration']['revisionNumber'];
		$this->string .= "\n* @see http://www.phpobjectgenerator.com/plog/tutorials/46/pdo-odbc";
		$this->string .= "\n* @copyright ".$GLOBALS['configuration']['copyright'];
		$this->string .= "\n* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=".$_SESSION['pdoDriver']."&objectName=".urlencode($this->objectName)."&attributeList=".urlencode(var_export($this->attributeList, true))."&typeList=".urlencode(var_export($this->typeList, true));;
		$this->string .= "\n*/";
	}
	
	// -------------------------------------------------------------
	function CreateGetAllFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","string limit"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tstatic function GetList(\$fcv_array, \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$limit = (\$limit != ''?\"TOP \$limit\":'');";
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."List = Array();";
		$this->string .= "\n\t\t\ttry";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\t\$pog_query = \"select \$limit ".strtolower($this->objectName)."id from ".strtolower($this->objectName)." where \";";
		$this->string .= "\n\t\t\t\tfor (\$i=0, \$c=sizeof(\$fcv_array)-1; \$i<\$c; \$i++)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".".$this->objectName."::Escape(\$fcv_array[\$i][2]).\"' AND \";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".".$this->objectName."::Escape(\$fcv_array[\$i][2]).\"' order by ".strtolower($this->objectName)."id asc\";";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$pog_query) as \$row)";
		$this->string .= "\n\t\t\t\t{";
      	$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)." = new ".$this->objectName."();";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)."->Get(\$row['".strtolower($this->objectName)."id']);";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)."List[] = \$".strtolower($this->objectName).";";
   		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tswitch (strtolower(\$sortBy))";
		$this->string .= "\n\t\t\t\t{";
		foreach ($this->attributeList as $attribute)
		{
			$this->string .= "\n\t\t\t\t\tcase strtolower(\"$attribute\"):";
			$this->string .= "\n\t\t\t\t\t\tusort(\$".strtolower($this->objectName)."List, array(\"".$this->objectName."\", \"Compare".$this->objectName."By".ucfirst($attribute)."\"));";
			$this->string .= "\n\t\t\t\t\tif (!\$ascending)";
			$this->string .= "\n\t\t\t\t\t\t{";
			$this->string .= "\n\t\t\t\t\t\t\t\$".strtolower($this->objectName)."List = array_reverse(\$".strtolower($this->objectName)."List);";
			$this->string .= "\n\t\t\t\t\t\t}";
			$this->string .= "\n\t\t\t\t\tbreak;";
		}
		$this->string .= "\n\t\t\t\t\tcase \"\":";
		$this->string .= "\n\t\t\t\t\tdefault:";
		$this->string .= "\n\t\t\t\t\tbreak;";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\treturn \$".strtolower($this->objectName)."List;";
		$this->string .="\n\t\t\t}";
		$this->string .="\n\t\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t\t{";
		$this->string .="\n\t\t\t\tthrow new Exception(\$e->getMessage());";
   		$this->string .="\n\t\t\t}";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\treturn null;";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateCompareFunctions()
	{
		include_once("class.misc.php");
		$misc = new Misc(array());
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			$this->string .= "\n\t$this->separator\n\t";
			$this->string .= $this->CreateComments("private function to sort an array of $this->objectName by $attribute",'',"+1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2");
			$this->string .= "\tfunction Compare".$this->objectName."By".ucfirst($attribute)."(\$".strtolower($this->objectName)."1, \$".strtolower($this->objectName)."2)\n\t{";
			if ($misc->TypeIsNumeric($this->typeList[$x]))
			{
				$this->string .= "\n\t\treturn \$".strtolower($this->objectName)."1->$attribute > \$".strtolower($this->objectName)."2->$attribute;";
			}
			else
			{
				$this->string .= "\n\t\treturn strcmp(strtolower(\$".strtolower($this->objectName)."1->$attribute), strtolower(\$".strtolower($this->objectName)."2->$attribute));";
			}
			$this->string .= "\n\t}";
			$x++;
		}
	}
	
	// -------------------------------------------------------------
	function CreateEscapeFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("This function will always try to encode \$text to base64, except when \$text is a number. This allows us to Escape all data before they're inserted in the database, regardless of attribute type.",array(1=>"string \$text"),"base64_encoded \$text");
		$this->string .= "\tfunction Escape(\$text)"; 
		$this->string .= "\n\t{";
		$this->string .= "\n\t\tif (!is_numeric(\$text))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn base64_encode(\$text);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$text;";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateUnescapeFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= "function Unescape(\$text)"; 
		$this->string .= "\n\t{";
		$this->string .= "\n\t\tif (!is_numeric(\$text))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn base64_decode(\$text);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$text;";
		$this->string .= "\n\t}";
	}
}
?>
