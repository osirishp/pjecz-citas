<?php
include_once("funciones.php");
$link = conectarse(); 
include_once("funciones_ajax.php") ;
		//inclumos la clase ajax
		require('resources/xajax/xajax_core/xajax.inc.php');
		//instanciamos el objeto de la clase xajax
		$xajax = new xajax();
		/* $xajax->decodeUTF8InputOn(); */
		//asociamos la funcin creada anteriormente al objeto xajax
		$xajax->register(XAJAX_FUNCTION,'confirmarCita');
		$xajax->register(XAJAX_FUNCTION,'cancelarCita');
		$xajax->register(XAJAX_FUNCTION,'valoresServicio');
		$xajax->register(XAJAX_FUNCTION,'buscarPaciente');	
		$xajax->register(XAJAX_FUNCTION,'buscarHorarios');				
		//    $xajax->registerFunction("generos");
		$xajax->configure('javascript URI','resources/xajax/' );
		//El objeto xajax tiene que procesar cualquier peticin
		$xajax->processRequest();
		//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario
		// $xajax->printJavascript("resources/xajax/");
		$xajax->printJavaScript();		
		
?>

	<style type="text/css">
		input{ text-transform:uppercase; }
		select{ min-height:35px; }
	</style>
    
    <script language="javascript">
		function eliminarCita(id){      
		  swal({
			  title: "Esta seguro de eliminar la cita?",
			  text: "una vez eliminada la cita no se podra restaurar",
			  icon: "warning",
			  buttons: [
				'No',
				'Si, eliminar cita'
			  ],
			  dangerMode: true,
			}).then(function(isConfirm) {
			  if (isConfirm) {
				swal({
				  title: 'Cita eliminada',
				  text: 'La cita fue eliminada de la base de datos',
				  icon: 'success'
				}).then(function() {
						$.post(
							"guardarEvento.php" ,
							{
								id_cita:id,
								evento:"Eliminar"   
							},
							function(resultado){
								if(resultado=="YES" ){
									location.reload();
								}
							}
						);
				});
			  } 
			});
		}
		
		function cancelarCita(id){      
		  swal({
			  title: "Esta seguro de cancelar la cita?",
			  text: "una vez cancelada la cita no se podra restaurar",
			  icon: "warning",
			  buttons: [
				'No',
				'Si, cancelar cita'
			  ],
			  dangerMode: true,
			}).then(function(isConfirm) {
			  if (isConfirm) {
				swal({
				  title: 'Cita cancelada',
				  text: 'La cita fue cancelada satisfactoriamente',
				  icon: 'success'
				}).then(function() {
						$.post(
							"guardarEvento.php" ,
							{
								id_cita:id,
								evento:"Cancelar"   
							},
							function(resultado){
								if(resultado=="YES" ){
									location.reload();
								}
							}
						);
				});
			  } 
			});
		}
		
		function confirmarCita(id){      
		  swal({
			  title: "La cita quedara confirmada",
			  text: "una vez cconfirmada la cita no tendra cambios",
			  icon: "info",
			  buttons: [
				'No',
				'Si, confirmar cita'
			  ],
			  dangerMode: false,
			}).then(function(isConfirm) {
			  if (isConfirm) {
				swal({
				  title: 'Cita confirmada',
				  text: 'La cita fue confirmada satisfactoriamente',
				  icon: 'success'
				}).then(function() {
						$.post(
							"guardarEvento.php" ,
							{
								id_cita:id,
								evento:"Confirmar"  
							},
							function(resultado){
								if(resultado=="YES" ){
									location.reload();
								}
							}
						);
							
				  	
				});
			  } 
			});
		}
		function bloquearHora(){
			var hora  = document.getElementById('horaSeleccionada').value ;
			var fecha  = document.getElementById('fechaSeleccionada').value ;
			var detalles  = document.getElementById('detalles').value ;      
		  				$.post(
							"funcionesJS.php" ,
							{
								accion : "bloquear hora" ,
								hora: hora ,
								fecha : fecha ,
								detalles : detalles 
							},
							function(resultado){
								if(resultado=="YES" ){
									location.reload();
								}
							}
						);
		}
		
		function bloquearHora_standby(hora, fecha){      
		  swal({
			  title: "La hora sera bloqueada para registrar Citas",
			  text: "confirme el cambio",
			  icon: "info",
			  buttons: [
				'No',
				'Si, bloquear horario'
			  ],
			  dangerMode: false,
			}).then(function(isConfirm) {
			  if (isConfirm) {
				swal({
				  title: 'Horario bloqueado para citas',
				  text: 'La hora fue bloqueada correctamente',
				  icon: 'success'
				}).then(function() {
						$.post(
							"funcionesJS.php" ,
							{
								accion : "bloquear hora" ,
								hora: hora ,
								fecha : fecha  
							},
							function(resultado){
								if(resultado=="YES" ){
									location.reload();
								}
							}
						);
							
				  	
				});
			  } 
			});
		}

		function desbloquearHora(hora, fecha){  
			$(".urlHoraSelected").removeClass("urlHoraSelected") ; 
			document.getElementById('divDetalles').style.display = 'none' ;
			document.getElementById('divConfirmar').style.display = 'none' ;
			document.getElementById('divTxtConfirmar').style.display = 'none' ;   
		  swal({
			  title: "La hora sera habilitada para registrar Citas",
			  text: "confirme el cambio",
			  icon: "info",
			  buttons: [
				'No',
				'Si, habilitar horario'
			  ],
			  dangerMode: false,
			}).then(function(isConfirm) {
			  if (isConfirm) {
				swal({
				  title: 'Horario habilitado para citas',
				  text: 'La hora fue habilitada correctamente',
				  icon: 'success'
				}).then(function() {
						$.post(
							"funcionesJS.php" ,
							{
								accion : "desbloquear hora" ,
								hora: hora ,
								fecha : fecha  
							},
							function(resultado){
								if(resultado=="YES" ){
									location.reload();
								}
							}
						);
							
				  	
				});
			  } 
			});
		}
		
	 function buscarCliente(_id){
		 xajax_buscarPaciente(_id);
	  }		
		
	</script>
    
    
