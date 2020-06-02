
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
$xajax->register(XAJAX_FUNCTION,'juzgados');
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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
 
<style>
  label{ text-decoration: none; font-weight: normal ; }
</style>  
<body>


<br>
<div class="container">
<div class="card">

    <div class="card-header">

        <div class="bannerx" >
          <div class="container">
            <div class="row">
              <div class="col-md-3" style="padding:0px 50px;">
                <img src='resources/imgs/logo_pjecz.png' width="250"> 
              </div>
              <div class="col-md-9 text-right" style="padding: 0px 50px;"><br>
                <span style="font-size: 2em">:: Sistema de Citas ::</span>  
                <br><br>
                <h4 style="font-size: 1em"><?php echo utf8_encode($_SESSION['juzgado']) ; ?></h4>
              </div>
            </div>
          </div>
        </div>

    </div>

    <div class="card-body">


        <form>
          
            <div class="container">
              <div class="row">
               <div class="col-md-4">
                        <div class="form-group">
                          <label for="distrito">Distrito</label> 
                          <div>
                            <select id="distrito" name="distrito" required="required" class="custom-select"  onchange="xajax_juzgados(this.value)">
                              <option value=""></option>
                              <?php 
                              $sql = "select distrito from juzgados group by distrito order by distrito" ;
                              $query = mysqli_query($link, $sql ); 
                              while($datos = mysqli_fetch_assoc($query)){
                                echo "<option value='$datos[distrito]'>$datos[distrito]</option>" ;
                              }
                                ?>
                              
                            </select>
                          </div>
                        </div>
                </div>
                <div class="col-md-8">
                        <div class="form-group">
                          <label for="juzgado">Juzgado</label> 
                          <div id="divJuzgados">
                            <select name="juzgado" id="juzgado" class="custom-select" required="required">
                             
                            </select>
                          </div>
                        </div>
                </div>
              </div>

              <div class="row">
                  <div class="col-md-4">
                        <div class="form-group">
                          <label for="text">Calendario</label> 
                          <div class="col-md-12"> 
                                <?php  include('miniCalendar.php') ;?>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-8">
                    <label for="text7">Horarios disponibles</label> 
                      <div class="row" id="divHoras" style="padding: 30px;">
                        
                      </div>
                  </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                          <label for="text1">Nombre</label> 
                          <input id="text1" name="text1" type="text" class="form-control" required="required">
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                          <label for="text2">Paterno</label> 
                          <input id="text2" name="text2" type="text" class="form-control" required="required">
                        </div>
                          </div>
                <div class="col-md-4">
                        <div class="form-group">
                          <label for="text3">Materno</label> 
                          <input id="text3" name="text3" type="text" class="form-control" required="required">
                        </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                       <div class="form-group">
                          <label for="text4">Correo electrónico</label> 
                          <input id="text4" name="text4" type="text" class="form-control" required="required">
                        </div>          
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                          <label for="text5">Teléfono de contacto</label> 
                          <input id="text5" name="text5" type="text" class="form-control" required="required">
                        </div>          
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                          <label for="text6">Domicilio</label> 
                          <input id="text6" name="text6" type="text" class="form-control">
                        </div>          
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                          <label for="text7">Ciudad</label> 
                          <input id="text7" name="text7" type="text" class="form-control">
                        </div>          
                </div>
              </div>
              
              <div class="row">
                 <div class="col-md-4">
                        <div class="form-group">
                          <label for="select">Servicio</label> 
                          <div>
                            <select id="select" name="select" required="required" class="custom-select">
                              <option value="">Revisión de expedientes</option>
                              <option value="">Tramitación de oficios</option>
                              <option value="">Tramitación de edictos</option>
                              <option value="">Citas con actuarios</option>
                              <option value="">Expedición de copias certificadas</option>
                              <option value="">Devolución de documentos</option>
                              <option value="">Entregas de cheques y certificados de depósito</option>
                            </select>
                          </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                          <label for="textarea">Nota</label> 
                          <textarea id="textarea" name="textarea" cols="40" rows="3" class="form-control"></textarea>
                        </div> 
                </div>

              </div>

                        <div class="form-group">
                          <button name="submit" type="submit" class="btn btn-secondary">Agendar cita</button>
                        </div>
            </div>
          </form>
      </div>

      <div class="card-footer">
        
      </div>
  </div> <!-- termina card -->

</div>

</body>
</html>

<script>
  function seleccionarDia(dia){

        $.ajax({
        type: "POST",
        url: "funcionesJS.php", 
        data: { accion: "buscar horas", dia: dia, juzgado: document.getElementById('juzgado').value },
        dataType : "json",
        success: function(respuesta) {
            var html = "" ;
            // Obteniendo todas las claves del JSON
              for (var clave in respuesta){
                // Controlando que json realmente tenga esa propiedad
                  for( var datos in  respuesta[clave] ){
                      
                      //alert(datos) ; llave
                      //alert(respuesta[clave][datos] ); valor
                      
                      if(datos == "horario") {
                        horarioRojo = '<div class="col-md-2 horaOcupada" > ' + respuesta[clave][datos] + '</div>' ;
                        horarioVerde = '<div class="col-md-2 horaLibre" ><a href="javascript:void(0)" onclick="asignarHora(' + respuesta[clave][datos] + ')" class="urlHora" > ' + respuesta[clave][datos] + ' </a> </div>' ;
                      }
                      if(datos == "citas"){
                        
                        if(respuesta[clave][datos]>0){
                          html = html + horarioRojo ;
                        }
                        else{
                          html = html + horarioVerde ;
                        }
                      }

                    //if (respuesta.hasOwnProperty(clave)) {
                      // Mostrando en pantalla la clave junto a su valor
                     // alert("La clave es " + clave + " y el valor es " + respuesta[clave]);
                    //}
                    
                  }

              }
              document.getElementById('divHoras').innerHTML =  html  ;             
        }
    });
   
  }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>