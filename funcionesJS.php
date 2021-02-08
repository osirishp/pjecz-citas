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
	if(strpos($_POST['juzgado'],"Tradicional")!==false){ $campo = "tradicional" ;  }
	if(strpos($_POST['juzgado'],"Tribunal")!==false){ $campo = "tribunal" ;  }

	if(strpos($_POST['juzgado'],"Archivo")!==false and 	( strpos($_POST['juzgado'],"Saltillo") !==false or	strpos($_POST['juzgado'],"Acuña" ) !==false or	strpos($_POST['juzgado'],"Monclova") !==false  ) ) { $campo = "archivo1" ;  }
	if(strpos($_POST['juzgado'],"Archivo")!==false and 	( strpos($_POST['juzgado'],"Torreon") !==false or	strpos($_POST['juzgado'],"Sabinas" ) !==false  ) ) { $campo = "archivo2" ;  }
	if(strpos($_POST['juzgado'],"Archivo")!==false and 	( strpos($_POST['juzgado'],"Piedras") !==false ) ) { $campo = "archivo3" ;  }

	if(strpos($_POST['juzgado'],"Registro")!==false){ $campo = "buzon" ;  }
	if(strpos($_POST['juzgado'],"Oficialía")!==false){ $campo = "oficialia" ;  }

	$sql = "select * from cat_servicios where $campo = 1";
	$query = mysqli_query($link, $sql);
	while($datos = mysqli_fetch_assoc($query)){
		$servicios[] = array("id_servicio"=>$datos[id] , "servicio" => "$datos[servicio]", "s"=>"") ;
	}
	
	echo json_encode($servicios) ;
}

