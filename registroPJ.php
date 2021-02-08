<?php
include ("funciones.php");
error_reporting(0);

require("SendGrid/sendgrid-php.php");
$link = conectarse();

$error="";
$emailEnv="";
$hash = md5( rand(0,1000) );
//$password = rand(1000,5000);

  if(isset($_POST['btnRegistro']))
  {    
    //Datos personales  
    $nombre=$_POST['txtNombre'];
    $apellidoP=$_POST['txtPat'];
    $apellidoM=$_POST['txtMat'];
    $edad=$_POST['txtEdad'];
    //$domi=$_POST['txtDomi'];
    //$idEstado=$_POST['cbx_estado'];
    //$idMunicipio=$_POST['cbx_municipio'];
    $cel=$_POST['txtCel'];
    $correo=$_POST['txtCorreo'];
	  $correo2=$_POST['txtCorreo2'];						  
    $contra=$_POST['txtContra'];
    $contra2=$_POST['txtContra2'];
    $sexo=$_POST['txtSexo'];
    $curp=$_POST['txtCurp'];
    $curp2=$_POST['txtCurp2'];
    $capcha=$_POST['txtcapcha'];

    $sqlExisteUsu = "select id, email from usuario where email = '$correo'";
    $resultExisteUsu = mysqli_query($link,$sqlExisteUsu);
    $count = mysqli_num_rows($resultExisteUsu);
    $subtitulo="" ;

if($capcha == 1){
  if ($correo != $correo2)
  {//ValiCorreo
    $error = "Los correos no coinciden, favor de revisarlo"; 
    $tipoalerta ='info';
  }//ValiCorreo
  else
  {//ValiCorreo	   		   
    if($count == 1)
    {
        $error = "El correo ya ha sido registrado, favor de ingresar otro"; 
        $tipoalerta ='info';   
    }
    else
    {
      if($curp!=$curp2)
      { //curp
        $error = "El CURP no coincide";   
        $tipoalerta = 'warning' ; 
      } //curp
      else
      { //curp
  
	    $sqlExisteCurp = "select id, curp from usuario where curp = '$curp2'";
      $resultExisteCurp = mysqli_query($link,$sqlExisteCurp);
      $countCurp = mysqli_num_rows($resultExisteCurp);

      if($countCurp >= 1)
      { //curpExi
        $error = "El CURP ya ha sido registrado";   
        $tipoalerta = 'info' ; 
      } //curpExi
      else
      {//curpExi
        if($contra!=$contra2 or strlen($contra) < 8 or strlen($contra2) < 8)
        {   
            $error = "Verifica que las contraseñas coincidan y que contengan un mínimo de 8 caracteres";  
            $tipoalerta = 'warning';  
        }
        else
    	  {
          $para = $correo ;

          $email = new \SendGrid\Mail\Mail(); 
          $email->setFrom("citas@pjec.gob.mx", "Poder Judicial del Estado de Coahuila");
          $email->setSubject("Activar cuenta (Sistema de Citas PJECZ)");
          $email->addTo($para);
          $email->addContent(
              "text/html", "<body>
                    <table>
                        <tr>
                          <td style='background-color:#495769; border-bottom:4px solid #555; color:#fff; text-align:center' colspan=2>
                            <h3>Poder Judicial del Estado de Coahuila</h3>
                          </td>
                        </tr>
                        <tr>  
                          <td colspan=2><br>
                            <h3>Su cuenta ha sido creada</h3>
                            Gracias por utilizar nuestro Sistema de Citas en Línea. A continuación le proporcionamos los detalles de su cuenta.<br>
                            <br><h3>Detalles de la cuenta</h3>
                          </td>
                        </td>
                        <tr>
                          <td>
                            <label>Nombre:</label>
                          </td>
                          <td>
                            <label>$nombre $apellidoP $apellidoM</label>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label>Usuario:</label>
                          </td>
                          <td>
                            <label>$correo</label>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label>CURP:</label>
                          </td>
                          <td>
                            <label>$curp</label> 
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label>Celular registrado:</label>
                          </td>
                          <td>
                            <label>$cel</label> 
                          </td>
                        </tr>                                     
                      <tr>
                        <td colspan=2 style='text-align:center'>
                          <br><a href='https://citas.poderjudicialcoahuila.gob.mx/verificar.php?correo=$correo&hash=$hash' style='box-sizing: border-box; border-color: #348eda; font-weight: 400; text-decoration: none; display: inline-block; margin: 0; color: #ffffff; background-color: #495769; border: solid 1px #348eda; border-radius: 2px; cursor: pointer; font-size: 14px; padding: 12px 45px'>Activar cuenta</a>
                        </td>
                      </tr>
                    </table>
                  </body>"
          );
          $sendgrid = new \SendGrid('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');
          try {
              $response = $sendgrid->send($email);
              $emailEnv="1";
          } catch (Exception $e) {
              $emailEnv="0";
          }
    		  
        if($emailEnv=="1")
        {
			    $contra = MD5($contra);					   
          $sqlRegistro = "INSERT INTO usuario(nombre, apPaterno, apMaterno, fechaNac, domicilio, idEstado, idMunicipio,
          celular, email, password, activo, hash, idRol, id_juzgado, curp, sexo)   
          VALUES ('$nombre', '$apellidoP', '$apellidoM', '$edad', null, null, null, '$cel', '$correo', '$contra', '0', '$hash', '1', '0', '$curp2', '$sexo')";

          $resultRegistro = mysqli_query($link,$sqlRegistro);
          $error = "Hemos creado tu cuenta, deberás verificarla en el link de activación que ha sido enviado a tu buzón de correo: $correo, en caso de no ver el correo de activación en tu bandeja de entrada te pedimos revisar en correo no deseado o promociones" ;
          $subtitulo = "";
          $tipoalerta = "success" ;			

          $nombre=""; 
          $apellidoP=""; 
          $apellidoM=""; 
          $cel=""; 
          $correo=""; 
          $correo2=""; 
          $sexo=""; 
          $curp=""; 
          $curp2=""; 
        }
        else
        {
           $error = "Tu cuenta no fue creada, vuelve a intentarlo";
           $tipoalerta = "warning" ;
        }
      }
	  } //curpExi
    } //curp
    }
  }//ValiCorreo
}//capcha

  else 
  { //capcha
    $error = "Favor de seleccionar el Captcha";
    $tipoalerta = "warning";
  } //capcha

  }       

