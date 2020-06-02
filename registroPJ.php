<?php
include ("funciones.php");
//include_once('resources/correo/class.phpmailer.php');
//include_once('resources/correo/class.smtp.php');
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
    $domi=$_POST['txtDomi'];
    $idEstado=$_POST['cbx_estado'];
    $idMunicipio=$_POST['cbx_municipio'];
    $tel=$_POST['txtTel'];
    $correo=$_POST['txtCorreo'];
    $contra=$_POST['txtContra'];
    $contra2=$_POST['txtContra2'];
    ///
      if($_POST['txtSexo']=='Femenino')
      {
        $sexo = '1';
      }
      else
      {
         $sexo = '2';
      }

    $curp=$_POST['txtCurp'];
    $curp2=$_POST['txtCurp2'];
    $cel=$_POST['txtCel'];

    $sqlExisteUsu = "select id, email from usuario where email = '$correo'";
    $resultExisteUsu = mysqli_query($link,$sqlExisteUsu);
    $count = mysqli_num_rows($resultExisteUsu);

    if($count == 1)
    {
        $error = "El correo ya ha sido registrado, favor de ingresar otro";    
    }
    else
    {
      if($curp!=$curp2)
      {
        $error = "El CURP no coindide";    
      }
      else
      {
      if($contra!=$contra2)
      {   
          $error = "Las contraseñas no coindiden";    
      }
      else
  	  {
  		  $para = $correo ;

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("informatica@coahuila.gob.mx", "INTRANET PJECZ");
        $email->setSubject("Activar cuenta(sistema de citas) PJECZ");
        $email->addTo($para);
        $email->addContent(
            "text/html", "<body>
                  <table>
                      <tr>
                        <td style='background-color:#efeff1: border-bottom:4px solid #555; text-align:center' colspan=2>
                          <h3>Poder Judicial del Estado de Coahuila</h3><br>
                        </td>
                      </tr>
                    
                      <tr>  
                        <td colspan=2>
                          <h3>Su cuenta ha sido creada</h3>
                          Gracias por utilizar nuestro sistema de citas. Debajo encontrara los detalles de su cuenta.
                          <br><h3>Detalles de la cuenta</h3>
                        </td>
                      </td>
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
                          <label>Contrasena:</label>
                        </td>
                        <td>
                          <label>$contra</label> 
                        </td>
                      </tr>
                      <tr>
                        <td colspan=2>
                          <br><h3>Detalle del beneficiario</h3>
                        </td>
                      </tr>

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
                          <label>Correo electr&oacute;nico:</label>
                        </td>
                        <td>
                          <label>$correo</label> 
                        </td>
                      </tr>                                     
                    <tr>
                      <td colspan=2 style='text-align:center'>
                        <br><a href='https://citas.poderjudicialcoahuila.gob.mx/verificar.php?correo=$correo&hash=$hash'>Activar cuenta</a>
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

        $sqlRegistro = "INSERT INTO usuario(nombre, apPaterno, apMaterno, fechaNac, domicilio, idEstado, idMunicipio,
        telefono, email, password, activo, hash, idRol, id_juzgado, curp, sexo, celular)   
        VALUES ('$nombre', '$apellidoP', '$apellidoM', '$edad', '$domi', '$idEstado', '$idMunicipio', '$tel', '$correo', '$contra', '0', '$hash', '1', '0', '$curp2', '$sexo', '$cel')";

        $resultRegistro = mysqli_query($link,$sqlRegistro);
        $error = "Tu cuenta ha sido creada, porfavor verifica el link de activacion que ha sido enviado a tu e-mail: ".$correo;			 
      }
      else
      {
         $error = "Tu cuenta no fue creada, vuelve a intentarlo";
      }

}}
    }
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

<link rel="stylesheet" href="resources/css/steps.css">
<script src="resources/js/steps.js"></script>
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

<script language="javascript">
                    function calcularEdad(fecha) {
                           
                            var hoy = new Date();
                            var parts = fecha.split("-");
                            fecha = parts[0]+'-'+parts[1]+'-'+parts[2] ;
                            var cumpleanos = new Date(fecha);
                            var edad = hoy.getFullYear() - cumpleanos.getFullYear();
                            var m = (hoy.getMonth()+1) - parseInt(parts[1]);

                            if (m <= 0 ) {
                                var diaH = hoy.getDate() ;
                                var diaN = parseInt(parts[0]) ;
                                if( diaN>diaH){
                                  edad-- ;
                                }

                            }

                            document.getElementById('txtEdad2').value = edad ; 
                        }

                    function sexo(gener) {

                            var genero = (gener.substring(10,11));
                            if (genero == 'M')
                            {
                              var sex = 'Femenino';
                            }
                            else
                            {
                              var sex = 'Masculino';
                            }
                            document.getElementById('txtSexo').value = sex ; 
                        }
