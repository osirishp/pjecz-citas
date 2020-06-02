<?php
include ("funciones.php");
$link = conectarse();
error_reporting(0);

$sql = "SELECT concat(cl.nombre,' ', cl.paterno, ' ', cl.materno) as nombrecompleto ,
s.servicio, c.id_servicio,c.id_cliente,c.hora,c.fecha,c.asistente,c.estatus from citas c 

inner join clientes cl on c.id_cliente = cl.id
inner join servicios s on c.id_servicio = s.id

order by c.fecha desc" ;
$query= mysqli_query($link, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="resources/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />

</head>

<body style="width:70%; margin:0 auto;">

	<div class="table-responsive-sm">
        <table class="table table-responsive table-striped"  >
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre completo</th>
                    <th>Servicio</th>
                    <th>Asistente</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($datos = mysqli_fetch_assoc($query)){
                    echo "
                        <tr>
                            <td>$datos[fecha]</td>
                            <td>$datos[hora]</td>
                            <td>$datos[nombrecompleto]</td>
                            <td>$datos[servicio]</td>
                            <td>$datos[asistente]</td>
                            <td>$datos[estatus]</td>							
                        </tr>
                    ";
                }?>
            </tbody>
        </table>
	</div>
	
</body>
</html>