<?php
include ("funciones.php");
//echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>"; 
$link = conectarse();
//$seguridad=seguridad();
error_reporting(0);
if(!isset($_SESSION['rolId']) or $_SESSION['rolId']!=1 ){header("location:index.php") ;}

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

<link rel="stylesheet" href="resources/css/steps.css">
<script src="resources/js/steps.js"></script>

<!-- MultiStep Form -->
<body>
    
<br>
<div class="container" id="grad1">
    <div class="card" style="border:1px solid #555">

      <div class="card-header" style="background-color: #3DD481">

          <div class="bannerx" >
            <div class="container">
              <div class="row">
                <div class="col-md-3" style="padding:0px 50px;">
                  <img src='resources/imgs/logo_pjecz.png' width="250"> 
                </div>
                <div class="col-md-9 text-right" style="padding: 0px 50px;"><br>
                  <span style="font-size: 2em; color: #fff">:: Sistema de Citas ::</span>  
                  <br><br>
                  <h4 style="font-size: 1em"><?php echo utf8_encode($_SESSION['juzgado']) ; ?></h4>
                  <a href="index.php" class="btn btn-secondary">Cerrar sesión</a>
                </div>
              </div>
            </div>
          </div>

      </div>

      <div class="card-body">

          <div class="row justify-content-center mt-0" >
              <div class="col-11 col-sm-9 col-md-7 col-lg-10 text-center p-10 mt-3 mb-2">
                  <div class="card px-0 pt-4 pb-10 mt-3 mb-3">
                      <h2><strong>Registro de Citas</strong></h2>
                      <p>Llena los datos que se solicitan en cada paso</p>
                      <div class="row">
                          <div class="col-md-12 mx-0">
                              <form id="msform" name="msform" method="post">
                                <input type="hidden" id="fechaSeleccionada" name="fechaSeleccionada">
                                <input type="hidden" id="horaSeleccionada" name="horaSeleccionada">
                                <input type="hidden" id="accion" name="accion">
                                <input type="hidden" id="correo" name="correo" value="<?php echo $_SESSION['correo']; ?>">
                                <input type="hidden" id="ultimoID" name="ultimoID" value="">
                                <input type="text" class="text-center" style=" background-color: #3DD481 ;color:#fff;" value="<?php echo strtoupper($_SESSION['nombreUsuario']) ; ?> " disabled>
                                  <!-- progressbar -->
                                  <ul id="progressbar">
                                      <li class="active" id="account"><strong>Distrito y Juzgado</strong></li>
                                      <li id="personal"><strong>Tipo de Trámite</strong></li>
                                      <li id="payment"><strong>Fecha y Hora</strong></li>
                                      <li id="details"><strong>Detalles de la Cita</strong></li>
                                      <li id="confirm"><strong>Confirmar Cita</strong></li>
                                  </ul> <!-- fieldsets -->
                                  
                                  <div class="container text-left">
                                      <br>
                                      <fieldset>
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div class="form-group">
                                                    <label for="distrito">Distrito</label> 
                                                    
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
                                          <div class="row">
                                              <div class="col-md-12">
                                                      <div class="form-group">
                                                        <label for="juzgado">Juzgado</label> 
                                                        <div id="divJuzgados">
                                                          <select name="juzgado" id="juzgado" class="custom-select" required="required" onchange="javascript: llenarCampos($('select[name=\'juzgado\'] option:selected').text() ,'det_juzgado') ;paso1();">
                                                           
                                                          </select>
                                                        </div>
                                                      </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              
                                          </div>
                                          <input type="button" name="next" class="next action-button float-right" value="Siguiente" id="botonSiguiente1" style="display:none ;" />
                                      </fieldset>
                                  <fieldset>
                                    <div class="row">
                                      <div class="col-md-5">
                                        <label for="servicio">Tipo de trámite</label>
                                        <select name="servicio" id="servicio" class="form-control" onchange="javascript:llenarCampos($('select[name=\'servicio\'] option:selected').text() , 'det_servicio') ; validarTramite(this.value)">
                                          <option value=""></option>
                                          <?php 
                                          /*$sqlS = "select * from cat_servicios order by servicio";
                                          $queryS = mysqli_query($link,$sqlS);
                                          while($datosS = mysqli_fetch_assoc($queryS)){
                                              echo "<option value='$datosS[id]'>$datosS[servicio]</option>";
                                          } */?>
                                        </select>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label for="">Expedientes</label>
                                            <input type="text" name="expediente1" id="expediente1" placeholder="Ej. 167/2020" onblur="llenarCampos(this.value,'det_expedientes');validarTramite(this.value)">
                                            <input type="text" name="expediente2" id="expediente2" placeholder="Ej. 675/2020" onblur="llenarCampos(this.value,'det_expedientes');validarTramite(this.value)">
                                            <input type="text" name="expediente3" id="expediente3" placeholder="Ej. 4654/2020" onblur="llenarCampos(this.value,'det_expedientes');validarTramite(this.value)">
                                            <input type="text" name="expediente4" id="expediente4" placeholder="Ej. 874/2020" onblur="llenarCampos(this.value,'det_expedientes');validarTramite(this.value)">
                                            <input type="text" name="expediente5" id="expediente5" placeholder="Ej. 9244/2020" onblur="llenarCampos(this.value,'det_expedientes');validarTramite(this.value)">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-7">
                                        <label for="detalles">Detalles del asunto al que asiste</label>
                                        <textarea name="detalles" id="detalles" cols="30" rows="5" class="form-control" style="font-weight: lighter; background-color: #fafafa" onchange="llenarCampos(this.value,'det_detalles')" ></textarea>
                                      </div>
                                    </div>
                                    <br>
                                      <input type="button" name="previous" id="botonAnterior2" class="previous action-button-previous float-left" value="Anterior"   />
                                      <input type="button" name="next" id="botonSiguiente2" class="next action-button float-right" value="Siguiente" style="display: none;" />


                                  </fieldset>
                                  <fieldset>
                                       <div class="row">
                                            <div class="col-md-4">
                                              
                                                  <div class="form-group">
                                                    
                                                    <div class="col-md-12"> 
                                                          <?php  include('miniCalendar.php') ;?>
                                                    </div>
                                                </div>
                                                  
                                            </div>
                                            <div class="col-md-7 offset-md-1">
                                                <div class="row">
                                                  <div class="col-md-12 text-center">
                                                      <label for="" id="labelFechaSeleccionada" ></label>    
                                                  </div>
                                                </div>
                                                
                                                <div class="row" id="divHoras" style="padding: 30px;">
                                                  
                                                </div>
                                            </div>
                                        </div> 
                                    <input onclick="javascript:" type="button" name="previous" id="botonAnterior3" class="previous action-button-previous float-left" value="Anterior" /> 
                                    <input type="button" name="make_payment" id="botonSiguiente3" class="next action-button float-right" style="width: 150px;display: none" value="Siguiente" onclick=""  />  
                                  </fieldset>

                                  <fieldset>
                                       <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Juzgado</label>
                                                  <div class="form-group">
                                                    <input type="text" name="det_juzgado" id="det_juzgado" readonly="readonly">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Trámite</label>
                                                  <div class="form-group">
                                                    <input type="text" name="det_servicio" id="det_servicio" readonly="readonly">
                                                  </div>
                                            </div>
                                             <div class="col-md-8">
                                                <label for="">Expedientes</label>
                                                  <div class="form-group">
                                                    <input type="text" name="det_expedientes" id="det_expedientes" readonly="readonly">
                                                  </div>
                                            </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-7">
                                          <label for="detalles">Detalles del trámite</label>
                                          <textarea name="det_detalles" id="det_detalles" cols="30" rows="2" readonly="readonly"></textarea>
                                        </div>
                                        <div class="col-md-3">
                                          <label for="detalles">Fecha</label>
                                          <input type="text" name="det_fecha" id="det_fecha" readonly="readonly">
                                        </div>
                                        <div class="col-md-2">
                                          <label for="detalles">Hora</label>
                                          <input type="text" name="det_hora" id="det_hora" readonly="readonly">
                                        </div>
                                      </div>
                                    <input onclick="javascript:limpiarHorarios();" type="button" name="previous" id="botonAnterior4" class="previous action-button-previous float-left" value="Anterior" /> 
                                    <input type="button" name="datos_cita" id="botonSiguiente4" class="next action-button float-right" style="width: 150px;display: block" value="Confirmar Cita" onclick="confirmarCita()"  />  
                                  </fieldset>                                  

                                  <fieldset>
                                      
                                          <h2 class="fs-title text-center">Registro!</h2> <br><br>
                                          <div class="row justify-content-center">
                                              <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                          </div> <br><br>
                                          <div class="row justify-content-center">
                                              <div class="col-7 text-center">
                                                  <h5>Cita Agendada correctamente</h5>
                                                  <p> Se ha enviado un correo electrónico a su cuenta registrada, con todos los datos de la cita agendada.</p>
                                              </div>
                                          </div>
                                     <input type="button" name="previous" id="botonNuevaCita" class="action-button-previous float-left" style="width: 200px;" value="Registrar nueva Cita" onclick="javascript:window.location.reload();" /> 
                                    <input type="button" name="make_payment" id="botonCerrarSesion" class="next action-button float-right" style="width: 150px;" value="Cerrar Sesión" onclick="javascript:window.location.href = 'https://www.pjecz.gob.mx';" />  
                                  </fieldset>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    
      </div> <!-- termina CARD Body -->

      <!--div class="card-footer">
      </div-->     

    </div>

