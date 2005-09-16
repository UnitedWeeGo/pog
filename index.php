<?
include "class.object.php";
include "class.zipfile.php";
include "class.misc.php";
include "pogged/misc.php";

if (IsPostback())
{
	$objectName = GetVariable('object');
	$attributeList=Array();
	$typeList=Array();
	for ($i=1; $i<50; $i++)
	{
		if (GetVariable(('fieldattribute_'.$i)))
		{
			$attributeList[] = GetVariable(('fieldattribute_'.$i));
		}
		if (GetVariable(('type_'.$i)))
		{
			$typeList[] = GetVariable(('type_'.$i));
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
	$object->CreateSQLQuery();
	$object->EndObject();
	
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
		
			<br/><br/>Last update: Sep 2 2005.
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
			</form>
		</div><!-- middle -->
		<div class="right2">
		Read more:
		<a href="/plog" title="php object generator weblog">The POG Weblog (PLOG)</a>
<br/><br/><br/><br/>
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
}
else
{
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
function RemoveField(qid)
{
	var id=qid;
	var eid = end;
	trs=document.getElementsByTagName("tr")
	for(var w=0;w<trs.length;w++)
	{
		var myid = trs[w].id;
		if(myid[1] == id)
		{
			trs[w].style.display="none";
			var control = document.getElementById(myid);
			break;
		}	
	}
}
//]]>
</script>
<title>Php Object Generator: A free php object relational database code generator</title>
<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
<meta name="description" content="Php Object Generator, (POG) automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented" />
</head>
<body>
<div class="main">
	<div class="left">
		<img src="./aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/><a href="http://www.phpobjectgenerator.com">Php Object Generator</a>, (<a href="http://www.phpobjectgenerator.com">POG</a>) automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application. Over the years, we've come to realize that a large portion of a PHP programmer's time is wasted on coding the Database Access Layer of an application simply because every application requires different types of objects. 
		
		<br/><br/>By generating the Database Access Layer code for you, POG saves you time; Time you can spend on other areas of your project. The easiest way to understand how Php Object Generator works is to give it a try.
		
		<br/><br/>POG was written by <a href="http://www.philosophicallies.com" title="Philosophic Allies">Joel Wan</a> and <a href="http://www.faintlight.com" title="Faint Light">Mark Slemko</a>. Designs by <a href="http://www.designyouwill.com" title="Design You Will">Jonathan Easton</a>. 
		
		<br/><br/>Drop us a line @ <a href="mailto:pogguys@phpobjectgenerator.com" title="Drop us a line">pogguys@phpobjectgenerator.com</a>	
		<br/><br/>Last update: Sep 2 2005.
	</div><!-- left -->
	
	<div class="middle">
		<div class="header">
		</div><!-- header -->
		<form method="post" action="index.php">
		<div class="objectname">
			<input type="text" name="object" class="i"/>
		</div><!-- objectname -->
		<div class="greybox">
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/> <input type="text" name="fieldattribute_1" class="i"></input>  &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> <input type="text" name="type_1" class="i"></input></span><br/><br/>
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_2" class="i"></input> &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> <input type="text" name="type_2" class="i"></input></span><br/><br/>
			<span class="line"><img src="./object2.jpg" width="33" height="29" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute" width="56" height="18"/>  <input type="text" name="fieldattribute_3" class="i"></input> &nbsp;&nbsp;<img src="./type.jpg" width="36" height="18" alt="object attribute"/> <input type="text" name="type_3" class="i"></input></span><br/>
		<? 
		for ($j=4; $j<50; $j++)
		{
		?>
			<div style="display:none" id="attribute_<?php print $j?>">
				<br/><span class="line"><img src="./object2.jpg" alt="object attribute"/><img src="./attribute.jpg" alt="object attribute"/>  <input type="text" name="fieldattribute_<?php print $j?>" class="i" id="fieldattribute_<?php print $j?>"/> &nbsp;&nbsp;<img src="./type.jpg" alt="object attribute"/> <input type="text" name="type_<?php print $j?>" class="i"></input></span><br/>
			</div><!-- none -->
		<?	
		}
		?>
		</div><!-- greybox -->
		<div class="generate">
			<a href="#" onclick="AddField()"><img src="./addattribute.jpg" border="0" alt="add attribute"/></a>
		</div><!-- generate -->
		<div class="submit">
			<input type="image"  src="./generate.jpg" alt="Generate!"/>
		</div><!-- submit -->
		</form>
	</div><!-- middle -->
	<div class="right">
	<a href="/plog" title="php object generator weblog">The POG Weblog (PLOG)</a>
<br/><br/><br/><br/>

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

<?
}
?>
</body>
</html>