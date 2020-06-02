function loadXMLDoc()
{
var xmlhttp;
var n=document.getElementById('bus').value;
if(n==''){
 document.getElementById("myDiv").innerHTML="";
 return;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
    }else{ document.getElementById("myDiv").innerHTML='<img src="resources/ajax-loader.gif" width="50" height="50" />'; }
  }
xmlhttp.open("POST","proc.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("q="+n);
}



function buscarBeneficiario(){
	var xmlhttp;
	var n=document.getElementById('bus').value;
	if(n==''){
	 document.getElementById("myDiv").innerHTML="";
	 return;
	}
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
		}else{ document.getElementById("myDiv").innerHTML='<img src="resources/ajax-loader.gif" width="50" height="50" />'; }
	  }
	xmlhttp.open("POST","procBeneficiarioGestion.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("q="+n);
}

function buscarNombre(){
	var xmlhttp;
	var n=document.getElementById('nombre').value;
	if(n==''){
	 document.getElementById("myDiv").innerHTML="";
	 return;
	}
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
		}else{ document.getElementById("myDiv").innerHTML='<img src="resources/ajax-loader.gif" width="50" height="50" />'; }
	  }
	xmlhttp.open("POST","procNombreSeguimiento.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("q="+n);
}


function buscarNombreObra(){
	var xmlhttp;
	var n=document.getElementById('nombre').value;
	if(n==''){
	 document.getElementById("myDiv").innerHTML="";
	 return;
	}
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
		}else{ document.getElementById("myDiv").innerHTML='<img src="resources/ajax-loader.gif" width="50" height="50" />'; }
	  }
	xmlhttp.open("POST","procNombreObra.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("q="+n);
}
