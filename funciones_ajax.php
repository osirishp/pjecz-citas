<?php
function contador(){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$_SESSION['contador']+=1 ;
	
	$arreglo = print_r($_SESSION,true) ;
	
	$script = "document.pacientes.contador.value = '$_SESSION[contador]' ;	
				document.pacientes.codigo.value = '$arreglo' ;	"	 ;
	$respuesta->script($script) ;
	$respuesta->assign("divX","innerHTML",$arreglo);

   	return $respuesta;
}


function juzgados($distrito){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
			$script="
				var select = document.getElementById('juzgado');
				while (select.length > 0) {
					select.remove(0);
				}	
				";

	if($distrito!="") {
		$sql = "select id,juzgado from juzgados where distrito = '$distrito' and activo=1 order by juzgado ";
		$query = mysqli_query($link,$sql);
		
		$script.="				
				var option =  document.createElement('option') ; 
				option.text = '' ;
				option.value = '' ;
				select.add(option) ;			";

			while($datos = mysqli_fetch_assoc($query)){
				$script.=" 	
							var option =  document.createElement('option') ; 
							option.text = '$datos[juzgado]' ;
							option.value = '$datos[id]' ;
							select.add(option) ; ";	
			}
	}
			
		$respuesta->script($script) ;
	  	//$respuesta->assign("divJuzgados","innerHTML",$sql);
	

   return $respuesta;
}

function agregarCita($id_cliente,$id_servicio,$fecha,$asistente,$hora,$sucursal){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select * from asistentes where id='$asistente' ";
	$query = mysqli_query($link,$sql) ;
	$datos = mysqli_fetch_assoc($query) ;
	
	$sql = "insert into citas (id_cliente,fecha,id_servicio,hora,asistente,id_asistente,sucursal,estatus,alta,alta_fecha) values ($id_cliente,'$fecha','$id_servicio','$hora','$datos[asistente]','$asistente','$sucursal','','$_SESSION[usuario]',now()) ";
	$query = mysqli_query($link,$sql);

	
	$script="
			document.getElementById('fecha_cita').value='';
			document.getElementById('divAsistente').style.display='none';
			document.getElementById('divHora').style.display='none';
			document.getElementById('divSucursal').style.display='none';
			
			var select = document.getElementById('servicio');
			while (select.length > 1) {
				select.remove(1);
			}		";
	$sql = "select s.*, c.id_servicio,c.citas from servicios s

				inner join (select id_servicio,count(id_servicio) as citas from citas where estatus!='Cancelada' group by id_servicio) c
				
				on s.id = c.id_servicio
														
				where s.id_cliente = '$id_cliente' and s.estatus='' 
														
				order by s.estatus,s.fecha desc";
				
	$query = mysqli_query($link,$sql);		
	while($datos = mysqli_fetch_assoc($query)){
			$script.=" 	
						var option =  document.createElement('option') ; 
						option.text = '".utf8_encode($datos['servicio'])." (citas: $datos[citas]/$datos[sesiones])' ;
						option.value = '$datos[id]' ;
						select.add(option) ; 
						
					";	
		}
	
	$script.="
			recargarCitas();
		";
		
	
//	$script = "document.registro_obras.codigo.value = '$datosCol[Codigo_Postal]' ";
	$respuesta->script($script) ;
//   $respuesta->assign("divCitas","innerHTML",$sql);
   return $respuesta;
}


function valoresServicio($servicio){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select costo,sesiones from  cat_servicios where servicio='".utf8_decode($servicio)."' limit 1";
	$query = mysqli_query($link,$sql);
	$datos = mysqli_fetch_assoc($query);
	
		
	$script="
			document.pacientes.costo.value = '$datos[costo]' ;
			document.pacientes.precioUnitario.value = '$datos[costo]' ;
			document.pacientes.sesiones.value = '$datos[sesiones]' ;
		";
		
	
//	$script = "document.registro_obras.codigo.value = '$datosCol[Codigo_Postal]' ";
	$respuesta->script($script) ;
//   $respuesta->assign("divCitas","innerHTML",$sql);
   return $respuesta;	
}

