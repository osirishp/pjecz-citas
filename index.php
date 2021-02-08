<?php
include ("funciones.php");
$link = conectarse();
 //session_start();
  $error="";

  if(isset($_POST['btnSesion']))
{
    $Usuario=$_POST['txtUsuario'];
    $Contra=MD5($_POST['txtContra']); //hp
 
    $sqlUsuario =  "select j.distrito, j.juzgado, u.*, d.distrito as DISTRITO ,concat(u.nombre,' ',u.apPaterno,' ', u.apMaterno) as Nombre from usuario u
                      left join juzgados j on u.id_juzgado = j.id
                      left join distritos d on u.id_distrito = d.id
                    where u.email = '$Usuario' and u.password = '$Contra'";
   // echo $sqlUsuario ; exit;
    $resultUsuario = mysqli_query($link,$sqlUsuario);
    $row = mysqli_fetch_array($resultUsuario,MYSQLI_ASSOC);
    $TotalUsu = mysqli_num_rows($resultUsuario);

    $rolId = $row['idRol'];
    $nombreCompleto = utf8_encode($row['Nombre']);
    $usuarioId = $row['id'];
    $activo = $row['activo'];

   if($TotalUsu == 1 and $activo == 1) 
   {
        $_SESSION['nombreUsuario'] = $nombreCompleto;
        $_SESSION['usuarioId'] = $usuarioId;
        $_SESSION['rolId'] = $rolId;

        $_SESSION["id_juzgado"] = $row['id_juzgado'];
        $_SESSION["id_distrito"] = $row['id_distrito']; // cambio Carlos
        $_SESSION["idRol"] = $row['idRol'];
        $_SESSION["distrito"] = $row['distrito'];
        $_SESSION["DISTRITO"] = $row['DISTRITO'];
        $_SESSION["juzgado"] = $row['juzgado'];
        $_SESSION["correo"] = $row['email'];
        $_SESSION['autentificado'] ="SI" ;
        $ip = $_SERVER['REMOTE_ADDR']; //aohp

    //Bitacora de entrada
    $sqlBitacora =  "insert into bitacoras (idUsuario, ip, fecha, movimiento) values ('$usuarioId','$ip',now(),'inicio sesion')";
   //echo $sqlBitacora ; exit;
    $resultBitacora = mysqli_query($link,$sqlBitacora);

       
        switch($rolId)
        {
            case 1:header("location: misCitas.php");break;
            case 2:header("location: calendario.php");break;
            case 3:header("location: actividades.php"); break; /// cambio Carlos
            case 4:header("location: estadisticas.php"); break; /// cambio Carlos
        }
    }
    elseif ($TotalUsu == 1 and $activo == 0)
    {
        $error = "Tu Usuario no esta activo, revisa tu correo";
        $tipoalerta ="warning" ;
    }
    else
    {
        $error = "Tu usuario o contraseña no son validos";
        $tipoalerta ="warning" ;
    }
}
?>
<!DOCTYPE html>
<html style="background-color: #46576b" >
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

<link rel="stylesheet" href="resources/css/steps.css">
<script src="resources/js/steps.js"></script>
<link href="resources/imgs/calendario.ico" rel="shortcut icon" type="image/x-icon" />
<body style="background-color: #46576b;">
    
<br>
<div class="container" id="grad1">
  <div class="row justify-content-center">
    <div class="col-md-4">
                    

  

                        <div class="bannerx" >
                          <div class="container">
                            <div class="row">
                              <div class="col-md-12" align="center">
                                <img src='resources/imgs/img_pjecz.png' width="150"> 
                              </div>
                              <div class="col-md-12 text-center">
                                <span style="font-size: 1.5em; color: #f3edce;">Sistema de Citas en Línea</span>  <br><br>
                              </div>
                            </div>
                          </div>
                        </div>

                <div class="card" style="border:1px solid #46576b ">
                    <div class="card-body">
                        <div class="row justify-content-center mt-0" >
                                <div class="col-sm-12 text-center"> 
                                    <div class="row">
                                      <div class="container text-center">
                                            <form class="col-12" method="post"><br><br>
                                              <div class="form-group" id="grupoUsu">
                                                <label for="" class="text-left">Usuario (correo electrónico)</label>
                                                <input type="email" class="form-control" placeholder="correo@ejemplo.com" id="txtUsuario" name="txtUsuario">
                                              </div>
                                              <div class="form-group" id="GrupoContra">
                                                <label for="Contraseña">Contraseña</label>
                                                <input type="password" class="form-control" placeholder="Contraseña" id="txtContra" name="txtContra">
                                              </div>
                                              <div class="col-sm-12 text-center">
                                                <button type="submit" class="btn" name="btnSesion" id="btnSesion" style="background-color: #46576b; color: #fff">
                                                <i class="fas fa-sign-in-alt" ></i>Iniciar Sesión</button>
                                                <br>  <br>
                                              </div>
                                              <div class="col-sm-12 text-center">
                                                <a style="font-weight: bold; font-size:16px; color:#250A77; margin-top:10px" href="registroPJ.php">Registro de nuevo usuario</a>
                                                <br><br>
                                                <a style="font-weight: bold; font-size:16px; color:#250A77; margin-top:10px" href="recuperarContra.php">¿Olvidó su contraseña?</a>
                                              </div>
                                            </form> 
                                  </div>
                                </div>
                            </div>
                    </div> <!-- termina CARD Body -->
            </div>
        </div>
        <br>
        <a href='javascript:void(0)'  data-toggle="modal" data-target="#modalAviso" style="color:#f2edce">Aviso de privacidad</a><br>
        <a href='javascript:void(0)' data-toggle="modal" data-target="#modalTerminos"  style="color:#f2edce">Términos y condiciones de uso</a><br>  <br><br>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<!-- The Modal -->
