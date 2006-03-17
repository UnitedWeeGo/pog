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
				$html = "";
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
?>