if($_POST["accion"] == "buscar horas"){

		if($_POST['tramite']==8){   //// CITA CON JUEZ //////
					$horarios = array(	 
						1 => array("horario" => "10:00", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "10:30", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "11:00", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "11:30", "citas" => 0 , "disponible" => "") 
					) ;
		}
		if($_POST['tramite']==4){   //// CITA CON ACTUARIOS //////
					$horarios = array(	 
						1 => array("horario" => "12:00", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "12:30", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "13:00", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "13:30", "citas" => 0 , "disponible" => "") 
					) ;
		}

		$diaBuzon = date("w",strtotime($_POST['dia'])) ;
		if($_POST['tramite']==13  ){   //// entrega de demandas //////
			$horarios = array(	
						1 => array("horario" => "09:00", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "09:15", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "09:30", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "09:45", "citas" => 0 , "disponible" => "") , 
						5 => array("horario" => "10:00", "citas" => 0 , "disponible" => "") , 
						6 => array("horario" => "10:15", "citas" => 0 , "disponible" => "") , 
						7 => array("horario" => "10:30", "citas" => 0 , "disponible" => "") ,
						8 => array("horario" => "10:45", "citas" => 0 , "disponible" => "") ,
						9 => array("horario" => "11:00", "citas" => 0 , "disponible" => "") , 
						10 => array("horario" => "11:15", "citas" => 0 , "disponible" => "") , 
						11 => array("horario" => "11:30", "citas" => 0 , "disponible" => "") ,
						12 => array("horario" => "11:45", "citas" => 0 , "disponible" => "") ,
						13 => array("horario" => "12:00", "citas" => 0 , "disponible" => "") , 
						14 => array("horario" => "12:15", "citas" => 0 , "disponible" => "") , 
						15 => array("horario" => "12:30", "citas" => 0 , "disponible" => "") ,
						16 => array("horario" => "12:45", "citas" => 0 , "disponible" => "") ,
						17 => array("horario" => "13:00", "citas" => 0 , "disponible" => "") , 
						18 => array("horario" => "13:15", "citas" => 0 , "disponible" => "") , 
						19 => array("horario" => "13:30", "citas" => 0 , "disponible" => "") ,
						20 => array("horario" => "13:45", "citas" => 0 , "disponible" => "") ,
						21 => array("horario" => "14:00", "citas" => 0 , "disponible" => "") , 
						22 => array("horario" => "14:15", "citas" => 0 , "disponible" => "") ,
						23 => array("horario" => "14:30", "citas" => 0 , "disponible" => "") ,
						24 => array("horario" => "14:45", "citas" => 0 , "disponible" => "") ,
						25 => array("horario" => "15:00", "citas" => 0 , "disponible" => "") 

					) ;
		} 

				$diaBuzon = date("w",strtotime($_POST['dia'])) ;
		if($_POST['tramite']==13 and $_POST['juzgado'] ==110  ){   //// entrega de demandas, Solo torreon //////
			$horarios = array(	
						1 => array("horario" => "09:00", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "09:15", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "09:30", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "09:45", "citas" => 0 , "disponible" => "") , 
						5 => array("horario" => "10:00", "citas" => 0 , "disponible" => "") , 
						6 => array("horario" => "10:15", "citas" => 0 , "disponible" => "") , 
						7 => array("horario" => "10:30", "citas" => 0 , "disponible" => "") ,
						8 => array("horario" => "10:45", "citas" => 0 , "disponible" => "") ,
						9 => array("horario" => "11:00", "citas" => 0 , "disponible" => "") , 
						10 => array("horario" => "11:15", "citas" => 0 , "disponible" => "") , 
						11 => array("horario" => "11:30", "citas" => 0 , "disponible" => "") ,
						12 => array("horario" => "11:45", "citas" => 0 , "disponible" => "") ,
						13 => array("horario" => "12:00", "citas" => 0 , "disponible" => "") , 
						14 => array("horario" => "12:15", "citas" => 0 , "disponible" => "") , 
						15 => array("horario" => "12:30", "citas" => 0 , "disponible" => "") ,
						16 => array("horario" => "12:45", "citas" => 0 , "disponible" => "") ,
						17 => array("horario" => "13:00", "citas" => 0 , "disponible" => "") , 
						18 => array("horario" => "13:15", "citas" => 0 , "disponible" => "") , 
						19 => array("horario" => "13:30", "citas" => 0 , "disponible" => "") 

					) ;
		} 

		if($_POST['tramite']==12 /* and $diaBuzon!=1 */ ){   //// BUZON ELECTRONICO //////
			$horarios = array(	
						1 => array("horario" => "09:00", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "09:30", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "10:00", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "10:30", "citas" => 0 , "disponible" => "") , 
						5 => array("horario" => "11:00", "citas" => 0 , "disponible" => "") , 
						6 => array("horario" => "11:30", "citas" => 0 , "disponible" => "") , 
						7 => array("horario" => "12:00", "citas" => 0 , "disponible" => "") , 
						8 => array("horario" => "12:30", "citas" => 0 , "disponible" => "") , 
						9 => array("horario" => "13:00", "citas" => 0 , "disponible" => "") , 
						10 => array("horario" => "13:30", "citas" => 0 , "disponible" => "") , 
						11 => array("horario" => "14:00", "citas" => 0 , "disponible" => "") ,
						12 => array("horario" => "14:30", "citas" => 0 , "disponible" => "") ,
						13 => array("horario" => "15:00", "citas" => 0 , "disponible" => "")   

					) ;
		}
		if($_POST['tramite']!=4 and $_POST['tramite']!=8 and $_POST['tramite']!=12 and $_POST['tramite']!=13){   //// OTRO TIPO DE TRAMITE //////
			$horarios = array(	
						1 => array("horario" => "09:00", "citas" => 0 , "disponible" => "") , 
						2 => array("horario" => "09:30", "citas" => 0 , "disponible" => "") , 
						3 => array("horario" => "10:00", "citas" => 0 , "disponible" => "") , 
						4 => array("horario" => "10:30", "citas" => 0 , "disponible" => "") , 
						5 => array("horario" => "11:00", "citas" => 0 , "disponible" => "") , 
						6 => array("horario" => "11:30", "citas" => 0 , "disponible" => "") , 
						7 => array("horario" => "12:00", "citas" => 0 , "disponible" => "") , 
						8 => array("horario" => "12:30", "citas" => 0 , "disponible" => "") , 
						9 => array("horario" => "13:00", "citas" => 0 , "disponible" => "") , 
						10 => array("horario" => "13:30", "citas" => 0 , "disponible" => "") , 
						11 => array("horario" => "14:00", "citas" => 0 , "disponible" => "") ,
						12 => array("horario" => "14:30", "citas" => 0 , "disponible" => "")  ,
						13 => array("horario" => "15:00", "citas" => 0 , "disponible" => "")  
					) ;
		}

		$sqlH = "select * from horasBloqueadas where id_juzgado = '$_POST[juzgado]' and fecha='$_POST[dia]' and activo=1 ";
		$queryH = mysqli_query($link, $sqlH) ;
		$bloqueadas=array();
		while($horasH = mysqli_fetch_assoc($queryH)){
			$bloqueadas[] = substr("$horasH[hora]",0,5) ;
		}

	foreach($horarios as $valor ){
		$x+=1 ;
		$horario = $horarios[$x]["horario"] ;
	
		$sql = "select count(fecha) as horasReservadas from citas where id_juzgado = '$_POST[juzgado]'  and fecha = '$_POST[dia]' and hora='$horario' and estatus!='Cancelada'" ;
		/* citas por juzgado, fecha y hora --  $sql = "select count(fecha) as horasReservadas from citas where id_juzgado = '$_POST[juzgado]'  and fecha = '$_POST[dia]' and substring(hora,1,5) = '$horario' " ; */
		$query = mysqli_query($link, $sql) ;
		$datos = mysqli_fetch_assoc($query) ;

		$sqlJuez = "select count(fecha) as horasReservadas from citas where id_juzgado = '$_POST[juzgado]'  and fecha = '$_POST[dia]' and hora='$horario' and estatus!='Cancelada' and id_servicio=8" ;
		/* citas por juzgado, fecha y hora --  $sql = "select count(fecha) as horasReservadas from citas where id_juzgado = '$_POST[juzgado]'  and fecha = '$_POST[dia]' and substring(hora,1,5) = '$horario' " ; */
		$queryJuez = mysqli_query($link, $sqlJuez) ;
		$datosJuez = mysqli_fetch_assoc($queryJuez) ;
		
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
		//$hoyEsSabado  = (date("w",strtotime($fecha))==6) ? true : false ;
		//$hoyEsDomingo  = (date("w",strtotime($fecha))==0) ? true : false ;
		//echo $hoyEsSabado ;

		$citas = 0 ;
		//if($hoyEsSabado or $hoyEsDomingo){
		//	$citas = 50 ;
		//}
		
		if( ($horaActual > "14:30" and $_POST['dia'] == $diaSiguiente) ){
			$citas = 100 ;
		}
		if(in_array("$horario",$bloqueadas)){
			$citas = 200 ;
		}
		if($_POST['dia']==$diaActual){
			$citas = 300 ;
		}
		if($_POST['tramite']==8 and $datosJuez['horasReservadas']>0 ){
			$citas = 400 ;
		}
		if($_POST['tramite']==13 and $datosJuez['horasReservadas']>0 ){
			$citas = 400 ;
		}
		$horarios["$x"]["citas"] = $datos['horasReservadas'] + $citas  ;
		$horarios["$x"]["disponible"] =  "$citas"  ;
		$horarios["$x"]["bloqueadas"] = $bloqueadas ;
		$horarios["$x"]["sql"] = "$diaBuzon hora actual : $horaActual - dia solicitado : $_POST[dia] - dia siguiente de hoy : $diaSiguiente " ;
		$arreglo["$horario"] = "$datos[horasReservadas]" ; 
		
		//$horarios[] = "$sql" ;
		
	}
	echo json_encode($horarios) ;
}


