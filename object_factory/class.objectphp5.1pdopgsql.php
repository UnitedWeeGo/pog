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
		$misc = new Misc(array());
		$this->string = "<?php\n";
		$this->string .= $this->CreatePreface();
		$this->string .= "\nclass ".$this->objectName."\n{\n\t";
		$this->string.="public \$".strtolower($this->objectName)."Id = '';\n\n\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "BELONGSTO")
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var INTEGER\n\t";
				$this->string .=" */\n\t";
				$this->string.="public $".strtolower($attribute)."Id;\n\t";
				$this->string.="\n\t";
			}
			else if ($this->typeList[$x] == "HASMANY")
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var private array of $attribute objects\n\t";
				$this->string .=" */\n\t";
				$this->string.="private \$_".strtolower($attribute)."List;\n\t";
				$this->string.="\n\t";
			}
			else
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var ".stripcslashes($this->typeList[$x])."\n\t";
				$this->string .=" */\n\t";
				$this->string.="public $".$attribute.";\n\t";
				$this->string.="\n\t";
			}
		}
		//	create attribute => type array map
		//	needed for setup
		$this->string .= "public \$pog_attribute_type = array(\n\t\t";
				$this->string .= "\"".strtolower($this->objectName)."id\" => array(\"NUMERIC\", \"INTEGER\"),\n\t\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "BELONGSTO")
			{
				$this->string .= "\"".strtolower($attribute)."id\" => array(\"".$misc->InterpretType($this->typeList[$x])."\", \"".$misc->GetAttributeType($this->typeList[$x])."\"".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ', "'.$misc->InterpretLength($this->typeList[$x]).'"' : '')."),\n\t\t";
			}
			else if ($this->typeList[$x] == "HASMANY")
			{
				$this->string .= "\"_".strtolower($attribute)."list\" => array(\"".$misc->InterpretType($this->typeList[$x])."\", \"".$misc->GetAttributeType($this->typeList[$x])."\"".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ', "'.$misc->InterpretLength($this->typeList[$x]).'"' : '')."),\n\t\t";
			}
			else
			{
				$this->string .= "\"".strtolower($attribute)."\" => array(\"".$misc->InterpretType($this->typeList[$x])."\", \"".$misc->GetAttributeType($this->typeList[$x])."\"".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ', "'.$misc->InterpretLength($this->typeList[$x]).'"' : '')."),\n\t\t";
			}
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
		$j = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$i] != "BELONGSTO" && $this->typeList[$i] != "HASMANY")
			{
				if ($j == 0)
				{
					$this->string .= '$'.$attribute.'=\'\'';
				}
				else
				{
					$this->string .= ', $'.$attribute.'=\'\'';
				}
				$j++;
			}
			$i++;
		}
		$this->string .= ")\n\t{";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "HASMANY")
			{
				$this->string .="\n\t\t\$this->_".strtolower($attribute)."List = array();";
			}
			else if ($this->typeList[$x] != "BELONGSTO")
			{
				$this->string .= "\n\t\t\$this->".$attribute." = $".$attribute.";";
			}
			$x++;
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
		$this->string .="\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':host='.\$GLOBALS['configuration']['host'].';dbname='.\$GLOBALS['configuration']['db'], \$GLOBALS['configuration']['user'], \$GLOBALS['configuration']['pass']);";
		$this->string .="\n\t\t\t\$this->pog_query = \"select * from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id= ? LIMIT 1\";";
		$this->string .="\n\t\t\t\$stmt = \$Database->prepare(\$this->pog_query);";
		$this->string .="\n\t\t\tif (\$stmt->execute(array(\$".strtolower($this->objectName)."Id)))";
		$this->string .="\n\t\t\t{";
		$this->string .="\n\t\t\t\twhile (\$row = \$stmt->fetch())";
		$this->string .="\n\t\t\t\t{";
		$this->string .="\n\t\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['".strtolower($this->objectName)."id'];";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY")
			{
				if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "\n\t\t\t\t\t\$this->".strtolower($attribute)."Id = \$row['".strtolower($attribute)."id'];";
					}
					else
					{
						$this->string .= "\n\t\t\t\t\t\$this->".$attribute." = \$row['".strtolower($attribute)."'];";
					}
				}
				else
				{
					$this->string .= "\n\t\t\t\t\t\$this->".$attribute." = \$this->Unescape(\$row['".strtolower($attribute)."']);";
				}
			}
		}
		$this->string .="\n\t\t\t\t}";
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
			case "pgsql":
				$this->sql .= "\tCREATE TABLE ".strtolower($this->objectName)." (\n\t".strtolower($this->objectName)."id SERIAL,";
				$x=0;
				foreach ($this->attributeList as $attribute)
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
							$this->sql .= "\n\t".strtolower($attribute)."id INTEGER,";
					}
					else if ($this->typeList[$x] != "HASMANY")
					{
						$this->sql .= "\n\t".strtolower($attribute)." ".stripcslashes($this->typeList[$x]).",";
					}
					$x++;
				}
				$this->sql .= " PRIMARY KEY  (".strtolower($this->objectName)."id)) WITHOUT OIDS;";
				break;
			break;
		}
	}

	// -------------------------------------------------------------
	function CreateSaveFunction($deep = false)
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Saves the object to the database",'',"integer $".strtolower($this->objectName)."Id");
		if ($deep)
		{
			$this->string .= "\tfunction Save(\$deep = true)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction Save()\n\t{";
		}
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':host='.\$GLOBALS['configuration']['host'].';dbname='.\$GLOBALS['configuration']['db'], \$GLOBALS['configuration']['user'], \$GLOBALS['configuration']['pass']);";
		$this->string .= "\n\t\t\t\$this->pog_query = \"select ".strtolower($this->objectName)."id from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id='\$this->".strtolower($this->objectName)."Id' limit 1\";";
		$this->string .= "\n\t\t\tif (\$Database->query(\$this->pog_query)!=null)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// update object";
		$this->string .= "\n\t\t\t\t\$this->pog_query = \"update ".strtolower($this->objectName)." set ";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY")
			{
				if ($x == (count($this->attributeList)-1))
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id = '\".\$this->".strtolower($attribute)."Id.\"'";
					}
					else
					{
						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= strtolower($attribute)." = '\".\$this->".$attribute.".\"'";
						}
						else
						{
							$this->string .= strtolower($attribute)." = '\".\$this->Escape(\$this->".$attribute.").\"'";
						}
					}
				}
				else
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id = '\".\$this->".strtolower($attribute)."Id.\"', ";
					}
					else
					{

						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= strtolower($attribute)." = '\".\$this->".$attribute."\, ";
						}
						else
						{
							$this->string .= strtolower($attribute)." = '\".\$this->Escape(\$this->".$attribute.").\"', ";
						}
					}
				}
			}
			$x++;
		}
		$this->string .= " where ".strtolower($this->objectName)."id = '\".\$this->".strtolower($this->objectName)."Id.\"';\"";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// insert object";
		$this->string .= "\n\t\t\t\t\$this->pog_query = \"insert into ".strtolower($this->objectName)." (";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY")
			{
				if ($x == (count($this->attributeList)-1))
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id";
					}
					else
					{
						$this->string .= strtolower($attribute);
					}
				}
				else
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id, ";
					}
					else
					{
						$this->string .= strtolower($attribute).", ";
					}
				}
			}
			$x++;
		}
		$this->string .= ") values (";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY")
			{
				if ($x == (count($this->attributeList)-1))
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "'\".\$this->".strtolower($attribute)."Id.\"'";
					}
					else
					{
						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= "'\".\$this->".$attribute.".\"'";
						}
						else
						{
							$this->string .= "'\".\$this->Escape(\$this->".$attribute.").\"'";
						}
					}
				}
				else
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "'\".\$this->".strtolower($attribute)."Id.\"', ";
					}
					else
					{
						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= "'\".\$this->".$attribute.".\"', ";
						}
						else
						{
							$this->string .= "'\".\$this->Escape(\$this->".$attribute.").\"', ";
						}
					}
				}
			}
			$x++;
		}
		$this->string .= ")\";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$Database->query(\$this->pog_query);";
		$this->string .= "\n\t\t\tif (\$this->".strtolower($this->objectName)."Id == \"\")";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->pog_query = (\"select max(".strtolower($this->objectName)."id) as max from ".strtolower($this->objectName)."\");";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['max'];";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		if ($deep)
		{
			$this->string .= "\n\t\t\tif (\$deep)";
			$this->string .= "\n\t\t\t{";
			$i = 0;
			foreach ($this->typeList as $type)
			{
				if ($type == "HASMANY")
				{
					$this->string .= "\n\t\t\t\t$".strtolower($this->attributeList[$i])."List = \$this->Get".ucfirst($this->attributeList[$i])."List();";
					$this->string .= "\n\t\t\t\tforeach (\$this->_".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t\t{";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->Save(\$deep);";
					$this->string .= "\n\t\t\t\t}";
				}
				$i++;
			}
			$this->string .= "\n\t\t\t}";
		}
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
	function CreateDeleteFunction($deep = false)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Deletes the object from the database",'',"integer \$affectedRows");
		if ($deep)
		{
			$this->string .= "\tfunction Delete(\$deep = false)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction Delete()\n\t{";
		}
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		if ($deep)
		{
			$this->string .= "\n\t\t\tif (\$deep)";
			$this->string .= "\n\t\t\t{";
			$i = 0;
			foreach ($this->typeList as $type)
			{
				if ($type == "HASMANY")
				{
					$this->string .= "\n\t\t\t\t$".strtolower($this->attributeList[$i])."List = \$this->Get".ucfirst($this->attributeList[$i])."List();";
					$this->string .= "\n\t\t\t\tforeach ($".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t\t{";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->Delete(\$deep);";
					$this->string .= "\n\t\t\t\t}";
				}
				$i++;
			}
			$this->string .= "\n\t\t\t}";
		}
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':host='.\$GLOBALS['configuration']['host'].';dbname='.\$GLOBALS['configuration']['db'], \$GLOBALS['configuration']['user'], \$GLOBALS['configuration']['pass']);";
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
	function CreateAddChildFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $child object to this one",'',"");
		$this->string .= "\tfunction Add".ucfirst(strtolower($child))."(&\$".strtolower($child).")\n\t{";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List[] =& \$".strtolower($child).";";
		$this->string .= "\n\t\t\$".strtolower($child)."->".strtolower($this->objectName)."Id = \$this->".strtolower($this->objectName)."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetChildrenFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Gets a list of $child objects associated to this one",'',"boolean");
		$this->string .= "\tfunction Get".ucfirst(strtolower($child))."List()\n\t{";
		$this->string .= "\n\t\t\$".strtolower($child)." = new ".$child."();";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List = array_merge(\$this->_".strtolower($child)."List, $".strtolower($child)."->GetList(array(array(\"".strtolower($this->objectName)."Id\", \"=\", \$this->".strtolower($this->objectName)."Id))));";
		$this->string .= "\n\t\treturn \$this->_".strtolower($child)."List;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetParentFunction($parent)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $parent object to this one",'',"");
		$this->string .= "\tfunction Set".ucfirst(strtolower($parent))."(&\$".strtolower($parent).")\n\t{";
		$this->string .= "\n\t\t\$this->".strtolower($parent)."Id = $".strtolower($parent)."->".strtolower($parent)."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetParentFunction($parent)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $parent object to this one",'',"boolean");
		$this->string .= "\tfunction Get".ucfirst(strtolower($parent))."()\n\t{";
		$this->string .= "\n\t\t\$".strtolower($parent)." = new ".$parent."();";
		$this->string .= "\n\t\treturn $".strtolower($parent)."->Get(\$this->".strtolower($parent)."Id);";
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
		$this->string .= "\n* @version ".$GLOBALS['configuration']['versionNumber']." ".$GLOBALS['configuration']['revisionNumber'];
		$this->string .= "\n* @see http://www.phpobjectgenerator.com/plog/tutorials/42/pdo-postgresql";
		$this->string .= "\n* @copyright ".$GLOBALS['configuration']['copyright'];
		$this->string .= "\n* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=".$_SESSION['pdoDriver']."&objectName=".urlencode($this->objectName)."&attributeList=".urlencode(var_export($this->attributeList, true))."&typeList=".urlencode(var_export($this->typeList, true));;
		$this->string .= "\n*/";
	}

	// -------------------------------------------------------------
	function CreateGetAllFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tstatic function GetList(\$fcv_array, \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$sqlLimit = (\$limit != '' && \$sortBy == ''?\"LIMIT \$limit\":'');";
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."List = Array();";
		$this->string .= "\n\t\t\ttry";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':host='.\$GLOBALS['configuration']['host'].';dbname='.\$GLOBALS['configuration']['db'], \$GLOBALS['configuration']['user'], \$GLOBALS['configuration']['pass']);";
		$this->string .= "\n\t\t\t\t\$pog_query = \"select ".strtolower($this->objectName)."id from ".strtolower($this->objectName)." where \";";
		$this->string .= "\n\t\t\t\tfor (\$i=0, \$c=sizeof(\$fcv_array)-1; \$i<\$c; \$i++)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".".$this->objectName."::Escape(\$fcv_array[\$i][2]).\"' AND \";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".".$this->objectName."::Escape(\$fcv_array[\$i][2]).\"' order by ".strtolower($this->objectName)."id asc \$sqlLimit\";";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$pog_query) as \$row)";
		$this->string .= "\n\t\t\t\t{";
      	$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)." = new ".$this->objectName."();";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)."->Get(\$row['".strtolower($this->objectName)."id']);";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)."List[] = \$".strtolower($this->objectName).";";
   		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (\$sortBy != '')";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$f = '';";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)." = new $this->objectName();";
		$this->string .= "\n\t\t\t\t\tif (isset(\$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)]) && \$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)][0] == \"NUMERIC\")";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$f = 'return \$".strtolower($this->objectName)."1->'.\$sortBy.' > \$".strtolower($this->objectName)."2->'.\$sortBy.';';";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse if (isset(\$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)]))";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$f = 'return strcmp(strtolower(\$".strtolower($this->objectName)."1->'.\$sortBy.'), strtolower(\$".strtolower($this->objectName)."2->'.\$sortBy.'));';";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\tusort(\$".strtolower($this->objectName)."List, create_function('\$".strtolower($this->objectName)."1, \$".strtolower($this->objectName)."2', \$f));";
		$this->string .= "\n\t\t\t\t\tif (!\$ascending)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$".strtolower($this->objectName)."List = array_reverse(\$".strtolower($this->objectName)."List);";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\tif (\$limit != '')";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$limitParts = explode(',', \$limit);";
		$this->string .= "\n\t\t\t\t\t\tif (sizeof(\$limitParts) > 1)";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\treturn array_slice(\$".strtolower($this->objectName)."List, \$limitParts[0], \$limitParts[1]);";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\treturn array_slice(\$".strtolower($this->objectName)."List, 0, \$limit);";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\treturn \$".strtolower($this->objectName)."List;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tcatch(PDOException \$e)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tthrow new Exception(\$e->getMessage());";;
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn null;";
		$this->string .= "\n\t}";
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