</script>

<body>
    
<br>
<div class="container" id="grad1">
    <div class="card" style="border:1px solid #555">

    <div class="card-header" style="background-color: #3DD481">

        <div class="bannerx" >
          <div class="container">
            <div class="row">
              <div class="col-md-3" style="padding:0px 50px;">
                <img src='resources/imgs/logo_pjecz.png' width="220"> 
              </div>
              <div class="col-md-9 text-right" style="padding: 0px 50px;"><br>
                <span style="font-size: 2em; color: #fff">:: Sistema de Citas ::</span> 
                <br>
                <a href="index.php" class="btn btn-secondary">Iniciar sesión</a> 
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="card-body">

        <div class="row justify-content-center mt-0" >
            <div class="col-11 col-sm-9 col-md-7 col-lg-10 text-center p-10 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-10 mt-3 mb-3">
                    <h2><strong>Registro al Sistema de Citas</strong></h2>
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="formReg" method="POST">                                
                                <div class="container text-left">
                                    <br>

                                <fieldset>
                                          <div class="row">
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="txtNombre">Nombre(s)</label> 
                                                  <input id="txtNombre" name="txtNombre" type="text" class="form-control" required="required">
                                                </div>
                                        </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="txtPat">Paterno</label> 
                                                  <input id="txtPat" name="txtPat" type="text" class="form-control" required="required">
                                                </div>
                                                  </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="txtMat">Materno</label> 
                                                  <input id="txtMat" name="txtMat" type="text" class="form-control" required="required">
                                                </div>
                                        </div>
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <label for="txtSexo">Sexo</label> 
                                                  <input id="txtSexo" name="txtSexo" type="text" class="form-control" readonly>
                                                </div>          
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-4">
                                               <div class="form-group">
                                                  <label for="txtCurp">CURP</label> 
                                                  <input id="txtCurp" name="txtCurp" type="text" class="form-control" required="required" onblur="sexo(this.value)" maxlength="18">
                                                </div>          
                                        </div>
                                        <div class="col-md-4">
                                               <div class="form-group">
                                                  <label for="txtCurp2">Confirmar CURP</label> 
                                                  <input id="txtCurp2" name="txtCurp2" type="text" class="form-control" required="required" maxlength="18">
                                                </div>          
                                        </div>
                                        <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="txt"> Consulta tu CURP</label> 
                                                <a class="btn btn-danger" href="https://www.gob.mx/curp/" target="_blank">Consulta tu CURP, Aqui !!</a> 
                                              </div>           
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <label for="txtEdad">Fecha de Nacimiento</label> 
                                                  <input id="txtEdad" name="txtEdad" type="date" class="form-control" required="required" onblur="calcularEdad(this.value)">
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <label for="txtEdad2">Edad</label> 
                                                  <input id="txtEdad2" name="txtEdad2" type="text" class="form-control" readonly>
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="txtTel">Teléfono de contacto</label> 
                                                  <input id="txtTel" name="txtTel" type="number" class="form-control">
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="txtCel">Celular</label> 
                                                  <input id="txtCel" name="txtCel" type="number" class="form-control" required="required">
                                                </div>          
                                        </div>
 
                                      </div>
                                      <div class="row">
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
                                        
                                      </div>
                                      <div class="row">
                                        <div class="col-md-6">
                                               <div class="form-group">
                                                  <label for="txtCorreo">Correo electrónico</label> 
                                                  <input id="txtCorreo" name="txtCorreo" type="email" class="form-control" required="required">
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <label for="txtContra">Crear contraseña</label> 
                                                  <input id="txtContra" name="txtContra" type="password" class="form-control" required="required">
                                                </div>          
                                        </div>
                                        <div class="col-md-3">
                                               <div class="form-group">
                                                  <label for="txtContra2">Confirmar contraseña</label> 
                                                  <input id="txtContra2" name="txtContra2" type="password" class="form-control" required="required">
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
                                        <div class="col-md-12 text-right">
                                          <input type="reset" id="btnLimpiar" name="btnLimpiar" class="btn btn-secondary" style="width: 150px;" value="Limpiar"/> 
                                          <input type="submit" id="btnRegistro" name="btnRegistro" class="btn btn-success" style="width: 150px;" value="Registrar" />                    
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-sm-12 text-center">
                                          <span style="font-weight: bold; font-size:15px; color:red; margin-top:10px"><?php echo $error;?>
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
    </div> <!-- termina CARD Body -->
    <div class="card-footer"> </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
  window.refreshcaptcha = function() {
    document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
}
</script>