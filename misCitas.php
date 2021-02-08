<?php
include ("funciones.php");

error_reporting(0);
$link = conectarse();

$error="";

if(isset($_SESSION['nombreUsuario']) and $_SESSION['nombreUsuario'] != '')
{     
}
else
{    
    header("Location: index.php");  
}
?>
<!DOCTYPE html>
<html  style="background-color: #fff">
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


<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>    


<link href="resources/imgs/calendario.ico" rel="shortcut icon" type="image/x-icon" />
<script src='https://www.google.com/recaptcha/api.js'></script>

<!--Whats-->
<link href="resources/whats/floating-wpp.css" rel="stylesheet" type="text/css" media="all"/>
<script src="resources/whats/floating-wpp.js"></script>

<script>
            $(document).ready(function()
            {
                $('#mitabla').DataTable(
                {
                 "order":[[0, "asc"]],
                 "searching": false,
                 "lengthChange": false,
                 "info":     false,
                 "language":
                    {
                        "lengthMenu":"Mostrar _MENU_ Registros por pagina",
                        "info":"Mostrando pagina _PAGE_ de _PAGES_",
                        "infoEmpty":"No hay registros disponibles",
                        "infoFiltered":"(Filtrada de _MAX_ registros)",
                        "loadingRecords":"Cargando...",
                        "processing":"Procesando...",
                        "search":"Buscar:",
                        "zeroRecords":"No se encontraron registros",
                        "paginate":{
                            "next":"Siguiente",
                            "previous":"Anterior"},
                    }                    
                });
            });
</script>
<script type="text/javascript">

 $(function () {
     $('#helpButtonWhatsApp').floatingWhatsApp({
         headerTitle: "WhatsApp Chat PJECZ",
         phone: '5218442775774',
         popupMessage: 'Hola, ¿En qué puedo ayudarte?',
         message: "Hola soy <?php echo $_SESSION['nombreUsuario']; ?>, tengo un problema en ",
         showPopup: true,
         showOnIE: true,
         buttonImage: '<img src="resources/whats/whatsapp.svg"/>',
         size: '60px'
     });

     $('#helpButtonWhatsApp').append('<div class="text-center"> <label>Ayuda</label> </div>');
 });

</script>
<script type="text/javascript">

var registrarInactividad = function () {
    var t;
    window.onload = reiniciarTiempo;
    // Eventos del DOM
    document.onmousemove = reiniciarTiempo;
    document.onkeypress = reiniciarTiempo;
    document.onload = reiniciarTiempo;
    document.onmousemove = reiniciarTiempo;
    document.onmousedown = reiniciarTiempo; // aplica para una pantalla touch
    document.ontouchstart = reiniciarTiempo;
    document.onclick = reiniciarTiempo;     // aplica para un clic del touchpad
    document.onscroll = reiniciarTiempo;    // navegando con flechas del teclado
    document.onkeypress = reiniciarTiempo;

    function tiempoExcedido() {
       window.location.href = "cerrarSesion.php";
    }

    function reiniciarTiempo() {
        clearTimeout(t);
        t = setTimeout(tiempoExcedido, 900000) //15 min
        // 1000 milisegundos = 1 segundo
    }
};

registrarInactividad(); //Esto activa el contador

</script>
<body style="background-color: #fff">
<?php include("header.php"); ?>
<br>
<div id="helpButtonWhatsApp"> </div>
<div class="container" id="grad1">

        <div class="row">
            <div class="col-md-12">
                   <div class="text-right"><br>
                      <a href="steps.php" class="btn btn-secondary" style="color:white; margin-right: 50px; font-family: Arial">Agendar cita</a>
                    </div><br>
            </div>
        </div>


        <div class="row" >

            <?php
            $hoy=date("Y-m-d") ;
            $sqlPuesto="
            SELECT  concat(u.nombre,' ', u.apPaterno, ' ', u.apMaterno) as Nombre,
                        j.distrito as Distrito,
                        j.juzgado as Juzgado,
                        s.servicio, 
                        c.hora,
                        c.fecha,
                        c.detalles,
                        c.estatus,
                        c.id,
                       concat(c.expediente1,' ',c.expediente2,' ',c.expediente3, ' ',c.expediente4, ' ',c.expediente5)  as 'Expedientes'
                        from citas c 
                inner join usuario u on c.id_beneficiario = u.id
                inner join cat_servicios s on c.id_servicio = s.id
                    inner join juzgados j on j.id = c.id_juzgado
                where c.correo = '$_SESSION[correo]' and c.fecha>='$hoy'
                order by c.fecha asc";
                              
            $resultPuesto = mysqli_query($link,$sqlPuesto); 
                
            while($muestra = mysqli_fetch_array($resultPuesto)){ 
                extract($muestra);
                echo "
                    <div class='col-md-4' style='margin-bottom: 40px;'' >

                          <div class='card'>
                                <div class='card-body'>
                                    <div class='row'>
                                        <div class='col-md-8'><b>".
                                            convertirFecha($fecha)
                                        ."</b></div>
                                        <div class='col-md-4 text-right'><b>
                                            ".substr($hora,0,5)."
                                        </b></div>
                                    </div> 
                                    <br>
                                    <div class='row'>
                                        <div class='col-md-12'>
                                            <h5 class='card-title'>Distrito Judicial $Distrito</h5>
                                            <p class='card-text'><b>$Juzgado</b></p>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-footer' style='background-color:#efeff1'>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <button class='btn'  style='background-color:#687C96; color:#fff' data-toggle='modal' data-target='#modalDetalles' onclick='pasarDatos(\"$detalles\",\"$expedientes\",\"$Distrito\",\"$Juzgado\",\"$servicio\",\"$fecha\",\"$hora\")'>Ver detalles</button>
                                        </div>";
                                        if($estatus!='Cancelada'){
                                            echo"
                                        <div class='col-md-6'>
                                            <button class='btn' style='background-color:#f97d09; color:#fff'  onclick='cancelarCita($muestra[id])'>Cancelar Cita</button>
                                        </div>";
                                        }
                            echo "
                                    </div>
                                </div>
                            </div>
                
                    </div> ";
             } ?>
        </div>