function agregarServicio($id,$servicio,$costo,$sesiones){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$servicio  = utf8_decode($servicio) ;
	$sqlSer = "insert into servicios (id_cliente,servicio,costo,resto,fecha,observaciones,sesiones,estatus) values ($id,'$servicio','$costo','$costo',curdate(),'$observaciones','$sesiones','') ";
	$query = mysqli_query($link,$sqlSer);
	
	
	$script="
			var select = document.getElementById('servicio');
			var select2 = document.getElementById('servicioPago');
			while (select.length > 1) {
				select.remove(1);
			}	

			while (select2.length > 1) {
				select2.remove(1);
			}	
			
			
			";
		$sql = "select * from servicios where id_cliente = '$id' and estatus='' order by estatus,fecha desc" ;
		$query = mysqli_query($link,$sql) ;
		while($datos = mysqli_fetch_assoc($query)){
			$script.=" 	
						var option =  document.createElement('option') ; 
						option.text = '".utf8_encode($datos['servicio'])."' ;
						option.value = '$datos[id]' ;
						select.add(option) ; 
						
						var option2 =  document.createElement('option') ; 
						option2.text = '".utf8_encode($datos['servicio'])."' ;
						option2.value = '$datos[id]' ;
						select2.add(option2) ; 
						
					";	
		}
	
	$script.="
			recargarServicios();
		";
		
		
	$respuesta->script($script) ;
//   $respuesta->assign("divCitas","innerHTML",$sql);

   return $respuesta;
}


function agregarPago($id,$pago,$forma,$remision,$idCliente){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sqlU = "update servicios set resto  = (resto - $pago) , estatus = if((resto-$pago)<=1,'PAGADO','') where id='$id' limit 1 ";
	$query = mysqli_query($link, $sqlU) ;
	
	$sqlI = "insert into pagos (id_servicio,importe,forma_de_pago,fecha,resto,notaRemision) values ($id,'$pago','$forma',curdate(),(select resto from servicios where id = '$id'),'$remision' ) ";
	$query = mysqli_query($link,$sqlI);
	

	
	$script="
			var select = document.getElementById('servicio');
			var select2 = document.getElementById('servicioPago');
			while (select.length > 1) {
				select.remove(1);
			}	

			while (select2.length > 1) {
				select2.remove(1);
			}	
			
			
			";
		$sql = "select * from servicios where id_cliente = '$idCliente' and estatus=' ' order by estatus,fecha desc" ;
		$query = mysqli_query($link,$sql) ;
		while($datos = mysqli_fetch_assoc($query)){
			$script.=" 	
						var option =  document.createElement('option') ; 
						option.text = '".utf8_encode($datos['servicio'])."' ;
						option.value = '$datos[id]' ;
						select.add(option) ; 
						
						var option2 =  document.createElement('option') ; 
						option2.text = '".utf8_encode($datos['servicio'])."' ;
						option2.value = '$datos[id]' ;
						select2.add(option2) ; 
						
					";	
		}
	
	$script.="
			recargarPagos();
		";
		
		
	$respuesta->script($script) ;
//   	$respuesta->assign("divCitas","innerHTML",$sql);

   return $respuesta;
}


function concluirServicio($id_servicio,$id_paciente){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update servicios set estatus='Concluido' where id='$id_servicio'" ;
	$query = mysqli_query($link,$sql) ;
	
	$script=$script="
			var select = document.getElementById('servicio');
			var select2 = document.getElementById('servicioPago');
			while (select.length > 1) {
				select.remove(1);
			}	

			while (select2.length > 1) {
				select2.remove(1);
			}	
			
			
			";
		$sql = "select * from servicios where id_cliente = '$id_paciente' and estatus='' order by estatus,fecha desc" ;
		$query = mysqli_query($link,$sql) ;
		while($datos = mysqli_fetch_assoc($query)){
			$script.=" 	
						var option =  document.createElement('option') ; 
						option.text = '".utf8_encode($datos['servicio'])."' ;
						option.value = '$datos[id]' ;
						select.add(option) ; 
						
						var option2 =  document.createElement('option') ; 
						option2.text = '".utf8_encode($datos['servicio'])."' ;
						option2.value = '$datos[id]' ;
						select2.add(option2) ; 
						
					";	
		}
	
	$script.="recargarServicios()" ;
	
	$respuesta->script($script) ;
	return $respuesta ;		
}


