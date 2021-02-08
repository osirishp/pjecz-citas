<?php
include ("funciones.php");
$link = conectarse();
$seguridad=seguridad();
error_reporting(0);

include_once("funciones_ajax.php") ;
//inclumos la clase ajax
require('resources/xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax
$xajax = new xajax();
/* $xajax->decodeUTF8InputOn(); */
//asociamos la funcin creada anteriormente al objeto xajax
$xajax->register(XAJAX_FUNCTION,'consultarCitas');
$xajax->register(XAJAX_FUNCTION,'estatusCitaAct');
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="resources/js/funciones_js.js"></script>

<script language="javascript">
	function estatus(id,id_paciente,estatus){
		if(estatus=='Cancelada'){mensaje="Desea CANCELAR la cita ? ";}
		if(estatus=='Confirmada'){mensaje="Desea CONFIRMAR la cita ? ";}
		if(confirm(mensaje)){
			xajax_estatusCitaAct(id,id_paciente,estatus);
		}	
	}
	
</script>


<style type="text/css">
	.ancho70{ width:70%; margin:0px auto; }
	.tablaDatos{ padding-top:50px;}
	.mayusculas{ text-transform:uppercase;}
</style>
</head>
	
<body>

<?php //include("usuario.php") ;?>

<!-- banner -->
<?php include("banner.php"); ?>
<!-- //banner -->

<?php if($_SESSION['idRol']==2){ ?>
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
  <?php } ?>


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

  <style>
    .swal-button--confirm {
      background-color: #DD6B55;
    }
  </style>

        
        <div class="container" style="border:0px #CC0099 solid; background-color:#efeff1;padding-bottom:30px; padding-top:30px; min-height: 600px;">
        
            <div class="row ancho70" style="border:0px #FF0000 solid;">
    
    <!-- Inicio del Formulario -->
    
   
            <form name="reportes" id="reportes" action="actividades.php" method="post">
                <input type="hidden" name="accion"/>
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="IdCita" id="IdCita" value="">
    			
                
                <!-- <h3 class="hdg">Consulta</h3> -->
                <div class="row">
             
                    <div class="col-md-2">
                            <label for="">Dia a consultar</label>
                             <div class="book_date">
                                <div class="input-group date">
                                  <input type="text"  name="fecha_ini" id="fecha_ini" class="form-control" onChange="javascript:xajax_consultarCitas(document.reportes.fecha_ini.value);"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>    
    
                                <!-- Include Date Range Picker -->
                                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
                                 
                                <script>
                                    $(document).ready(function(){
                                        var date_input=$('input[name="fecha_ini"]'); //our date input has the name "date"
                                        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                        date_input.datepicker({
                                            format: 'dd/mm/yyyy',
                                            container: container,
                                            todayHighlight: true,
                                            autoclose: true,
                                        })
                                    })

                                </script>
                            </div>
                     </div>
                     <!--div class="col-md-1" id="divBoton" style="display:block">
	                     <a href=""><img src="resources/imgs/img_calendar.png" width="80">	</a>
                     </div-->
                    
                </div>

                <div class="row">
	                <div class="col-md-2" >
                        
                    </div>
                </div>
                
                <br>
                <br>
                <div class="row">
	                <div class="col-md-12" id="divCitas" style="border:0px #00CCFF solid" >
                    
                    	
                    </div>
                </div>    

            </div>

		</div>

<!-- footer -->
<?php include("footer.php"); ?>
<!-- //footer -->


</body>
</html>

<?php
 //echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>";
 //exit; 
?>

<script>
function pasarCitaID(ID){
      document.getElementById('IdCita').value = ID ;  
    }

     $(document).on('click', '#verificar_cita', function() {
      $('#ModalVerificarCita').modal('show');
    });    
</script>

  

    <div id="ModalVerificarCita" class="modal fade " role="dialog" > 
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#efeff1; color:#333"> 
            <h4 class="modal-tittle">Definir si hubo asistencia a la cita</h4>
          </div> 
          <form class="form-horizontal" role="form" id="form-agregar" name="form_agregar">
            <br>
            <div class="modal-body col-md-12"> 

              <div class="row col-md-12" style="border:0px #FF0000 solid;  margin-bottom:20px; ">
                  <center><h1> Asistio el usuario a la cita ?</h1></center>
                            </div>                    
                    
              <div class="row col-md-12" style="border:0px #FF0000 solid;  margin:0 auto; ">
                                <div class="col-md-5">
                  <button type="button" id="CitaSI" class="btn btn-success btn-lg">Si asistio</button>
                              </div> 

                              <div class="col-md-5 col-sm-offset-2">
                  <button type="button" id="CitaNO" class="btn btn-danger btn-lg">No asistio</button>                             
                              </div> 
                            </div>                    
            
            </div>
            <br><br>
            <div class="modal-footer" style="background-color:#efeff1 ">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                <span class="glyphicon glyphicon-remove"></span><span class="hidden-xs"> Cerrar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>  

  <script language="javascript">
     $(document).on('click', '#CitaSI', function() {
        var id = document.getElementById('IdCita').value ;
        xajax_verificarCita(id,"SI");
        xajax_consultarCitas(document.reportes.fecha_ini.value);
        
    });    
     $(document).on('click', '#CitaNO', function() {
        var id = document.getElementById('IdCita').value ;
        xajax_verificarCita(id,"NO");
        document.reportes.submit() ;
    });    

  </script>