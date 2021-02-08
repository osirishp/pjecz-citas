<?php
session_start();
$buscar = strstr("localhost",$_SERVER['SERVER_NAME']);
define("TITULO","Sistema de Citas :: Poder Judicial del Estado de Coahuila") ;
if(! empty($buscar)){
	$_SESSION['path']="http://localhost/citas/" ;
	$_SESSION['entorno']="Local";
}
else{
	$_SESSION['path']="https://citas.poderjudicialcoahuila.gob.mx/" ;
	$_SESSION['entorno']="Internet";
}


	function convertirFecha($fecha){
		$mes=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$dato = explode("-",$fecha);
		$datoFinal = "$dato[2]-".$mes[intval($dato[1])]."-".$dato[0];
		return $datoFinal ;
	}


	function conectarse(){
	
		if($_SESSION['entorno']=="Local"){
			$servidor="localhost"; 
			$usuario="root"; 
			$passwd="";
			$database ="pjecgob_citas";}
		
		if($_SESSION['entorno']=="Internet")	{
			$servidor="localhost"; 
			$database = "citasdb";
			$usuario="citasadmin"; 
			$passwd="87JYTrYofXcc";

		}
		
		if (!($link=mysqli_connect("$servidor","$usuario","$passwd","$database"))){
	  		echo "Se presento un error con el Servidor o Nombre de Usuario";
	  		exit();
		}
        mysqli_query($link, "set names 'utf8' ");  		
	 	return $link ;
	}


function seguridad(){
	session_start();
	if($_SESSION["autentificado"] != "SI"){
		header("location:control.php");
		exit();
	}
}

function subtitulo(){
echo "<table width='100%' cellpadding='0' cellspacing='0' border=0>
	<tr>
		<td width='90%'>
			<img src='img/hdtitulo.jpg'>
			<br>
			<font color='white' size='4' face='Trebuchet MS'>
				$_SESSION[secretaria]
			</font>
		</td>
		<td valign='bottom' align='center'>
			<a href='index.php'><img src='img/btn_salir.png'></a>
		</td>
	</tr>
</table>";
}


function UltimoDia($anio,$mes){ 
   if (((fmod($anio,4)==0) and (fmod($anio,100)!=0)) or (fmod($anio,400)==0)) { 
       $dias_febrero = 29; 
   } else { 
       $dias_febrero = 28; 
   } 
   switch($mes) { 
       case 01: return 31; break; 
       case 02: return $dias_febrero; break; 
       case 03: return 31; break; 
       case 04: return 30; break; 
       case 05: return 31; break; 
       case 06: return 30; break; 
       case 07: return 31; break; 
       case 8: return 31; break; 
       case 9: return 30; break; 
       case 10: return 31; break; 
       case 11: return 30; break; 
       case 12: return 31; break; 
   } 
} 



function barValueFormat($aLabel) { 
    // Format '1000 english style 
//    return number_format($aLabel) 
    // Format '1000 french style 
     return number_format($aLabel, 0, '.', ','); 
}





?>
