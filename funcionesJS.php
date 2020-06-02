<?php 
include_once("funciones.php") ;
require("SendGrid/sendgrid-php.php");
$link = conectarse() ;
date_default_timezone_set('America/Mexico_City');


if($_POST["accion"] == "buscar juzgados"){
	if(strpos($_POST['juzgado'],"Mixto")!==false){ $campo = "mixto" ;  }
	if(strpos($_POST['juzgado'],"Familiar")!==false){ $campo = "familiar" ;  }
	if(strpos($_POST['juzgado'],"Civil")!==false){ 	$campo = "civil" ;  }
	if(strpos($_POST['juzgado'],"Mercantil")!==false){ $campo = "mercantil" ;  }
	if(strpos($_POST['juzgado'],"Penal")!==false){ $campo = "penal" ;  }
	$sql = "select * from cat_servicios where $campo = 1";
	$query = mysqli_query($link, $sql);
	while($datos = mysqli_fetch_assoc($query)){
		$servicios[] = array("id_servicio"=>$datos[id] , "servicio" => "$datos[servicio]") ;
	}
	
	echo json_encode($servicios) ;
}

if($_POST["accion"] == "buscar horas"){

		$sqlH = "select * from horasBloqueadas where id_juzgado = '$_POST[juzgado]' and fecha='$_POST[dia]' ";
		$queryH = mysqli_query($link, $sqlH) ;
		$bloqueadas=array();
		while($horasH = mysqli_fetch_assoc($queryH)){
			$bloqueadas[] = substr("$horasH[hora]",0,5) ;
		}

		$horarios = array(	
						1 => array("horario" => "08:30", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "09:00", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "09:30", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "10:00", "citas" => 0 , "disponible" => "") , 
						5 => array("horario" => "10:30", "citas" => 0 , "disponible" => "") , 
						6 => array("horario" => "11:00", "citas" => 0 , "disponible" => "") , 
						7 => array("horario" => "11:30", "citas" => 0 , "disponible" => "") , 
						8 => array("horario" => "12:00", "citas" => 0 , "disponible" => "") , 
						9 => array("horario" => "12:30", "citas" => 0 , "disponible" => "") , 
						10 => array("horario" => "13:00", "citas" => 0 , "disponible" => "") , 
						11 => array("horario" => "13:30", "citas" => 0 , "disponible" => "") , 
						12 => array("horario" => "14:00", "citas" => 0 , "disponible" => "") 
					) ;

	foreach($horarios as $valor ){
		$x+=1 ;
		$horario = $horarios[$x]["horario"] ;
	
		$sql = "select count(fecha) as horasReservadas from citas where id_juzgado = '$_POST[juzgado]'  and fecha = '$_POST[dia]' " ;
		/* citas por juzgado, fecha y hora --  $sql = "select count(fecha) as horasReservadas from citas where id_juzgado = '$_POST[juzgado]'  and fecha = '$_POST[dia]' and substring(hora,1,5) = '$horario' " ; */
		$query = mysqli_query($link, $sql) ;
		$datos = mysqli_fetch_assoc($query) ;
		
		$horaActual = date("H:i");
		$diaActual = date("Y-m-d");

		for($y = 1 ; $y<=10 ; $y++){
			$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
			if(date("w",$nuevafecha)!=0 and date("w",$nuevafecha)!=6  ){
				$diaSiguiente = $nuevafecha ; // DIA SIGUIENTE HABIL //
				$y=11 ;
			}
		}

		$citas = 0 ;
		if( ($horaActual > "14:30" and $_POST['dia'] == $diaSiguiente) ){
			$citas = 100 ;
		}
		if(in_array("$horario",$bloqueadas)){
			$citas = 200 ;
		}
		if($_POST['dia']==$diaActual){
			$citas = 300 ;
		}
		$horarios["$x"]["citas"] = $datos['horasReservadas'] + $citas  ;
		$horarios["$x"]["disponible"] =  "$citas"  ;
		$horarios["$x"]["bloqueadas"] = $bloqueadas ;
		$horarios["$x"]["sql"] = "hora actual : $horaActual - dia solicitado : $_POST[dia] - dia siguiente de hoy : $diaSiguiente " ;
		$arreglo["$horario"] = "$datos[horasReservadas]" ; 
		
		//$horarios[] = "$sql" ;
		
	}
	echo json_encode($horarios) ;
}


if($_POST['accion']=="buscar beneficiario"){
    
    $sql = "select count(fecha) as citas from citas where fecha = '$_POST[dia]' and correo = '$_POST[correo]'" ;
    $query = mysqli_query($link , $sql) ;
    $registros = mysqli_fetch_assoc($query) ;
    echo $registros['citas'] ;
    
}