<!-- SWEET ALERT  -->
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- END SWEET ALERT  -->    

<script src="resources/js/ajax.js"></script>
    
   <div class="modal-dialog modal-lg" style="font-style: normal;" >
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color:#900">
<!--        <button type="button" class="close" data-dismiss="modal" value='Cerrar ventana'></button> -->
			<?php /*** Arreglo con los meses ***/
            $arrMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); ?>
            <h4 class="modal-title" style="color:#FFF">Citas para el dia <?php echo substr($_POST['fecha'],8,2)," de ",$arrMes[(int)substr($_POST['fecha'],5,2)] ," de ",substr($_POST['fecha'],0,4); ?></h4>

          
        </div>
        <div class="modal-body" style="text-align:left">
        
      
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">      
      
                                         
                            <?php listado(); ?>
							
							<div class="row">
								<div class="col-md-12 text-center">
									<hr>
									<h3 style="color:#9bbc85">Horarios disponibles para este día</h3>
									<hr>
								</div>
							</div>
							<style type="text/css">
								.horaLibre {padding:5px ; background-color:#efeff1 ; text-align:center; border:2px #fff solid ; }
								.horaOcupada {padding:5px ; background-color:#f44b4b ; text-align:center; border:2px #fff solid ;  color: #fff ;}
								.urlHora { text-decoration: none; color : #555 ; display: block; font-weight: lighter;}
								.urlHoraBlanco { text-decoration: none; color : #fff ; display: block; font-weight: lighter;}
								.urlHoraSelected { text-decoration: none;  display: block; background-color: #27AE60; color: #fff}
							</style>
							<div class="row">
							
                            <?php 
                            	$horasBloqueadas = array() ;
                            	$sql = "select * from horasBloqueadas where fecha = '$_POST[fecha]' and id_juzgado = '$_SESSION[id_juzgado]' and activo=1 " ;
                            	$query = mysqli_query($link, $sql) ;
                            	while($datos = mysqli_fetch_assoc($query)){
                            		$horasBloqueadas[] = substr($datos['hora'],0,5) ;
                            	}
                            			$horarios = array(	
															 "09:00", 
															 "09:30", 
															 "10:00", 
															 "10:30", 
															 "11:00", 
															 "11:30", 
															 "12:00", 
															 "12:30", 
															 "13:00", 
															 "13:30", 
															 "14:00",
															 "14:30",
															 "15:00"
														) ;
                            	foreach($horarios as $llave => $valor){
                            		if(in_array($valor,$horasBloqueadas)) {
                            			echo  "<div class='col-md-1 horaOcupada'>
                            					<a href='javascript:void(0) ;desbloquearHora(\"$valor\",\"$_POST[fecha]\")' class='urlHoraBlanco' >
                            							$valor
                            					</a>
                            				</div>" ;
                            		}
                            		else{
                            			echo  "<div class='col-md-1 horaLibre'>
                            					<a href='javascript:void(0) ;' onclick='mostrarCampos(\"$valor\",\"$_POST[fecha]\",this)' class='urlHora' >
                            							$valor
                            					</a>
                            				</div>" ;	
                            		}
                            		
                            	}  
                            	?>
        						</div>	
								<input type="hidden" name="fechaSeleccionada" id="fechaSeleccionada">
								<input type="hidden" name="horaSeleccionada" id="horaSeleccionada">
        						<div class="row" id="divDetalles" style="display:none;">
        							<div class="col-md-12" >
        								<br>
        								<label for="">El horario seleccionado será bloqueado para registrar Citas en este juzgado. Al momento de proceder se generará un reporte automático. </label>
        								<br><br>
        								<label for="">Por favor indique con detalles el motivo para bloquear el horario del juzgado</label><br>
        								<textarea name="detalles" id="detalles" cols="30" rows="4" class="form-control" onkeydown="verBloquear(this.value)"></textarea>
        								<label style="font-size: 12px;">Teclee al menos 50 caracteres como detalle</label>
        							</div>
        						</div>
        						<br>
        						<div class="row" id="divTxtConfirmar" style="display: none">
        							<div class="col-md-12">
        								<label for="">Tecleé la palabra <b>BLOQUEAR</b> y de click en confirmar</label>
        								<input type="text" value="" name="bloquear" id="bloquear" class="form-control" onkeyup="javascript:if(this.value=='bloquear' || this.value=='BLOQUEAR'){document.getElementById('divConfirmar').style.display = 'block'; document.getElementById('detalles').disabled = true  ;}else{document.getElementById('divConfirmar').style.display = 'none'}">
        							</div>
        						</div>
        						<div class="row" id="divConfirmar" style="display: none">
        							<div class="col-md-12">
        								<br>
        								<input type="button" value="Confirmar" class="btn btn-secondary" onclick="bloquearHora()">
        								
        							</div>
        						</div>
        
        				</div>
        			</div>
        		</div>
        	</div>
                
                            
        </div> <!-- termina el div de contenido -->
                                            
        
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar ventana</button>
        </div>
        
        
      </div>
      
    </div>
    
 <script>
 	function mostrarCampos(hora,fecha,elemento){
 		document.getElementById('divDetalles').style.display = ' block ';
 		document.getElementById('fechaSeleccionada').value = fecha ;
 		document.getElementById('horaSeleccionada').value = hora ;
 		$(".urlHoraSelected").removeClass("urlHoraSelected") ;
    	$(elemento).addClass('urlHoraSelected') ;
 	}
 	function verBloquear(valor){
 		var dato = valor ;
 		if(dato!="" && dato.length >50){
 			document.getElementById('divTxtConfirmar').style.display = 'block'
 		}
 	}
</script>   
    
<!-- Latest minified bootstrap css -->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

<!-- Latest minified bootstrap js -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>    -->
    
    <script language="javascript">
    function validarDatos(_hora,_fecha,_paterno,_materno,_nombre,_servicio,_asistente,_celular,_sesiones,_costo,_sucursal){

	  	var f = document.pacientes
		var flag = 0 ;
  
	     if (f.nombre.value=="" && flag == 0){
			  swal("Teclee el nombre",'','warning');
			  f.nombre.focus();
			  flag=1;
		  }
		  if (f.paterno.value=="" && flag == 0){
			  swal("Teclee el apellido Paterno",'','warning');
			  f.paterno.focus();
			  flag=1;
		  }
		   if (f.materno.value=="" && flag == 0){
			  swal("Teclee el apellido materno",'','warning');
			  f.materno.focus();
			  flag=1;
		  }
		  if (f.celular.value=="" && flag == 0){
			  swal("Teclee el celular de contacto",'','warning');
			  f.celular.focus();
			  flag=1;
		  }

		  if (f.servicio.value=="" && flag == 0){
			  swal("Seleccione el servicio a asignar",'','warning');
			  f.servicio.focus();
			  flag=1;
		  }
		  if (f.sesiones.value=="" && flag == 0){
			  swal("Defina el numero de sesiones",'','warning');
			  f.sesiones.focus();
			  flag=1;
		  }
		  if (f.costo.value=="" && flag == 0){
			  swal("Defina el costo del servicio",'','warning');
			  f.costo.focus();
			  flag=1;
		  }
		  		  
		  if (f.hora.value=="" && flag == 0){
			  swal("Seleccione la hora de la cita",'','warning');
			  f.hora.focus();
			  flag=1;
		  }
		  if (f.asistente.value=="" && flag == 0){
			  swal("Seleccione quien atendera al paciente",'','warning');
			  f.asistente.focus();
			  flag=1;
		  }		  		  
		  if (f.sucursal.value=="" && flag == 0){
			  swal("Seleccione la sucursal donde se atendera la cita",'','warning');
			  f.sucursal.focus();
			  flag=1;
		  }		  		  


		  if(flag == 0){
			$.ajax({
				type:'POST',
				url:'guardarCita.php',
				data: { 
						hora: _hora, 
						fecha: _fecha,
						paterno: _paterno,
						materno: _materno,
						nombre: _nombre,
						servicio: _servicio,
						asistente: _asistente,
						celular: _celular,
						sesiones: _sesiones,
						costo: _costo,
						sucursal: _sucursal
				} ,
			success:function(data){
				//alert(data);
				swal(data,'','success');
				},
			complete:function(){
				location.reload();
				}				  
			}) ;
		  }
		
		
    }
	</script>
    
    
         
        <?php
		function listado(){
			$link=conectarse();
//			echo "$_POST[asistente]","<br>","$_POST[administrador]" ;
			if($_POST['administrador']==1){
				$where = " c.fecha='$_POST[fecha]' and (c.estatus!='Eliminada' or c.estatus IS NULL)";
			}
			else{
				$where = " c.fecha='$_POST[fecha]' and c.id_juzgado='$_SESSION[id_juzgado]'  and (c.estatus!='Eliminada' or c.estatus IS NULL)";	
			}
			
				$sql = "SELECT c.*,concat(u.nombre,' ',u.apPaterno,' ',u.apMaterno) as nombre,u.celular,s.servicio FROM citas c
								inner join usuario u on c.id_beneficiario = u.id
								inner join cat_servicios s on c.id_servicio = s.id
								where $where
								order by hora ;";
			//echo "<br>$sql";					
			$query = mysqli_query($link,$sql) ;
			while($datos = mysqli_fetch_assoc($query)){
				$confirmar =""; $cancelar=""; $estatus=""; $eliminar ="" ;$imagenC ="";
				if(empty($datos['estatus'])){
					$confirmar = "<img onclick='confirmarCita(".$datos['id'].")' src='resources/imgs/btn_concluir.png' width='20' title='Confirmar asistencia'>";
					$cancelar=" &nbsp;&nbsp;&nbsp;&nbsp; <img onclick='cancelarCita(".$datos['id'].")' src='resources/imgs/btn_cancelar.png' width='23' title='Cancelar asistencia'>";
					$eliminar = "<a href='javascript:void(0);'> <img onclick='eliminarCita(".$datos['id'].")' src='resources/imgs/img_trash.png' width='23' title='Eliminar registro'></a>" ;					
					
				}
				else{
					if($datos['estatus']=="Cancelada"){
							$estatus = "<font color='red'>Cancelada</font>" ;	
							$eliminar = "<a href='javascript:void(0);'> <img onclick='eliminarCita(".$datos['id'].")' src='resources/imgs/img_trash.png' width='23' title='Eliminar registro'></a>" ;					
					}
					if($datos['estatus']== "Confirmada"){
						$estatus = "<font color='green'>Confirmada</font>" ;
						
						if($datos['asistio']==""){
							$cancelar="<img onclick='cancelarCita(".$datos['id'].")' src='resources/imgs/btn_cancelar.png' width='23' title='Cancelar asistencia'>";
						}
						if($datos['asistio']=="SI"){
							$imagenC="<img  src='resources/imgs/img_si_asistio.png' width='23' title='Si asitio a la cita'>";	
						}	
						if($datos['asistio']=="NO"){
							$imagenC="<img  src='resources/imgs/img_no_asistio.png' width='23' title='No asitio a la cita'>";	
						}		
							
					}
				}
				echo $confirmar , $cancelar , "&nbsp;&nbsp;&nbsp;&nbsp;", substr($datos['hora'],0,5),  "&nbsp;&nbsp;&nbsp;&nbsp;", $datos['nombre'] ," $estatus ($datos[num_tel]) $imagenC
				<h5 style='color:green;'>($datos[servicio])  
				$eliminar 
				</h5> 
				<br>" ;
			} 
		
		}?>
		

