<?
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();
include "./include/configuration.php";
include "./include/class.misc.php";
include "./include/misc.php";

if (IsPostback())
{
	$_GET = null;
	$objectName = GetVariable('object');
	$attributeList=Array();
	$typeList=Array();
	$z=0;
	for ($i=1; $i<50; $i++)
	{
		if (GetVariable(('fieldattribute_'.$i)))
		{
			$attributeList[] = GetVariable(('fieldattribute_'.$i));
			$z++;
		}
		if (GetVariable(('type_'.$i)) && $z==$i)
		{
			if (GetVariable(('type_'.$i)) != "OTHER"  && GetVariable(('ttype_'.$i)) == null)
				$typeList[] = GetVariable(('type_'.$i));
			else
				$typeList[] = GetVariable(('ttype_'.$i));
		}
	}

	$_SESSION['language'] = $language = GetVariable('language');
	$_SESSION['wrapper'] = $wrapper = GetVariable('wrapper');
	$_SESSION['pdoDriver'] = $pdoDriver = GetVariable('pdoDriver');
	if (strtoupper($wrapper) == "PDO")
	{
		eval("include \"./object_factory/class.object".$language.strtolower($wrapper).$pdoDriver.".php\";");
	}
	else
	{
		if  ($language == "php4")
		{
			eval("include \"./object_factory/class.objectphp4pogmysql.php\";");
		}
		else
		{
			eval("include \"./object_factory/class.objectphp5pogmysql.php\";");
		}
	}
	$object = new Object($objectName,$attributeList,$typeList,$pdoDriver);

	$object->BeginObject();
	$object->CreateConstructor();
	$object->CreateGetFunction();
	$object->CreateGetAllFunction();
	$object->CreateSaveFunction();
	$object->CreateSaveNewFunction();
	$object->CreateDeleteFunction();
	if(strtoupper($wrapper) == "PDO")
	{
		$object->CreateEscapeFunction();
		$object->CreateUnescapeFunction();
	}
	$object->EndObject();

	$_SESSION['objectName'] = $objectName;
	$_SESSION['attributeList'] = serialize($attributeList);
	$_SESSION['typeList'] = serialize($typeList);

	$objectList[]=$object->objectName;
	$_SESSION['objectString'] = $object->string;
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title>Php Object Generator (<?=$GLOBALS['configuration']['versionNumber']?> <?=$GLOBALS['configuration']['revisionNumber']?>) - Open Source PHP Code Generator</title>
	<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	_uacct = "UA-72762-1";
	urchinTracker();
	</script>
	</head>
	<body>
	<div class="main">
		<div class="left2">
			<img src="./images/aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/><a href="http://www.phpobjectgenerator.com" title="PHP Object Generator">Php Object Generator</a>, (<a href="http://www.phpobjectgenerator.com" title="POG">POG</a>) is an open source <h1><a href="http://www.phpobjectgenerator.com" title="PHP code generator">PHP code generator</a></h1>&nbsp;which automatically generates clean &amp; tested Object Oriented code for your PHP4/PHP5 application. Over the years, we realized that a large portion of a PHP programmer's time is wasted on repetitive coding of the Database Access Layer of an application simply because different applications require different objects.

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
		<br/><a href="http://www.phpobjectgenerator.com/plog" title="php object generator weblog">The POG Weblog</a> and <a href="http://www.phpobjectgenerator.com/plog/rss/" title="POG RSS feed">RSS feed</a>.
		<br/><a href="http://groups.google.com/group/Php-Object-Generator" title="Php object generator google group">The POG Google group</a>
		<br/><a href="http://www.phpobjectgenerator.com/plog/tutorials" title="php object generator tutorials and documentation">The POG Tutorials (in progress)</a>
		<br/><a href="http://www.faintlight.com/techinfo/pog" title="POG mirror site">The POG mirror site</a>
		<br/><a href="http://www.phpobjectgenerator.com/plog/version" title="POG history log">The POG history log</a>

		<br/><br/>Programmers:<br/><a href="http://www.philosophicallies.com" title="Joel Wan">Joel Wan</a><br/><a href="http://www.faintlight.com" title="Mark Slemko">Mark Slemko</a><br/>Designer:<br/><a href="http://www.designyouwill.com" title="Jonathan Easton">Jonathan Easton</a><br/>Consultancy:<br/><a href="http://www.finessehosting.com" title="Veemal Gungadin">Veemal Gungadin</a>


		<br/><br/>Feedback, Feature Requests, Bugs to: <a href="mailto:pogguys@phpobjectgenerator.com" title="Drop us a line">pogguys@phpobjectgenerator.com</a>


		</div><!-- left -->
		<div class="middle">
			<div class="header2">

			</div><!-- header -->
			<form method="post" action="index3.php">
			<div class="result">
				<input type="image" src="./images/download.jpg"/>
			</div><!-- result -->
			<div class="greybox2">
				<textarea cols="200" rows="30"><?=$object->string;?></textarea>
			</div><!-- greybox -->
			<div class="generate2">
			</div><!-- generate -->
			<div class="restart">
				<a href="./index.php"><img src="./images/back1.gif" border="0"/></a><br/>
				<a href="./restart.php"><img src="./images/back2.gif" border="0"/></a>
			</div><!-- restart -->
			</form>
			</div><!-- middle -->
		<div class="right2">
		<script type="text/javascript"><!--
google_ad_client = "pub-7832108692498114";
google_alternate_color = "FFFFFF";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_ad_type = "text";
google_ad_channel = "9663063625";
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
<?
$_POST = null;
}
else
{
	header("Location:/");
}
?>