//Combo Estado  
  $squery="SELECT id_estado,descripcion from estados";
  $resultado = mysqli_query($link,$squery);

?>
<!DOCTYPE html>
<html>
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


<link href="resources/imgs/calendario.ico" rel="shortcut icon" type="image/x-icon" />
<script src='https://www.google.com/recaptcha/api.js'></script>

<script language="javascript">
      $(document).ready(function(){
        $("#cbx_estado").change(function () {
                 
          $("#cbx_estado option:selected").each(function () {
            id_estado = $(this).val();
            $.post("getMunicipio.php", { id_estado: id_estado }, function(data){
              $("#cbx_municipio").html(data);
            });            
          });
        })
      });
</script>

<script type="text/javascript">
          var onloadCallback = function() {
            grecaptcha.render('html_element', {
                'sitekey' : '6LfnBQEVAAAAADissYsgB90rCO04phl-nEpQ0GEB',            
                'callback' : verifyCallback
            });
          };

          var verifyCallback = function(response) {
                //alert(response);
                var si=1;
                document.getElementById('txtcapcha').value = si;
                aceptar()
          };
  </script>

<script language="javascript">

function verificarPassword(contra){
  if (contra.length >= 8) {}
  else{
    alert("Debes escribir mas de 8 caracteres");
  }
}

