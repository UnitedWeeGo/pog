<?
class SQL
{
	var $string;
	var $separator = "\n\t// -------------------------------------------------------------";
	
	// -------------------------------------------------------------
	function CreateSQL($object)
	{
		$this->objectName = $objectName;
		$this->attributeList = $attributeList;
		$this->string .= "CREATE TABLE `$object->objectName` (";
  		foreach ($object->attributeList as $attribute)
  		{
  			$this->string .= "`$attribute` $type,";
  		}
		$this->string .= ")";
		return $this->string;
	}
}
?>