function cancelarServicio($id_servicio,$id_paciente){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update servicios set estatus='Cancelado' where id='$id_servicio'" ;
	$query = mysqli_query($link,$sql) ;
	
	$script=$script="
			var select = document.getElementById('servicio');
			var select2 = document.getElementById('servicioPago');
			while (select.length > 1) {
				select.remove(1);
			}	

			while (select2.length > 1) {
				select2.remove(1);
			}	
			
			
			";
		$sql = "select * from servicios where id_cliente = '$id_paciente' and estatus='' order by estatus,fecha desc" ;
		$query = mysqli_query($link,$sql) ;
		while($datos = mysqli_fetch_assoc($query)){
			$script.=" 	
						var option =  document.createElement('option') ; 
						option.text = '".utf8_encode($datos['servicio'])."' ;
						option.value = '$datos[id]' ;
						select.add(option) ; 
						
						var option2 =  document.createElement('option') ; 
						option2.text = '".utf8_encode($datos['servicio'])."' ;
						option2.value = '$datos[id]' ;
						select2.add(option2) ; 
						
					";	
		}
	
	$script.="recargarServicios()" ;
	
	$respuesta->script($script) ;
	return $respuesta ;		
}

function cancelarCita($id_cita){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update citas set estatus='Cancelada' where id='$id_cita'" ;
	$query = mysqli_query($link,$sql) ;
	
	$script.="recargarCitas()" ;
	
	$respuesta->script($script) ;
	return $respuesta ;		
}

function confirmarCita($id_cita){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update citas set estatus='Confirmada' where id='$id_cita'" ;
	$query = mysqli_query($link,$sql) ;
	
//	$script="alert('$sql')" ;
	$respuesta->script($script) ;
	return $respuesta ;		
}

function consultarCitas_x_servicio($diaInicio,$diaFin){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select count(nombre) as registros from usuario where date(fecha_altas) BETWEEN '$diaInicio' and '$diaFin' " ;
	$query = mysqli_query( $link, $sql) ;
	$datos = mysqli_fetch_assoc($query) ;
	$registros = $datos['registros'] ;

	$sql = "select count(fecha) as citas,cat.servicio from citas c 
				left join cat_servicios cat on c.id_servicio = cat.id 
			where date(c.alta_fecha) BETWEEN '$diaInicio' and '$diaFin' 
			group by c.id_servicio 	" ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br>
			<h3>Usuarios registrados : $registros</h3>
			<br><br>
			<div class='table-responsive-sm'>
			<h2><strong>CITAS DADAS DE ALTA</strong></h2><br>
			<h3>Citas por Trámite</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Trámite</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Citas</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
					$total += $datos[citas] ;
						$html.="
							<tr >
								<td style='color:#555'>$datos[servicio]</td>
								<td style='color:#555'>$datos[citas]</td>
							</tr>";
	}
		$html.="
							<tr >
								<td style='background-color:#687C96; color:#fff'>Total de citas</td>
								<td style='background-color:#687C96; color:#fff'>$total</td>
							</tr>
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divCitas","innerHTML",$html) ;
	return $respuesta ;
	
}