function upperCurp(curp){
  var curpMax = curp.toUpperCase();
  document.getElementById('txtCurp').value = curpMax;
}
function upperCurp2(curp){
  var curpMax = curp.toUpperCase();
  document.getElementById('txtCurp2').value = curpMax;
}
   
function calcularEdad(curp){
  
  var anio  = ( ( parseInt(curp.substring(4,6))>50 ) ? "19" : "20" ) + curp.substring(4,6) ;
  var mes  =  curp.substring(6,8) ;
  var dia  =  curp.substring(8,10) ;

  var hoy = new Date();
  
  fecha = anio+'-'+mes+'-'+dia ;

  var cumpleanos = new Date(fecha);
  var edad = hoy.getFullYear() - cumpleanos.getFullYear();
  var m = (hoy.getMonth()+1) - parseInt(mes);
  if (m <= 0 ) {
  var diaH = hoy.getDate() ;
  var diaN = parseInt(dia) ;
  if( diaN>diaH){
    edad-- ;
    }}
  document.getElementById('txtEdad2').value = edad ; 
  document.getElementById('txtEdad').value = fecha ; 
}

function sexo(gener){
  var genero = (gener.substring(10,11));
  if (genero == 'M'){
    var sex = 'Femenino'; }
  else if(genero == 'H'){
    var sex = 'Masculino'; }
  else{
    var sex = ''; }
  document.getElementById('txtSexo').value = sex ; 
}

$(document).ready(function() {
document.getElementById('txtCel').addEventListener('input', function (e) {
var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : ''); });
});

function validarCorreo(){
  var correo1 = document.getElementById('txtCorreo').value;
  var correo2 = document.getElementById('txtCorreo2').value;
  if (correo1 == correo2)
  {
    document.getElementById('lblvalCorreo').innerHTML = 'Las direcciones de correo SI coinciden';
    document.getElementById('lblvalCorreo').style.display = "none" ;
  }
  else
  {
    document.getElementById('lblvalCorreo').innerHTML = 'Las direcciones de correo NO coinciden';
    document.getElementById('lblvalCorreo').style.display = "block" ;
  }
}			

</script>

