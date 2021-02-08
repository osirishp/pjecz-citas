<?php
include ("funciones.php");
$link = conectarse();

  if($_POST['accion']=="declinar encuesta"){
    $respuesta = 4 ;    
  }
  if($_POST['accion']=="aceptar encuesta"){
    $respuesta = 3 ;    
  }
  
  $sql = "insert into encuesta (idUsuario, fecha, estatus) values ('$_SESSION[usuarioId]', NOW(), $respuesta) " ;
  echo  $sql;
  if($query = mysqli_query($link, $sql) ) {
    echo "encuesta registrada" ;
  }
  else{
    echo "error al insertar registro $sql " ;   
  }
?>