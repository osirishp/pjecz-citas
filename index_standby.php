<?php
session_start();
$_SESSION=array();
session_destroy();

?>
<!DOCTYPE html>
<html style="height:100%;">
<head>
<title>Sistema de Citas :: Poder Judicial del Estado de Coahuila</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="resources/css/style.css" rel="stylesheet" type="text/css" media="all" />
 <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet"> 

<style>
    font-family: 'Roboto', sans-serif;
</style>
</head>
	
<body style="height:100%;">
<!-- banner -->

<div class="banner">
	<div class="container h-100"  >
        <div class="row justify-content-center align-items-center h-100"  >
            <div class="col-lg-8 col-md-10 col-sm-12 h-100" >
                
                <div class="card mx-auto" style="margin-top: 300px;">
                    <div class="card-header text-center">
                    	<img src="resources/imgs/logo_pjecz.png" width="300" alt="">
                    </div>
                    <div class="card-body">
                        <form name="forma" method="post" action="control.php">
                            <div class="form-group row">
                                <label for="usuario" class="col-md-4 col-form-label text-md-right">Usuario</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="usuario" id="usuario" onfocus="this.value = '';" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="password" name="password" id="password" value="" onfocus="this.value = '';"  required="">
                                </div>
                            </div>
				
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    Ingresar
                                </button>
                                <br>
                                <a href="#" class="btn btn-link">
                                    Olvidaste tu password?
                                </a>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

</div>
</body>
</html>