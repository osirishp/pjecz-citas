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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Sistema de Citas</title>
    <link href="css/style.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div id="header">
        <span style="font-weight: bold; font-size:15px; color:red; margin-top:10px"><?php echo $error;?></span>
        <div class="col-sm-2 text-left">
            <a style="font-weight: bold; font-size:15px; color:red; margin-top:10px" href="Login.php">Iniciar Sesion</a>
            <br><br>
        </div>
    </div>  
    <div id="wrap">
    </div>
</body>
</html>