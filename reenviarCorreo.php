<?php 
include ("funciones.php") ;
error_reporting(0);
require("SendGrid/sendgrid-php.php");
$link = conectarse() ;
date_default_timezone_set('America/Mexico_City');
$Error="";

if(isset($_POST['btnBuscar']))
{
	$Usuario=$_POST['txtCitaId'];

		$sqlDatosAds = "select c.id as idCita, c.*, u.* ,j.distrito,j.juzgado, cat.servicio from citas c
					left join usuario u on u.email = c.correo
					left join juzgados j  on j.id = c.id_juzgado
					left join cat_servicios cat on  cat.id = c.id_servicio  
				where c.id = $Usuario";

		$resultDatosAds = mysqli_query($link,$sqlDatosAds);
		$row = mysqli_fetch_array($resultDatosAds,MYSQLI_ASSOC);
		$idCita=$row['idCita'];
		$folio="#".(substr($row['fecha'],5,2) ."_". substr($row['fecha'],8,2) ."_". str_replace(":","_",substr($row['hora'],0,5)) . "_" . $row['idCita']);
		$distrito=$row['distrito'];
		$juzgado=$row['juzgado'];
		$fecha=$row['fecha'];
		$hora=$row['hora'];
		$servicio=$row['servicio'];
		$detalles=$row['detalles'];
		$nombre=$row['nombre'];
		$apPaterno=$row['apPaterno'];
		$apMaterno=$row['apMaterno'];
		$correo=$row['correo'];
        $curp=$row['curp'];
		$celular=$row['celular'];}

if(isset($_POST['btnReenviar']))
{
	$Usuario=$_POST['txtCitaId'];
		include_once("funciones.php") ;
		
		$sql = "select c.id as idCita, c.*, u.* ,j.distrito,j.juzgado, cat.servicio from citas c
					left join usuario u on u.email = c.correo
					left join juzgados j  on j.id = c.id_juzgado
					left join cat_servicios cat on  cat.id = c.id_servicio  
				where c.id = $Usuario" ;
		//echo $sql ;
		$query = mysqli_query($link, $sql) ;
		$datos = mysqli_fetch_assoc($query) ;
		$para = "$datos[correo]" ;
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
									A su ingreso deberá presentar una identificación oficial y este mensaje de correo, ya sea impreso o en medio electrónico . Si acude 10 minutos después de la hora señalada en esta confirmación no será posible garantizarle el servicio.r.<br><br>
									<h3>Detalles de la cita</h3>
								</td>
							</td>
							<tr>
								<td>
									<label>Folio</label>
								</td>
								<td>
									<label>#". (substr($datos['fecha'],5,2) ."_". substr($datos['fecha'],8,2) ."_".str_replace(":","_",substr($datos['hora'],0,5)) . "_" . $datos['idCita']) . "</label>
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

?>


<html lang ="es">
	<head>
    <title>Sistema de Citas</title>
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="resources/js/funciones_js.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
    </head>
    
    <body>
    <?php include("header.php"); ?>
	<br>
	<div class="container">
            <div class="row text-center"> <br>
                <h2><strong>Reenviar correo de cita</strong></h2> <br><br>
            </div>
     <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data" autocomplete="off">  
      <div class="form-row">
            <div class="col-md-4">
                    <label for="txtCitaId" class="control-label">Numero de cita ID</label>
                    <input type="text" class="form-control" id="txtCitaId" name="txtCitaId" value="<?php if(isset($Usuario)) echo $Usuario?>">
      		</div>
      		<div class="col-sm-6 text-left">
      			<label for="lblBuscar" class="control-label">Buscar</label><br>
            	<button type="submit" class="btn btn-primary" name="btnBuscar" style="background-color: #46576b; color: #fff" id="btnBuscar">Buscar</button>
            </div>
            <div class="col-xs-6">
                	<label for="txtFolio" class="control-label">Folio</label>
                  <input type="text" class="form-control" id="txtFolio" name="txtFolio" value="<?php echo $folio?>">
            </div>
             <div class="col-xs-6">
                	<label for="txtDistrito" class="control-label">Distrito</label>
                  <input type="text" class="form-control" id="txtDistrito" name="txtDistrito" value="<?php echo $distrito?>">
            </div>            
            <div class="col-xs-12">
                	<label for="txtJuzgado" class="control-label">Juzgado</label>
                  <input type="text" class="form-control" id="txtJuzgado" name="txtJuzgado" value="<?php echo $juzgado?>">
            </div>
        	  <div class="col-sm-4">
                	<label for="txtFecha" class="control-label">Fecha</label>
                  <input type="text" class="form-control" id="txtFecha" name="txtFecha" value="<?php echo $fecha?>">
            </div>
            <div class="col-sm-4">
                	<label for="txtHora" class="control-label">Hora</label>
                  <input type="text" class="form-control" id="txtHora" name="txtHora" value="<?php echo $hora?>">
            </div>
            <div class="col-sm-12">
                	<label for="txtAsunto" class="control-label">Asunto</label>
                  <input type="text" class="form-control" id="txtAsunto" name="txtAsunto" value="<?php echo $servicio?>">
            </div>
            <div class="col-sm-12">
                	<label for="txtDetalles" class="control-label">Detalles</label>
                  <input type="text" class="form-control" id="txtDetalles" name="txtDetalles" value="<?php echo $detalles?>">
            </div>
            <div class="col-sm-6">
                	<label for="txtNombre" class="control-label">Nombre completo</label>
                  <input type="text" class="form-control" id="txtNombre" name="txtNombre" value="<?php echo $nombre; echo $apPaterno; echo $apMaterno?>">
            </div>
            <div class="col-sm-6">
                	<label for="txtCorreo" class="control-label">Correo</label>
                  <input type="text" class="form-control" id="txtCorreo" name="txtCorreo" value="<?php echo $correo?>">
  			    </div>
            <div class="col-sm-6">
                	<label for="txtCurp" class="control-label">CURP</label>
                  <input type="text" class="form-control" id="txtCurp" name="txtCurp" value="<?php echo $curp?>">
            </div>
            <div class="col-sm-6">
                	<label for="txtCelular" class="control-label">Celular</label>
                  <input type="text" class="form-control" id="txtCelular" name="txtCelular" value="<?php echo $celular?>">
  			</div>          
     	</div>
  </div>
        <div class="row">
            <div class="col-sm-12 text-center">
            	<button type="submit" class="btn btn-primary" name="btnReenviar" style="background-color: #46576b; color: #fff" id="btnGDA">Reenviar</button>
            </div>
     	</div>
     </form>    
     </div> 		
	</body>
</html>

<?php if($Error!=''){echo "<script>swal('$Error','','$tipoalerta')</script>";}?>