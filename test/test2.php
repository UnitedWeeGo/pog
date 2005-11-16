<?php
//include "testmysql.php";
include "testphp4.php";
include "../pogged/class.database.php4.php";
include "../pogged/configuration.php";
$object= new Object();
$object->var1 = "666";
$object->var2 = "var2";
$object->var3 = "var3";
$object->Save();

$object->var2 = "000";
$object->Save();

$object->Get($object->objectId);
echo $object->var2;

echo $object->objectId."<br/>";

$object->objectId = 0;


$objectList= $object->GetObjectList("objectid", ">", $object->objectId, "", "", "");
echo "count:".count($objectList);


foreach ($objectList as $myObject)
{
	echo "<br/>";
	echo $myObject->objectId;
	echo $myObject->var1;
	echo $myObject->var2;
	echo $myObject->var3;
	echo "<br/>";
	//echo "deleting object...";
	//$myObject->Delete();
}


?>