</div>


<script>
    function pasarDatos(detalles,expedientes,distrito,juzgado,servicio,fecha,hora){
        document.getElementById('pDetalles').innerHTML = detalles ;
        document.getElementById('pExpedientes').innerHTML = expedientes ;
        document.getElementById('pDistrito').innerHTML = distrito ;
        document.getElementById('pJuzgado').innerHTML = juzgado ;
        document.getElementById('pServicio').innerHTML = servicio ;
        document.getElementById('pFecha').innerHTML = fecha ;
        document.getElementById('pHora').innerHTML = hora ;
        $("#modalDetalles").show();
    }

</script>



<!-- The Modal -->
<div class="modal" id="modalDetalles">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Detalles de la Cita</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        <p id="pDistrito"></p>
        <p id="pJuzgado"></p>
        <p id="pDetalles"></p>
        <p id="pExpedientes"></p>
        <p id="pServicio"></p>
        <p id="pFecha"></p>
        <p id="pHora"></p>
        <label for=""></label>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<?php
    $sql = "select * from encuesta where idUsuario = '$_SESSION[usuarioId]' " ;

    if ($query = mysqli_query( $link, $sql )){ 
    $registros = mysqli_num_rows($query) ;
    $datos = mysqli_fetch_assoc($query) ;
                   
    if($registros==0 ){  ?>

                                <div class="modal" id="modalEncuesta" style="overflow-y: scroll;">
                                  <div class="modal-dialog " >
                                    <div class="modal-content" style="width:700px; max-height: 600px;">

                                      <div class="modal-body">
                                        <iframe id="frameEncuesta" scrolling="auto" src="https://docs.google.com/forms/d/e/1FAIpQLSd5VUKoFkwUiTtdmG39I4n27BZKpQypI0Y_1W9ctyHDUlY7oA/viewform?usp=sf_link" width="640" height="500" frameborder="0" marginheight="0" marginwidth="0"></iframe>
                                      </div>
                                      <div class="modal-footer">
                                        <button id="botonCerrar" style="display:none" type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarEncuesta()">Cerrar</button>

                                      </div>
                                    </div>
                                    
                                  </div>
                                </div>

                                <div class="modal" id="modalPregunta" style="position:fixed; top:30%">
                                  <div class="modal-dialog " >
                                    <div class="modal-content" style="width:500px; max-height: 500px;">
                                      <div class="modal-header text-white" style="background-color: #495769;">
                                        <p class="h3">Nos interesa su opinión</p>
                                      </div>
                                      <div class="modal-body" >
                                        <p>Estimado(a): <?php echo $_SESSION['nombreUsuario']; ?>
                                            <br>
                                            <br>
                                            En el Poder Judicial del Estado de Coahuila buscamos mejorar las herramientas digitales que ponemos a su servicio. Con este propósito le solicitamos contestar una breve encuesta.</p>  
                                            <div class="row">
                                              <div class="col-md-6">
                                                <button type="button" class="btn btn-danger"  onclick="declinarEncuesta();">Declinar encuesta</button>
                                              </div>   
                                              <div class="col-md-6">
                                                <button type="button" class="btn btn-success"  onclick="aceptarEncuesta()">Aceptar encuesta</button>
                                              </div>
                                            </div> 
                                      </div>
           
                                    </div>
                                    
                                  </div>
                                </div>
<?php  }}
?>
  <!--div class="card" style="border:0px solid #163b67">
    <div class="card-body text-center" >
        <div class="row justify-content-center" >
            <div class="col-md-12" >

                <div class="card px-0 pt-4" style="border:0px solid #163b67">
                     <div class="row">
                                        <div class="col-md-12">
                                               <div class="text-right"><br>
                                                  <a href="steps.php" class="btn btn-secondary" style="color:white; margin-right: 50px;">Agendar cita</a>
                                                </div>       <br>   
                                        </div>
                                      </div>
                    <h2><strong>Mis citas</strong></h2>
                    <div class="row text-center">
                        <div class="col-md-12 text-center">
                            <form id="formMisCitas" method="POST">                                
                                <div class="container text-left">
                                    <br>
                                <fieldset>
                                      <div class="row">
                                        <div class="col-md-12 ">
                    
                                        <table id="mitabla" class="table table-striped table-bordered responsive " style="width:100%">
                                            <thead style="background-color:#46576B; color:#fff">
                                                <tr>
                                                    <th class="text-center">Acción</th>
                                                    <th class="text-center">Juzgado</th>
                                                    <th class="text-center">Servicio</th>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Hora</th>
                                                    <th class="text-center">Expedientes/Folios</th>
                                                    <th class="text-center">Detalles</th>
                                                    <th class="text-center">Estatus</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                
                                                <?php
                                                $sqlPuesto="
                                                SELECT  concat(u.nombre,' ', u.apPaterno, ' ', u.apMaterno) as Nombre,
                                                            concat(j.distrito,', ', j.juzgado) as Juzgado,
                                                            s.servicio, 
                                                            c.hora,
                                                            c.fecha,
                                                            c.detalles,
                                                            c.estatus,
                                                            c.id,
                                                           concat(c.expediente1,' ',c.expediente2,' ',c.expediente3, ' ',c.expediente4, ' ',c.expediente5)  as 'Expedientes'
                                                            from citas c 
                                                    inner join usuario u on c.id_beneficiario = u.id
                                                    inner join cat_servicios s on c.id_servicio = s.id
                                                        inner join juzgados j on j.id = c.id_juzgado
                                                    where c.correo = '$_SESSION[correo]' ";
                                                                  
                                                $resultPuesto = mysqli_query($link,$sqlPuesto); 
                                                    
                                                while($muestra = mysqli_fetch_array($resultPuesto)){ ?>
                                                
                                                    <tr>
                                                        <td>
                                                            <?php if($muestra['fecha']>date("Y-m-d") and $muestra['estatus']!='Cancelada'){ ?>
                                                                <a href='javascript:void(0)' onclick="cancelarCita(<?php echo $muestra['id'];?>)">Cancelar cita</a>

                                                            <?php } ?>
                                                        </td>
                                                        <td align="center"><?php echo ($muestra['Juzgado']);?></td>
                                                        <td align="center"><?php echo ($muestra['servicio']);?></td>
                                                        <td align="center"><?php echo ($muestra['fecha']);?></td>
                                                        <td align="center"><?php echo ($muestra['hora']);?></td>
                                                        <td align="center"><?php echo ($muestra['Expedientes']);?></td>
                                                        <td align="center"><?php echo ($muestra['detalles']);?></td>
                                                        <td align="center"><?php echo ($muestra['estatus']);?></td>
                                                       
                                                     </tr>    
                                                
                                                <?php } ?>
                                            </tbody>
                                        </table>
    
            </div>
                                      </div>
                                     
                                </fieldset>
                                 </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- termina CARD Body -->
