<?php
	$btnSesion="";
if (array_pop(explode('/', $_SERVER['PHP_SELF']))=="registroPJ.php" or array_pop(explode('/', $_SERVER['PHP_SELF']))=="recuperarContra.php" or array_pop(explode('/', $_SERVER['PHP_SELF']))=="verificar.php" or array_pop(explode('/', $_SERVER['PHP_SELF']))=="verificarContra.php")
{
	$btnSesion="Iniciar Sesión";
}
elseif(array_pop(explode('/', $_SERVER['PHP_SELF']))!="index.php" and array_pop(explode('/', $_SERVER['PHP_SELF']))!="calendario.php")
{
	$btnSesion="Cerrar Sesión";
}
?>

<div class="container-fluid bg-azul header">
	<div class="row justify-content-right">
		<div class="col-md-12 text-right">	
			<a href='cerrarSesion.php' style="text-decoration: none; color: #fff; font-size:.75em; padding-right:50px; "><?php  echo $btnSesion?></a>
		</div>
	</div>
	<div class="row justify-content-center">
		  <div class="col-md-4 text-center">
		  	<br>
		    <img src='resources/imgs/img_pjecz.png' width="100"> 
		    <h4 style="padding-top: 15px; color: #f2edce;">Sistema de Citas en Linea</h4>
		  </div>
	</div>
	<?php if($_SESSION['idRol']==2 and array_pop(explode('/', $_SERVER['PHP_SELF']))=="calendario.php"){  ?>
	<div class="row justify-content-right">
		<div class="col-md-12 text-right">	
			<h3 style="color: #fff"><?php 
										if($_SESSION['idRol']==2) {echo "Distrito " . $_SESSION['distrito']. " , " . utf8_decode($_SESSION['juzgado']) ; }
										if($_SESSION['idRol']==3) {echo  $_SESSION['DISTRITO'] ; }
									?>
											
			</h3>
		</div>
	</div>
	<?php } ?>

</div>

