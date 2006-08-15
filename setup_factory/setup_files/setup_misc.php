<?php


	/**
	 * Specifies what test data is used during unit testing (step 2 of the setup process)
	 * Todo: Can be improved but satisfatory for now
	 * @return array
	 */
	function InitializeTestValues($pog_attribute_type)
	{
		$DATETIME = '1997-12-15 23:50:26';
		$DATE = '1997-12-15';
		$TIMESTAMP = '1997-12-15 23:50:26';
		$TIME = '23:50:26';
		$YEAR = '1997';
		$DECIMAL =
		$DOUBLE =
		$FLOAT =
		$BIGINT =
		$INT = '12345678';
		$SMALLINT = '1234';
		$MEDIUMINT = '12345';
		$TINYINT = '1';
		$CHAR = 'L';
		$VARCHAR =
		$TEXT =
		$TINYBLOB =
		$TINYTEXT =
		$BLOB =
		$MEDIUMBLOB =
		$MEDIUMTEXT =
		$LONGBLOB =
		$LONGTEXT = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry';
		$attribute_testValues = array();
		array_shift($pog_attribute_type); //get rid of objectid
		foreach ($pog_attribute_type as $attribute => $property)
		{
			if (isset($property[2]))
			//length is specified, for e.g. if attribute = VARCHAR(255), $property[2]=255
			{
				$limit =  explode(',', $property[2]);
				//field is limited
				if (intval($limit[0]) > 0)
				{
					if (isset($limit[1]) && intval($limit[1]) > 0)
					{
						//decimal, enum, set
						$attribute_testValues[$attribute] = substr(${$property[1]}, 0, ceil($limit[0]*0.6)).".".substr(${$property[1]}, 0, $limit[1]);
					}
					else
					{
						$attribute_testValues[$attribute] = substr(${$property[1]}, 0, ceil($limit[0] * 0.6));
					}
				}
			}
			else
			//length not specified, but we still need to account for default mysql behavior
			//for eg, FLOAT(X), if X isn't specified, mysql defaults to (10,2).
			{
				if ($property[1] == "FLOAT" || $property[1] == "DOUBLE")
				{
					$attribute_testValues[$attribute] = "1234.56";
				}
				else if ($property[1] != "HASMANY" && $property[1] != "BELONGSTO")
				{
					$attribute_testValues[$attribute] = ${$property[1]};
				}
			}
		}
		return $attribute_testValues;
	}

	/**
	 * Specifies how object attributes are rendered during scaffolding (step 3 of the setup process)
	 * Todo: Can be improved but satisfactory for now
	 * @param string $attributeName
	 * @param string $attributeType
	 * @param string $attributeValue
	 * @param int $objectId
	 * @return string $html
	 */
	function ConvertAttributeToHtml($attributeName, $attributeProperties, $attributeValue='', $objectId='')
	{
		switch ($attributeProperties[1])
		{
			case "ENUM":
				$enumParts = explode(',', $attributeProperties[2]);
				$html = "<select id='".($objectId != ''?$attributeName."_".$objectId:$attributeName)."' class='s'>";
					foreach ($enumParts as $enumPart)
					{
						if ($attributeValue == trim($enumPart, "\' "))
						{
							$html .= "<option value='".trim($enumPart, "\' ")."' selected>".trim($enumPart, "\' ")."</option>";
						}
						else
						{
							$html .= "<option value='".trim($enumPart, "\' ")."'>".trim($enumPart, "\' ")."</option>";
						}
					}
					$html .= "</select>";
			break;
			case "HASMANY":
				$html = $attributeValue;
			break;
			case "BELONGSTO":
				$html = $attributeValue;
			break;
			case "MEDIUMBLOB":
				$html = "sorry. cannot render attribute of type LONGBLOB";
			break;
			case "LONGBLOB":
				$html = "sorry. cannot render attribute of type LONGBLOB";
			break;
			case "TEXT":
			case "LONGTEXT":
			case "BINARY":
			case "MEDIUMTEXT":
			case "TINYTEXT":
			case "VARCHAR":
			case "TINYBLOB":
			case "BLOB":
				$html = "<textarea class='t' id='".($objectId != ''?$attributeName."_".$objectId:$attributeName)."'>".($attributeValue != ''?$attributeValue:'')."</textarea>";
			break;
			case "DATETIME":
			case "DATE":
			case "TIMESTAMP":
			case "TIME":
			case "YEAR":
			case "DECIMAL":
			case "DOUBLE":
			case "FLOAT":
			case "BIGINT":
			case "INT":
			case "YEAR":
			case "SMALLINT":
			case "MEDIUMINT":
			case "TINYINT":
			case "CHAR":
				$html = "<input class='i' id='".($objectId != ''?$attributeName."_".$objectId:$attributeName)."' value='".($attributeValue != ''?$attributeValue:'')."' type='text' />";
			break;
			default:
				$html = substr($attributeValue, 0, 500);
				if (strlen($attributeValue) > 500)
				{
					$html .= "...";
				}
			break;
		}
		return $html;
	}

	/**
	 * Renders an object as an Xtree Node
	 *
	 * @param unknown_type $child
	 */
	function ConvertObjectToNode(&$instance, &$masterNode, $js, $anchor)
	{
		$attributeList = array_keys(get_object_vars($instance));
		$objectName = $className = get_class($instance);
		$node = &$masterNode->addItem(new XNode("<span style='color:#0BAA9D'>[".$instance->{strtolower($className)."Id"}."]</span>  <a href='#' onclick='javascript:sndReq(\"Delete\", getOpenNodes(), \"$objectName\", \"".$instance->{strtolower($objectName).'Id'}."\", this.parentNode.parentNode.parentNode.parentNode.id, $js, \"$anchor\");return false;'><img src=\"./setup_images/button_delete.gif\" border=\"0\"/></a>", false,"setup_images/folderclose.gif","setup_images/folderopen.gif"));

		//regular attributes
		foreach($attributeList as $attribute)
		{
			if ($attribute != "pog_attribute_type" && $attribute!= "pog_query" )
			{
				if (isset($instance->pog_attribute_type[strtolower($attribute)]))
				{
					$thisValue = ConvertAttributeToHtml($attribute, $instance->pog_attribute_type[strtolower($attribute)], $instance->{$attribute}, $instance->{$attributeList[0]});
					$subnode = &$node->addItem(new XNode($attribute."<span style='font-weight:normal;color:#ADA8B2;'>{".$instance->pog_attribute_type[strtolower($attribute)][1]."}</span><br/>".$thisValue."<br/><br/>", false,'',"setup_images/folderopen.gif"));
				}
			}
		}

		//parents and children
		foreach ($instance->pog_attribute_type as $attribute_name => $attrubute_type)
		{
			if ($attrubute_type[1] == "HASMANY" || $attrubute_type[1] == "BELONGSTO")
			{
				if ($attrubute_type[1] == "BELONGSTO")
				{
					eval ('$value = $instance->'.strtolower($attribute_name).'Id;');
					$thisValue = ConvertAttributeToHtml($attribute_name, $attrubute_type, $value, '');
					$subnode = &$node->addItem(new XNode("<br/>".$attribute_name."<span style='font-weight:normal;color:#ADA8B2;'>{".($attrubute_type[1] == "HASMANY" ? "CHILD" : "PARENT")."}</span><br/>".$thisValue."<br/><br/>", false,'',"setup_images/folderopen.gif"));
				}
				else
				{
					$value = '';
					eval('$childrenList = $instance->Get'.ucfirst(strtolower($attribute_name)).'List();');
					if (sizeof($childrenList) > 0)
					{
						$myNode = &$node->addItem(new XNode("<span style='color:#4d4a4a'>[".$attribute_name."List]{Dimensions:[".sizeof($childrenList)."]}</span>", false, "setup_images/folderclose.gif","setup_images/folderopen.gif"));
						$child = $childrenList[0];
						$js2 = "new Array(";
						$attributeList = array_keys(get_object_vars($child));
						$x=0;
						foreach($attributeList as $attribute)
						{
							if ($attribute != "pog_attribute_type" && $attribute!= "pog_query")
							{
								if ($x != 0 && isset($child->pog_attribute_type[strtolower($attribute)]))
								{
									$js2 .= '"'.$attribute.'",';
								}
							}
							$x++;
						}
						$js2 = trim($js2, ",");
						$js2 .= ")";

						foreach ($childrenList as $child)
						{
							/*$value .= $child->{strtolower($attribute_name)."Id"} . ",";*/
							ConvertObjectToNode($child, $myNode, $js2, $anchor);
						}
					}
					else
					{
						$node->addItem(new XNode("<span style='color:#4d4a4a'>[".$attribute_name."List]{Dimensions:[0]}</span><br/><br/>", false, '',"setup_images/folderopen.gif"));
					}
				}
			}
		}
		$subnode = &$node->addItem(new XNode("<br/><a style='float:left;' href='#' onclick='javascript:PleaseWait(\"".$instance->{strtolower($objectName).'Id'}."\"); sndReq(\"Update\", getOpenNodes(), \"$objectName\", \"".$instance->{strtolower($objectName).'Id'}."\", this.parentNode.parentNode.parentNode.parentNode.id, $js, \"$anchor\");return false;'><img src='./setup_images/button_update.gif' border='0'/></a><span id='pleasewait".$instance->{strtolower($objectName).'Id'}."' style='float:left;display:none;'><img src='./setup_images/loading.gif' style='float:left;'/></span><br/>", false,'',"folderopen.gif"));
	}


	/**
	 * Populates object attributes with test values
	 *
	 * @param unknown_type $object
	 * @return unknown
	 */
	function PopulateTestValues(&$object)
	{
		$attributeList = array_keys(get_object_vars($object));
		$type_value = InitializeTestValues($object->pog_attribute_type);

		$objectName = get_class($object);
		foreach($attributeList as $attribute)
		{
			if (isset($object->pog_attribute_type[strtolower($attribute)]))
			{
				if (isset($type_value[strtolower($attribute)]))
				{
					$object->{$attribute} = $type_value[strtolower($attribute)];
				}
				else if ($object->pog_attribute_type[strtolower($attribute)][0] != "OBJECT")
				{
					$object->{$attribute} = "1";
				}
			}
		}
		eval ("\$object -> ".strtolower($objectName)."Id = '';");
		return $object;
	}

	/**
	 * Extracts @link from object file
	 *
	 * @param unknown_type $objectFilePath
	 * @return unknown
	 */
	function GetAtLink($objectFilePath)
	{
		$content = file_get_contents($objectFilePath);
		$contentParts = split("<b>",$content);
		if (isset($contentParts[1]))
		{
			$contentParts2 = split("</b>",$contentParts[1]);
		}
		if (isset($contentParts2[0]))
		{
			$className = trim($contentParts2[0]);
		}
		if (isset($className))
		{
			$linkParts1 = split("\*\/", $contentParts[1]);
			$linkParts2 = split("\@link", $linkParts1[0]);
			$link = $linkParts2[1];
		}
		return $link;
	}

	/**
	 * Extracts object name from object file. Do not rely on filename.
	 *
	 * @param unknown_type $objectFilePath
	 */
	function GetObjectName($objectFilePath)
	{
		$content = file_get_contents($objectFilePath);
		$contentParts = split("<b>",$content);
		if (isset($contentParts[1]))
		{
			$contentParts2 = split("</b>",$contentParts[1]);
		}
		if (isset($contentParts2[0]))
		{
			$className = trim($contentParts2[0]);
		}
		return $className;
	}

	/**
	 * Adds message to error queue
	 *
	 * @param unknown_type $error
	 */
	function AddError($error)
	{
		if (isset($_SESSION['errorMessages']))
		{
			$errorMessages = unserialize($_SESSION['errorMessages']);
			if (!array_search($error, $errorMessages))
			{
				$errorMessages[] = $error;
			}
		}
		else
		{
			$errorMessages = array();
			$errorMessages[] = $error;
		}
		$_SESSION['errorMessages'] = serialize($errorMessages);
	}

	/**
	 * Add message to tracing queue
	 *
	 * @param unknown_type $trace
	 */
	function AddTrace($trace)
	{
		if (isset($_SESSION['traceMessages']))
		{
			$traceMessages = unserialize($_SESSION['traceMessages']);
			$traceMessages[] = $trace;
		}
		else
		{
			$traceMessages = array();
			$traceMessages[] = $trace;
		}
		$_SESSION['traceMessages'] = serialize($traceMessages);
	}

	/**
	 * Unit tests
	 */

	/**
	 * Test the base 5 CRUD methods
	 *
	 * @param unknown_type $instance
	 * @return unknown
	 */
	function TestEssentials($instance, $optimizeAsWell = true)
	{
		$errors = 0;
		if (!TestSave($instance))
		{

			$errors++;
		}

		if (!TestSaveNew($instance))
		{
			$errors++;
		}
		if (!TestDelete($instance))
		{
			$errors++;
		}
		if (!TestGetList($instance))
		{
			$errors++;
		}
		if ($optimizeAsWell)
		{
			if (!TestOptimizeStorage(strtolower(get_class($instance))))
			{
				$errors++;
			}
		}

		if ($errors == 0)
		{
			return  true;
		}
		return  false;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $instance
	 * @return unknown
	 */
	function TestRelationsPreRequisites($instance, $allObjectsList, $thisObjectName)
	{
		if (TestIsSingle($instance))
		{
			AddTrace("\tIs single (OK)");
			return true;
		}
		else
		{
			if (!TestParentChildLink($instance, $allObjectsList, $thisObjectName))
			{
				return false;
			}
			else
			{
				AddTrace("\tIs properly connected (OK)");
				return true;
			}
		}
	}

	/**
	 * Test the optional object relations methods
	 *
	 * @param unknown_type $instance
	 * @return unknown
	 */
	function TestRelations($instance)
	{
		$errors=0;
		if (TestIsParent($instance))
		{

			if (!TestAddChild($instance))
			{
				$errors++;
			}

			if (!TestGetChildrenList($instance))
			{
				$errors++;
			}
			if (!TestDeleteDeep($instance))
			{
				$errors++;
			}
			if (!TestSaveDeep($instance))
			{
				$errors++;
			}
		}
		if (TestIsChild($instance))
		{
			if (!TestSetParent($instance))
			{
				$errors++;
			}
			if (!TestGetParent($instance))
			{
				$errors++;
			}
		}
		if ($errors == 0)
		{
			return  true;
		}
		return  false;
	}

	/**
	 * Tests whether object table already exists
	 *
	 */
	function TestStorageExists($objectName, $databaseType = "mysql")
	{
		switch ($databaseType)
		{
			case "mysql":
				$query = "show tables like '".strtolower($objectName)."'";
			break;
			case "sqlite":
				$query = "select name FROM sqlite_master WHERE type='table' and name='".strtolower($objectName)."'";
			break;
			case "pgsql":
				$query = "select table_name FROM information_schema.tables WHERE table_schema = 'public' and table_name='".strtolower($objectName)."'";
			break;
			case "odbc":
				//assume mssql
				$query = "select * from information_schema.tables where table_type = 'BASE TABLE' and table_name='".strtolower($objectName)."'";
			break;
			case "firebird":
				AddError("POG Setup doesn't support automatic table detection for Firebird databases yet. Therefore, your objects/tables may be misaligned. If POG Essential tests failed, this may very well be the case. Create the tables manually and re-run setup.");
				return true;
			break;
		}
		if (!isset($GLOBALS['configuration']['pdoDriver']))
		{
			$databaseConnection = new DatabaseConnection();
			$databaseConnection->Query($query);
		}
		else
		{
			$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			$affectedRows = $databaseConnection->query($query);
		}

		if (!isset($GLOBALS['configuration']['pdoDriver']))
		{
			if ($databaseConnection->Rows() > 0)
			{
				return  true;
			}
			return false;
		}
		else
		{
			if (sizeof($affectedRows->fetchAll()) > 0)
			{
				return true;
			}
			else
			{
				return  false;
			}
		}
	}

	/**
	 * Creates the table to store objects
	 *
	 */
	function TestCreateStorage($objectFilePath, $databaseType = "mysql")
	{
		if ($databaseType == "firebird")
		{
			AddError("POG Setup doesn't support automatic table creation for Firebird databases yet. Therefore, your objects/tables may be misaligned. If POG Essential tests failed, this may very well be the case. Create the tables manually and re-run setup.");
			return true;
		}

		$objectName = GetObjectName($objectFilePath);

		//extract sql
		$content = file_get_contents($objectFilePath);
		$contentParts = split("<b>",$content);
		if (isset($contentParts[1]))
		{
			$contentParts2 = split("</b>",$contentParts[1]);
		}
		if (isset($contentParts2[0]))
		{
			$className = trim($contentParts2[0]);
		}
		if (isset($className))
		{
			$sqlParts = split(";",$contentParts[0]);
			$sqlPart = split("CREATE",$sqlParts[0]);
			$sql = "CREATE ".$sqlPart[1].";";

			//execute sql
			if ($databaseType == "mysql" && !isset($GLOBALS['configuration']['pdoDriver']))
			{
				$databaseConnection = new DatabaseConnection();
			}
			else if ($databaseType == "sqlite" || $databaseType == "pgsql" || $databaseType == "mysql")
			{
				$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			}
			else if ($databaseType == "odbc")
			{
				$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['odbcDSN']);
			}
			if ($databaseConnection->Query($sql))
			{
				return true;
			}
		}
		AddError("Query failed: $sql");
		return false;
	}

	/**
	 * Drops the table for the corresponding object
	 *
	 */
	function TestDeleteStorage($objectName, $databaseType = "mysql")
	{
		if (!isset($GLOBALS['configuration']['pdoDriver']))
		{
			$databaseConnection = new DatabaseConnection();
		}
		else
		{
			$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
		}
		if ($databaseConnection->Query('drop table '.strtolower($objectName)))
		{
			return true;
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $object
	 * @return unknown
	 */
	function TestAlterStorage($object, $databaseType = "mysql")
	{
		if ($databaseType != "mysql")
		{
			AddError("POG Setup doesn't support table automatic alteration for non-MySQL databases yet. Therefore, your objects/tables may be misaligned. If POG Essential tests failed, this may very well be the case. Drop and recreate the tables and re-run setup.");
			return true;
		}

		//find object attributes/table columns mismatch
		$tableName = strtolower(get_class($object));
		$columns = array();

		$query = "describe $tableName";
		if (!isset($GLOBALS['configuration']['pdoDriver']))
		{
			$databaseConnection = new DatabaseConnection();
		}
		else
		{
			$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
		}
		if ($databaseConnection->Query($query))
		{
			if (!isset($GLOBALS['configuration']['pdoDriver']))
			{
				for($i = 0; $i < $databaseConnection -> AffectedRows(); $i++)
				{
					$columns[$databaseConnection->Result($i, "Field")] = $databaseConnection->Result($i, "Type");
				}
			}
			else
			{
				foreach ($databaseConnection->Query($query) as $row)
				{
			       $columns[$row['Field']] = $row['Type'];
				}
			}

			$attribute_types = $object -> pog_attribute_type;

			//columns to remove
			$columnsToRemove = array_diff(array_keys($columns), array_keys($attribute_types));

			//columns to add
			$columnsToAdd = array_diff(array_keys($attribute_types), array_keys($columns));

			//columns whose type has changed
			$otherColumns = array_intersect(array_keys($attribute_types), array_keys($columns));

			$columnsToModify = array();
			foreach ($otherColumns as $otherColumn)
			{
				$type = strtolower($attribute_types[$otherColumn][1]);
				if (isset($attribute_types[$otherColumn][2]))
				{
					$type .= "(".$attribute_types[$otherColumn][2].")";
				}
				if (strpos(strtolower($columns[$otherColumn]), $type) === false && $type != "hasmany")
				{
					if ($type == "belongsto")
					{
						$columnsToModify[strtolower($otherColumn)] = "int";
					}
					else
					{
						$columnsToModify[$otherColumn] = $type;
					}
				}
			}

			$columnsToRemove2 = array();
			foreach ($columnsToRemove as $c)
			{
				$columnsToRemove2[] = strtolower($c);
			}

			$columnsToRemove = $columnsToRemove2;

			$columnsToAdd2 = array();
			foreach ($columnsToAdd as $c)
			{
				if ($attribute_types[$c][1] != "HASMANY")
				{
					if ($attribute_types[$c][1] == "BELONGSTO")
					{
						$colMarkedForDeletion = array_search(strtolower($c)."id", $columnsToRemove);
						if ($colMarkedForDeletion === false) //this is clumsy, until we think of something better
						{
							$columnsToAdd2[] = strtolower($c)."id int";
						}
						else
						{
							//remove entry from columnsToRemove since they are the same. Will lose  data if dropped & recreated
							array_splice($columnsToRemove, $colMarkedForDeletion, 1);
						}
					}
					else
					{
						$columnsToAdd2[] = $c;
					}
				}
			}

			$common = array();
			$common = array_intersect($columnsToAdd2, $columnsToRemove);

			$columnsToAdd = array();
			foreach ($columnsToAdd2 as $col)
			{
				if (array_search($col, $common) === false)
				{
					$columnsToAdd[] = $col;
				}
			}
			$columnsToRemove2 = array();
			foreach ($columnsToRemove as $col)
			{
				if (array_search($col, $common) === false)
				{
					$columnsToRemove2[] = $col;
				}
			}


			if (sizeof($columnsToAdd) == 0 && sizeof($columnsToRemove2) == 0 && sizeof($columnsToModify) == 0)
			{
				return true;
			}

			//construct query
			$query = "alter table $tableName ";

			foreach ($columnsToRemove2 as $remove)
			{
				$query .= "drop column $remove,";
			}

			foreach ($columnsToAdd as $add)
			{
				$columnType = strtolower($attribute_types[$add][1]);
				if (isset($attribute_types[$add][2]))
				{
					$columnType .= "(".$attribute_types[$add][2].")";
				}
				$query .= "add column $add $columnType,";
			}


			foreach (array_keys($columnsToModify) as $modify)
			{
				$query .= "modify $modify ".$columnsToModify[$modify].",";
			}
			$query = trim($query, ',');
			//execute query
			if ($databaseConnection -> Query($query))
			{
				return true;
			}
		}
		AddError("Query failed: $query");
		return  false;
	}

	/**
	 * Optimizes the table by running mysql optimize
	 *
	 * @param unknown_type $objectName
	 * @return unknown
	 */
	function TestOptimizeStorage($objectName)
	{
		if (!isset($GLOBALS['configuration']['pdoDriver']))
		{
			$databaseConnection = new DatabaseConnection();
		}
		else
		{
			$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
		}
		if ($databaseConnection->Query("optimize table ".strtolower($objectName)))
		{
			AddTrace("\tOptimizing....OK!");
			return true;
		}
		return false;
	}

	/**
	 * Unit test for Save()
	 *
	 */
	function TestSave($object, $trace=true)
	{
		$className = get_class($object);
		$object = PopulateTestValues($object);
		$objectId = false;
		$object->{strtolower($className)."Id"} = "";
		$objectId = $object->Save(false);
		if(!$objectId)
		{
			if ($trace)
			{
				AddTrace("\tSave() failed");
				AddError("Query failed: ".$object->pog_query);
			}
			return false;
		}
		//cleanup test data
		$query = "delete from ".strtolower($className)." where ".strtolower($className)."Id = '".$objectId."';";
		if (!isset($GLOBALS['configuration']['pdoDriver']))
		{
			$databaseConnection = new DatabaseConnection();
		}
		else
		{
			$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
		}
		$databaseConnection->Query($query);
		if ($trace)
		{
			AddTrace("\tSave()....OK!");
		}
		return true;
	}

	/**
	 * Unit test for SaveNew()
	 *
	 */
	function TestSaveNew($object, $trace = true)
	{
		$className = get_class($object);
		if(!TestSave($object, false))
		{
			if ($trace)
			{
				AddTrace("\tSaveNew() ignored");
			}
			return false;
		}
		$objectId = $object->SaveNew(false);
		if ($objectId)
		{
			$query = "delete from ".strtolower($className)." where ".strtolower($className)."Id = '".$objectId."';";
			if (!isset($GLOBALS['configuration']['pdoDriver']))
			{
				$databaseConnection = new DatabaseConnection();
			}
			else
			{
				$databaseConnection = new PDO($GLOBALS['configuration']['pdoDriver'].':host='.$GLOBALS['configuration']['host'].';dbname='.$GLOBALS['configuration']['db'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']);
			}
			$databaseConnection->Query($query);
			if ($trace)
			{
				AddTrace("\tSaveNew()....OK!");
			}
			return true;
		}
		if ($trace)
		{
			AddTrace("\tSaveNew() failed");
			AddError("Query failed: ".$object->pog_query);
		}
		return false;
	}

	/**
	 * Unit test for GetList(). Implicitly tests Get()
	 *
	 */
	function TestGetList($object)
	{
		AddTrace("\tGetList()");
		$errors = 0;
		if (TestSave($object,false) && TestSaveNew($object, false) && TestDelete($object, false))
		{
			$className = get_class($object);
			$objectList = $object->GetList(array(array(strtolower($className)."Id", ">", 0)));
			$oldCount = count($instanceList);
			$object = PopulateTestValues($object);
			$objectId = false;
			$object->{strtolower($className)."Id"} = 0;
			$objectId = $object->Save(false);
			$objectId2 = $object->SaveNew(false);
			$objectId3 = $object->SaveNew(false);


			//Test Multiple Conditions
			$objectList = $object->GetList(array(array(strtolower($className)."Id", ">=",$objectId), array(strtolower($className)."Id", "<=", $objectId+2)), strtolower($className)."Id", false, 2);
			if (sizeof($objectList) != 2)
			{
				//Test Limit
				AddTrace("\t\tLimit failed");
				AddError('ERROR: GetList() :sizeof(list) != \$limit\n');
				AddError("Query failed: ".$object->pog_query);
				$errors++;
			}
			else
			{
				AddTrace("\t\tLimit....OK!");
			}
			if ($objectList[1]->{strtolower($className)."Id"} > $objectList[0]->{strtolower($className)."Id"})
			{
				//Test Sorting
				AddTrace("\t\tSorting failed");
				AddError("ERROR: GetList() :list is not properly sorted");
				AddError("Query failed: ".$object->pog_query);
				$errors++;
			}
			else
			{
				AddTrace("\t\tSorting....OK!");
			}
			if ($errors == 0)
			{
				$objectList = $object->GetList(array(array(strtolower($className)."Id", ">=",$objectId), array(strtolower($className)."Id", "<=", $objectId+2)), strtolower($className)."Id", false, 3);
				foreach ($objectList as $object)
				{
					$attributeList = array_keys(get_object_vars($object));
					foreach ($attributeList as $attribute)
					{
						if (isset($object->pog_attribute_type[strtolower($attribute)]))
						{
							if (isset($type_value[strtolower($attribute)]))
							{
								if ($object->{$attribute} != $type_value[strtolower($attribute)])
								{
									AddError("WARNING: Failed to retrieve attribute `$attribute`. Expecting `".$type_value[strtolower($attribute)]."`; found `".$object->{$attribute}."`. Check that column `$attribute` in the `$className` table is of type `".$object->pog_attribute_type[strtolower($attribute)][1]."`");
								}
							}
						}
					}
					$object->Delete();
				}
				return true;
			}
			else
			{
				AddTrace("\tGetList() failed");
				AddError("Query failed: ".$object->pog_query);
				return  false;
			}
		}
		AddTrace("\tGetList() ignored");
		return false;
	}

	/**
	 * Unit test for Delete()
	 *
	 */
	function TestDelete($object, $trace = true)
	{
		if(!TestSave($object, false))
		{
			if ($trace)
			{
				AddTrace("\tDelete() ignored");
			}
			return false;
		}
		if ($object->Delete(false))
		{
			if ($trace)
			{
				AddTrace("\tDelete()....OK!");
			}
			return true;
		}
		if ($trace)
		{
			AddTrace("\tDelete() failed");
			AddError("Query failed: ".$object->pog_query);
		}
		return false;
	}

	/**
	 * Tests whether the object is connected at all
	 *
	 * @param unknown_type $object
	 * @param unknown_type $allObjectsList
	 */
	function TestIsSingle($object)
	{
		$attribute_types = $object->pog_attribute_type;

		//get all child classes
		$childrenList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "HASMANY")
			{
				$childrenList[] = $key;
			}
		}

		//get all parent classes
		$parentsList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "BELONGSTO")
			{
				$parentsList[] = $key;
			}
		}

		if (sizeof($childrenList) == 0 && sizeof($parentsList) == 0)
		{
			return true;
		}
		return  false;
	}

	/**
	 * Tests that all parents have children and vice-versa
	 *
	 * @param unknown_type $object
	 * @param unknown_type $allObjectsList
	 * @param unknown_type $thisObjectName
	 * @return unknown
	 */
	function TestParentChildLink($object, $allObjectsList, $thisObjectName = '')
	{
		$attribute_types = $object->pog_attribute_type;

		//get all child classes
		$childrenList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "HASMANY")
			{
				$childrenList[] = $key;
			}
		}

		//get all parent classes
		$parentsList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "BELONGSTO")
			{
				$parentsList[] = $key;
			}
		}

		$errors = 0;
		foreach ($childrenList as $child)
		{
			if (array_search($child, $allObjectsList) === false)
			{
				$errors++;
				AddError("$thisObjectName refers to $child as {Child}, which couldn't be found. Generate the $child object with reference to $thisObjectName as {Parent}");
			}
			else
			{
				//test that child refers to this object as parent
				eval ("\$childInstance = new $child();");
				$childAttributes = array_keys($childInstance->pog_attribute_type);
				if (array_search($thisObjectName, $childAttributes) === false)
				{
					$errors++;
					AddError("$thisObjectName refers to $child as {Child}, but $child does not refer to $thisObjectName as {Parent}. Relations need to be reciprocal.");
				}
			}
		}

		foreach ($parentsList as $parent)
		{
			if (array_search($parent, $allObjectsList) === false)
			{
				$errors++;
				AddError("$thisObjectName refers to $parent as parent, which couldn't be found. Generate the $parent object with reference to $thisObjectName as {Child}");
			}
			else
			{
				//test that parent refers to this object as child
				eval ("\$parentInstance = new $parent();");
				$parentAttributes = array_keys($parentInstance->pog_attribute_type);
				if (array_search($thisObjectName, $parentAttributes) === false)
				{
					$errors++;
					AddError("$thisObjectName refers to $parent as {Parent}, but $parent does not refer to $thisObjectName as {Child}. Relations need to be reciprocal.");
				}
			}
		}

		if ($errors == 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Unit test to see if object is a parent
	 *
	 * @param unknown_type $object
	 */
	function TestIsParent($object)
	{
		$attribute_types = $object->pog_attribute_type;
		foreach ($attribute_types as $attribute_array)
		{
			if ($attribute_array[1] == "HASMANY")
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Unit test to see if object is child
	 *
	 * @param unknown_type $object
	 */
	function TestIsChild($object)
	{
		$attribute_types = $object->pog_attribute_type;
		foreach ($attribute_types as $attribute_array)
		{
			if ($attribute_array[1] == "BELONGSTO")
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Unit test for Save($deep)
	 *
	 * @param unknown_type $object
	 */
	function TestSaveDeep($object)
	{
		$thisObjectName = get_class($object);
		if (!TestAddChild($object, false))
		{
			AddTrace("\tSave(deep) ignored");
			AddError("Save(deep) ignored since AddChild could not be performed");
			return false;
		}
		if (!TestGetChildrenList($object, false))
		{
			AddTrace("\tSave(deep) ignored");
			AddError("Save(deep) ignored since GetChildrenList could not be performed");
			return false;
		}
		if (!TestDeleteDeep($object, false))
		{
			AddTrace("\tSave(deep) ignored");
			AddError("Save(deep) ignored since Delete(deep) could not be performed");
			return false;
		}

		//get all child classes
		$childrenList = array();
		eval("\$object = new $thisObjectName();");
		$object = PopulateTestValues($object);
		$attribute_types = $object->pog_attribute_type;

		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "HASMANY")
			{
				$childrenList[] = $key;
			}
		}

		$errors = 0;
		foreach ($childrenList as $child)
		{
			//instantiate
			eval("\$childInstance = new $child();");
			$childInstance = PopulateTestValues($childInstance);

			//add children
			eval ("\$object -> Add$child(\$childInstance);");
		}

		//test
		if (!$object->Save(true))
		{
			$errors++;
			return false;
		}

		foreach ($childrenList as $child)
		{
			//instantiate
			eval("\$childArray = \$object->Get".$child."List();");
			if (sizeof($childArray) == 0)
			{
				AddTrace("\tSave($child) failed");
				$errors++;
			}
			else
			{
				AddTrace("\tSave($child)....OK!");
			}
		}

		//cleanup
		$object->Delete(true);

		if ($errors == 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Unit test for Delete($deep)
	 *
	 * @param unknown_type $object
	 */
	function TestDeleteDeep($object, $trace = true)
	{
		$thisObjectName = get_class($object);
		$attribute_types = $object->pog_attribute_type;

		if (!TestSetParent($object, false))
		{
			AddTrace("\tDelete(deep) ignored");
			return false;
		}
		//get all child classes
		$childrenList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "HASMANY")
			{
				$childrenList[] = $key;
			}
		}

		$errors = 0;

		$object = PopulateTestValues($object);
		$objectId = $object->Save(false);


		$childrenStore = array();
		foreach ($childrenList as $child)
		{
			//instantiate
			eval("\$childInstance = new $child();");
			$childInstance = PopulateTestValues($childInstance);
			eval("\$childInstance -> Set".$thisObjectName."(\$object);");
			$childInstance -> Save();
			$childrenStore[] = &$childInstance;
		}

		//test
		if (!$object->Delete(true))
		{
			$errors++;
		}

		foreach ($childrenList as $child)
		{
			eval("\$childInstance = new $child();");
			$parentList = $childInstance->GetList(array(array(strtolower($thisObjectName)."Id", "=", $objectId)));
			if (sizeof($parentList) > 0)
			{
				if ($trace)
				{
					AddTrace("\tDelete($child) failed");
					$errors++;
				}
			}
			else
			{
				if ($trace)
				{
					AddTrace("\tDelete($child)....OK!");
				}
			}
		}

		//cleanup
		foreach ($childrenStore as $child);
		{
			$child->Delete();
		}

		if ($errors == 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Unit test for SetParent()
	 *
	 * @param unknown_type $object
	 */
	function TestSetParent($object, $trace = true)
	{
		$thisObjectName = get_class($object);
		$attribute_types = $object->pog_attribute_type;

		//get all parent classes
		$parentList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "BELONGSTO")
			{
				$parentList[] = $key;
			}
		}

		$errors = 0;
		foreach ($parentList as $parent)
		{
			//instantiate
			eval("\$parentInstance = new $parent();");

			//save
			$parentInstance = PopulateTestValues($parentInstance);
			$parentInstance -> Save(false);

			//set parent
			eval ("\$object -> Set$parent(\$parentInstance);");

			eval ("\$objectId = \$object->".strtolower($parent)."Id;");

			eval ("\$parentId = \$parentInstance->".strtolower($parent)."Id;");

			if ($objectId != $parentId)
			{
				if ($trace)
				{
					AddTrace("\tSet$parent() failed");
					AddError("Could not set $parent as {Parent} of $thisObjectName");
				}
				$errors++;
			}
			else
			{
				if ($trace)
				{
					AddTrace("\tSet$parent()....OK!");
				}
			}
			//cleanup (delete parent)
			$parentInstance -> Delete(false);
			eval ("\$object->".strtolower($parent)."Id = '';");
		}

		if ($errors == 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Unit test for GetParent()
	 *
	 * @param unknown_type $object
	 */
	function TestGetParent($object)
	{
		$thisObjectName = get_class($object);
		eval ("\$object = new $thisObjectName();");

		$attribute_types = $object->pog_attribute_type;

		//get all parent classes
		$parentList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "BELONGSTO")
			{
				$parentList[] = $key;
			}
		}

		$errors = 0;

		foreach ($parentList as $parent)
		{
			/*if (TestSetParent($object, false))
			{*/
				//instantiate
				eval("\$parentInstance = new $parent();");

				//save
				$parentInstance = PopulateTestValues($parentInstance);
				$parentInstance -> Save(false);


				//set parent
				eval ("\$object -> Set$parent(\$parentInstance);");

				eval("\$myParent = \$object->Get$parent();");

				eval ("\$objectId = \$object->".strtolower($parent)."Id;");

				eval ("\$parentId = \$myParent->".strtolower($parent)."Id;");

				if ($objectId != $parentId)
				{
					AddTrace("\tGet$parent() failed");
					AddError("Could not retrieve parent object $parent");
					$errors++;
				}
				else
				{
					AddTrace("\tGet$parent()....OK!");
				}

				//cleanup (delete parent)
				$parentInstance -> Delete(false);
			/*}
			else
			{
				AddTrace("\tGet$parent() ignored");
			}*/
		}
		if ($errors == 0)
		{
			return true;
		}
		return  false;
	}

	/**
	 * Unit test for AddChild()
	 *
	 * @param unknown_type $object
	 */
	function TestAddChild($object, $trace = true)
	{
		$thisObjectName = get_class($object);
		eval ("\$object = new $thisObjectName();");
		$attribute_types = $object->pog_attribute_type;

		$object = PopulateTestValues($object);

		$object -> Save(false);

		//get all child classes
		$childrenList = array();
		foreach ($attribute_types as $key => $attribute_array)
		{
			if ($attribute_array[1] == "HASMANY")
			{
				$childrenList[] = $key;
			}
		}

		$errors = 0;
		foreach ($childrenList as $child)
		{
			//instantiate
			eval ("\$childInstance = new $child();");
			$childInstance = PopulateTestValues($childInstance);

			//save
			$childInstance->Save();

			//instantiate other
			eval("\$childInstance2 = new $child();");
			$childInstance2 = PopulateTestValues($childInstance2);

			//add children
			eval ("\$object -> Add$child(\$childInstance);");
			eval ("\$object -> Add$child(\$childInstance2);");

			//verify that child was added
			eval  ("\$children = \$object -> Get".$child."List();");

			if (sizeof($children) != 2)
			{
				if ($trace)
				{
					AddTrace("\tAdd$child() failed");
					AddError("Could not add child object $child");
				}
				$errors++;
			}
			else
			{
				if ($trace)
				{
					AddTrace("\tAdd$child()....OK!");
				}
			}
			//clean up
			$childInstance->Delete();
			eval ("\$object -> Set".$child."List(new array());");
		}

		$object -> Delete(false);

		if ($errors == 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Unit test for GetChildrenList()
	 *
	 * @param unknown_type $object
	 */
	function TestGetChildrenList($object, $trace = true)
	{
		$thisObjectName = get_class($object);
		eval ("\$object = new $thisObjectName();");
		$attribute_types = $object->pog_attribute_type;
		$errors = 0;
		if (!TestAddChild($object, false))
		{
			AddTrace("\tGetChildrenList() ignored");
			$errors++;
		}
		else
		{
			//get all child classes
			$childrenList = array();
			foreach ($attribute_types as $key => $attribute_array)
			{
				if ($attribute_array[1] == "HASMANY")
				{
					$childrenList[] = $key;
				}
			}

			//save shallow
			$object = PopulateTestValues($object);
			$object->Save(false);


			foreach ($childrenList as $child)
			{
				//instantiate
				eval("\$childInstance = new $child();");
				$childInstance = PopulateTestValues($childInstance);

				if (!TestSetParent($childInstance, false))
				{
					AddTrace("\tGetChildrenList() ignored");
					return  false;
				}
				eval("\$childInstance->Set".$thisObjectName."(\$object);");

				$childInstance->Save();



				//try getting all children
				eval ("\$children = \$object -> Get".$child."List();");
				if (sizeof($children) != 1)
				{
					AddTrace("\tGet".$child."List() failed");
					AddError("Could not get children list");
					$errors++;
				}

				//cleanup
				$childInstance->Delete();

				if ($errors == 0 && $trace)
				{
					AddTrace("\tGet".$child."List()....OK!");
				}
			}

			$object->Delete(false);
		}
		if ($errors == 0)
		{
			return true;
		}
		return false;
	}
?>