if($_POST['accion']=="buscar beneficiario"){
    
    $hoy = date("Y-m-d") ;

	$vie_lunes = strtotime ( '+3 day' , strtotime ( $hoy ) ) ;
	$vie_lunes = date ( 'Y-m-d' , $vie_lunes );

	$sab_lunes = strtotime ( '+2 day' , strtotime ( $hoy ) ) ;
	$sab_lunes = date ( 'Y-m-d' , $sab_lunes );

	$dom_lunes = strtotime ( '+1 day' , strtotime ( $hoy ) ) ;
	$dom_lunes = date ( 'Y-m-d' , $dom_lunes );
	
	if(   $_POST['juzgado']==105   and $_POST['dia'] < '2020-08-05' ) { 
		echo "Sin servicio Rio Grande" ; exit ;
	}

	if(   $_POST['juzgado']==104   and $_POST['dia'] < '2020-08-04' ) { 
		echo "Sin servicio Monclova" ; exit ;
	}

	if(  ( $_POST['juzgado']==99  or  $_POST['juzgado']==100 ) and $_POST['dia'] < '2020-07-13' ) { 
		echo "Sin servicio Torreon" ; exit ;
	}

	if(  ( $_POST['juzgado']==97  or  $_POST['juzgado']==93 ) and $_POST['dia'] > '2020-07-10' ) { 
		echo "Sin servicio" ; exit ;
	}
	if($_POST['dia']<"2020-06-10") {
		echo "Despues" ;
	}
	else{	
			$horaActual = date("H:i");	
		    if( 
		    			

		    		(
				    	(	
				    		(date("w",strtotime($hoy))==6 /*sabdo*/ or date("w",strtotime($hoy))==0 /*domingo*/ )
				    		and 
				    		($_POST['dia'] == $sab_lunes or $_POST['dia'] == $dom_lunes)
				    	)
				    		or 

				    	(
				    		date("w",strtotime($hoy))==5 and $_POST['dia']==$vie_lunes and $horaActual > "14:30" 
				    	)		
				    )
				    and 
				     $_POST['tramite']!=12 

		    	) {
		    		echo "Lunes" ;
		    }
		    else{
		    	   $sql = "select count(fecha) as citas from citas where fecha = '$_POST[dia]' and correo = '$_POST[correo]' and estatus!='Cancelada' and id_juzgado='$_POST[juzgado]'" ;
		    		$query = mysqli_query($link , $sql) ;
		    		$registros = mysqli_fetch_assoc($query) ;
		    		echo $registros['citas'] ;
		     }
    }
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
								estatus,
								expediente1,
								expediente2,
								expediente3,
								expediente4,
								expediente5

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
								'Agendada',
								'$_POST[expediente1]',
								'$_POST[expediente2]',
								'$_POST[expediente3]',
								'$_POST[expediente4]',
								'$_POST[expediente5]'
							)" ;
	if( $query = mysqli_query($link, $sql) ){
		$ultimoID = mysqli_insert_id($link) ;
		echo $ultimoID  ;
	}
	else{
		echo "Error" ;
	}	    
}


