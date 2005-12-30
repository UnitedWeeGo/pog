<?php

	// -------------------------------------------------------------
	function InitializeTestValues()
	{
		return array(
					"VARCHAR(255)" => "Lorem Ipsum",
					"TINYINT" => "1",
					"TEXT" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"DATE" => "1997-12-15",
					"SMALLINT" => "1234",
					"MEDIUMINT" => "12345",
					"INT" => "99",
					"BIGINT" => "12345678",
					"FLOAT" => "1234.5678",
					"DOUBLE" => "1234.5678",
					"DECIMAL" => "1234.5678",
					"DATETIME" => "1997-12-15 23:50:26",
					"TIMESTAMP" => "20050517120000",
					"TIME" => "23:50:26",
					"YEAR" => "1997",
					"CHAR(255)" => "Lorem Ipsum",
					"TINYBLOB" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"TINYTEXT" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"BLOB" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"MEDIUMBLOB" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"MEDIUMTEXT" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"LONGBLOB" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"LONGTEXT" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
					"BINARY(1)" => "d",				
					);
	}
	
	// -------------------------------------------------------------
	function ConvertAttributeToHtml($attributeName, $attributeType, $attributeValue='', $objectId='')
	{
		$attributeTypeParts = explode("(",$attributeType);
		switch (strtoupper($attributeTypeParts[0]))
		{
			case "TEXT":
			case "BLOB":
				$html = "<textarea class='t' name='".($objectId != ''?$attributeName."_".$objectId:$attributeName)."'>".($attributeValue != ''?$attributeValue:'')."</textarea>";
			break;
			case "BIGINT":
			case "VARCHAR":
			case "INT":
			case "YEAR":
				$html = "<input class='i' name='".($objectId != ''?$attributeName."_".$objectId:$attributeName)."' value='".($attributeValue != ''?$attributeValue:'')."' />";
			break;
			default:
				$html = substr($attributeValue,0,20)."...";
			break;
		}
		return $html;
	}
?>