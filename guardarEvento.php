<?php
	include_once("funciones.php");
	$link=conectarse();	
	
	if($_POST['evento']=="Cancelar"){$evento="Cancelada" ;}
	if($_POST['evento']=="Confirmar"){$evento="Confirmada" ;}
	if($_POST['evento']=="Eliminar"){$evento="Eliminada" ;}
	
	$campo_usuario = strtolower($evento) ;
	$campo_fecha = strtolower($evento)."_fecha" ;
	
	$sql = "update citas set estatus='$evento', $campo_fecha = now() where id='$_POST[id_cita]'" ;
	if($query = mysqli_query($link,$sql)){
		echo "YES" ;
	}
	else{
		echo "NO";
		} 
	
?>