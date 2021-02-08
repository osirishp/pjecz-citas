<?php
include ("funciones.php");
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
$xajax->register(XAJAX_FUNCTION,'buscarHorarios');
$xajax->register(XAJAX_FUNCTION,'reagendarCita');
$xajax->register(XAJAX_FUNCTION,'verificarCita');
//    $xajax->registerFunction("generos");
$xajax->configure('javascript URI','resources/xajax/' );
//El objeto xajax tiene que procesar cualquier peticin
$xajax->processRequest();
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario
// $xajax->printJavascript("resources/xajax/");
$xajax->printJavaScript();


?>
<!DOCTYPE html>
<html><head>
<title><?php echo TITULO; ?></title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- //for-mobile-apps -->

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"> 
<link href="resources/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />  
<link href="resources/css/style.css" rel="stylesheet" type="text/css" media="all" />

<link href="resources/css/fonts.css" rel="stylesheet" type="text/css">
<link href="resources/css/estilos.css" rel="stylesheet" type="text/css">

<!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



</head>
	
<body>
<?php //include("usuario.php") ; ?>

<!-- banner -->
<?php //include("banner.php"); ?>
<?php include("banner.php"); ?>
<!-- //banner -->

<!-- header-nav -->
	<div class="header-nav">
		<div class="container">
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
<!-- //header-nav -->


    <?php include("agendaResponsiva.php");?>
</div>
<!-- footer -->
<div class="container-fluid" style="background-color:#163b67 ">
	<?php include("footer.php"); ?>	
</div>
<!-- //footer -->



</body>
</html>