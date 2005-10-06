<?php
include "class.misc.php";
$misc = new Misc(array());
session_cache_limiter('nocache');
$cache_limiter = session_cache_limiter();
session_start();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); 
header('Cache-Control: post-check=0, pre-check=0', FALSE); 
header('Pragma: no-cache'); 
header('Expires: 0'); 
if (isset($_SESSION['objectName']))
{
	$objectName = $_SESSION['objectName'];
}
if (isset($_SESSION['attributeList']))
{
	$attributeList = unserialize($_SESSION['attributeList']);
}
if (isset($_SESSION['$typeList']))
{
	$typeList = unserialize($_SESSION['$typeList']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="Php Object Generator, (POG) automatically generates tested Object Oriented code that you can use for your PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented" />
<script type="text/javascript">
//<![CDATA[
function AddField()
{
	trs=document.getElementsByTagName("div");
	for(var w=0;w<trs.length;w++)
	{
		if(trs[w].style.display == "none")
		{
			trs[w].style.display="block";
			var control = document.getElementById("field"+trs[w].id);
			try
			{ 
				control.focus();
			}
			catch(e)
			{
			}
			break;
		}
	}
}
function ResetFields()
{
	trs=document.getElementsByTagName("input")
	for(var w=0;w<trs.length;w++)
	{
		trs[w].value= "";
	}
}
function ConvertDDLToTextfield(id)
{
	var thisId = id;
	trs=document.getElementsByTagName("select");
	for(var w=0;w<trs.length;w++)
	{
		if(trs[w].id == thisId)
		{
			
			if (trs[w].value == "OTHER")
			{
				trs[w].style.display="none";
				trs2=document.getElementsByTagName("input");
				for(var v=0;v<trs2.length;v++)
				{
					if(trs2[v].id == thisId)
					{
						trs2[v].style.display="inline";
						break;
					}
				}
			}
			break;
		}
	}
}
//]]>
</script>
<title>Php Object Generator (1.0 rev19): A free php object relational database code generator</title>
<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
<meta name="description" content="Php Object Generator, (POG) automatically generates tested Object Oriented code that you can use for your PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
</head>
<body>
<div class="main">
	<div class="left">
		<img src="./aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/><a href="http://www.phpobjectgenerator.com">Php Object Generator</a>, (<a href="http://www.phpobjectgenerator.com">POG</a>) automatically generates tested Object Oriented code that you can use for your PHP5 application. Over the years, we've come to realize that a large portion of a PHP programmer's time is wasted on coding the Database Access Layer of an application simply because every application requires different types of objects. 
		
		<br/><br/>By generating the Database Access Layer code for you, POG saves you time; Time you can spend on other areas of your project. The easiest way to understand how Php Object Generator works is to give it a try.
		
		<br/><br/>POG was written by <a href="http://www.philosophicallies.com" title="Philosophic Allies">Joel Wan</a> and <a href="http://www.faintlight.com" title="Faint Light">Mark Slemko</a>. Designs by <a href="http://www.designyouwill.com" title="Design You Will">Jonathan Easton</a>. 
		
		<br/><br/>Drop us a line @ <a href="mailto:pogguys@phpobjectgenerator.com" title="Drop us a line">pogguys@phpobjectgenerator.com</a>	
		<br/><br/>Want more? there's <a href="http://www.phpobjectgenerator.com/plog" title="php object generator weblog">the POG Weblog</a>.
	</div><!-- left -->
	
	<div class="middle">
		<div class="header">
		</div><!-- header -->
		<form method="post" action="index2.php">
		<div class="objectname">
			<input type="text" name="object" class="i" value="<?=(isset($objectName)?$objectName:'')?>"/>
		</div><!-- objectname -->
		<div class="greybox">
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/> <input  type="text" name="fieldattribute_1" class="i" value="<?=(isset($attributeList)&&isset($attributeList[0])?$attributeList[0]:'')?>"></input>  &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/>
                <select class="s" style="display:<?=(!isset($typeList)||$misc->TypeIsKnown($typeList[0]) ?"inline":"none")?>" onchange="ConvertDDLToTextfield('type_1')" name="type_1" id="type_1">
                <option value="VARCHAR(255)" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="VARCHAR(255)"?"selected":'')?>>VARCHAR(255)</option>
				<option value="TINYINT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="TINYINT"?"selected":'')?>>TINYINT</option>
                <option value="TEXT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="TEXT"?"selected":'')?>>TEXT</option>
                <option value="DATE" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="DATE"?"selected":'')?>>DATE</option>
                <option value="SMALLINT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="SMALLINT"?"selected":'')?>>SMALLINT</option>
                <option value="MEDIUMINT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="MEDIUMINT"?"selected":'')?>>MEDIUMINT</option>
                <option value="INT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="INT"?"selected":'')?>>INT</option>
                <option value="BIGINT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="BIGINT"?"selected":'')?>>BIGINT</option>
                <option value="FLOAT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="FLOAT"?"selected":'')?>>FLOAT</option>
                <option value="DOUBLE" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="DOUBLE"?"selected":'')?>>DOUBLE</option>
                <option value="DECIMAL" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="DECIMAL"?"selected":'')?>>DECIMAL</option>
                <option value="DATETIME" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="DATETIME"?"selected":'')?>>DATETIME</option>
                <option value="TIMESTAMP" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="TIMESTAMP"?"selected":'')?>>TIMESTAMP</option>
                <option value="TIME" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="TIME"?"selected":'')?>>TIME</option>
                <option value="YEAR" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="YEAR"?"selected":'')?>>YEAR</option>
                <option value="CHAR(255)" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="CHAR(255)"?"selected":'')?>>CHAR(255)</option>
                <option value="TINYBLOB" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="TINYBLOB"?"selected":'')?>>TINYBLOB</option>
                <option value="TINYTEXT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="TINYTEXT"?"selected":'')?>>TINYTEXT</option>
                <option value="BLOB" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="BLOB"?"selected":'')?>>BLOB</option>
                <option value="MEDIUMBLOB" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="MEDIUMBLOB"?"selected":'')?>>MEDIUMBLOB</option>
                <option value="MEDIUMTEXT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="MEDIUMTEXT"?"selected":'')?>>MEDIUMTEXT</option>
                <option value="LONGBLOB" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="LONGBLOB"?"selected":'')?>>LONGBLOB</option>
                <option value="LONGTEXT" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="LONGTEXT"?"selected":'')?>>LONGTEXT</option>
                <option value="BINARY" <?=(isset($typeList)&&isset($typeList[0])&&$typeList[0]=="BINARY"?"selected":'')?>>BINARY</option>
                <option value="OTHER">OTHER...</option>
                </select>
              	<input style="display:<?=(!isset($typeList)||$misc->TypeIsKnown($typeList[0])?"none":"inline")?>" type="text" name="ttype_1" class="i" id="type_1" value="<?=(isset($typeList)&&isset($typeList[0])?$typeList[0]:'')?>"></input></span><br/><br/>
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_2" class="i" value="<?=(isset($attributeList)&&isset($attributeList[1])?$attributeList[1]:'')?>"></input> &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> 
			<select class="s" style="display:<?=(!isset($typeList)||$misc->TypeIsKnown($typeList[1]) ?"inline":"none")?>" onchange="ConvertDDLToTextfield('type_2')" name="type_2" id="type_2">
                <option value="VARCHAR(255)" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="VARCHAR(255)"?"selected":'')?>>VARCHAR(255)</option>
				<option value="TINYINT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="TINYINT"?"selected":'')?>>TINYINT</option>
                <option value="TEXT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="TEXT"?"selected":'')?>>TEXT</option>
                <option value="DATE" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="DATE"?"selected":'')?>>DATE</option>
                <option value="SMALLINT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="SMALLINT"?"selected":'')?>>SMALLINT</option>
                <option value="MEDIUMINT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="MEDIUMINT"?"selected":'')?>>MEDIUMINT</option>
                <option value="INT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="INT"?"selected":'')?>>INT</option>
                <option value="BIGINT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="BIGINT"?"selected":'')?>>BIGINT</option>
                <option value="FLOAT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="FLOAT"?"selected":'')?>>FLOAT</option>
                <option value="DOUBLE" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="DOUBLE"?"selected":'')?>>DOUBLE</option>
                <option value="DECIMAL" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="DECIMAL"?"selected":'')?>>DECIMAL</option>
                <option value="DATETIME" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="DATETIME"?"selected":'')?>>DATETIME</option>
                <option value="TIMESTAMP" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="TIMESTAMP"?"selected":'')?>>TIMESTAMP</option>
                <option value="TIME" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="TIME"?"selected":'')?>>TIME</option>
                <option value="YEAR" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="YEAR"?"selected":'')?>>YEAR</option>
                <option value="CHAR(255)" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="CHAR(255)"?"selected":'')?>>CHAR(255)</option>
                <option value="TINYBLOB" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="TINYBLOB"?"selected":'')?>>TINYBLOB</option>
                <option value="TINYTEXT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="TINYTEXT"?"selected":'')?>>TINYTEXT</option>
                <option value="BLOB" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="BLOB"?"selected":'')?>>BLOB</option>
                <option value="MEDIUMBLOB" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="MEDIUMBLOB"?"selected":'')?>>MEDIUMBLOB</option>
                <option value="MEDIUMTEXT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="MEDIUMTEXT"?"selected":'')?>>MEDIUMTEXT</option>
                <option value="LONGBLOB" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="LONGBLOB"?"selected":'')?>>LONGBLOB</option>
                <option value="LONGTEXT" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="LONGTEXT"?"selected":'')?>>LONGTEXT</option>
                <option value="BINARY" <?=(isset($typeList)&&isset($typeList[1])&&$typeList[1]=="BINARY"?"selected":'')?>>BINARY</option>
                <option value="OTHER">OTHER...</option>
                </select>
                <input style="display:<?=(!isset($typeList)||$misc->TypeIsKnown($typeList[1]) ?"none":"inline")?>" type="text" name="ttype_2" class="i" id="type_2" value="<?=(isset($typeList)&&isset($typeList[1])?$typeList[1]:'')?>"></input></span><br/><br/>
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_3" class="i" value="<?=(isset($attributeList)&&isset($attributeList[2])?$attributeList[2]:'')?>"></input> &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> 
			<select class="s" style="display:<?=(!isset($typeList)||$misc->TypeIsKnown($typeList[2]) ?"inline":"none")?>" onchange="ConvertDDLToTextfield('type_3')" name="type_3" id="type_3">
                <option value="VARCHAR(255)" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="VARCHAR(255)"?"selected":'')?>>VARCHAR(255)</option>
				<option value="TINYINT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="TINYINT"?"selected":'')?>>TINYINT</option>
                <option value="TEXT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="TEXT"?"selected":'')?>>TEXT</option>
                <option value="DATE" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="DATE"?"selected":'')?>>DATE</option>
                <option value="SMALLINT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="SMALLINT"?"selected":'')?>>SMALLINT</option>
                <option value="MEDIUMINT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="MEDIUMINT"?"selected":'')?>>MEDIUMINT</option>
                <option value="INT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="INT"?"selected":'')?>>INT</option>
                <option value="BIGINT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="BIGINT"?"selected":'')?>>BIGINT</option>
                <option value="FLOAT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="FLOAT"?"selected":'')?>>FLOAT</option>
                <option value="DOUBLE" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="DOUBLE"?"selected":'')?>>DOUBLE</option>
                <option value="DECIMAL" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="DECIMAL"?"selected":'')?>>DECIMAL</option>
                <option value="DATETIME" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="DATETIME"?"selected":'')?>>DATETIME</option>
                <option value="TIMESTAMP" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="TIMESTAMP"?"selected":'')?>>TIMESTAMP</option>
                <option value="TIME" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="TIME"?"selected":'')?>>TIME</option>
                <option value="YEAR" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="YEAR"?"selected":'')?>>YEAR</option>
                <option value="CHAR(255)" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="CHAR(255)"?"selected":'')?>>CHAR(255)</option>
                <option value="TINYBLOB" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="TINYBLOB"?"selected":'')?>>TINYBLOB</option>
                <option value="TINYTEXT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="TINYTEXT"?"selected":'')?>>TINYTEXT</option>
                <option value="BLOB" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="BLOB"?"selected":'')?>>BLOB</option>
                <option value="MEDIUMBLOB" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="MEDIUMBLOB"?"selected":'')?>>MEDIUMBLOB</option>
                <option value="MEDIUMTEXT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="MEDIUMTEXT"?"selected":'')?>>MEDIUMTEXT</option>
                <option value="LONGBLOB" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="LONGBLOB"?"selected":'')?>>LONGBLOB</option>
                <option value="LONGTEXT" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="LONGTEXT"?"selected":'')?>>LONGTEXT</option>
                <option value="BINARY" <?=(isset($typeList)&&isset($typeList[2])&&$typeList[2]=="BINARY"?"selected":'')?>>BINARY</option>
                <option value="OTHER">OTHER...</option>
                </select>
                <input style="display:<?=(!isset($typeList)||$misc->TypeIsKnown($typeList[2]) ?"none":"inline")?>" type="text" name="ttype_3" class="i" id="type_3" value="<?=(isset($typeList)&&isset($typeList[2])?$typeList[2]:'')?>"></input></span><br/>
		<?
		if (isset($attributeList))
		{
			$max = count($attributeList);
			for ($j=4; $j<= $max; $j++)
			{
				echo '<div style="display:block" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value="'.(isset($attributeList)&&isset($attributeList[$j-1])?$attributeList[$j-1]:'').'"/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> 
				<select class="s" style="display:'.(!isset($typeList)||$misc->TypeIsKnown($typeList[$j-1])?"inline":"none").'" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">
                <option value="VARCHAR(255)" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="VARCHAR(255)"?"selected":'').'>VARCHAR(255)</option>
				<option value="TINYINT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="TINYINT"?"selected":'').'>TINYINT</option>
                <option value="TEXT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="TEXT"?"selected":'').'>TEXT</option>
                <option value="DATE" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="DATE"?"selected":'').'>DATE</option>
                <option value="SMALLINT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="SMALLINT"?"selected":'').'>SMALLINT</option>
                <option value="MEDIUMINT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="MEDIUMINT"?"selected":'').'>MEDIUMINT</option>
                <option value="INT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="INT"?"selected":'').'>INT</option>
                <option value="BIGINT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="BIGINT"?"selected":'').'>BIGINT</option>
                <option value="FLOAT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="FLOAT"?"selected":'').'>FLOAT</option>
                <option value="DOUBLE" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="DOUBLE"?"selected":'').'>DOUBLE</option>
                <option value="DECIMAL" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="DECIMAL"?"selected":'').'>DECIMAL</option>
                <option value="DATETIME" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="DATETIME"?"selected":'').'>DATETIME</option>
                <option value="TIMESTAMP" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="TIMESTAMP"?"selected":'').' >TIMESTAMP</option>
                <option value="TIME" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="TIME"?"selected":'').'>TIME</option>
                <option value="YEAR" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="YEAR"?"selected":'').'>YEAR</option>
                <option value="CHAR(255)" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="CHAR(255)"?"selected":'').'>CHAR(255)</option>
                <option value="TINYBLOB" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="TINYBLOB"?"selected":'').'>TINYBLOB</option>
                <option value="TINYTEXT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="TINYTEXT"?"selected":'').'>TINYTEXT</option>
                <option value="BLOB" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="BLOB"?"selected":'').'>BLOB</option>
                <option value="MEDIUMBLOB" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="MEDIUMBLOB"?"selected":'').'>MEDIUMBLOB</option>
                <option value="MEDIUMTEXT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="MEDIUMTEXT"?"selected":'').'>MEDIUMTEXT</option>
                <option value="LONGBLOB" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="LONGBLOB"?"selected":'').'>LONGBLOB</option>
                <option value="LONGTEXT" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="LONGTEXT"?"selected":'').'>LONGTEXT</option>
                <option value="BINARY" '.(isset($typeList)&&isset($typeList[$j-1])&&$typeList[$j-1]=="BINARY"?"selected":'').'>BINARY</option>
                <option value="OTHER">OTHER...</option>
                </select>
				<input style="display:'.(!isset($typeList)||$misc->TypeIsKnown($typeList[$j-1]) ?"none":"inline").'" type="text" id="type_'.$j.'"  name="ttype_'.$j.'" class="i" value="'.(isset($typeList)&&isset($typeList[$j-1])?$typeList[$j-1]:'').'"></input></span><br/>
				</div>';
				
			}
			$max++;
			for ($j=$max; $j<50; $j++)
			{
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value=""/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> 
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">
                <option value="VARCHAR(255)"  selected="selected">VARCHAR(255)</option>
				<option value="TINYINT">TINYINT</option>
                <option value="TEXT">TEXT</option>
                <option value="DATE">DATE</option>
                <option value="SMALLINT">SMALLINT</option>
                <option value="MEDIUMINT">MEDIUMINT</option>
                <option value="INT">INT</option>
                <option value="BIGINT">BIGINT</option>
                <option value="FLOAT">FLOAT</option>
                <option value="DOUBLE">DOUBLE</option>
                <option value="DECIMAL">DECIMAL</option>
                <option value="DATETIME">DATETIME</option>
                <option value="TIMESTAMP">TIMESTAMP</option>
                <option value="TIME">TIME</option>
                <option value="YEAR">YEAR</option>
                <option value="CHAR(255)">CHAR(255)</option>
                <option value="TINYBLOB">TINYBLOB</option>
                <option value="TINYTEXT">TINYTEXT</option>
                <option value="BLOB">BLOB</option>
                <option value="MEDIUMBLOB">MEDIUMBLOB</option>
                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                <option value="LONGBLOB">LONGBLOB</option>
                <option value="LONGTEXT">LONGTEXT</option>
                <option value="BINARY">BINARY</option>
                <option value="OTHER">OTHER...</option>
                </select>
				<input style="display:none" type="text" id="type_'.$j.'" name="ttype_'.$j.'" class="i"></input></span>
				<br/>
				</div>';
				
			}
		}
		else 
		{
			for ($j=4; $j<50; $j++)
			{
			
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'"/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> 
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">
                <option value="VARCHAR(255)"  selected="selected">VARCHAR(255)</option>
				<option value="TINYINT">TINYINT</option>
                <option value="TEXT">TEXT</option>
                <option value="DATE">DATE</option>
                <option value="SMALLINT">SMALLINT</option>
                <option value="MEDIUMINT">MEDIUMINT</option>
                <option value="INT">INT</option>
                <option value="BIGINT">BIGINT</option>
                <option value="FLOAT">FLOAT</option>
                <option value="DOUBLE">DOUBLE</option>
                <option value="DECIMAL">DECIMAL</option>
                <option value="DATETIME">DATETIME</option>
                <option value="TIMESTAMP">TIMESTAMP</option>
                <option value="TIME">TIME</option>
                <option value="YEAR">YEAR</option>
                <option value="CHAR(255)">CHAR(255)</option>
                <option value="TINYBLOB">TINYBLOB</option>
                <option value="TINYTEXT">TINYTEXT</option>
                <option value="BLOB">BLOB</option>
                <option value="MEDIUMBLOB">MEDIUMBLOB</option>
                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                <option value="LONGBLOB">LONGBLOB</option>
                <option value="LONGTEXT">LONGTEXT</option>
                <option value="BINARY">BINARY</option>
                <option value="OTHER">OTHER...</option>
                </select>
				<input style="display:none" type="text" id="type_'.$j.'" name="ttype_'.$j.'" class="i"></input></span><br/>
				</div>';	
			}
		}
		?>
		</div><!-- greybox -->
		<div class="generate">
			<a href="#" onclick="AddField()"><img src="./addattribute.jpg" border="0" alt="add attribute"/></a> <a href="#" onclick="ResetFields()"><img src="./resetfields.jpg" border="0" alt="reset fields"/></a>
		</div><!-- generate -->
		<div class="submit">
			<input type="image"  src="./generate.jpg" alt="Generate!"/>
		</div><!-- submit -->
		</form>
	</div><!-- middle -->
	<div class="right">
	<script type="text/javascript"><!--
google_ad_client = "pub-7832108692498114";
google_alternate_color = "FFFFFF";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "716500";
google_color_url = "B8B8B8";
google_color_text = "CCC078";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</div>
</div><!-- main -->

</body>
</html>