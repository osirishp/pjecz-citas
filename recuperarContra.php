<?php
include ("funciones.php");
error_reporting(0);
require("SendGrid/sendgrid-php.php");
$link = conectarse();
$error="";

if(isset($_POST['btnContra']))
{
    $correo=$_POST['txtEmail'];

    if($correo =="")
    {
        $error="Ingresar correo electronico";
    }
    else
    {
      $sqlExisteUsu = "select concat(nombre,' ',apPaterno,' ', apMaterno) as Nombre, id, email from usuario where email = '$correo'";

      $resultExisteUsu = mysqli_query($link,$sqlExisteUsu);
      $row = mysqli_fetch_array($resultExisteUsu,MYSQLI_ASSOC);
      $count = mysqli_num_rows($resultExisteUsu);

      if($count == 1)
      {
        $nombreCompleto = utf8_encode($row['Nombre']);
        $usuarioId = $row['id'];
        //$activo = $row['email'];

      $para = $correo;

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("citas@pjec.gob.mx", "Poder Judicial del Estado de Coahuila");
        $email->setSubject("Recuperar contraseña(Sistema de citas)");
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
                  <h3>Recuperar contraseña</h3>
                  Usted ha solicitado restablecer su contraseña para el Sistema de Citas en Línea del Poder Judicial 
                  del Estado de Coahuila. A continuación le proporcionamos los detalles de su cuenta y el vínculo para
                  restablecer su contraseña. Si usted no hizo esta petición por favor elimine inmediatamente este 
                  mensaje.<br><br>
                  <h3>Detalles de la cuenta</h3>
                </td>
              </td>
              <tr>
                <td>
                  <label>Nombre:</label>
                </td>
                <td>
                  <label>$nombreCompleto</label>
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
              <td colspan=2 style='text-align:center'>
                <br><a href='https://citas.poderjudicialcoahuila.gob.mx/verificarContra.php?correo=$correo&usuarioId=$usuarioId' style='box-sizing: border-box; border-color: #348eda; font-weight: 400; text-decoration: none; display: inline-block; margin: 0; color: #ffffff; background-color: #495769; border: solid 1px #348eda; border-radius: 2px; cursor: pointer; font-size: 14px; padding: 12px 45px'>Cambiar contraseña</a>
              </td>
            </tr>
          </table>
        </body>
          ");

        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            $emailEnv="1";
        } catch (Exception $e) {
            $emailEnv="0";
        }

          if($emailEnv=="1")
          {
              $error = "Se envio un correo a su cuenta ".$correo; 
              $tipoalerta ='success'; 
          }
          else
          {
              $error = "Error en el envio, vuelve a intentarlo";
              $tipoalerta ='warning';
          }
      }
      else
      {
        $error = "El correo no se encuentra registrado ".$correo; 
        $tipoalerta ='info';  
      }
    }
}
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

<body>
<?php include("header.php"); ?>
<br>
<div class="container" id="grad1">
    <div class="card-body text-center" >
        <div class="row justify-content-center" >
            <div class="col-md-6">
          
                    <h2><strong>Recuperar contraseña</strong></h2>
                    <div class="row text-center">
                        <div class="col-md-12 text-center">                        
                          <div class="container text-left">                  
                            <form class="col-12" method="post">
                              <div class="form-group"><br>
                                <label for="" class="text-left">Correo electronico</label>
                                <input type="email" class="form-control" placeholder="correo@ejemplo.com" id="txtEmail" name="txtEmail" required>
                              </div>
                              <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-success" name="btnContra" id="btnContra" style="background-color: #163B67; color: #fff">
                                <i class="fas fa-sign-in-alt"></i>Enviar</button>
                                <br><br>
                              </div>
                              <div class="col-sm-12 text-center">
                                <a href="registroPJ.php" class="btn btn-secondary" style="color:white">Registrarme</a>
                                <br><br>
                              </div>
                              
                            </form>   
                          </div>
                        </div>
                    </div>
            </div>
        </div>
    </div> <!-- termina CARD Body -->
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php if($error!=''){echo "<script>swal('$error','$subtitulo','$tipoalerta')</script>";}?>