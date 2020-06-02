<?php
	include_once("funciones.php");
	$link=conectarse();	

	$paterno = strtoupper($_POST['paterno']) ;
	$materno = strtoupper($_POST['materno']) ;
	$nombre = strtoupper($_POST['nombre']) ;
	$servicio = $_POST['servicio'] ;
	$sesiones = $_POST['sesiones'] ;
	$costo = $_POST['costo'] ;
	$fecha = $_POST['fecha'] ;
	$celular = $_POST['celular'] ;
	$sucursal = $_POST['sucursal'] ;
	
	$hora = $_POST['hora'] ;
	/*if(strpos($hora,"pm")){
		if(substr($hora,0,strpos($hora,":") )  <8){				
			$hora = (substr($hora,0,strpos($hora,":") )+12 ) . substr($hora,strpos($hora,":")  ,5 ) ;
		}
	}*/
	
	$asistente = $_POST['asistente'] ;
	$sql = "select * from asistentes where id='$asistente'" ;
	$query = mysqli_query($link,$sql) ;
	$datos = mysqli_fetch_assoc($query);
	$nombre_asistente = $datos['asistente'] ;
	
	$sql = "select * from cat_servicios where servicio = '$servicio'" ;
	$query = mysqli_query($link,$sql);
	$datos = mysqli_fetch_assoc($query) ;
	
	$sql = "select * from clientes where concat(paterno,' ',materno,' ',nombre)= '$paterno $materno $nombre'" ;
	$query = mysqli_query($link,$sql);
	$registros = mysqli_num_rows($query) ;

	if($registros>0){
		$datos = mysqli_fetch_assoc($query);
		$id = $datos['id'];
		
		if(empty($datos['num_tel'])){
			$sqlU = "update clientes set num_tel = '$celular'  where id='$id' limit 1" ;
			$queryU = mysqli_query($link,$sqlU)	 ;
		}
		
		$sql="insert into servicios (id_cliente,servicio,fecha,sesiones,costo,resto) values ('$id','$servicio',curdate(),'$sesiones','$costo','$costo')";
		$query = mysqli_query($link,utf8_decode($sql));
		$id_servicio = mysqli_insert_id($link);
		//echo $sql ,"<br>";

		$sql="insert into citas (id_servicio,servicio,hora,fecha,id_cliente,id_asistente,asistente,sucursal,alta,alta_fecha,estatus) values ('$id_servicio','".utf8_decode("$servicio")."','$hora','$fecha','$id','$asistente','$nombre_asistente','$sucursal','$_SESSION[usuario]',now(),'')";
		$query = mysqli_query($link,$sql);
		//echo $sql ; exit();
		
		echo "Cita agendada correctamente" ;	

		}
	else{
		$sql = "insert into clientes (nombre,paterno,materno,num_tel) values ('$nombre','$paterno','$materno','$celular')";
		$query = mysqli_query($link,$sql) ;
		$id_cliente = mysqli_insert_id($link);
		//echo $sql,"<br>";
		
		$sql="insert into servicios (id_cliente,servicio,fecha,sesiones,costo,resto) values ('$id_cliente','$servicio',curdate(),'$sesiones','$costo','$costo')";
		$query = mysqli_query($link,utf8_decode($sql));
		$id_servicio = mysqli_insert_id($link);
		//echo $sql,"<br>";

		$sql="insert into citas (id_servicio,servicio,hora,fecha,id_cliente,id_asistente,asistente,sucursal,alta,alta_fecha,estatus) values ('$id_servicio','".utf8_decode("$servicio")."','$hora','$fecha','$id_cliente','$asistente','$nombre_asistente','$sucursal','$_SESSION[usuario]',now(),'')";
		$query = mysqli_query($link,utf8_decode($sql));
		//echo $sql,"<br>"; exit();

		echo "Cliente nuevo";
	}

//	$sql = "insert into citas (hora,fecha) values ('$_POST[hora]','$_POST[fecha]') ";
//	$query = mysqli_query($link,$sql);

?>