function consultarCitas_x_juzgado($diaInicio,$diaFin){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select count(fecha) as citas, j.distrito, j.juzgado from citas c 
				left join juzgados j on c.id_juzgado = j.id 
			where date(c.alta_fecha) BETWEEN '$diaInicio' and '$diaFin' 
			group by c.id_juzgado  
			ORDER BY `citas` desc " ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br><br>
			<div class='table-responsive-sm'>
			<h3>Citas por Juzgado</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Distrito</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Juzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Citas</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
				$total += $datos[citas] ;
						$html.="
							<tr >
								<td style='color:#555'>Distrito Judicial $datos[distrito]</td>
								<td style='color:#555'>$datos[juzgado]</td>
								<td style='color:#555'>$datos[citas]</td>
							</tr>";
	}
		$html.="			<tr >
								<td style='background-color:#687C96; color:#fff' colspan=2>Total de citas</td>
								<td style='background-color:#687C96; color:#fff'>$total</td>
							</tr>
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divCitas_x_juzgado","innerHTML",$html) ;
	return $respuesta ;
	
}

//////////////////

function consultarCitasProgras($diaInicio,$diaFin){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select count(fecha) as citas, j.distrito, j.juzgado from citas c 
				left join juzgados j on c.id_juzgado = j.id 
			where date(c.fecha) BETWEEN '$diaInicio' and '$diaFin' 
			group by c.id_juzgado  
			ORDER BY `citas` desc " ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br><br>
			<div class='table-responsive-sm'>
			<h2><strong>CITAS PROGRAMADAS</strong></h2><br>
			<h3>Citas por Juzgado del $diaInicio al $diaFin</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Distrito</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Juzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Citas</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
				$total += $datos[citas] ;
						$html.="
							<tr >
								<td style='color:#555'>Distrito Judicial $datos[distrito]</td>
								<td style='color:#555'>$datos[juzgado]</td>
								<td style='color:#555'>$datos[citas]</td>
							</tr>";
	}
		$html.="			<tr >
								<td style='background-color:#687C96; color:#fff' colspan=2>Total de citas</td>
								<td style='background-color:#687C96; color:#fff'>$total</td>
							</tr>
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divCitasProgras","innerHTML",$html) ;
	return $respuesta ;
	
}

function consultarHorasBloq($diaInicio,$diaFin){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select count(hora) as HorasBloq, j.distrito, j.juzgado, h.id_juzgado 
			from horasBloqueadas h left join juzgados j on h.id_juzgado = j.id 
            where h.activo = 1
            and date(h.fecha) BETWEEN '$diaInicio' and '$diaFin'
			group by h.id_juzgado
            order by HorasBloq desc;" ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br><br>
			<div class='table-responsive-sm'>
			<h2><strong>SESIONES BLOQUEADAS</strong></h2><br>
			<h3>Sesiones bloqueadas por Juzgado del $diaInicio al $diaFin</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Distrito</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Juzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Sesiones bloqueadas</th>
						<th hidden='active' style='background-color:#ccc; font-size:1em; color:#333;'>IdJuzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Consultar</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
				$total += $datos[HorasBloq] ;
						$html.="
							<tr >
								<td style='color:#555'>Distrito Judicial $datos[distrito]</td>
								<td style='color:#555'>$datos[juzgado]</td>
								<td align='center' style='color:#555'>$datos[HorasBloq]</td>
								<td align='center' hidden='active' style='color:#555'>$datos[id_juzgado]</td>
								<td align='center' style='color:#555'> <a href='javascript:void(0);' onclick='VerJuzgadoId($datos[id_juzgado])' id='verJuzgado'><span class='glyphicon glyphicon-eye-open'></span></a></td>
							</tr>";
	}
		$html.="			<tr >
								<td style='background-color:#687C96; color:#fff' colspan=2>Total de sesiones bloqueadas</td>
								<td align='center' style='background-color:#687C96; color:#fff'>$total</td>
							</tr>
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divHorasBloq","innerHTML",$html) ;
	return $respuesta ;
	
}