if($_POST['accion']=="guardar cita"){
	$link = conectarse() ;
	$sql = "insert into citas 
							(
								id_servicio,
								detalles,
								hora,
								fecha,
								id_beneficiario,
								id_juzgado,
								alta_fecha,
								correo,
								estatus
							)
							values 
							(
								'$_POST[servicio]',
								'$_POST[detalles]',
								'$_POST[horaSeleccionada]',
								'$_POST[fechaSeleccionada]',
								'$_SESSION[usuarioId]',
								'$_POST[juzgado]',
								now(),
								'$_SESSION[correo]',
								'Agendada'
							)" ;
	if( $query = mysqli_query($link, $sql) ){
		$ultimoID = mysqli_insert_id($link) ;
		echo $ultimoID ;
	}
	else{
		echo "surgio un error al registrar la cita  " ;
	}	    
}


if($_POST['accion']=="enviar correo"){
		include_once("funciones.php") ;
		require("SendGrid/sendgrid-php.php");
		//include_once('resources/correo/class.phpmailer.php');
		//include_once('resources/correo/class.smtp.php');

		$sql = "select c.*, u.* ,j.distrito,j.juzgado, cat.servicio from citas c
					left join usuario u on u.email = c.correo
					left join juzgados j  on j.id = c.id_juzgado
					left join cat_servicios cat on  cat.id = c.id_servicio  
				where c.id = $_POST[ultimoID] limit 1" ;

		$query = mysqli_query($link, $sql) ;
		$datos = mysqli_fetch_assoc($query) ;
		$para = "carlos.hernandez@coahuila.gob.mx" ;
	 //Este bloque es importante
	    $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("informatica@coahuila.gob.mx", "INTRANET PJECZ");
        $email->setSubject("Cita agendada PJECZ");
        $email->addTo($para);
        $email->addContent(
        	"text/html", "
	    	<body>

	    		<table>
							<tr>
								<td style='background-color:#efeff1; border-bottom:4px solid #555; text-align:center' colspan=2>
									<h3>Poder Judicial del Estado de Coahuila</h3>
								</td>
							</tr>
						
							<tr>	
								<td colspan=2>
									<h3>Su cita ha sido agendada correctamente</h3>
									Gracias por utilizar nuestro servicio para agendar su cita. Debajo encontrar&oacute; los detalles de su cita.
									<h3>Detalles de la cita</h3>
								</td>
							</td>
							<tr>
								<td>
									<label>Folio</label>
								</td>
								<td>
									<label>#". (substr($_POST['fechaSeleccionada'],5,2) ."_". substr($_POST['fechaSeleccionada'],8,2) ."_". str_replace(":","_",$_POST['horaSeleccionada']) . "_" . $datos[id]) . "</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Distrito</label>
								</td>
								<td>
									<label>$datos[distrito]</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Juzgado</label>
								</td>
								<td>
									<label>$datos[juzgado]</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Fecha y hora de la cita</label>
								</td>
								<td>
									<label>$datos[fecha] / $datos[hora]</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Asunto al que asiste</label>
								</td>
								<td>
									<label>$datos[servicio] <br> $datos[detalles]</label>
								</td>
							</tr>

							<tr>
								<td colspan=2>
									<h3>Detalle del beneficiario</h3>
								</td>
							</tr>

							<tr>
								<td>
									<label>Nombre</label>
								</td>
								<td>
									<label>$datos[nombre] $datos[apPaterno] $datos[apMaterno] </label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Correo electr&oacute;nico</label>
								</td>
								<td>
									<label>$datos[correo]</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>N&uacute;mero de tel&eacute;fono</label>
								</td>
								<td>
									<label>$datos[telefono]</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Domicilio</label>
								</td>
								<td>
									<label>$datos[domicilio]</label>
								</td>
							</tr>																																																																												
						<tr>
							<td colspan=2 style='text-align:center'>
								<a href='https://pjecz.gob.mx'>Sitio del Poder Judicial del Estado de Coahuila</a>
							</td>
						</tr>
	    		</table>
	    	</body>
	    		");
	   
	    //Avisar si fue enviado o no y dirigir al index
	    $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
           
        } catch (Exception $e) {
          
        }


}

if(isset($_POST['accion']) and $_POST['accion']=="bloquear hora"){
	
	$sql = "insert into horasBloqueadas (id_juzgado ,fecha, hora, activo) values ('$_SESSION[id_juzgado]','$_POST[fecha]', '$_POST[hora]',1)" ;
	if($query = mysqli_query($link, $sql)){
		echo "YES" ;
	}
	else{
		echo "ERROR" ;
	}
}


 ?>