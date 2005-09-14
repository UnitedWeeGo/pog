<?
Class Javascript{
	
	// -------------------------------------------------------------
	function Begin() 
	{
?>
	<script  type="text/javascript">
	//<![CDATA[
<?	
	}
	
	// -------------------------------------------------------------
	function MM_jumpMenu() 
	{
?>
		function MM_jumpMenu(targ,selObj,restore){
		eval(targ+".location='./main.php?templateId="+selObj.options[selObj.selectedIndex].value+"'")
		if(restore)selObj.selectedIndex=0}
<?
	}
	
	// -------------------------------------------------------------
	function MakeVisible() 
	{
?>
		function MakeVisible(qid,end){
		var id=qid;
		var eid = end;
		divs=document.getElementsByTagName("div")
		for(var w=0;w<divs.length;w++)
		{
		var myid = divs[w].id;
		var realid = myid.split("_");
		if(((realid[1] == id) && (parseInt(realid[2]) < parseInt(eid))) && (divs[w].style.display=="none"))
		{
			divs[w].style.display="block";
			var control = document.getElementById(myid);
			try{ 
			eval("document.skoochie." + control.id +".focus()");
			}
			catch(e)
			{
				eval("document.skoochie." + control.id +"_1.focus()");
			}
			break;
		}
		}
		}
<?
	}
	
	// -------------------------------------------------------------
	function MakeTabActive() 
	{
?>
		function MakeTabActive(tabId)
		{
			var id=tabId;
			li=document.getElementsByTagName("li");
			for(var w=0;w<li.length;w++)
			{
				if (li[w].id == id)
				{
					li[w].id ="current";
					div=document.getElementsByTagName("div");
					for(var v=0;v<div.length;v++)
					{
						if (div[v].id == ("content_"+id))
						{
							div[v].style.display ="block";
				
						}
						else if (div[v].id != "tabs" && div[v].id != "button")
						{
							div[v].style.display ="none";
				
						}
					}
				}
				else if (li[w].id =="current")
				{
					li[w].id = w;
				}
			}
		}
<?
	}
	
	// -------------------------------------------------------------
	function MakeInvisible() 
	{
?>
		function MakeInvisible(qid){
	   
		var id=qid
		divs=document.getElementsByTagName("div")
		for(var w=0;w<divs.length;w++){
		if(divs[w].id==id){divs[w].style.display="none";}}
		inputs=document.getElementsByTagName("input")
		for(var w=0;w<inputs.length;w++){
		if(inputs[w].id==id){inputs[w].value='';}}
		}
<?
	}

// -------------------------------------------------------------
	function PopulateAnswer() 
	{
?>
		function PopulateAnswer(qid,answer){
		var id=qid;
		var a=answer;
		var question = document.getElementById(id);
		try{
		question.value=decode64(a);
		}
		catch(e)
		{
		}
		}
<?
	}
	
	
	// -------------------------------------------------------------
	function CallPopulateAnswer($questionId,$answer) 
	{

		 return "PopulateAnswer(\"".$questionId."\",\"".base64_encode($answer)."\");";
	}
	
	// -------------------------------------------------------------
	function ConvertQuestionsToAnswers() 
	{
?>
		function ConvertQuestionToAnswers(){
		//allows the user to see an accurate preview of their event while creating it.
<?
	}
	
	/** -------------------------------------------------------------
	* @abstract Removes the frames an a browser if noticed.
	*/
	function RemoveFrames()
	{
?>
		if (location != top.location)
		{
			top.location = location;
		}
<?		
	}
	
	// -------------------------------------------------------------
	function Stack() 
	{
?>
		function stack(thoughtId){
		var id=thoughtId
		thoughts=document.getElementsByTagName("div")
		if(id=='all'){
		for(w=0;w<thoughts.length;w++){
		if(thoughts[w].id.substring(0,4)=='side'){thoughts[w].style.display="none";}
		if(thoughts[w].id.substring(0,2)=='up'){thoughts[w].style.display="inline";}
		if(thoughts[w].id.substring(0,3)=='exc'){thoughts[w].style.display="none";}}}
		else{
		document.getElementById('side'+id).style.display="none"
		document.getElementById('up'+id).style.display="inline"
		for(var w=0;w<thoughts.length;w++){
		if(thoughts[w].id=='exc'+id){thoughts[w].style.display="none";}}}}
<?
	}
	
	// -------------------------------------------------------------
	function Base64Decode() 
	{
?>
		function decode64(inp)
		{
			var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" + //all caps
"abcdefghijklmnopqrstuvwxyz" + //all lowercase
"0123456789+/="; // all numbers plus +/=

			var out = ""; //This is the output
			var chr1, chr2, chr3 = ""; //These are the 3 decoded bytes
			var enc1, enc2, enc3, enc4 = ""; //These are the 4 bytes to be decoded
			var i = 0; //Position counter
		
			// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
			var base64test = /[^A-Za-z0-9\+\/\=]/g;
		
			if (base64test.exec(inp)) { //Do some error checking
				alert("There were invalid base64 characters in the input text.\n" +
				"Valid base64 characters are A-Z, a-z, 0-9, ?+?, ?/?, and ?=?\n" +
				"Expect errors in decoding.");
			}
			inp = inp.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		
			do { 
		
				//Grab 4 bytes of encoded content.
				enc1 = keyStr.indexOf(inp.charAt(i++));
				enc2 = keyStr.indexOf(inp.charAt(i++));
				enc3 = keyStr.indexOf(inp.charAt(i++));
				enc4 = keyStr.indexOf(inp.charAt(i++));
		
				
				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;
		
				
				out = out + String.fromCharCode(chr1);
		
				if (enc3 != 64) {
					out = out + String.fromCharCode(chr2);
				}
				if (enc4 != 64) {
					out = out + String.fromCharCode(chr3);
				}
		
				
				chr1 = chr2 = chr3 = "";
				enc1 = enc2 = enc3 = enc4 = "";
		
			} while (i < inp.length); 
		
			
			return out;
		}
<?
	}
	// -------------------------------------------------------------
	function UnStack() 
	{
?>
		function unstack(thoughtId){
		var id=thoughtId
		thoughts=document.getElementsByTagName("div")
		if(id=='all'){
		for(w=0;w<thoughts.length;w++){
		if(thoughts[w].id.substring(0,4)=='side'){thoughts[w].style.display="inline";}
		if(thoughts[w].id.substring(0,2)=='up'){thoughts[w].style.display="none";}
		if(thoughts[w].id.substring(0,3)=='exc'){thoughts[w].style.display="inline";}}}
		else{
		document.getElementById('side'+id).style.display="inline"
		document.getElementById('up'+id).style.display="none"
		for(w=0;w<thoughts.length;w++){
		if(thoughts[w].id=='exc'+id){thoughts[w].style.display="inline";}}}}
<?
	}
	
	// -------------------------------------------------------------
	function Livesearch() 
	{
?>
		var liveSearchReq=false;
		var t=null;
		var liveSearchLast="";
		var isIE=false;
		if(window.XMLHttpRequest)
		{
			liveSearchReq1=new XMLHttpRequest();
		}
		
		function clearSearch()
		{
			var searchInput=document.getElementById('livesearch');
			searchInput.value='';
		}
		
		function liveSearchInit()
		{
			var searchInput=document.getElementById('livesearch');
			searchInput.value="";
			if(navigator.userAgent.indexOf("Safari")>0)
			{
				document.getElementById('livesearch').addEventListener("keydown",liveSearchKeyPress,false);
			}
			else if(navigator.product=="Gecko")
			{
				document.getElementById('livesearch').addEventListener("keypress",liveSearchKeyPress,false);
				document.getElementById('livesearch').addEventListener("blur",liveSearchHideDelayed,false);
			}
			else
			{
				document.getElementById('livesearch').attachEvent('onkeydown',liveSearchKeyPress);
				/*document.getElementById('livesearch').attachEvent("onblur",liveSearchHideDelayed,false);*/
				isIE=true;
			}
			document.getElementById('livesearch').setAttribute("autocomplete","off");
		}
		
		function liveSearchHideDelayed()
		{
			document.getElementById('livesearch').value="Search Cancelled";
			window.setTimeout("liveSearchHide()",400);
		}

		function liveSearchHide()
		{
			document.getElementById("column1s").style.display="none";
			document.getElementById("column1").style.display="block";
		}

		function liveSearchKeyPress(event)
		{
			if(event.keyCode==13)
			{
				liveSearchHide();
			}
		}

		function liveSearchStart()
		{
			if(t)
			{
				window.clearTimeout(t);
			}
			t=window.setTimeout("liveSearchDoSearch()",200);
		}

		function liveSearchDoSearch()
		{
			if(typeof liveSearchRoot=="undefined")
			{
				liveSearchRoot="";
			}
			if(typeof liveSearchRootSubDir=="undefined")
			{
				liveSearchRootSubDir="";
			}
			if(typeof liveSearchParams=="undefined")
			{
				liveSearchParams="";
			}
			if(liveSearchLast !=document.getElementById('livesearch').value)
			{
				if(document.getElementById('livesearch').value=="")
				{
					liveSearchHide();
					return false;
				}
				if(window.XMLHttpRequest)
				{
				}
				else if(window.ActiveXObject)
				{
					liveSearchReq1=new ActiveXObject("Microsoft.XMLHTTP");
				}
				liveSearchReq1.onreadystatechange=liveSearchProcessReqChange1;
				liveSearchReq1.open("GET",liveSearchRoot+"/coachlog/livesearch.php?qu="+document.getElementById('livesearch').value+liveSearchParams);
				liveSearchLast=document.getElementById('livesearch').value;
				liveSearchReq1.send(null);
			}
		}

		function liveSearchProcessReqChange1()
		{
			if(liveSearchReq1.readyState==4)
			{
				var res=document.getElementById("column1s");
				res.style.display="block";
				var sh=document.getElementById("column1s2");
				sh.innerHTML=liveSearchReq1.responseText;
				var column1=document.getElementById("column1");
				column1.style.display="none";
			}
		}

		function liveSearchSubmit()
		{
			var highlight=document.getElementById("LSHighlight");
			if(highlight&&highlight.firstChild)
			{
				window.location=liveSearchRoot+liveSearchRootSubDir+highlight.firstChild.nextSibling.getAttribute("href");
				return false;
			}
			else
			{
				return true;
			}
		}
<?
	}
	// -------------------------------------------------------------
		function End() 
		{
?>
		//]]>
		</script >
<?	
	}
}
?>