if($_POST['accion']=="enviar correo"){
		include_once("funciones.php") ;
		
		//include_once('resources/correo/class.phpmailer.php');
		//include_once('resources/correo/class.smtp.php');

		$sql = "select c.id as idCita, c.*, u.* ,j.distrito,j.juzgado, cat.servicio from citas c
					left join usuario u on u.email = c.correo
					left join juzgados j  on j.id = c.id_juzgado
					left join cat_servicios cat on  cat.id = c.id_servicio  
				where c.id = $_POST[ultimoID] limit 1" ;
		//echo $sql ;
		$query = mysqli_query($link, $sql) ;
		$datos = mysqli_fetch_assoc($query) ;
		$para = "$_POST[correo]" ;
	 //Este bloque es importante
	    $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("citas@pjec.gob.mx", "Poder Judicial del Estado de Coahuila");
        $email->setSubject("Confirmación de Cita (Sistema de Citas PJECZ)");
        $email->addTo($para);
        $email->addContent(
        	"text/html", "
	    	<body>

	    		<table>
							<tr>
								<td style='background-color:#495769; border-bottom:4px solid #555; color:#fff; text-align:center' colspan=2>
									<h3>Poder Judicial del Estado de Coahuila</h3>
								</td>
							</tr>
						
							<tr>	
								<td colspan=2><br>
									<h3>Su cita ha sido programada correctamente</h3>
									Le agradecemos utilizar nuestro Sistema de Citas en Línea, a continuación le proporcionamos los detalles de confirmación. Le sugerimos acudir a nuestra sede con 10 minutos de anticipación para brindarle un mejor servicio. <br><br> 
									A su ingreso deberá presentar una identificación oficial y este mensaje de correo, ya sea impreso o en medio electrónico . Si acude 10 minutos después de la hora señalada en esta confirmación no será posible garantizarle el servicio.<br><br>
									<h3>Detalles de la cita</h3>
								</td>
							</td>
							<tr>
								<td>
									<label>Folio</label>
								</td>
								<td>
									<label>#". (substr($_POST['fechaSeleccionada'],5,2) ."_". substr($_POST['fechaSeleccionada'],8,2) ."_". str_replace(":","_",$_POST['horaSeleccionada']) . "_" . $datos['idCita']) . "</label>
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
								<td colspan=2><br>
									<h3>Datos del usuario</h3>
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
									<label>CURP</label>
								</td>
								<td>
									<label>$datos[curp]</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Celular</label>
								</td>
								<td>
									<label>$datos[celular]</label>
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
	$sql = "select count(fecha) as registros from horasBloqueadas where id_juzgado='$_SESSION[id_juzgado]' and fecha = '$_POST[fecha]' and substring(hora,1,5)='$_POST[hora]' ";
	$query = mysqli_query($link ,$sql) ;
	$datos = mysqli_fetch_assoc($query) ;
	if($datos['registros']>0){
		$sql = "update horasBloqueadas set  activo=1, detalles='$_POST[detalles]' where id_juzgado='$_SESSION[id_juzgado]' and fecha = '$_POST[fecha]' and substring(hora,1,5)='$_POST[hora]' limit 1" ;	
	}
	else{
		$sql = "insert into horasBloqueadas (id_juzgado ,fecha, hora, activo,detalles) values ('$_SESSION[id_juzgado]','$_POST[fecha]', '$_POST[hora]',1,'$_POST[detalles]')" ;	
	}
	
	if($query = mysqli_query($link, $sql)){
		echo "YES" ;
	}
	else{
		echo "ERROR" ;
	}
}

if(isset($_POST['accion']) and $_POST['accion']=="desbloquear hora"){
	
	$sql = "update horasBloqueadas set activo=0 where id_juzgado = '$_SESSION[id_juzgado]' and fecha='$_POST[fecha]' and hora='$_POST[hora]' limit 1" ;
	if($query = mysqli_query($link, $sql)){
		echo "YES" ;
	}
	else{
		echo "ERROR" ;
	}
}

if(isset($_POST['accion']) and $_POST['accion']=="cancelar cita"){
	
	$sql = "update citas set estatus ='Cancelada' where id = '$_POST[id]' limit 1 " ;
	if($query = mysqli_query($link, $sql)){
		echo "YES" ;
	}
	else{
		echo "ERROR" ;
	}
}

 ?>