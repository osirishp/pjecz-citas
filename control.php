<?php 
include("funciones.php");

$link=conectarse();

session_start();

  
if (isset($_POST["usuario"]))
  {$usuario=$_POST["usuario"];}

if (isset($_POST["password"]))
	{$password=$_POST["password"];}

//	echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>"; exit ;
if (strlen($usuario)<=15 and strlen($password)<=15)	{
	$instruccion = "SELECT u.*,j.distrito,j.juzgado FROM usuarios u 
						left join juzgados j on u.id_juzgado = j.id 
					WHERE u.usuario='$usuario' and u.password='$password'"; 
	$query = mysqli_query($link,$instruccion); 
	$_SESSION['usuario']=strtoupper($usuario);


	if (mysqli_num_rows($query)!=0){  
	    $registros=mysqli_fetch_assoc($query);
	    $_SESSION["autentificado"]="SI"; 	
	    $_SESSION["usuario"] = $registros['usuario']; 
	    $_SESSION["cuentas"] = $registros['cuentas'];
        $_SESSION["nombre"] = $registros['nombre'];
		$_SESSION["asistente"] = $registros['asistente'];
		$_SESSION["id_juzgado"] = $registros['id_juzgado'];
		$_SESSION["idRol"] = $registros['idRol'];
		$_SESSION["distrito"] = $registros['distrito'];
		$_SESSION["juzgado"] = $registros['juzgado'];
	    $_SESSION["programas"] = $registros['programas'] ;
	    $_SESSION["administrador"] = $registros['administrador'] ;		
		$_SESSION["paterno"] = $registros['paterno'] ;
		$_SESSION["materno"] = $registros['materno'] ;
		$_SESSION["fecha_nacimiento"] = $registros['fecha_nacimiento'] ;
		$_SESSION["telefono"] = $registros['telefono'] ;
		$_SESSION["correo"] = $registros['correo'] ;
		$_SESSION["domicilio"] = $registros['domicilio'] ;
		$_SESSION["estado"] = $registros['estado'] ;
		$_SESSION["municipio"] = $registros['municipio'] ;
		$_SESSION['ultimoAcceso'] =  date("Y-n-j H:i:s");
		
		if($registros['id_juzgado']>0){
			header("Location: registro.php");
		}
		else{
			header("Location: steps.php");	
		}
	}
	else{ 
		$_SESSION["autentificado"] = "NO" ;
		header("Location: index.php");
 	}
}
else
{
	header("Location: registro.php"); 
}
	//echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>";

?> 

