<?php
include ("funciones.php");
error_reporting(0);
$link = conectarse();
$invalida="";
$valida="";

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

             $valida = 'Cuenta activada';
        }
        else
        {
            $invalida = 'URL invalida';
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


<link href="resources/imgs/calendario.ico" rel="shortcut icon" type="image/x-icon" />
<script src='https://www.google.com/recaptcha/api.js'></script>

<body>
<?php include("header.php"); ?>
<br>
<div class="container" id="grad1">
    <div class="card-body text-center" >
        <div class="row justify-content-center" >
            <div class="col-md-6">
                    <h2><strong>Activación de cuenta</strong></h2>
                    <div class="row text-center">
                        <div class="col-md-12 text-center">                       
                          <div class="container text-center">
                            <div class="col-md-12 main-section">
                              <form class="col-12" method="post">
                                <div class="form-group" id="grupoUsu">
                                  <div class="col-sm-12 text-center">
                                  <span style="font-weight: bold; font-size:15px; color:green; margin-top:10px"><?php echo $valida;?></span>
                                  <span style="font-weight: bold; font-size:15px; color:red; margin-top:10px"><?php echo $invalida;?></span>
                                </div>
                                </div>
                                <div class="form-group" id="GrupoContra"><br><br>
                                 <a style="background-color: #163B67; color: #fff" href="index.php" class="btn btn-secondary">Iniciar sesión</a> 
                                </div>
                              </form>
                        </div>
             
                      
                                  </div>
           
                        </div>
                    </div>
          
            </div>
        </div>
    </div> <!-- termina CARD Body -->
</div>
