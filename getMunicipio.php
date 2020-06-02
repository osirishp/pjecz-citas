<?php
include ("funciones.php");
$link = conectarse();
 
 	$id_estado=$_POST['id_estado'];
 
 	$queryM="SELECT id_municipio, descripcion from municipios where id_estado ='$id_estado' ";
	$resultadoM = mysqli_query($link,$queryM);
	
	$html = "<option value='0'> Seleccionar Municipio</option>"; 
	
	while($rowM = mysqli_fetch_array($resultadoM))
			{
				$html.= "<option value='".$rowM['id_municipio']."'>".utf8_encode($rowM['descripcion'])."</option>"; 
				
			}
			
			echo $html;
			 
 ?>