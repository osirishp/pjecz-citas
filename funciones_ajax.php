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
		$sql = "select id,juzgado from juzgados where distrito = '$distrito' order by juzgado ";
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


function consultarCitas($dia){
	include_once("funciones.php");
	$link=conectarse();	
	$respuesta = new xajaxResponse();
	$diaOriginal = $dia ;
	$dia = explode("/",$dia);
	$dia = $dia[2]."/".$dia[1]."/".$dia[0] ;
	
	if(isset($_SESSION['id_juzgado']) and $_SESSION['id_juzgado']!=''){$where = "and c.id_juzgado = '$_SESSION[id_juzgado]' " ;}
	$sqlA = "select c.id_juzgado from citas c where c.fecha='$dia' $where group by c.id_juzgado  order by c.id_juzgado " ;
	
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
						c.estatus,
						u.telefono
						from citas c 
		
		inner join usuario u on c.id_beneficiario = u.id
		inner join cat_servicios s on c.id_servicio = s.id
		
		
		where c.fecha='$dia' and c.id_juzgado='$datosA[id_juzgado]' and (c.estatus!='Eliminada' or c.estatus IS NULL) 
		order by c.id_juzgado, c.hora " ;
		
		$query = mysqli_query($link, $sql);
		$verquery.= "<div>".utf8_encode($sql)."</div>" ;
	
		$html.= "<div class='table-responsive-sm'>
		
			<table class='table table-responsive table-striped' >
				<thead>
					
					<tr>
						<th>Acci&oacute;n</th>
						<th>Estatus</th>						
						<th>Fecha</th>
						<th>Hora</th>
						<th>Nombre completo</th>
						<th>Servicio</th>
						<th>Tel&eacute;fono</th>
					</tr>
				</thead>
				<tbody>";
	
					while($datos = mysqli_fetch_assoc($query)){
						$concluir="";
						$cancelar="";
						$html.="
							<tr>
								<td>";
								if(empty($datos['estatus'])){
									if($_SESSION['administrador']==1 and empty($datos['estatus'])){
										$concluir="<a href=javascript:estatus('$datos[id]','$diaOriginal','Confirmada');><img src='resources/imgs/btn_concluir.png' width='20'></a>";
										$cancelar=" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href=javascript:estatus('$datos[id]','$diaOriginal','Cancelada');><img src='resources/imgs/btn_cancelar.png' width='23'></a>";
									}								
									$html.="
											$concluir
											$cancelar
											";
								}
								if($datos['estatus']=="Cancelada"){$fontI = "<font color='#ff0000'>"; $fontF ="</font>";}
								if($datos['estatus']=="Confirmada"){$fontI = "<font color='#00cc00'>"; $fontF ="</font>";}
								if($datos['estatus']=="Eliminada"){$fontI = "<font color='#ffe500'>"; $fontF ="</font>";}
							$html.="</td>
								<td>$fontI $datos[estatus] $fontF</td>	
								<td>$datos[fecha]</td>
								<td>$datos[hora]</td>
								<td>$datos[nombrecompleto]</td>
								<td>$datos[servicio]</td>
								<td>$datos[num_tel]</td>
							</tr>";
							
					}
		$html."
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