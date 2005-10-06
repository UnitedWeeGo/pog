<?
session_start();
include "class.object.php";
include "class.zipfile.php";
include "class.misc.php";
include "pogged/misc.php";

if (IsPostback())
{
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
		if (GetVariable(('type_'.$i)))
		{
			if (GetVariable(('type_'.$i)) != "OTHER"  && GetVariable(('ttype_'.$i)) == null)
				$typeList[] = GetVariable(('type_'.$i));
			else
				$typeList[] = GetVariable(('ttype_'.$i));
		}
	}
	$object = new Object($objectName,$attributeList,$typeList);
	
	$object->BeginObject();
	$object->CreateConstructor();
	$object->CreateGetFunction();
	$object->CreateGetAllFunction();
	$object->CreateSaveFunction();
	$object->CreateSaveNewFunction();
	$object->CreateDeleteFunction();
	$object->CreateCompareFunctions();
	$object->CreateSQLQuery();
	$object->EndObject();
	
	$_SESSION['objectName'] = $objectName;
	$_SESSION['attributeList'] = serialize($attributeList);
	$_SESSION['$typeList'] = serialize($typeList);
	
	$objectList[]=$object->objectName;
	
	$zipfile = new zipfile();
	// add the subdirectory ... important! 
	$zipfile -> add_dir("./pogged/"); 
	
	
	$filename = time().".php";
	$filedata = fopen("./pogged/$filename","w+");
	fwrite ($filedata, $object -> string);
	fclose ($filedata); 
	
	//read database file
	$filedata = fopen("./pogged/class.database.php","r");
	$data = fread($filedata, filesize("./pogged/class.database.php"));
	fclose($filedata);
	$zipfile -> add_file($data, "class.database.php");
	
	//read configuration file
	$filedata = fopen("./pogged/configuration.php","r");
	$data = fread($filedata, filesize("./pogged/configuration.php"));
	fclose($filedata);
	$zipfile -> add_file($data, "configuration.php");
	
	//read object file;
	$filedata = fopen("./pogged/$filename","r");
	$data = fread($filedata, filesize("./pogged/$filename"));
	fclose($filedata);
	
	$zipfile -> add_file($data, $filename);
	
	// OR instead of doing that, you can write out the file to the loca disk like this: 
	$outputFile = $filename.".zip"; 
	$fd = fopen ("./pogged/$outputFile", "wb"); 
	$out = fwrite ($fd, $zipfile -> file()); 
	fclose ($fd); 
	
	mail("joelwan@gmail.com", "POG", $object->string,"From:POG@PHPOBJECTGENERATOR.COM");

	?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title>Php Object Generator: A free php object relational database code generator</title>
	<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body>
	<div class="main">
		<div class="left2">
			<img src="./aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/><a href="http://www.phpobjectgenerator.com">Php Object Generator</a>, (<a href="http://www.phpobjectgenerator.com">POG</a>) automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application. Over the years, we've come to realize that a large portion of a PHP programmer's time is wasted on coding the Database Access Layer of an application simply because every application requires different types of objects. 
			
			<br/><br/>By generating the Database Access Layer code for you, POG saves you time; Time you can spend on other areas of your project. The easiest way to understand how Php Object Generator works is to give it a try.
			
			<br/><br/>POG was written by <a href="http://www.philosophicallies.com" title="Philosophic Allies">Joel Wan</a> and <a href="http://www.faintlight.com" title="Faint Light">Mark Slemko</a>. Designs by <a href="http://www.designyouwill.com" title="Design You Will">Jonathan Easton</a>. 
			
			<br/><br/>Drop us a line @ <a href="mailto:pogguys@phpobjectgenerator.com" title="Drop us a line">pogguys@phpobjectgenerator.com</a>	
		
			<br/><br/>Want more? there's <a href="http://www.phpobjectgenerator.com/plog" title="php object generator weblog">the POG Weblog</a>.
		</div><!-- left -->
		
		<div class="middle">
			<div class="header2">
				
			</div><!-- header -->
			<form method="post" action="index.php">
			<div class="result">
				<a href="./pogged/<?=$outputFile?>" title="Download Code"><img src="./download.jpg" border="0"/></a>
			</div><!-- result -->
			<div class="greybox2">
				<textarea cols="200" rows="30"><?= $object->string;?></textarea>
			</div><!-- greybox -->
			<div class="generate2">
			</div><!-- generate -->
			<div class="restart">
				<a href="./index.php"><img src="./back1.gif" border="0"/></a><br/>
				<a href="./restart.php"><img src="./back2.gif" border="0"/></a>
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
<?
$_POST = null;
}
else
{
	header("Location:/");
}
?>