function consultarHorasBloq_x_juzgado($diaInicio,$diaFin,$idJuzgado){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select j.distrito, j.juzgado,h.fecha,h.hora, h.detalles 
			from horasBloqueadas h left join juzgados j on h.id_juzgado = j.id 
            where h.activo = 1 and date(h.fecha) BETWEEN '$diaInicio' and '$diaFin'
            and h.id_juzgado = '$idJuzgado'
            order by h.fecha desc, hora asc;" ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br>
			<div class='table-responsive-sm'>
			<h2><strong>SESIONES BLOQUEADAS</strong></h2><br>
			<h3>Fecha y hora bloqueadas por el Juzgado, del $diaInicio al $diaFin</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Distrito</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Juzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Fecha</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Hora</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Detalles</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
				$total += $datos[HorasBloq] ;
						$html.="
							<tr >
								<td style='color:#555'>Distrito Judicial $datos[distrito]</td>
								<td style='color:#555'>$datos[juzgado]</td>
								<td align='center' style='color:#555'>$datos[fecha]</td>
								<td align='center' style='color:#555'>$datos[hora]</td>
								<td align='center' style='color:#555'>$datos[detalles]</td>
							</tr>";
	}
		$html.="
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divHorasBloqXjuzgado","innerHTML",$html) ;
	return $respuesta ;
	
}



function consultarCitasPrograsAge($diaInicio,$diaFin){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select count(fecha) as citas, j.distrito, j.juzgado from citas c 
				left join juzgados j on c.id_juzgado = j.id 
			where date(c.fecha) BETWEEN '$diaInicio' and '$diaFin' 
			and estatus = 'Agendada'
			group by c.id_juzgado  
			ORDER BY `citas` desc " ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br><br><br>
			<div class='table-responsive-sm'>
			<h3>Citas Agendadas por Juzgado del $diaInicio al $diaFin</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Distrito</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Juzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Citas</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
				$total += $datos[citas] ;
						$html.="
							<tr >
								<td style='color:#555'>Distrito Judicial $datos[distrito]</td>
								<td style='color:#555'>$datos[juzgado]</td>
								<td style='color:#555'>$datos[citas]</td>
							</tr>";
	}
		$html.="			<tr >
								<td style='background-color:#687C96; color:#fff' colspan=2>Total de citas</td>
								<td style='background-color:#687C96; color:#fff'>$total</td>
							</tr>
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divCitasPrograsAge","innerHTML",$html) ;
	return $respuesta ;
	
}

function consultarCitasPrograsCan($diaInicio,$diaFin){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "select count(fecha) as citas, j.distrito, j.juzgado from citas c 
				left join juzgados j on c.id_juzgado = j.id 
			where date(c.fecha) BETWEEN '$diaInicio' and '$diaFin' 
			and estatus = 'Cancelada'
			group by c.id_juzgado  
			ORDER BY `citas` desc " ;
	$queryA = mysqli_query($link, $sql) ;

	$html=" <br><br><br><br>
			<div class='table-responsive-sm'>
			<h3>Citas Canceladas por Juzgado del $diaInicio al $diaFin</h3>
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Distrito</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Juzgado</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Citas</th>
					</tr>
				</thead>
				<tbody>";
	
	while($datos = mysqli_fetch_assoc($queryA)){
				$total += $datos[citas] ;
						$html.="
							<tr >
								<td style='color:#555'>Distrito Judicial $datos[distrito]</td>
								<td style='color:#555'>$datos[juzgado]</td>
								<td style='color:#555'>$datos[citas]</td>
							</tr>";
	}
		$html.="			<tr >
								<td style='background-color:#687C96; color:#fff' colspan=2>Total de citas</td>
								<td style='background-color:#687C96; color:#fff'>$total</td>
							</tr>
				</tbody>
			</table>
		</div>";  
		
	$respuesta->assign("divCitasPrograsCan","innerHTML",$html) ;
	return $respuesta ;
	
}

