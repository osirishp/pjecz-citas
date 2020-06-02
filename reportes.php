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
$xajax->register(XAJAX_FUNCTION,'agregarCita');
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

  <style>
    .swal-button--confirm {
      background-color: #DD6B55;
    }
  </style>

        
        <div class="container" style="border:0px #CC0099 solid; background-color:#efeff1;padding-bottom:30px; padding-top:30px;">
        
            <div class="row ancho70" style="border:0px #FF0000 solid;">
    
    <!-- Inicio del Formulario -->
    
   
            <form name="reportes" id="reportes" action="registro.php" method="post">
                <input type="hidden" name="accion"/>
                <input type="hidden" name="id" id="id" />
    
    			
                
                <h3 class="hdg">Filtros</h3>
                <div class="row">
             
                    <div class="col-md-2">
                            <label for="">Fecha inicial</label>
                             <div class="book_date">
                                <div class="input-group date">
                                  <input type="text"  name="fecha_ini" id="fecha_ini" class="form-control" onChange="javascript:document.reportes.fecha_fin.value = this.value;"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
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
                    
                    <div class="col-md-2">
                            <label for="">Fecha final</label>
                             <div class="book_date">
                                <div class="input-group date">
                                  <input type="text"  name="fecha_fin" id="fecha_fin" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>    
    
                                <script>
                                    $(document).ready(function(){
                                        var date_input=$('input[name="fecha_fin"]'); //our date input has the name "date"
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
                     <div class="col-md-3">
                            <label for="servicio">Servicio</label>
                            <select name="servicio" id="servicio" class="form-control">
                                <option value=""></option>
                                <?php
                                $sql = "select * from cat_servicios order by servicio" ;
                                $query = mysqli_query($link,$sql);
                                while($datos = mysqli_fetch_assoc($query)){
                                    echo "<option value='$datos[id]'>$datos[servicio]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        
   
                </div><br>
                <br>
                <script language="javascript">
        					function screen(){
        						var reporte = document.getElementById('reporte').value ;
        						
        					}
        				</script>	
                
				<script language="javascript">
                    function nombres(fechaIni,fechaFin,tipo,servicio,div,url){
                        $.post(
                            url,
                            {
              								fechaIni:fechaIni,
              								fechaFin:fechaFin,
              								servicio:servicio,
              								tipo:tipo
              							},
                            function(resp){
                                $("#"+div+"").html(resp);	
                            }
                        );
                    }
                    
                
				function reportePDF(){
					
					var tipo = document.getElementById("reporte").value ;
					if(tipo=="General"){file = "generalPDF.php" ;}
					if(tipo=="Pacientes"){file = "pacientesPDF.php" ;}
					if(tipo=="Citas"){file = "citasPDF.php" ;}
					if(tipo=="Pagos"){file = "pagosPDF.php" ;}
					if(tipo=="Servicios"){file = "serviciosPDF.php" ;}
					document.reportes.action = file;
					document.reportes.submit();
				}
                </script>                
				
                
                
                <div class="row">
                    <div class="col-md-12" style="text-align:center">
	                     <a class='edit' onclick="reportePDF()" style="cursor:pointer; cursor:hand" />
							             <img src="resources/imgs/img_PDF.png" width="130">
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                       
                         <a class='edit' onclick="nombres(
                        									                 document.reportes.fecha_ini.value,
                                                            document.reportes.fecha_fin.value,
                                                            document.reportes.reporte.value,
                                                            document.reportes.servicio.value,
                                                            'myModal',
                                                            'reporteScreen.php'
                                                         )"  data-toggle='modal'  data-target='#myModal' href='javascript:void(0);' />
							               <img src="resources/imgs/img_HTML.png" width="120">
                        </a>
                    </div>
                </div>                
                
              <!-- Modal -->
              <div class="modal fade" id="myModal" role="dialog">

              </div>

                <div class="row">
                    <div class="col-md-6">
                       
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




  
