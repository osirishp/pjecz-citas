<?php
include("funciones.php");
$link = conectarse();
error_reporting(0);
$seguridad = seguridad();

include_once("funciones_ajax.php") ;
//inclumos la clase ajax
require('resources/xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax
$xajax = new xajax();
/* $xajax->decodeUTF8InputOn(); */
//asociamos la funcin creada anteriormente al objeto xajax
$xajax->register(XAJAX_FUNCTION,'agregarCita');
$xajax->register(XAJAX_FUNCTION,'valoresServicio');
$xajax->register(XAJAX_FUNCTION,'agregarServicio');
$xajax->register(XAJAX_FUNCTION,'agregarPago');
$xajax->register(XAJAX_FUNCTION,'concluirServicio');
$xajax->register(XAJAX_FUNCTION,'cancelarServicio');
$xajax->register(XAJAX_FUNCTION,'cancelarCita');
$xajax->register(XAJAX_FUNCTION,'buscarHorarios');
//    $xajax->registerFunction("generos");
$xajax->configure('javascript URI','resources/xajax/' );
//El objeto xajax tiene que procesar cualquier peticin
$xajax->processRequest();
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario
// $xajax->printJavascript("resources/xajax/");
$xajax->printJavaScript();

if(!isset($_POST['accion']) or empty($_POST['accion']) or $_POST['accion']=="nuevo Usuario"){
	$tipoLectura = "" ;
	$proceso="Nuevo" ;
}

if(isset($_POST['accion']) and $_POST['accion']=="eliminar Usuario" ){
	$instruccion ="delete from clientes where id='$_POST[id]'";
    $query = mysqli_query($link,$instruccion) ;
}

if(isset($_POST['accion']) and $_POST['accion']=="guardar beneficiario" ){

        // declaracion de variables //
        $nombre = strtoupper(ucfirst($_POST['nombre'])) ;
        $paterno = strtoupper(ucfirst($_POST['paterno']));
        $materno = strtoupper(ucfirst($_POST['materno']));
        $fecha_nac = ($_POST['fechaNacimiento'])!=""?("'".$_POST['FechaNacimiento']."'"):'null' ;
        $num_tel =$_POST['telefono'] ;
        $email = $_POST['email'] ;
        $medio = $_POST['medio'] ;
		$otro = $_POST['otro'] ;

   
          $instruccion ="INSERT INTO clientes (nombre, paterno, materno, fecha_nac, num_tel, email, medio, otro) values ('$nombre', '$paterno', '$materno',$fecha_nac,'$num_tel','$email','$medio','$otro')";
          //echo $instruccion ;  exit();
         $query = mysqli_query($link,$instruccion) ;
		 
		 $_POST['accion']="buscar beneficiario" ;
		 $_POST['id'] = mysqli_insert_id($query) ;

		 $swal = "INSERT" ;
        
    }


if(isset($_POST['accion']) and $_POST['accion']=="actualizar beneficiario" ){

        // declaracion de variables //
        $nombre = strtoupper(ucfirst($_POST['nombre'])) ;
        $paterno = strtoupper(ucfirst($_POST['paterno']));
        $materno = strtoupper(ucfirst($_POST['materno']));
        $fecha_nac = ($_POST['fechaNacimiento'])!=""?("'".$_POST['FechaNacimiento']."'"):'null' ;
        $num_tel =$_POST['telefono'] ;
        $email = $_POST['email'] ;
        $medio = $_POST['medio'] ;
		$otro = $_POST['otro'] ;

          $instruccion ="UPDATE clientes 
		  				SET 
							nombre = '$nombre', 
							paterno = '$paterno', 
							materno = '$materno', 
							fecha_nac = $fecha_nac, 
							num_tel = '$num_tel', 
							email = '$email', 
							medio = '$medio', 
							otro = '$otro'
						WHERE id='$_POST[id]'
							
							";
							//echo $instruccion ; exit();
         $query = mysqli_query($link,$instruccion) ;
		 
		 $_POST['accion']="buscar beneficiario" ;
		 
         $swal = "UPDATE" ; 
             }

if($_POST['accion']=="buscar beneficiario"){

/* mandar a traer de la Base de Datos la Informacion para poder Editarlo*/

    if(isset($_POST['id']) and ! empty($_POST['id'])){
  
    	$sqlB = "select * from clientes where id='$_POST[id]'"	 ;
    	$queryB = mysqli_query($link,$sqlB);
    	$datosB = mysqli_fetch_assoc($queryB) ;

/* variables para poder Editarlo y se muestran en los input*/
		$id = $datosB['id'];
    	$nombre =  $datosB['nombre'];
    	$paterno = $datosB['paterno'];
    	$materno = $datosB['materno'];
    	$fechaNacimiento = $datosB['fecha_nac'];
    	$num_tel = $datosB['num_tel'];
    	$email = $datosB['email'];
    	$medio = $datosB['medio'] ;
    	
    }
	$proceso = "Consulta";

 }
//echo "hola,",$_SESSION['actualizarUsuario'] ;
if($_SESSION['actualizarUsuario']=="NO"){	$tipoLectura = "readonly" ;}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo TITULO ; ?></title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<script src="resources/js/ajax.js"></script>
<!-- //for-mobile-apps -->
<link href="resources/css/fonts.css" rel="stylesheet" type="text/css" media="all" />
<link href="resources/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="resources/css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- js -->
<!--<script src="resources/js/jquery-1.11.1.min.js"></script> esta libreria madrea a las siguientes 2 -->
<!-- //js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 


<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>



<script language="javascript" >
	 function buscarCliente(_id){
		  document.Usuarios.id.value = _id ;
		  document.Usuarios.accion.value = "buscar beneficiario" ;
		  document.Usuarios.submit();
	  }
		

	function guardarInfo(_accion){
	  	var f = document.Usuarios
		var flag = 0 ;
  
	     if (f.nombre.value=="" && flag == 0){
			  swal("Teclee el nombre",'','info');
			  f.nombre.focus();
			  flag=1;
		  }
		  if (f.paterno.value=="" && flag == 0){
			  swal("Teclee el apellido Paterno",'','info');
			  f.paterno.focus();
			  flag=1;
		  }
		   if (f.materno.value=="" && flag == 0){
			  swal("Teclee el apellido materno",'','info');
			  f.materno.focus();
			  flag=1;
		  }
		  if (f.telefono.value=="" && flag == 0){
			  swal("Teclee el telefono de contacto",'','info');
			  f.telefono.focus();
			  flag=1;
		  }

		  if(flag == 0){
			 	if(_accion=="Guardar"){	  f.accion.value = "guardar beneficiario"; }
				if(_accion=="Actualizar"){	  f.accion.value = "actualizar beneficiario"; }
			  	f.submit();
		  }
	  }

	function recargarCitas(){
		$('#citas').dataTable().fnDestroy();
		mostrarCitas();
		
	}

	function mostrarCitas(){
		$(document).ready( function () {
			$('#citas').dataTable({
				ordering: false,
				searching:false,
				ajax: {
					"url":"sqlCitas.php" ,
					"type":"POST",
					"data":{
							"ID":"<?php echo $_POST['id']; ?>"
							}
				},
				columns : [
					{"data":"servicio"},
					{"data":"fecha"},
					{"data":"hora"},
					{"data":"asistente"},
					{"data":"estatus"},
					{"data":"accion"}
				],
				
				 language:{ "sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":    "",
						"sSearch":         "Buscar:",
						"sUrl":            "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}
	
				 }
			});
		});
	}
	
	mostrarCitas();
	
	function recargarServicios(){
		$('#servicios').dataTable().fnDestroy();
		mostrarServicios();
		
	}	
	
	function mostrarServicios(){
		$(document).ready( function () {
			$('#servicios').DataTable({
				ordering: false,
				searching:false,

				ajax: {
					"url":"sqlServicios.php" ,
					"type":"POST",
					"data":{
							"ID":"<?php echo $_POST['id']; ?>"
							}
				},
				columns : [
					{"data":"servicio"},
					{"data":"fecha"},
					{"data":"costo"},
					{"data":"resto"},
					{"data":"sesiones"},
					{"data":"estatus"},
					{"data":"accion"}
					
				],				
								
				 language:{ "sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":    "",
						"sSearch":         "Buscar:",
						"sUrl":            "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}
	
				 }
			});
		});

	}

	mostrarServicios();
	
	function recargarPagos(){
		$('#pagos').dataTable().fnDestroy();
		mostrarPagos();
		
	}	
		
	function mostrarPagos(){	
		$(document).ready( function () {
			$('#pagos').DataTable({
				ordering: false,
				searching:false,
				
					ajax: {
						"url":"sqlPagos.php" ,
						"type":"POST",
					"data":{
							"ID":"<?php echo $_POST['id']; ?>"
							}
					},
					columns : [
						{"data":"servicio"},
						{"data":"fecha"},
						{"data":"importe"},
						{"data":"resto"},						
						{"data":"forma_de_pago"},
						{"data":"remision"}
					],	
								
				 language:{ "sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":    "",
						"sSearch":         "Buscar:",
						"sUrl":            "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}
	
				 }
			});
		});	
	}
	
	mostrarPagos();	
	
	
	function concluir_standby(id,id_Usuario){
		if(confirm("Desea cocluir el servicio ? ")){
			xajax_concluirServicio(id,id_Usuario);
		}	
	}
	
	function cancelar_standby(id,id_Usuario){
		if(confirm("Desea cancelar el servicio ? ")){
			xajax_cancelarServicio(id,id_Usuario);
		}	
	}
	function concluir(id,id_Usuario){      
      swal({
          title: "Esta seguro de CONCLUIR el servicio?",
          text: "una vez concluido no se podra restaurar",
          icon: "warning",
          buttons: [
            'No',
            'Si, concluir servicio'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) {
            swal({
              title: 'Servicio Concluido',
              text: 'El servicio fue concluido',
              icon: 'success'
            }).then(function() {
              xajax_concluirServicio(id,id_Usuario);
            });
          } 
        });
	}	
	
	function cancelar(id,id_Usuario){      
      swal({
          title: "Esta seguro de cancelar el servicio?",
          text: "una vez cancelado no se podra restaurar",
          icon: "warning",
          buttons: [
            'No',
            'Si, cancelar servicio'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) {
            swal({
              title: 'Servicio Cancelado',
              text: 'El servicio fue cancelado',
              icon: 'success'
            }).then(function() {
              xajax_cancelarServicio(id,id_Usuario);
            });
          } 
        });
	}


	function cancelarCita(id,id_Usuario){      
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
              xajax_cancelarCita(id,id_Usuario);
            });
          } 
        });
	}
  	
	
	function eliminarUsuario(){      
      swal({
          title: "Esta seguro de eliminar este Usuario ?",
          text: "una vez eliminado el Usuario no se podra restaurar",
          icon: "warning",
          buttons: [
            'No',
            'Si, eliminar Usuario'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) {
				document.Usuarios.accion.value="eliminar Usuario" ;
				document.Usuarios.submit();
          } 
        });
	}	
	
	</script>