<body>
<?php include("header.php"); ?>
<br>
<div class="container" id="grad1">

    <div class="card-body text-center" >
        <div class="row justify-content-center" >
            <div class="col-md-6">
          
                    <h2><strong>Registro al Sistema de Citas</strong></h2>
                    <div class="row text-center">
                        <div class="col-md-12 text-center">
                            <form id="formReg" method="POST">                                
                                <div class="container text-left">
                                    <br>
                                <fieldset>
                                      <div class="row">
                                        <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="txtNombre">Nombre(s)</label> 
                                                  <input id="txtNombre" name="txtNombre" type="text" class="form-control" required="required" value="<?php if(isset($nombre)) echo $nombre?>">
                                                </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="txtPat">Apellido Paterno</label> 
                                                  <input id="txtPat" name="txtPat" type="text" class="form-control" required="required" value="<?php if(isset($apellidoP)) echo $apellidoP?>">
                                                </div>
                                                  </div>
                                        </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="txtMat">Apellido Materno</label> 
                                                  <input id="txtMat" name="txtMat" type="text" class="form-control" required="required" value="<?php if(isset($apellidoM)) echo $apellidoM?>">
                                                </div>
                                        </div>
                                        </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="txtCel">Numero de Celular</label> 
                                                  <input id="txtCel" name="txtCel" type="text" class="form-control" required="required" placeholder="(000) 000-0000" pattern=".{14,14}" value="<?php if(isset($cel)) echo $cel?>">
                                                </div>          
                                        </div>
                                          </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <input id="txtSexo" name="txtSexo" type="hidden" class="form-control" readonly value="<?php if(isset($sexo)) echo $sexo?>">
                                                </div>          
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <label for="txtCurp">Ingresa tu Clave Única de Registro de Población (CURP)</label> 
                                                  <input id="txtCurp" name="txtCurp" type="text" class="form-control" required="required" onblur="upperCurp(this.value),sexo(this.value); calcularEdad(this.value)" pattern="^[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]$"  maxlength="18" value="<?php if(isset($curp)) echo $curp?>">
                                                </div>          
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <label for="txtCurp2">Confirmar CURP</label> 
                                                  <input id="txtCurp2" name="txtCurp2" type="text" class="form-control" required="required" pattern="^[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]$" maxlength="18" onblur="upperCurp2(this.value)" value="<?php if(isset($curp2)) echo $curp2?>">
                                                </div>          
                                        </div>
                                      </div>
                                      <div class="row">
                                         <div class="col-md-12">
                                              <div class="form-group">
                                                <label for="txt"> </label> 
                                                <a style="font-weight: normal; font-size: 14px; color: #222222;" href="https://www.gob.mx/curp/" target="_blank">Si no conoces tu CURP puedes consultarla aquí.</a> 
                                              </div>           
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <input id="txtEdad" name="txtEdad" type="hidden" class="form-control" required="required" value="<?php if(isset($edad)) echo $edad?>">
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <input id="txtEdad2" name="txtEdad2" type="hidden" class="form-control" readonly >
                                                </div>          
                                        </div>
                                        
                                      </div>
                                      <!-- <div class="row">
                                        <div class="col-md-5">
                                                <div class="form-group">
                                                  <label for="txtDomi">Domicilio</label> 
                                                  <input id="txtDomi" name="txtDomi" type="text" class="form-control">
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="cbx_estado" class="control-label">Estado</label>
                                                    <select class="form-control" name="cbx_estado" id="cbx_estado">
                                                        <option value="0">Seleccionar Estado</option>
                                                                <?php while($row = $resultado->fetch_assoc()) { ?>
                                                        <option value="<?php echo $row['id_estado']; ?>"><?php echo $row['descripcion']; ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>      
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="cbx_municipio" class="control-label">Municipio</label>
                                                    <select class="form-control" id="cbx_municipio" name="cbx_municipio">
                                                    <option value='0' >Seleccionar Municipio </option>  
                                                    </select>
                                                </div>  
                                        </div>
                                      </div> -->
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <label for="txtCorreo">Correo electrónico</label> 
                                                  <input id="txtCorreo" name="txtCorreo" type="email" class="form-control" required="required" onblur="validarCorreo()" oncopy="return false" onpaste="return false" value="<?php if(isset($correo)) echo $correo?>">
                                                </div>          
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <label for="txtCorreo2">Confirmar Correo electrónico</label> 
                                                  <input id="txtCorreo2" name="txtCorreo2" type="email" class="form-control" required="required" onblur="validarCorreo()" oncopy="return false" onpaste="return false" value="<?php if(isset($correo2)) echo $correo2?>">
                                                  <label for="txt"> </label> 
                                                  <a style="font-weight: normal; font-size: 14px; color: black;">Favor de revisar bien su correo electrónico.</a> 
                                                  <label style="font-weight: bold; font-size:12px; color:red;" id="lblvalCorreo"> </label> 
                                                </div>          
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <label for="txtContra">Crear contraseña</label> 
                                                  <input id="txtContra" name="txtContra" type="password" class="form-control" required="required" maxlength="20" pattern=".{8,20}">
                                                  <label style="font-weight: bold; font-size:10px; color:black;" for="txtContra8">Minimo 8 caracteres</label> 
                                                </div>          
                                        </div>
                                       </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                               <div class="form-group">
                                                  <label for="txtContra2">Confirmar contraseña</label> 
                                                  <input id="txtContra2" name="txtContra2" type="password" class="form-control" required="required" maxlength="20" pattern=".{8,20}">
                                                  <label style="font-weight: bold; font-size:10px; color:black;" for="txtContra9">Minimo 8 caracteres</label>
                                                </div>          
                                        </div>
                                      </div>
                                      <!--div class="row">
                                        <div class="col-md-4">
                                          
                                                  <label for="captcha">Escriba el texto del Captcha</label><br>
                                                  <img src="captcha.php" alt="CAPTCHA" class="captcha-image">
                                                      <a href="javascript:void(0);" onclick="refreshcaptcha();"><img src='resources/imgs/img_refresh.png' width='30'></a>
                                                  <br> <br>
                                                  
                                                  <input type="text" id="captcha"  onkeydown="upperCaseF(this)" name="captcha_challenge" pattern="[A-Z]{6}" required class="form-control">
                                          
                                        </div>
                                      </div-->
                                      <div class="row">
                                        <div class="col-md-12">
                                            <label for="">He leído y acepto:</label><br>
                                            <input onclick="aceptar()" id="check1" type="checkbox"> <a href='javascript:void(0)' data-toggle="modal" data-target="#modalAviso">Aviso de privacidad</a><br>
                                            <input onclick="aceptar()" id="check2" type="checkbox"> <a href='javascript:void(0)' data-toggle="modal" data-target="#modalPoliticas">Términos y condiciones de uso</a><br>    
                                        </div>
                                       </div>
                                      <div class="row">
                                        <div class="col-md-12"><br>
                                           <input id="txtcapcha" name="txtcapcha" type="hidden" class="form-control">       
                                        </div>
                                      </div>
                        <div id="html_element"></div>
                                      <div class="row ">
                                        <div class="col-md-12"><br>
                                          <input type="submit" id="btnRegistro" name="btnRegistro" class="btn btn-success" style="width: 150px; display: none; margin:0 auto;" value="Registrar"/>                
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-sm-12 text-center">
                                          <span style="font-weight: bold; font-size:15px; color:#163b67; margin-top:10px">
                                          </span>
                                        </div> 
                                      </div>
                                </fieldset>
                            </form>
                        </div>
           
                </div>
            </div>
        </div>

</div>

<script>
  function aceptar(){
    var check1  = document.getElementById('check1') ;
    var check2  = document.getElementById('check2') ;
    if(check1.checked == true && check2.checked == true && document.getElementById("txtcapcha").value=="1"){
      document.getElementById('btnRegistro').style.display = ' block ' ;
    }
    else {
      document.getElementById('btnRegistro').style.display = ' none ' ;
    }
  }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
  window.refreshcaptcha = function() {
    document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
}
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<!-- The Modal -->
<div class="modal" id="modalAviso">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Politicas de privacidad</h4>
        <button type="button" class="cerrar" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       <b>AVISO DE PRIVACIDAD SIMPLIFICADO</b><br>
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

<!-- The Modal -->
<div class="modal" id="modalPoliticas">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Terminos y condiciones de uso</h4>
        <button type="button" class="cerrar" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
<center><b>REGLAS DE OPERACIÓN DEL SISTEMA DE CITAS MEDIANTE PLATAFORMA EN LÍNEADEL PODER JUDICIAL DEL ESTADO DE COAHUILA DE ZARAGOZA</b></center><br><br>

<center><b>CAPÍTULO I. DISPOSICIONES GENERALES</b></center>
<br><br>

<b>Artículo 1.</b>  El sistema de citas mediante plataforma en línea (en adelante SCL) tendrá porobjeto   agendar   citas   en   línea   para   las   personas   que   son   parte,   así   como   para   susrepresentantes legales, en los procedimientos que se tramitan en los juzgados de primerainstancia en materias civil, mercantil, familiar y penal; en los juzgados letrados civiles y en lostribunales distritales.
<br><br>

<b>Artículo 2.</b> Para hacer uso del SCL se deben cumplir los mismos requisitos de capacidad legalprevistos en el Código Civil y en el Código Procesal Civil del Estado de Coahuila de Zaragoza yla legislación en materia penal, y demás normatividad aplicable a los órganos jurisdiccionalesreferidos en el párrafo anterior.
<br><br>

<b>Artículo 3.</b>  El SCL no dejará sin efectos los mecanismos y modalidades que previo a suimplementación se han venido practicando en el Poder Judicial del Estado de Coahuila deZaragoza. El SCL no impide que las y los justiciables puedan acudir a los órganos jurisdiccionales sinprevia cita. 
<br><br>

<b>Artículo 4.</b> La Oficialía Mayor del Poder Judicial del Estado será el órgano encargado de laimplementación y administración del SCL a través de la Dirección competente para ello, por loque deberá realizar las gestiones administrativas necesarias para tal efecto, así como las desocialización del sistema entre las y los operadores de justicia y de la ciudadanía en general. 
<br><br>

<b>Artículo 5.</b> La Oficialía Mayor podrá elaborar manuales, lineamientos y demás documentos quesean necesarios para cumplir con el artículo anterior. Asimismo, podrá realizar las acciones que se requieran para la debida coordinación con losórganos jurisdiccionales y administrativos que sean pertinentes. 
<br><br>

<b>Artículo 6.</b> Lo no previsto en las presentes reglas será resuelto y establecido por la OficialíaMayor del Poder Judicial del Estado de Coahuila de Zaragoza.
<br><br>

<center><b>CAPÍTULO II. REGISTRO EN EL SISTEMA DE CITAS</center></b>
<br><br>

<b>Artículo 7.</b> Para hacer uso del SCL se deberá realizar el registro correspondiente.Para ello, se deberá contestar un formulario de registro y proporcionar los datos requeridosjunto   con   puntos   de   contacto.  Además,   se   solicitará   generar   una   contraseña   que   deberesguardarse adecuadamente. Las contraseñas mediante las cuales las y los usuarios podrán acceder a los servicios del SCLserán creadas por ellos mismos, bajo las instrucciones que se señalen previamente en el
sistema, a través de una serie consecutiva de caracteres alfanuméricos. La responsabilidad del uso de las contraseñas que sean dadas de alta en el sistema seránexclusivamente de la o el usuario por ser su creador y conocedor de las mismas. Una vez que la persona se registre, deberá aceptar los términos y condiciones del SCL. 
<br><br>

<b>Artículo 8.</b> Los datos que se requieren para ser usuario o usuaria del SCL, serán: a.Nombre y apellidos paterno y materno.b.Clave Única de Registro de Población (CURP).c.Número de teléfono móvil.d.Correo electrónico.El   administrador   del   sistema   deberá   verificar   el   cumplimiento   estricto   de   estos   datos,procurando que los mismos llenen a satisfacción una identificación real del usuario, a quien sele podrá negar el registro hasta que aclare cualquier información dudosa o incorrecta.
<br><br>

<b>Artículo 9.</b> En el registro inicial del usuario manifestará bajo protesta de decir verdad que seconducirá con respeto y legalidad en el manejo de la información y los componentes delsistema,   a   fin   de   obtener   el   compromiso   fehaciente   del   usuario   en   cuanto   a   sudesenvolvimiento correcto dentro del SCL. 
<br><br>

<center><b>CAPÍTULO III. PROCEDIMIENTO PARA AGENDAR LA CITA</b></center>
<br><br>

<b>Artículo 10.</b> Las citas se podrán agendar en un horario de 8:30 horas a 14:00 horas, pudiendoestablecer un horario particular para trámites específicos.
<br><br>

<b>Artículo 11.</b> Para agendar una cita, se deberán seguir los pasos siguientes: 
<br><br>
<b>a.</b> Ingresar al SCL desde el sitio web del Poder Judicial del Estado de Coahuila deZaragoza, utilizando la combinación de correo electrónico y contraseña.
<br>
<b>b.</b> Seleccionar el órgano jurisdiccional en el que se desea agendar la cita.
<br>
<b>c.</b> Indicar   el   tipo   de   trámite   que   se   va   a   realizar   y   el   número   de   expediente   quecorresponda al asunto.
<br>
<b>d.</b> Seleccionar   la  fecha   y  la   hora  de  preferencia  para   acudir  a   la   sede  del  órganojurisdiccional de que se trate, siempre y cuando haya disponibilidad.
<br>
<b>e.</b> Revisar que los datos de la cita sean correctos, seleccionando la opción de confirmarcita.
<br>
<b>f.</b> En el correo utilizado para el registro se recibirá la confirmación de la cita con los datoscorrespondientes, confirmación que deberá ser mostrada de manera electrónica o impresa   en   el   punto   de   entrada   al   edificio   así   como   al   órgano   jurisdiccionalcorrespondiente.
<br><br>

<b>Artículo 12.</b> Para agendar la cita será necesario elegir la opción correspondiente del catálogode servicios que se prestarán con la cita. Los servicios son los siguientes: 
a.Revisión de expedientes.b.Tramitación de oficios, edictos y exhortos .c.Citas con actuarios y actuarias (civiles y familiares únicamente) .d.Citas con el juzgador o juzgadora .e.Expedición de copias simples certificadas .f.Devolución de documentos .g.Entrega de cheques y certificados de depósito.h.Los demás que estén disponibles por parte de las autoridades en beneficio de laciudadanía.
<br><br>

<center><b>CAPÍTULO IV. REGLAS DE USO DEL SISTEMA DE CITAS</b></center>
<br><br>

<b>Artículo 13.</b>  Las y los titulares de los órganos jurisdiccionales serán los encargados de laoperación adecuada del SCL; en tratándose de los Juzgados del Sistema Penal Acusatorio yOral, el encargado de la operación adecuada del sistema estará a cargo el administrador decada órgano jurisdiccional. En el supuesto de que constaten fallas técnicas en el SCL, deberán informarlo a la Dirección deInnovación del Poder Judicial del Estado, a través de reportes de servicio, podrán también poroficio manifestar comentarios adicionales que se susciten con motivo de la operación delreferido sistema.Si el usuario o usuaria detecta alguna falla técnica o se presenta algún problema con el uso delSCL, deberá reportarlo a través de la línea de whatsapp 844 2775774.
<br><br>

<b>Artículo   14.</b>   Para   el   uso   del   SCL   se   observarán   las   siguientes   reglas:   
<br><br>
<b>a.</b> Las citas tendrán una duración de 30 minutos.
<br>
<b>b.</b> Las citas con juezas,  jueces y personal actuarial tendrán una duración de 15 minutos.
<br>
<b>c.</b> No se podrá agendar más de una cita en un día para acudir a un mismo órganojurisdiccional.
<br>
<b>d.</b> Si queda tiempo del que corresponde para la cita o citas agendadas, se podrá realizarun trámite adicional, siempre y cuando la persona lo especifique en el rubro dedetalles del SCL.
<br>
<b>e.</b> Las citas con juezas y jueces se realizarán en un horario de 10 a 12 horas.
<br>
<b>f.</b> Las citas con personal actuarial se realizarán en un horario de 12 a 14 horas.En ningún caso, el tiempo para la cita no podrá excederse del previamente establecido.  
<br>

<b>Artículo 15.</b> La persona juzgadora podrá cancelar la cita, siempre que haya alguna cuestiónurgente   que   deba   atender   conforme   a   sus   atribuciones   y   obligaciones   legales   yconstitucionales. Al respecto, sobre la cancelación se deberá avisar previamente a la o el interesado. Asimismo,se   le  dará  a   conocer  la   posibilidad   de  que   otro  servidor   o  servidora  pública   del   órganojurisdiccional pueda atenderle. En caso de que la o el interesado acceda a ser atendido por otroservidor o servidora pública, deberá señalarlo, de lo contrario podrá programar otra cita. 
<br><br>

<b>Artículo 16.</b>   El mal uso del SCL dará pie a la suspensión en el uso del mismo por los operadores del sistema.
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

<?php if($error!=''){echo "<script>swal('$error','$subtitulo','$tipoalerta')</script>";}?>