<div class="modal" id="modalTerminos">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Terminos y condiciones de uso</h4>
        <button type="button" class="cerrar" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
<center><b>REGLAS DE OPERACIÓN DEL SISTEMA DE CITAS MEDIANTE PLATAFORMA EN LÍNEA DEL PODER JUDICIAL DEL ESTADO DE COAHUILA DE ZARAGOZA</b></center>
            <br>
            <br>
            <center><b>CAPÍTULO I. DISPOSICIONES GENERALES</b></center>
            <br>
            <br>
            <b>Artículo 1. </b> El sistema de citas mediante plataforma en línea (en adelante SCL) tendrá por objeto agendar citas en línea para las personas que son parte, así como para sus representantes legales, en los procedimientos que se tramitan en los juzgados de primera instancia en materias civil, mercantil, familiar y penal; en los juzgados letrados civiles y en los tribunales distritales.<br>
            <br>
            <b>Artículo 2. </b> Para hacer uso del SCL se deben cumplir los mismos requisitos de capacidad legal previstos en el Código Civil y en el Código Procesal Civil del Estado de Coahuila de Zaragoza y la legislación en materia penal, y demás normatividad aplicable a los órganos jurisdiccionales referidos en el párrafo anterior. <br>
            <br>
            <b>Artículo 3. </b> El SCL no dejará sin efectos los mecanismos y modalidades que previo a su implementación se han venido practicando en el Poder Judicial del Estado de Coahuila de Zaragoza. <br>
            <br>
            El SCL no impide que las y los justiciables puedan acudir a los órganos jurisdiccionales sin previa cita. <br>
            <br>
            <b>Artículo 4. </b> La Oficialía Mayor del Poder Judicial del Estado será el órgano encargado de la implementación y administración del SCL a través de la Dirección competente para ello, por lo que deberá realizar las gestiones administrativas necesarias para tal efecto, así como las de socialización del sistema entre las y los operadores de justicia y de la ciudadanía en general. <br> 
            <br>
            <b>Artículo 5. </b> La Oficialía Mayor podrá elaborar manuales, lineamientos y demás documentos que sean necesarios para cumplir con el artículo anterior. <br>
            <br>
            Asimismo, podrá realizar las acciones que se requieran para la debida coordinación con los órganos jurisdiccionales y administrativos que sean pertinentes. <br>
            <br>
            <b>Artículo 6. </b> Lo no previsto en las presentes reglas será resuelto y establecido por la Oficialía Mayor del Poder Judicial del Estado de Coahuila de Zaragoza <br>
            <br><br>

            <b>CAPÍTULO II. REGISTRO EN EL SISTEMA DE CITAS</b>

            <br>
            <b>Artículo 7. </b> Para hacer uso del SCL se deberá realizar el registro correspondiente. <br>
            <br>
            Para ello, se deberá contestar un formulario de registro y proporcionar los datos requeridos junto con puntos de contacto. Además, se solicitará generar una contraseña que debe resguardarse adecuadamente. <br>
            <br>
            Las contraseñas mediante las cuales las y los usuarios podrán acceder a los servicios del SCL serán creadas por ellos mismos, bajo las instrucciones que se señalen previamente en el sistema, a través de una serie consecutiva de caracteres alfanuméricos. <br>
            <br>
            La responsabilidad del uso de las contraseñas que sean dadas de alta en el sistema serán exclusivamente de la o el usuario por ser su creador y conocedor de las mismas. <br>
            <br>
            Una vez que la persona se registre, deberá aceptar los términos y condiciones del SCL. <br>
            <br>
            <b>Artículo 8. </b> Los datos que se requieren para ser usuario o usuaria del SCL, serán: <br> 
            <br>
              <ol type='a'>
                <li>Nombre y apellidos paterno y materno.</li>
                <li>Clave Única de Registro de Población (CURP).</li>
                <li>Número de teléfono móvil.</li>
                <li>Correo electrónico.</li>
              </ol>
            <br>
            El administrador del sistema deberá verificar el cumplimiento estricto de estos datos, procurando que los mismos llenen a satisfacción una identificación real del usuario, a quien se le podrá negar el registro hasta que aclare cualquier información dudosa o incorrecta. <br>
            <br>
            <b>Artículo 9. </b> En el registro inicial del usuario manifestará bajo protesta de decir verdad que se conducirá con respeto y legalidad en el manejo de la información y los componentes del sistema, a fin de obtener el compromiso fehaciente del usuario en cuanto a su desenvolvimiento correcto dentro del SCL. <br>
            <br>
            <br>
            <b>CAPÍTULO III. PROCEDIMIENTO PARA AGENDAR LA CITA</b><br>
            <br>
            <br>
            <b>Artículo 10. </b> Las citas se podrán agendar en un horario de 8:30 horas a 14:00 horas, pudiendo establecer un horario particular para trámites específicos. <br>
            <br>
            <b>Artículo 11. </b> Para agendar una cita, se deberán seguir los pasos siguientes: <br>
            <br>
            <ol type='a'>
                <li>Ingresar al SCL desde el sitio web del Poder Judicial del Estado de Coahuila de Zaragoza, utilizando la combinación de correo electrónico y contraseña. </li>
                <li>Seleccionar el órgano jurisdiccional en el que se desea agendar la cita. </li>
                <li>Indicar el tipo de trámite que se va a realizar y el número de expediente que corresponda al asunto.</li>
                <li>Seleccionar la fecha y la hora de preferencia para acudir a la sede del órgano jurisdiccional de que se trate, siempre y cuando haya disponibilidad. </li>
                <li>Revisar que los datos de la cita sean correctos, seleccionando la opción de confirmar cita. </li>
                <li>En el correo utilizado para el registro se recibirá la confirmación de la cita con los datos correspondientes, confirmación que deberá ser mostrada de manera electrónica o impresa en el punto de entrada al edificio así como al órgano jurisdiccional correspondiente. </li>
            </ol>
            <br>
            <b>Artículo 12. </b> Para agendar la cita será necesario elegir la opción correspondiente del catálogo de servicios que se prestarán con la cita. Los servicios son los siguientes: <br>
            <br>
            <ol type='a'>
                <li>Revisión de expedientes.</li>
                <li>Tramitación de oficios, edictos y exhortos. </li>
                <li>Citas con actuarios y actuarias (civiles y familiares únicamente). </li>
                <li>Citas con el juzgador o juzgadora. </li>
                <li>Expedición de copias simples certificadas.</li> 
                <li>Devolución de documentos. </li>
                <li>Entrega de cheques y certificados de depósito.</li>
                <li>Los demás que estén disponibles por parte de las autoridades en beneficio de la ciudadanía.</li>
            </ol>
            <br>
            <br>
            <b>CAPÍTULO IV. REGLAS DE USO DEL SISTEMA DE CITAS</b><br>
            <br>
            <br>
            <b>Artículo 13. </b> Las y los titulares de los órganos jurisdiccionales serán los encargados de la operación adecuada del SCL; en tratándose de los Juzgados del Sistema Penal Acusatorio y Oral, el encargado de la operación adecuada del sistema estará a cargo el administrador de cada órgano jurisdiccional. <br>
            <br>
            En el supuesto de que constaten fallas técnicas en el SCL, deberán informarlo a la Dirección de Innovación del Poder Judicial del Estado, a través de reportes de servicio, podrán también por oficio manifestar comentarios adicionales que se susciten con motivo de la operación del referido sistema. <br>
            <br>
            Si el usuario o usuaria detecta alguna falla técnica o se presenta algún problema con el uso del SCL, deberá reportarlo a través de la línea de whatsapp 844 2775774. <br>
            <br>
            <b>Artículo 14. </b> Para el uso del SCL se observarán las siguientes reglas:</b> <br>
            <br>
            <ol type='a'>
                <li>Las citas tendrán una duración de 30 minutos. </li>
                <li>Las citas con juezas,  jueces y personal actuarial tendrán una duración de 15 minutos.</li>
                <li>No se podrá agendar más de una cita en un día para acudir a un mismo órgano jurisdiccional.</li>
                <li>Si queda tiempo del que corresponde para la cita o citas agendadas, se podrá realizar un trámite adicional, siempre y cuando la persona lo especifique en el rubro de detalles del SCL.</li>
                <li>Las citas con juezas y jueces se realizarán en un horario de 10 a 12 horas.</li>
                <li>Las citas con personal actuarial se realizarán en un horario de 12 a 14 horas.</li>
            </ol>
            <br>
            En ningún caso, el tiempo para la cita no podrá excederse del previamente establecido.  <br>
            <br>
            <br>
            <b>Artículo 15. </b> La persona juzgadora podrá cancelar la cita, siempre que haya alguna cuestión urgente que deba atender conforme a sus atribuciones y obligaciones legales y constitucionales. <br>
            <br>
            Al respecto, sobre la cancelación se deberá avisar previamente a la o el interesado. Asimismo, se le dará a conocer la posibilidad de que otro servidor o servidora pública del órgano jurisdiccional pueda atenderle. En caso de que la o el interesado acceda a ser atendido por otro servidor o servidora pública, deberá señalarlo; de lo contrario podrá programar otra cita. <br>
            <br>

            <b>Artículo 16.</b>  El mal uso del SCL dará pie a la suspensión en el uso del mismo por los operadores del sistema.        

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal" id="modalAviso">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Aviso de privacidad</h4>
        <button type="button" class="cerrar" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            
        AVISO DE PRIVACIDAD SIMPLIFICADO<br>
        <br>
        En los términos  de lo dispuesto por los artículos 16, 20, 21 y 22 de la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados del Estado de Coahuila, se emite el actual aviso de privacidad simplificado, en los siguientes términos:<br>
        <br>

        <b>Las finalidades del tratamiento para las cuales se obtienen sus datos personales.</b><br>

        El Sistema de Citas mediante plataforma en línea (en adelante SCL), recaba datos personales de los usuarios con el fin de brindar los servicios siguientes: a) revisión de expedientes; b) tramitación de oficios, edictos y exhortos; c) citas con actuarios y actuarias (civiles y familiares únicamente); d) citas con el juzgador o juzgadora; e) expedición de copias simples o certificadas; f) devolución de documentos; g) entrega de cheques y certificados de depósitos; h) los demás que estén disponibles por parte de las autoridades en beneficio de la ciudadanía. Razón por la cual los datos personales únicamente serán utilizados en el momento en el que se brinden los servicios mencionados.<br>
        <br>

        <b>Transferencias de datos personales. </b><br>

        Sus datos personales no podrán ser difundidos o transmitidos a terceros o al público en general, salvo que: a) medie su consentimiento expreso; b) por disposición legal; o c) por ser indispensable para el ejercicio de alguna atribución por parte de esta u otra autoridad competente, incluyendo cualquier otro órgano jurisdiccional o área del Poder Judicial, en términos de los artículos 16 y 72 de la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados del Estado de Coahuila.<br>
        <br>

        <b>Mecanismos y medios disponibles para manifestar la negativa para el tratamiento de sus datos personales para finalidades y transferencias de datos personales.</b><br>

        Usted por su propia cuenta o por medio de su representante, podrán solicitar el acceso, rectificación, cancelación u oposición al tratamiento de sus datos personales, conocidos como derechos ARCO. Para lo anterior, deberá comparecer personalmente o presentar la solicitud respectiva, por escrito, ante la Unidad de Atención a las Solicitudes de Acceso a la Información de la Secretaría Técnica y de Transparencia de la Presidencia del Tribunal Superior de Justicia del Estado. ubicada en Blvd. Venustiano Carranza número 2673, Colonia Santiago, en Saltillo, Coahuila de Zaragoza en un horario de atención de 8:30 a 16:30 horas de lunes a viernes.<br>
        <br>
        El teléfono de contacto de la Unidad de Atención a las Solicitudes de Acceso a la Información Pública de la Secretaría Técnica y de Transparencia de la Presidencia del Tribunal Superior de Justicia del Estado, para cualquier duda, es el siguiente: 844 438 09 80 ext. 6808, el cual será atendido en un horario de lunes a viernes de 08:30 a 16:30 horas. <br>
        <br>
        <b>El sitio donde se podrá consultar el Aviso de Privacidad Integral.</b><br>
        El Aviso de Privacidad Integral estará a su disposición en la página de internet siguiente: <a href='https://www.pjecz.gob.mx/aviso-de-privacidad/' target="_blank">https://www.pjecz.gob.mx/aviso-de-privacidad/</a>
        <br>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php if($error!=''){echo "<script>swal('$error','','$tipoalerta')</script>";}?>