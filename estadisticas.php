<?php
include ("funciones.php");
$link = conectarse();

if($_SESSION['correo']!="administrador@pjecz.gob.mx"){header("location:index.php"); exit;}
error_reporting(0);

include_once("funciones_ajax.php") ;
//inclumos la clase ajax
require('resources/xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax
$xajax = new xajax();
/* $xajax->decodeUTF8InputOn(); */
//asociamos la funcin creada anteriormente al objeto xajax
$xajax->register(XAJAX_FUNCTION,'consultarCitas_x_servicio');
$xajax->register(XAJAX_FUNCTION,'consultarCitas_x_juzgado');
$xajax->register(XAJAX_FUNCTION,'consultarCitasProgras');
$xajax->register(XAJAX_FUNCTION,'consultarCitasPrograsAge');
$xajax->register(XAJAX_FUNCTION,'consultarCitasPrograsCan');
$xajax->register(XAJAX_FUNCTION,'consultarHorasBloq');
$xajax->register(XAJAX_FUNCTION,'consultarHorasBloq_x_juzgado');



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

  function ocultarDivs()
  {
    document.getElementById('divCitas').style.display = "none" ;
    document.getElementById('divCitas_x_juzgado').style.display = "none" ;
    document.getElementById('divCitasProgras').style.display = "block" ;
    document.getElementById('divCitasPrograsAge').style.display = "block" ;
    document.getElementById('divCitasPrograsCan').style.display = "block" ;
    document.getElementById('divHorasBloq').style.display = "none" ;
  }

  function VerDivs()
  {
    document.getElementById('divCitas').style.display = "block" ;
    document.getElementById('divCitas_x_juzgado').style.display = "block" ;
    document.getElementById('divCitasProgras').style.display = "none" ;
    document.getElementById('divCitasPrograsAge').style.display = "none" ;
    document.getElementById('divCitasPrograsCan').style.display = "none" ;
    document.getElementById('divHorasBloq').style.display = "none" ;
  }
	
  function VerDivsHoras()
  {
    document.getElementById('divCitas').style.display = "none" ;
    document.getElementById('divCitas_x_juzgado').style.display = "none" ;
    document.getElementById('divCitasProgras').style.display = "none" ;
    document.getElementById('divCitasPrograsAge').style.display = "none" ;
    document.getElementById('divCitasPrograsCan').style.display = "none" ;
    document.getElementById('divHorasBloq').style.display = "block" ;
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

        <div class="container" style="border:0px #CC0099 solid; background-color:#efeff1;padding-bottom:30px; padding-top:30px; min-height: 600px;">
        
            <div class="row ancho70" style="border:0px #FF0000 solid;">
    
    <!-- Inicio del Formulario -->
    
   
                <input type="hidden" name="IdJuzgadoId" id="IdJuzgadoId" value="">
                <!-- <h3 class="hdg">Consulta</h3> -->
                <div class="row">
             
                    <div class="col-md-12">
                            <label for="">Dias a consultar</label>
                             <div class="book_date">
                             <div class="row">
                               <div class="col-md-2">
                                <div class="input-group date">
                                  <input type="text"  name="fecha_ini" id="fecha_ini" class="form-control" onChange=""><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div> 
                               </div>
                               <div class="col-md-2">
                                <div class="input-group date">
                                  <input type="text"  name="fecha_fin" id="fecha_fin" class="form-control" onChange=""><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
<!-- onChange="javascript:xajax_consultarCitas_x_servicio(document.getElementById('fecha_ini').value,this.value);xajax_consultarCitas_x_juzgado(document.getElementById('fecha_ini').value,this.value);VerDivs()" -->

                                </div> 
                               </div>
                            </div>  

                            <div class="row">
                                <div class="col-md-2">
                                  <button type='button' id='btnAlta' name='btnAlta' class='btn btn-danger' style="background-color: #163B67;" onclick="javascript:xajax_consultarCitas_x_servicio(document.getElementById('fecha_ini').value,document.getElementById('fecha_fin').value);xajax_consultarCitas_x_juzgado(document.getElementById('fecha_ini').value,document.getElementById('fecha_fin').value);VerDivs()"> Citas Dadas de alta</button>
                               </div>
                               <div class="col-md-2">
                                  <button type='button' id='btnProgras' name='btnProgras' class='btn btn-success' style="background-color: #163B67;" onclick="javascript:xajax_consultarCitasProgras(document.getElementById('fecha_ini').value, document.getElementById('fecha_fin').value);javascript:xajax_consultarCitasPrograsAge(document.getElementById('fecha_ini').value, document.getElementById('fecha_fin').value);javascript:xajax_consultarCitasPrograsCan(document.getElementById('fecha_ini').value, document.getElementById('fecha_fin').value);ocultarDivs()"> Citas Programadas</button>
                               </div>
                               <div class="col-md-2">
                                  <button type='button' id='btnHoras' name='btnHoras' class='btn btn-success' style="background-color: #163B67;" onclick="javascript:xajax_consultarHorasBloq(document.getElementById('fecha_ini').value, document.getElementById('fecha_fin').value); VerDivsHoras();">Sesiones bloqueadas</button>
                               </div> 
                            </div>  
                                <!-- Include Date Range Picker -->
                                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
                                 
                                <script>
                                    $(document).ready(function(){
                                        var date_input=$('input[name="fecha_ini"]'); //our date input has the name "date"
                                        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                        date_input.datepicker({
                                            format: 'yyyy-mm-dd',
                                            container: container,
                                            todayHighlight: true,
                                            autoclose: true,
                                        })
                                    })

                                     $(document).ready(function(){
                                        var date_input2=$('input[name="fecha_fin"]'); //our date input has the name "date"
                                        var container2=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                        date_input2.datepicker({
                                            format: 'yyyy-mm-dd',
                                            container2: container2,
                                            todayHighlight: true,
                                            autoclose: true,
                                        })
                                    })
                                </script>
                            </div>
                     </div>
                </div>
              <div class="row">
	              <div class="col-md-12" id="divCitas" style="border:0px #00CCFF solid" ></div>
              </div>   
              <div class="row">
                <div class="col-md-12" id="divCitas_x_juzgado" style="border:0px #00CCFF solid" ></div>
              </div>
              <div class="row">
                <div class="col-md-12" id="divCitasProgras" style="border:0px #00CCFF solid" ></div>
                <div class="col-md-12" id="divCitasPrograsAge" style="border:0px #00CCFF solid" ></div>
                <div class="col-md-12" id="divCitasPrograsCan" style="border:0px #00CCFF solid" ></div>
              </div>
              <div class="row">
                <div class="col-md-12" id="divHorasBloq" style="border:0px #00CCFF solid" ></div>
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
function VerJuzgadoId(ID){
      document.getElementById('IdJuzgadoId').value = ID ;  
    }

     $(document).on('click', '#verJuzgado', function() {
      $('#ModalConsultarSesiones').modal('show');
      xajax_consultarHorasBloq_x_juzgado(document.getElementById('fecha_ini').value, document.getElementById('fecha_fin').value, document.getElementById('IdJuzgadoId').value);
    });    
</script>

    <div id="ModalConsultarSesiones" class="modal fade " role="dialog"> 
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#efeff1; color:#333"> 
            <h4 class="modal-tittle">Fecha y Hora de las sesiones bloqueadas</h4>
          </div> 
          <form class="form-horizontal" role="form" id="form-agregar" name="form_agregar">
            <div class="modal-body col-md-12" >   
            <!--  <input type="text"  name="fecha_ini" id="fecha_ini" class="form-control" onfocus ="javascript:xajax_consultarHorasBloq_x_juzgado(document.getElementById('fecha_ini').value, document.getElementById('fecha_fin').value, document.getElementById('IdJuzgadoId').value);"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span> -->

                <div class="col-md-12" id="divHorasBloqXjuzgado" style="border:0px #00CCFF solid" ></div>
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




  
