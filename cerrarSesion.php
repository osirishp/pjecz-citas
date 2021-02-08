<?php
include ("funciones.php");
$link = conectarse();

//Bitacora
$ip = $_SERVER['REMOTE_ADDR']; //aohp
$usuarioId = $_SESSION['usuarioId'];
$sqlBitacora = "insert into bitacoras (idUsuario, ip, fecha, movimiento) values	('$usuarioId','$ip',now(),'cerro sesion')";
//echo $sqlBitacora ; exit;
$resultBitacora = mysqli_query($link,$sqlBitacora);

     session_start(); //Inicia todas las variables de sesion
     session_unset(); //Libera todas las variables de sesion
     session_destroy(); //Destruye toda la informacion registrada de una sesion
     header("Location: index.php");
?>