</div>


<script>
  function llenarTramites(){
      var juzgado = $('select[name=\'juzgado\'] option:selected').text() ;
      $.ajax({
              url : "funcionesJS.php" ,
              type :  "post" ,
              data : {"accion" : "buscar juzgados" , "juzgado" : juzgado },
              dataType : "json",
              success: function(respuesta){

                 var select = document.getElementById('servicio');
                  while (select.length > 0) {
                    select.remove(0);
                  }
                  var option =  document.createElement('option') ; 
                  option.text = '' ;
                  option.value = '' ;
                  select.add(option) ;

                        for (var clave in respuesta){
                                for( var datos in  respuesta[clave] ){
                                    if(datos=='id_servicio'){var id = respuesta[clave][datos] ;}
                                    if(datos=='servicio'){ 
                                          var servicio = respuesta[clave][datos] ; 
                                          var option =  document.createElement('option') ; 
                                          option.text = servicio ;
                                          option.value = id ;
                                          select.add(option) ;
                                     }
 
                                    //alert(datos) ; // llave
                                    //alert(respuesta[clave][datos] );  //valor
                                }

                            }
              }    

      });
  }

   function llenarCampos(valor,campo) {
    document.getElementById(campo).value = valor ;
    if(campo=='det_expedientes'){
      document.getElementById(campo).value = document.getElementById('expediente1').value + " | " + document.getElementById('expediente2').value + " | " + document.getElementById('expediente3').value + " | " + document.getElementById('expediente4').value + " | " + document.getElementById('expediente5').value ;
    }
   }

  function limpiarHorarios(){
    document.getElementById('divHoras').innerHTML =  '' ;  
    document.getElementById("labelFechaSeleccionada").innerHTML ='';
  }
  
  function confirmarCita(){
    var form = $("#msform") ;
    document.getElementById("accion").value = "guardar cita" ;
    $.ajax({
        url : "funcionesJS.php",
        type :"post" ,
        data : form.serialize() ,
        success: function(respuesta){
          document.getElementById('ultimoID').value = respuesta ;
          document.getElementById("accion").value = "enviar correo" ,
          $.ajax({
            url : "funcionesJS.php" ,
            type : "post" ,
            data : form.serialize(),
            success : function(respuesta){
               //* avanzar a siguiente paso *//
               //alert(respuesta) ;
            }
          })
        }
    });
  }


  function paso1(){
    if(document.getElementById("distrito").value!="" && document.getElementById("juzgado").value!=""){
      llenarTramites();
      document.getElementById("botonSiguiente1").style.display =  " block " ;
    }
    else{
      document.getElementById("botonSiguiente1").style.display =  " none " ;
    }
  }
  function paso2(valor){
      
      document.getElementById("botonSiguiente2").style.display =  valor ;
  }
  
  function paso3(){
      document.getElementById("botonSiguiente3").style.display =  " block " ;
  }

  function validarTramite(valor){
    
      paso2((valor!="" && document.getElementById('expediente1').value!='' ) ? " block " : " none " ) ;
    
  }
  function asignarHora(hora,elemento){
    document.getElementById("horaSeleccionada").value = hora ;
    document.getElementById('det_hora').value = hora ;
    
    $(".urlHoraSelected").removeClass("urlHoraSelected") ;
    $(elemento).addClass('urlHoraSelected') ;
    paso3();
  }

  function seleccionarDia(dia){
        var correo = document.getElementById("correo").value ;
        document.getElementById("fechaSeleccionada").value = dia ;
        document.getElementById("det_fecha").value = dia ;
        

        $.ajax({
            type: "POST",
            url: "funcionesJS.php", 
            data: { accion: "buscar beneficiario", correo : correo , dia: document.getElementById('fechaSeleccionada').value ,  juzgado: document.getElementById('juzgado').value },
    
            success: function(respuesta) {
            
                if(respuesta>=1){
                  swal("Ya tiene registrada 1 cita en este Juzgado y esta fecha ","Seleccione otra fecha","warning") ;
                  document.getElementById('divHoras').innerHTML =  '' ;  
                  document.getElementById("labelFechaSeleccionada").innerHTML ='';
                }       
                else{
                  
                   // ***************************** SELECCIONAR HORARIOS DISPONIBLES *************************// 
                      var partes = dia.split('-') ;
                      var f=new Date(partes[0],parseInt(partes[1])-1,partes[2]);

                      var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                      var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                      
                      document.getElementById("labelFechaSeleccionada").innerHTML = (diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear());

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
                                      horarioVerde = '<div class="col-md-2 horaLibre" ><a href="javascript:void(0) ;" onclick="asignarHora(\'' + respuesta[clave][datos] + '\' , this)" class="urlHora" > ' + respuesta[clave][datos] + ' </a> </div>' ;
                                    }
                                    if(datos == "citas"){
                                      
                                      if(respuesta[clave][datos]>1){ /* mas de # citas por hora */
                                        html = html + horarioRojo ;
                                      }
                                      else{
                                        html = html + horarioVerde;
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
                   // ***************************** FINALIZA SELECCIONAR HORARIOS DISPONIBLES *************************//
                } 
            }
        });
                    

                      
        
   
  }


    function buscarBeneficiario(correo){

        $.ajax({
            type: "POST",
            url: "funcionesJS.php", 
            data: { accion: "buscar beneficiario", correo : correo , dia: document.getElementById('fechaSeleccionada').value ,  juzgado: document.getElementById('juzgado').value },
    
            success: function(respuesta) {
            
                if(respuesta>=2){
                  swal("Ya tiene registradas 2 citas en el dia seleccionado","Seleccione otra fecha","info") ;
                  return false ;
                }       
                else{
                  return true ;
                } 
            }
        });
   
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>