</div>


<script>
    function cancelarCita(id){

          swal({
              title: "¿ Esta seguro de cancelar la cita?",
              text: "Una vez eliminada la cita no se podrá restaurar.",
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
                  text: '',
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

</script>

<script>
//Modales encuesta
var contador = 0 ; 
document.getElementById('frameEncuesta').onload = function() {
    contador = contador +1 ;
        if(contador==2){
            document.getElementById("botonCerrar").style.display ="block" ;
                $.ajax({
                url : "funcionesEncuesta.php",
                type : "post" ,
                data :  {"accion" : "aceptar encuesta"},
                success: function(){}
                        });
                        }
}
                            
$(document).ready(function() {
$("#modalPregunta").modal({
    backdrop: 'static',
    keyboard: false
    }) ;
});

function declinarEncuesta(){
     
     $.ajax({
                url : "funcionesEncuesta.php",
                type : "post" ,
                data :  {"accion" : "declinar encuesta"},
                success: function(){
                    console.log("holalaa");
                    $("#modalPregunta").modal('hide');
                }
                        });
    
}

function aceptarEncuesta(){
    $("#modalPregunta").modal('hide');
    $("#modalEncuesta").modal({
        backdrop: 'static',
        keyboard: false 
        }); 
}

function cerrarEncuesta(){
    $("#modalEncuesta").modal('hide');
}
                    
</script>

<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>