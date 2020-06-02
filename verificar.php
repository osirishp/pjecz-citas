<?php
include ("funciones.php");
$link = conectarse();
$error="";

    if(isset($_GET['correo']) && !empty($_GET['correo']) AND isset($_GET['hash']) && !empty($_GET['hash']))
    {
        $email = $_GET['correo']; 
        $hash = $_GET['hash']; 

        $sqlUsuario =  "select email, hash, activo from usuario where email = '$email' and hash = '$hash' and activo = 0";
       
        $resultUsuario = mysqli_query($link,$sqlUsuario);
        $TotalUsu = mysqli_num_rows($resultUsuario);

        if($TotalUsu > 0)
        {
            $sqlActivar = "UPDATE usuario set activo='1' where email = '$email' and hash = '$hash' ";  
            $resultActivar = mysqli_query($link,$sqlActivar);

             $error = 'Cuenta activada';
        }
        else
        {
            $error = 'URL invalida';
        }
    }
    else
    {
    // Invalid approach
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

<link rel="stylesheet" href="resources/css/steps.css">
<script src="resources/js/steps.js"></script>
<link href="resources/imgs/calendario.ico" rel="shortcut icon" type="image/x-icon" />
<body>
    
<br>
<div class="container" id="grad1">
    <div class="card" style="border:1px solid #555">

    <div class="card-header">

        <div class="bannerx" >
          <div class="container">
            <div class="row">
              <div class="col-md-3" style="padding:0px 50px;">
                <img src='resources/imgs/logo_pjecz.png' width="220"> 
              </div>
              <div class="col-md-9 text-right" style="padding: 0px 50px;"><br>
                <span style="font-size: 2em">:: Sistema de Citas ::</span>  
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row justify-content-center mt-0" >
            <div class="col-11 col-sm-9 col-md-7 col-lg-10 text-center p-10 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-10 mt-3 mb-3">
                    <h2><strong>Bienvenido(a) al Sistema de Citas</strong></h2>
                    <div class="row">
                      <div class="container text-center">
                        <fieldset>
                          <div class="modal-dialog text-center">
                            <div class="col-md-12 main-section">
                              <div class="modal-content" style="background: transparent; border:#45543D 3px solid;">
                                <br>
                                <div class="col-12 user-img">
                                  <img src="resources/imgs/user.png" width="15%">
                                </div> 
                                <br>
                            <form class="col-12" method="post">
                              <div class="form-group" id="grupoUsu">
                                <span style="font-weight: bold; font-size:15px; color:red; margin-top:10px"><?php echo $error;?></span>
                              </div>
                              <div class="form-group" id="GrupoContra">
                               <a href="index.php" class="btn btn-success">Iniciar sesión</a> 
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
            </div>
        </div>
    </div> <!-- termina CARD Body -->
<div class="card-footer"> </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>