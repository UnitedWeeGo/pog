<?
class Object
{
	var $string;
	var $sql;
	var $objectName;
	var $attributeList;
	var $optionList;
	var $separator = "\n\t";
	
	// -------------------------------------------------------------
	function Object($objectName, $attributeList = '', $optionList ='')
	{
		$this->objectName = $objectName;
		$this->attributeList = $attributeList;
		$this->optionList = $optionList;
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
		$this->string .= $this->CreateComments("Gets the object from the database",array(strtolower($this->objectName)."Id"),"object");
		$this->string .="\tfunction Get(\$".strtolower($this->objectName)."Id)\n\t{";
		$this->string .= "\n\t\t\$Database = new DatabaseConnection();";
		$this->string .= "\n\t\t\$query = \"select * from `".strtolower($this->objectName)."` where `".strtolower($this->objectName)."id`='\".\$".strtolower($this->objectName)."Id.\"' LIMIT 1\";";
		$this->string .= "\n\t\t\$Database->Query(\$query);";
		$this->string .= "\n\t\t\$this->".strtolower($this->objectName)."Id = \$Database->Result(0,\"".strtolower($this->objectName)."id\");";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			/*if (!strstr(strtolower($this->optionList[$x]),"int"))
			{
				*/$this->string .= "\n\t\t\$this->".$attribute." = \$Database->Unescape(\$Database->Result(0,\"".strtolower($attribute)."\"));";
			/*}
			else
			{
				$this->string .= "\n\t\t\$this->".$attribute." = \$Database->Result(0,\"".strtolower($attribute)."\");";
			}*/
			$x++;
		}
		$this->string .= "\n\t\treturn \$this;";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateSQLQuery()
	{
		$this->sql .= "\tCREATE TABLE `".strtolower($this->objectName)."` (".strtolower($this->objectName)."id int(11) auto_increment,";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($x == (count($this->optionList)-1))
			{
				$this->sql .= "\n\t`".strtolower($attribute)."` ".$this->optionList[$x].",";
			}
			else
			{
				$this->sql .= "\n\t`".strtolower($attribute)."` ".$this->optionList[$x].",";
			}
			$x++;
		}
		$this->sql .= " PRIMARY KEY  (`".strtolower($this->objectName)."id`));";
	}
	
	// -------------------------------------------------------------
	function CreateSaveFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Saves the object to the database",'',"nothing");
		$this->string .= "\tfunction Save()\n\t{";
		$this->string .= "\n\t\t\$Database = new DatabaseConnection();";
		$this->string .= "\n\t\t\$query = \"select * from `".strtolower($this->objectName)."` where `".strtolower($this->objectName)."id`='\".\$this->".strtolower($this->objectName)."Id.\"' LIMIT 1\";";
		$this->string .= "\n\t\t\$Database->Query(\$query);";
		$this->string .= "\n\t\tif (\$Database->Rows() > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$query = \"update `".strtolower($this->objectName)."` set ";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($x == (count($this->attributeList)-1))
			{
				/*if (strstr(strtolower($this->optionList[$x]),"int"))
				{
					$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$this->$attribute.\"' ";
				}
				else
				{*/
					$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$Database->Escape(\$this->$attribute).\"' ";
				/*}*/
			}
			else
			{
				/*if (strstr(strtolower($this->optionList[$x]),"int"))
				{
					$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$this->$attribute.\"', ";
				}
				else
				{*/
					$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$Database->Escape(\$this->$attribute).\"', ";
				/*}*/
			}
			$x++;
		}
		$this->string .= "where `".strtolower($this->objectName)."id`='\".\$this->".strtolower($this->objectName)."Id.\"'\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$query = \"insert into `".strtolower($this->objectName)."` (";
		$y=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($y == (count($this->attributeList)-1))
			{
				$this->string .= "`".strtolower($attribute)."` ";
			}
			else 
			{
				$this->string .= "`".strtolower($attribute)."`, ";
			}
			$y++;
		}
		$this->string .= ") values (";
		$z=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($z == (count($this->attributeList)-1))
			{
				/*if (strstr(strtolower($this->optionList[$z]),"int"))
				{
					$this->string .= "\n\t\t\t'\".\$this->$attribute.\"' ";
				}
				else
				{*/
					$this->string .= "\n\t\t\t'\".\$Database->Escape(\$this->$attribute).\"' ";
				/*}*/
			}
			else
			{
				/*if (strstr(strtolower($this->optionList[$z]),"int"))
				{
					$this->string .= "\n\t\t\t'\".\$this->$attribute.\"', ";
				}
				else
				{*/
					$this->string .= "\n\t\t\t'\".\$Database->Escape(\$this->$attribute).\"', ";
				/*}*/
			}
			$z++;
		}
		$this->string .= ")\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\t\$Database->InsertOrUpdate(\$query);";
		$this->string .= "\n\t\tif (\$this->".strtolower($this->objectName)."Id == \"\")";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->objectName)."Id = \$Database->GetCurrentId();";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
	}
	
	// -------------------------------------------------------------
	function CreateSaveNewFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Clones the object and saves it to the database",'',"nothing");
		$this->string .="\tfunction SaveNew()\n\t{";
		$this->string .= "\n\t\t\$this->".strtolower($this->objectName)."Id='';";
		$this->string .= "\n\t\t\$this->Save();";
		$this->string .= "\n\t}";
	}
	
	
	// -------------------------------------------------------------
	function CreateDeleteFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Deletes the object from the database",'',"nothing");
		$this->string .= "\tfunction Delete()\n\t{";
		$this->string .= "\n\t\t\$Database = new DatabaseConnection();";
		$this->string .= "\n\t\t\$query = \"delete from `".strtolower($this->objectName)."` where `".strtolower($this->objectName)."id`='\".\$this->".strtolower($this->objectName)."Id.\"'\";";
		$this->string .= "\n\t\t\$Database->Query(\$query);";
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
		$this->string .= "//\tPhp Object Generator v.1.0\n"; 
		$this->string .= "//\thttp://www.phpobjectgenerator.com\n\n"; 
		$this->string .= "//\tThis SQL query will create the table to store your object.\n";
		$this->CreateSQLQuery();
		$this->string .= "/*\n".$this->sql."\n*/";
	}
	
	// -------------------------------------------------------------
	function CreateGetAllFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Gets all objects from the database",'',"array of objects");
		$this->string .= "\tstatic function Get".$this->objectName."List(\$field,\$comparator,\$fieldValue)\n\t{\n\t\t";
		$this->string .= "\n\t\t\$".strtolower($this->objectName)."List = Array();";
		$this->string .= "\n\t\t\$Database = new DatabaseConnection();";
		$this->string .= "\n\t\t\$query = \"select ".strtolower($this->objectName)."id from ".strtolower($this->objectName)." where `\".\$field.\"`\".\$comparator.\"'\".\$Database->Escape(\$fieldValue).\"'\";";
		$this->string .= "\n\t\t\$Database->Query(\$query);";
		$this->string .= "\n\t\tfor (\$i=0; \$i < \$Database->Rows(); \$i++)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)." = new $this->objectName();";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."->Get(\$Database->Result(\$i,\"".strtolower($this->objectName)."id\"));";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."List[] = $".strtolower($this->objectName).";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$".strtolower($this->objectName)."List;";
		$this->string .= "\n\t}";
	}
}
?>