/////////////////
function consultarCitas($dia){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	$diaOriginal = $dia ;
	$dia = explode("/",$dia);
	$dia = $dia[2]."/".$dia[1]."/".$dia[0] ;
	
	if(isset($_SESSION['idRol']) and $_SESSION['idRol']==2){
		$sqlA = "select c.id_juzgado from citas c 
					where c.fecha='$dia' and c.id_juzgado = '$_SESSION[id_juzgado]'  
					group by c.id_juzgado  
					order by c.id_juzgado " ;	
	}
	if(isset($_SESSION['idRol']) and $_SESSION['idRol']==3){
		$sqlA = "select c.id_juzgado,j.juzgado from citas c 
					left join juzgados j on c.id_juzgado = j.id  
					where c.fecha='$dia' and c.id_juzgado in (select id from juzgados where id_distrito = '$_SESSION[id_distrito]')  
					group by c.id_juzgado  
					order by c.id_juzgado " ;	
	}

	
	//$html.="$sqlA" ;
	$queryA = mysqli_query($link, $sqlA) ;
	
	while($datosA = mysqli_fetch_assoc($queryA)){

		$sql = "SELECT 	
						concat(u.nombre,' ', u.apPaterno, ' ', u.apMaterno) as nombrecompleto ,
						s.servicio, 
						c.id_servicio,
						c.id_beneficiario,
						c.hora,
						c.fecha,
						c.id_juzgado,
						c.id,
						c.detalles,
						c.estatus,
						c.asistio,
						u.celular,
						c.expediente1,
						c.expediente2,
						c.expediente3,
						c.expediente4,
						c.expediente5
						from citas c 
		
		inner join usuario u on c.id_beneficiario = u.id
		inner join cat_servicios s on c.id_servicio = s.id
		
		
		where c.fecha='$dia' and c.id_juzgado='$datosA[id_juzgado]' and (c.estatus!='Eliminada' or c.estatus IS NULL) 
		order by c.id_juzgado, c.hora " ;
		
		$query = mysqli_query($link, $sql);
		$verquery.= "<div>".utf8_encode($sql)."</div>" ;
		$token = md5('=Cita5@EnLinea=');
		$html.= "
		<br>
		<div class='row'>
		
			<div class='col-md-12'>
				 <a href='actividadesXLS.php?dia=".str_replace("/", "-", $dia)."&ij=$datosA[id_juzgado]&token=$token'><img src='resources/imgs/img_downloadXLS.png' width='30' ><br> Descargar en excel </a><h2 > $datosA[juzgado] </h2>
				<br>
			</div>
		</div>	
		<div class='table-responsive-sm'>
			
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Confirmar asistencia</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Hora</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Nombre completo</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Servicio</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Detalles</td>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Tel&eacute;fono</th>
						<th style='background-color:#ccc; font-size:1em; color:#333;'>Asistio</th>
					</tr>
				</thead>
				<tbody>";
	
					while($datos = mysqli_fetch_assoc($query)){
						$concluir="";
						$cancelar="";
						if( ($datos['estatus']=="Agendada" and empty($datos['asistio']) and $datos['fecha']<date('Y-m-d') and $_SESSION['idRol']==2)
									or 
									($datos['estatus']=="Agendada" and empty($datos['asistio']) and $datos['fecha']==date('Y-m-d') and substr($datos['hora'],0,5) < date("H:i")  and $_SESSION['idRol']==2)
								){
									$asistio = "<a href='javascript:void(0);' onclick='pasarCitaID($datos[id])' id='verificar_cita'><img src='resources/imgs/img_asistencia.png' width='30'></a>" ;
								}
						$html.="
							<tr >
								<td style='color:#555'>$asistio</td>
								<td style='color:#555'>$datos[hora]</td>
								<td style='color:#555'>$datos[nombrecompleto]</td>
								<td style='color:#555'>$datos[servicio]<br>$datos[expediente1]  $datos[expediente2]  $datos[expediente3]  $datos[expediente4]  $datos[expediente5] </td>
								<td style='color:#555'>$datos[detalles]</td>
								<td style='color:#555'>$datos[celular]</td>
								<td style='color:#555'>$datos[asistio]</td>
							</tr>";
							
					}
		$html.="
				</tbody>
			</table>
		</div>";  
	}
	
	$respuesta->assign("divCitas","innerHTML",$html) ;
	return $respuesta ;
	
}

