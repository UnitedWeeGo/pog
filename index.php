<?php
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
<meta name="description" content="Php Object Generator, (POG) automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
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
//]]>
</script>
<title>Php Object Generator (1.0 rev8): A free php object relational database code generator</title>
<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
<meta name="description" content="Php Object Generator, (POG) automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
</head>
<body>
<div class="main">
	<div class="left">
		<img src="./aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/><a href="http://www.phpobjectgenerator.com">Php Object Generator</a>, (<a href="http://www.phpobjectgenerator.com">POG</a>) automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application. Over the years, we've come to realize that a large portion of a PHP programmer's time is wasted on coding the Database Access Layer of an application simply because every application requires different types of objects. 
		
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
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/> <input type="text" name="fieldattribute_1" class="i" value="<?=(isset($attributeList)&&isset($attributeList[0])?$attributeList[0]:'')?>"></input>  &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> <input type="text" name="type_1" class="i" value="<?=(isset($typeList)&&isset($typeList[0])?$typeList[0]:'')?>"></input></span><br/><br/>
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_2" class="i" value="<?=(isset($attributeList)&&isset($attributeList[1])?$attributeList[1]:'')?>"></input> &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> <input type="text" name="type_2" class="i" value="<?=(isset($typeList)&&isset($typeList[1])?$typeList[1]:'')?>"></input></span><br/><br/>
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_3" class="i" value="<?=(isset($attributeList)&&isset($attributeList[2])?$attributeList[2]:'')?>"></input> &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> <input type="text" name="type_3" class="i"  value="<?=(isset($typeList)&&isset($typeList[2])?$typeList[2]:'')?>"></input></span><br/>
		<?
		if (isset($attributeList) || isset($typeList))
		{
			$max = count($typeList);
			if (count($attributeList)>count($typeList))
			{
				$max = count($attributeList);
			}
			for ($j=4; $j<= $max; $j++)
			{
				echo '<div style="display:block" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value="'.(isset($attributeList)&&isset($attributeList[$j-1])?$attributeList[$j-1]:'').'"/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> <input type="text" name="type_'.$j.'" class="i" value="'.(isset($typeList)&&isset($typeList[$j-1])?$typeList[$j-1]:'').'"></input></span><br/>
				</div>';
				
			}
			for ($j=$max; $j<50; $j++)
			{
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value=""/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> <input type="text" name="type_'.$j.'" class="i"></input></span><br/>
				</div>';
				
			}
		}
		else 
		{
			for ($j=4; $j<50; $j++)
			{
			
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'"/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> <input type="text" name="type_'.$j.'" class="i"></input></span><br/>
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