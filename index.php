<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application. 
*/
include "./include/class.misc.php";
include "./include/configuration.php";
$misc = new Misc(array());
session_cache_limiter('nocache');
$cache_limiter = session_cache_limiter();
session_start();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); 
header('Cache-Control: post-check=0, pre-check=0', FALSE); 
header('Pragma: no-cache'); 
header('Expires: 0'); 

if ($misc->GetVariable('objectName')!= null)
{
	$objectName = $misc->GetVariable('objectName');
}
if ($misc->GetVariable('attributeList') != null)
{
	if (isset($_GET['attributeList']))
		eval ("\$attributeList =". stripcslashes(urldecode($_GET['attributeList'])).";");	
	else
		$attributeList=unserialize($_SESSION['attributeList']);
}
if ($misc->GetVariable('typeList') != null)
{
	if (isset($_GET['typeList']))
	{
		$typeList = urldecode($_GET['typeList']);
		eval ("\$typeList =".trim($typeList).";");
	}
	else
	{
		$typeList = unserialize($_SESSION['typeList']);
		if (count($typeList) == 0)
		{
			$typeList = null;
		}
	}
}
$pdoDriver = ($misc->GetVariable('pdoDriver')!=null?$misc->GetVariable('pdoDriver'):'mysql');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
<title>Php Object Generator (v<?=$GLOBALS['configuration']['versionNumber']?> <?=$GLOBALS['configuration']['revisionNumber']?>) - Open Source Object Relational Mapping PHP Code Generator</title>
<meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented, CRUD" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="./pog.js" type="text/javascript">
</script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-72762-1";
urchinTracker();
</script>
</head>
<body onload="FocusOnFirstField()">
<div class="main">
	<div class="left">
		<div class="news"><a href="http://www.phpobjectgenerator.com/plog/article/65/a-short-video-of-the-pog-setup-process" title="Video preview of PHP Object Generator 1.6">NEW! Video preview of POG 1.6</a></div>
		<img src="./images/aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/><a href="http://www.phpobjectgenerator.com">Php Object Generator</a>, (<a href="http://www.phpobjectgenerator.com">POG</a>) is an open source <a href="http://www.phpobjectgenerator.com">PHP code generator</a> which automatically generates clean &amp; tested Object Oriented code for your PHP4/PHP5 application. Over the years, we realized that a large portion of a PHP programmer's time is wasted on repetitive coding of the Database Access Layer of an application simply because different applications require different objects. 
		
		<br/><br/>By generating PHP objects with integrated CRUD methods, POG gives you a head start in any project and saves you from writing and testing SQL queries. The time you save can be spent on more interesting areas of your project. But don't take our word for it, give it a try!
		<br/><br/><img src="./images/keyfeaturesphpobjectgenerator.jpg" alt="Key Features of  Php Object Generator"/>
		<br/>Generates clean &amp; tested code
		<br/>Generates CRUD methods
		<br/>Generates Setup file
		<br/>Compatible with PHP4 &amp; PHP5
		<br/>Compatible with PDO
		<br/>Automatic data encoding
		<br/>Free for personal use
		<br/>Free for commercial use
		<br/>Open Source
		<br/><br/><img src="./images/wantmorepog.jpg" alt="Want more Php Object Generator?"/>
		<br/><a href="http://www.phpobjectgenerator.com/plog" title="php object generator weblog">The POG Weblog</a> and <a href="http://www.phpobjectgenerator.com/plog/rss/">RSS feed</a>.
		<br/><a href="http://groups.google.com/group/Php-Object-Generator" title="Php object generator google group">The POG Google group</a>
		<br/><a href="http://www.phpobjectgenerator.com/plog/tutorials" title="php object generator tutorials and documentation">The POG Tutorials (in progress)</a>
		<br/><a href="http://www.faintlight.com/techinfo/pog">The POG mirror site</a>
		<br/><a href="http://www.phpobjectgenerator.com/plog/version">The POG history log</a>
		<br/><a href="http://www.phpobjectgenerator.com/plog/article/51/pog-source-code-locations">The POG source code</a>
		
		<br/><br/>Programmers:<br/><a href="http://www.philosophicallies.com" title="Philosophic Allies">Joel Wan</a><br/><a href="http://www.faintlight.com" title="Faint Light">Mark Slemko</a><br/>Designer:<br/><a href="http://www.designyouwill.com" title="Design You Will">Jonathan Easton</a><br/>Consultancy:<br/><a href="http://www.finessehosting.com" title="Finesse Hosting">Veemal Gungadin</a> 
		
		
		<br/><br/>Feedback, Feature Requests, Bugs to: <a href="mailto:pogguys@phpobjectgenerator.com" title="Drop us a line">pogguys@phpobjectgenerator.com</a>	
		
	</div><!-- left -->
	
	<div class="middle">
		<div class="header">
		</div><!-- header -->
		<form method="post" action="index2.php">
		<div class="customize">
			<select class="s" name="language" id="FirstField" onchange="CascadePhpVersion()">
				<option value="php4" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php4"?"selected":"")?>>PHP 4</option>
				<option value="php5" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5"?"selected":"")?>>PHP 5</option>
				<option value="php5.1" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5.1"?"selected":"")?>>PHP 5.1</option>
			</select>
			<br/><br/>
			<select class="s" name="wrapper" id="wrapper" onchange="IsPDO()">
				<option value="POG"  <?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="POG"?"selected":"")?>>POG</option>
				<?
				if (($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"))
				{
				?>
					<option value="PDO" <?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"selected":"")?>>PDO</option>
				<?
				}
				?>
			</select>
			<select class="s" name="pdoDriver" id="PDOdriver" style="display:<?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"inline":"none")?>" onchange="GenerateSQLTypesForDriver(this.value);">
				<option value="mysql" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="mysql"?"selected":"")?>>MYSQL</option>
				<!--<option value="oci" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="oci"?"selected":"")?>>OCI</option>-->
				<!--<option value="dblib" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="dblib"?"selected":"")?>>DBLIB</option>-->
				<!--untested pdo drivers have been commented out. uncomment once they are tested-->
				<option value="firebird" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="firebird"?"selected":"")?>>FIREBIRD</option>
				<option value="odbc" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="odbc"?"selected":"")?>>ODBC</option>
				<option value="pgsql" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="pgsql"?"selected":"")?>>PGSQL</option>
				<option value="sqlite" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="sqlite"?"selected":"")?>>SQLITE</option>
			</select>	
			
			<a id="disappear" style="display:<?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"none":"inline")?>" href="http://www.phpobjectgenerator.com/plog/pdo" target="_blank"><img src="./images/whatsthis.jpg" border="0" alt="what's this?"/></a>
		</div><!-- customize -->
		<div class="objectname">
			<input type="text" name="object" class="i" value="<?=(isset($objectName)?$objectName:'')?>"/>
		</div><!-- objectname -->
		<div class="greybox">
			<span class="line"><img src="./images/object2.jpg" width="33" height="29" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute" width="56" height="18"/> <input  type="text" name="fieldattribute_1" class="i" value="<?=(isset($attributeList)&&isset($attributeList[0])?$attributeList[0]:'')?>"></input>  &nbsp;&nbsp;<img src="./images/type.jpg" width="36" height="18" alt="object attribute"/>
                <select class="s" style="display:<?=(!isset($typeList[0])||$misc->TypeIsKnown($typeList[0]) ?"inline":"none")?>" onchange="ConvertDDLToTextfield('type_1')" name="type_1" id="type_1">
                	<?
                		$dataTypeIndex = 0;
						eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
					?>
                </select>
              	<input style="display:<?=(!isset($typeList[0])||$misc->TypeIsKnown($typeList[0])?"none":"inline")?>" type="text" name="ttype_1" class="i" id="ttype_1" value="<?=(isset($typeList)&&isset($typeList[0])&&!$misc->TypeIsKnown($typeList[0])?$typeList[0]:'')?>"></input></span><br/><br/>
			<span class="line"><img src="./images/object2.jpg" width="33" height="29" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_2" class="i" value="<?=(isset($attributeList)&&isset($attributeList[1])?$attributeList[1]:'')?>"></input> &nbsp;&nbsp;<img src="./images/type.jpg" width="36" height="18" alt="object attribute"/> 
			<select class="s" style="display:<?=(!isset($typeList[1])||$misc->TypeIsKnown($typeList[1]) ?"inline":"none")?>" onchange="ConvertDDLToTextfield('type_2')" name="type_2" id="type_2">
              		<?
                		$dataTypeIndex = 1;
						eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
					?>
                </select>
                <input style="display:<?=(!isset($typeList[1])||$misc->TypeIsKnown($typeList[1]) ?"none":"inline")?>" type="text" name="ttype_2" class="i" id="ttype_2" value="<?=(isset($typeList)&&isset($typeList[1])&&!$misc->TypeIsKnown($typeList[1])?$typeList[1]:'')?>"></input></span><br/><br/>
			<span class="line"><img src="./images/object2.jpg" width="33" height="29" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_3" class="i" value="<?=(isset($attributeList)&&isset($attributeList[2])?$attributeList[2]:'')?>"></input> &nbsp;&nbsp;<img src="./images/type.jpg" width="36" height="18" alt="object attribute"/> 
			<select class="s" style="display:<?=(!isset($typeList[2])||$misc->TypeIsKnown($typeList[2]) ?"inline":"none")?>" onchange="ConvertDDLToTextfield('type_3')" name="type_3" id="type_3">
                	<?
                		$dataTypeIndex = 2;
						eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
					?>
			</select>
                <input style="display:<?=(!isset($typeList[2])||$misc->TypeIsKnown($typeList[2]) ?"none":"inline")?>" type="text" name="ttype_3" class="i" id="ttype_3" value="<?=(isset($typeList)&&isset($typeList[2])&&!$misc->TypeIsKnown($typeList[2])?$typeList[2]:'')?>"></input></span><br/>
		<?
		if (isset($attributeList))
		{
			$max = count($attributeList);
			for ($j=4; $j<= $max; $j++)
			{
				echo '<div style="display:block" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./images/object2.jpg" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value="'.(isset($attributeList)&&isset($attributeList[$j-1])?$attributeList[$j-1]:'').'"/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute"/> 
					<select class="s" style="display:'.(!isset($typeList[$j-1])||$misc->TypeIsKnown($typeList[$j-1])?"inline":"none").'" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';
				
				$dataTypeIndex = $j-1;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
				
				echo '</select>;
                <input style="display:'.(!isset($typeList[$j-1])||$misc->TypeIsKnown($typeList[$j-1]) ?"none":"inline").'" type="text" id="ttype_'.$j.'"  name="ttype_'.$j.'" class="i" value="'.(isset($typeList)&&isset($typeList[$j-1])&&!$misc->TypeIsKnown($typeList[$j-1])?$typeList[$j-1]:'').'"></input></span><br/>
				</div>';				
			}
			$max++;
			for ($j=$max; $j<50; $j++)
			{
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./images/object2.jpg" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value=""/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute"/> 
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';
                
				$dataTypeIndex = $j;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
				
				echo '</select>
				<input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input></span>
				<br/>
				</div>';
				
			}
		}
		else 
		{
			for ($j=4; $j<50; $j++)
			{
			
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./images/object2.jpg" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'"/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute"/> 
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';
                
                $dataTypeIndex = $j;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
				
				
                echo '</select>
				<input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input></span><br/>
				</div>';	
			}
		}
		?>
		</div><!-- greybox -->
		<div class="generate">
			<a href="#" onclick="AddField();return false;"><img src="./images/addattribute.jpg" border="0" alt="add attribute"/></a> <a href="#" onclick="ResetFields();return false"><img src="./images/resetfields.jpg" border="0" alt="reset fields"/></a>
		</div><!-- generate -->
		
		<div class="submit">
			<input type="image"  src="./images/generate.jpg" alt="Generate!"/>
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