function estatusCitaAct($id_cita,$dia,$estatus){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update citas set estatus='$estatus' where id='$id_cita'" ;
	$query = mysqli_query($link,$sql) ;

	$script="xajax_consultarCitas('$dia');";
	$respuesta->script($script) ;
	return $respuesta ;		
}



function buscarPaciente($id){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$instruccion = "select * from clientes where id=$id limit 1" ;
	$query = mysqli_query($link,$instruccion) ;
	$datos = mysqli_fetch_assoc($query);
	
	$script = "document.pacientes.nombre.value ='$datos[nombre]';
				document.pacientes.paterno.value ='$datos[paterno]';
				document.pacientes.materno.value ='$datos[materno]';
				document.pacientes.celular.value ='$datos[num_tel]';
				
				document.getElementById('myDiv').style.display='none'; ";
	
	$respuesta->script($script);
	return $respuesta;
	}


function buscarHorarios($asistente,$fecha){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	$script = "alert('$fecha')" ;
	$fecha = explode("/",$fecha) ;
	$fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0] ;
	
//	$asistente = utf8_decode($asistente) ;
	$instruccion = "select hora from citas where id_juzgado='$asistente' and fecha='$fecha' and (estatus='' or estatus='Confirmada' or isnull(estatus))" ;
	$query = mysqli_query($link,$instruccion) ;
	$registros = mysqli_num_rows($query);
	$horarios=array() ;

	if($registros>0){
		while($datos = mysqli_fetch_assoc($query) ){
			$horarios[]="$datos[hora]" ;
		}	
		
	}
	$valor = implode("x",$horarios) ;
	
	$display = ($asistente=="")?" none ":" block " ;
		
	$script="
			document.getElementById('divHora').style.display='$display'; 
			
			var x = document.getElementById('hora') ;
			while(x.options.length){
				x.remove(0);
			}
			var option = document.createElement('option') ; 
			option.text = '' ; 
			x.add(option);			
			";
	for($x=9 ; $x<=16 ; $x++){
		
		for($m = 0 ; $m<=30 ; $m++ ){
			
			$minutos = 	substr("00"."$m",-2) ;
			$horaInterna = substr("00"."$x",-2). ":" . $minutos. ":00" ; 
			
			$meridiano = ($x<=12)?" am": " pm";

			$horaMostrar = ($x<=12)?($x.":$minutos"). $meridiano:(($x-12).":$minutos") . $meridiano;  
			
			if( in_array($horaInterna , $horarios ) ){ $estatus='true' ; $horaMostrar = $horaMostrar." Reservado " ;}else{$estatus='false';}
			
			
			$script.="
						var option = document.createElement('option') ; 
						option.text = '$horaMostrar' ; 
						option.value = '$horaInterna'; 
						x.add(option); 
						option.disabled = $estatus; ";
			if($x==20  and $m==0){  $m=100 ;}						
			$m+=29 ;

		}
		
	}	
		

	$respuesta->script($script) ;
	//$respuesta->assign("divHora","innerHTML","$instruccion") ;
	return $respuesta ;
}


function reagendarCita($id,$fecha,$asistente,$hora){
	$fecha = explode("/",$fecha);
	$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0] ;
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update citas set hora='$hora', fecha='$fecha', id_juzgado='$asistente', estatus='' where id='$id' " ;
	$query = mysqli_query($link,$sql) ;
	
	$script = "
				$('#ModalAgendarCita').modal('hide');
				document.agenda.submit();
			";
	$respuesta->script($script);
	return $respuesta ;
}


function verificarCita($id,$valor){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	
	$sql = "update citas set asistio='$valor' where id='$id' " ;
	$query = mysqli_query($link,$sql) ;
	
	$script = "
				$('#ModalVerificarCita').modal('hide');
				document.agenda.submit();
			";
	$respuesta->script($script);
	//$respuesta->script("alert('ejecutada la funcion ajax $valor')") ;
	//$respuesta->assign("divRespuestax","innerHTML","ejecutando ajax") ;
	return $respuesta ;
}	
?>