<style type="text/css">
	.ancho70{ width:70%; margin:0px auto; }
	.tablaDatos{ padding-top:50px;}
	.mayusculas{ text-transform:uppercase;}
</style>
</head>
	
<body>

<?php //include("usuario.php");?>

<!-- banner -->
<?php include("banner.php"); ?>
<!-- //banner -->

<!-- header-nav -->
	<div class="header-nav">
		<div class="container">
        	<div class="row ancho70">
                <div class="header-nav-bottom">
                    <nav class="navbar navbar-default">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                          </button>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <?php include("menu.php"); ?>
                    </nav>
                </div>
			</div>
		</div>
	</div>



      <!-- script-for sticky-nav -->
          <script>
          $(document).ready(function() {
               var navoffeset=$(".header-nav").offset().top;
               $(window).scroll(function(){
                  var scrollpos=$(window).scrollTop(); 
                  if(scrollpos >=navoffeset){
                      $(".header-nav").addClass("fixed");
                  }else{
                      $(".header-nav").removeClass("fixed");
                  }
               });
               
          });
          </script>
      <!-- //script-for sticky-nav -->

<!-- //header-nav -->
<!--typography-page -->
		<div class="container" style="border:0px #CC0099 solid;background-color: #FFF; padding-top:20px;">
			<div class="row" style="border:0px #FF0000 solid; width:70%; margin:0 auto; ">
					<div class="form-group">
						<div class="col-md-6 ">
							<h3 class="hdg">Buscar</h3>
			 				<div class="input-group">
                            
								<span class="input-group-addon" id="basic-addon1">Nombre</span>
								<input type="text" placeholder="Buscar" aria-describedby="basic-addon1" id="bus" class="form-control" name="bus" onkeyup="loadXMLDoc()" required>
								<div id="myDiv"></div>
							</div>
					   </div>
				    </div>
			</div><br>
            
			<div class="row" style="border:0px #FF0000 solid; width:70%; margin:0 auto; ">
                <div class="form-group">
                    <div class="col-md-6 ">
                    	<?php
						if($proceso=="Consulta" and $_SESSION['altaUsuario']=="SI"){
							echo "<input type='button' class='btn btn-primary' value='Alta de Usuario' onClick='javascript:document.Usuarios.id.value= \"\"; document.Usuarios.accion.value=\"nuevo Usuario\"; document.Usuarios.submit() ;'/>" ;	
						}?>
                    </div>
                </div>
		    </div>
			<br>
		</div>
        
     
  <style>
    .swal-button--confirm {
      background-color: #DD6B55;
    }
  </style>

        
        <div class="container" style="border:0px #CC0099 solid; background-color:#efeff1;padding-bottom:30px; padding-top:30px;">
        
            <div class="row ancho70" style="border:0px #FF0000 solid;">
    
    <!-- Inicio del Formulario -->
    
   
            <form name="Usuarios" id="Usuarios" action="registro.php" method="post">
                <input type="hidden" name="accion"/>
                <input type="hidden" name="id" id="id" value="<?php echo $_POST['id'];?>" />
                <input type="hidden" name="precioUnitario" id="precioUnitario" />
    
    			
                
                <h3 class="hdg">Usuario</h3>
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Numero de Usuario</label>
                        <input type="text" class="form-control" placeholder="# Expediente" name="id_Usuario" id="id_Usuario" aria-describedby="basic-addon1" value="<?php echo $id;?>" readonly>
                   </div>
                   <div class="col-md-3">
                        <label for="Nombre(s)">Nombre(s)</label>
                        <input type="text" class="form-control mayusculas" placeholder="Nombre" name="nombre" id="nombre" value="<?php echo $nombre;?>" <?php echo $tipoLectura ; ?> >
                   </div>
                    <div class="col-md-3">
                        <label for="Apellido Paterno">Apellido Paterno</label>
                        <input type="text" class="form-control mayusculas" placeholder="Paterno" name="paterno" id="paterno" value="<?php echo $paterno;?>" <?php echo $tipoLectura ; ?>>
                   </div>
                    <div class="col-md-3">
                        <label for="Apellido Materno">Apellido Materno</label>
                        <input type="text" class="form-control mayusculas" placeholder="materno" name="materno" id="materno" value="<?php echo $materno;?>" <?php echo $tipoLectura ; ?>>
                   </div>                   
                </div><br><br>
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Fecha de Nacimiento</label>
                        <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" value="<?php echo $fechaNacimiento ;?>" <?php echo $tipoLectura ; ?>>
                        <div class="bfh-timepicker">
                        </div>
	                        <script language="javascript">
		                        $().bfhtimepicker('toggle')
	                        </script>
                        </div>
                    
                    <div class="col-md-3">
                        <label for="Numero de Celular">Numero de Celular</label>
                        <input type="text" class="form-control" placeholder="Numero de Celular" name="telefono" id="telefono" value="<?php echo $num_tel;?>" <?php echo $tipoLectura ; ?>>
                    </div>
                    <div class="col-md-3">
                        <label for="Correo Electronico">Correo Electronico</label>
                        <input type="text" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo $email;?>" <?php echo $tipoLectura ; ?>>
                    </div>
                    <div class="col-md-3">
                        <label for="Por que medio se entero?">Por que medio se entero?</label>
                        <select name="medio" id="medio" class="form-control" value="<?php echo $medio;?>" <?php echo $tipoLectura ; ?> onChange="otroMedio(this.value);">
                            <option value=""></option>
                            <option value="FaceBook"<?php if($medio=="FaceBook"){echo " selected ";}?> >FaceBook</option>
                            <option value="Radio"<?php if($medio=="Radio"){echo " selected ";}?> >Radio</option>
                            <option value="Recomendaci&oacute;n"<?php if($medio=="Recomendación"){echo " selected ";}?> >Recomendaci&oacute;n</option>
                            <option value="Otro"<?php if($medio=="Otro"){echo " selected ";}?> >Otro</option>
                        </select>
                        <br>
                        <script language="javascript">
							function otroMedio(valor){
								if(valor=="Otro" || valor=="Recomendaci\u00F3n"){
									document.getElementById("divOtro").style.display='block';	
								} 	
								else{
									document.getElementById("divOtro").style.display='none';	
									}
							}
						</script>
                        <div id="divOtro" style="display:none">
                        	<input type="text" width="100%" name="otro" id="otro" class="form-control">
                        </div>
                    </div>
                </div><br>
                <br>
                <div class="row">
                    <div class="col-md-2">
                    	<?php
							if($proceso=="Nuevo" and $_SESSION['altaUsuario']=="SI"){
								echo "<input type='button' class='btn btn-success' value='Guardar Informaci&oacute;n'  onClick='javascript:guardarInfo(\"Guardar\");'/>" ;
							}
							if($proceso=="Consulta" and $_SESSION['actualizarUsuario']=="SI"){
								echo "<input type='button' class='btn btn-warning' value='Actualizar Informaci&oacute;n'  onClick='javascript:guardarInfo(\"Actualizar\");'/>" ;	
							}
							?>
                            
                    </div>
                    <div class="col-md-2">
                    	<?php
							if($proceso=="Consulta" and $_SESSION['eliminarUsuario']=="SI"){
								echo "<input type='button' class='btn btn-danger' value='Eliminar Usuario'  onClick='javascript:eliminarUsuario();'/>" ;	
							}
							?>
                    </div>
                    
                </div>
			
            </div>

		</div>



	<?php 
	
	if($proceso=="Consulta"){
		
		include("moduloServicios.php"); 
		include("moduloCitas.php"); 
		include("moduloPagos.php"); 
	
	}

	
if(isset($_POST['accion']) and $_POST['accion']=="eliminar Usuario" ){

   echo "    <script language='javascript'>
				 swal('Informacion','El registro fue eliminado de forma permanente','success') ; 
			  </script>";
}
?>







		</form>
<!-- Fin del Formulario -->

<?php include("footer.php") ;?>

<!-- SWEET ALERT  -->
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- END SWEET ALERT  -->


<?php 
if($swal =="INSERT"){
	 echo "    <script language='javascript'>
                       swal('Usuario guardado correctamente','','success') ; 
                       </script>";
}
if($swal == "UPDATE"){
	 echo "    <script language='javascript'>
                       swal('Usuario Actualizado correctamente','','success') ; 
                       </script>";
}
?>

</body>
</html>

<?php
 //echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>";
 //exit; 
?>