<?php
include ("funciones.php");
error_reporting(0);
require("SendGrid/sendgrid-php.php");
$link = conectarse();
$invalida="";
$valida="";
$error="";

 if(isset($_GET['correo']) && !empty($_GET['correo']) AND isset($_GET['usuarioId']) && !empty($_GET['usuarioId']))
    {
        $email = $_GET['correo'];
        $usuarioId = $_GET['usuarioId'];  
        //$hash = $_GET['hash']; 

        $sqlUsuario =  "select email, activo from usuario where email = '$email' and id = '$usuarioId' and activo = 1";
       
        $resultUsuario = mysqli_query($link,$sqlUsuario);
        $TotalUsu = mysqli_num_rows($resultUsuario);

        if($TotalUsu > 0)
        {
             $valida = 'Cuenta activa, ingresar nueva contraseña';

             if(isset($_POST['btnContra']))
              {      
                $contra=$_POST['txtContra'];
                $newContra=$_POST['txtNewContra'];
                  
                  if($contra!=$newContra)
                  {   
                    $error = "Las contraseñas no coindiden";
                    $tipoalerta = "warning";    
                  }
                  else
                  {
                    $contra = MD5($contra);
                    $sqlNewContra = "UPDATE usuario set password='$contra' where email = '$email' and id = '$usuarioId' and activo = 1";  
                    $resultNewContra = mysqli_query($link,$sqlNewContra);

                    $error = "Contraseña actualizada correctamente"; 
                    $tipoalerta = "success";    
                  }        
              }
        }
        else
        {
            $invalida = 'No se encontro el correo o la cuenta fue desactivada';
        }    
    }
    else
    {
      $invalida = 'URL invalida';
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
                                <label for="" class="text-left">Nueva contraseña</label>
                                <input type="password" class="form-control" placeholder="Contraseña" id="txtContra" name="txtContra" required>
                              </div>
                              <div class="form-group">
                                <label for="" class="text-left">Confirmar contraseña</label>
                                <input type="password" class="form-control" placeholder="Confirmar Contraseña" id="txtNewContra" name="txtNewContra" required>
                              </div>
                              <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-dark" name="btnContra" id="btnContra" style="background-color: #163B67; color: #fff">
                                <i class="fas fa-sign-in-alt"></i>Cambiar contraseña</button>
                                <br> <br>
                              </div>
                              <div class="col-sm-12 text-center">
                                <span style="font-weight: bold; font-size:15px; color:green; margin-top:10px"><?php echo $valida;?></span>
                                <span style="font-weight: bold; font-size:15px; color:red; margin-top:10px"><?php echo $invalida;?></span>
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