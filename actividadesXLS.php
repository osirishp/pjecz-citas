<?php 
	if(md5("=Cita5@EnLinea=") == $_GET['token']){
		include_once("funciones.php") ;
		$link = conectarse() ;

		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=actividades$_GET[dia].xls");

		$sql = "SELECT 	
								concat(u.nombre,' ', u.apPaterno, ' ', u.apMaterno) as nombrecompleto ,
								s.servicio, 
								c.id_servicio,
								c.id_beneficiario,
								c.hora,
								c.fecha,
								c.id_juzgado,
								c.id,
								c.detalles,
								c.estatus,
								u.celular,
								c.expediente1,
								c.expediente2,
								c.expediente3,
								c.expediente4,
								c.expediente5
								from citas c 
				
				inner join usuario u on c.id_beneficiario = u.id
				inner join cat_servicios s on c.id_servicio = s.id
				
				
				where c.fecha='$_GET[dia]' and c.id_juzgado='$_GET[ij]'  
				order by c.id_juzgado, c.hora " ;
				
				$query = mysqli_query($link, $sql);
			
				$html.= "
					<table>
						<thead>
							
							<tr>
								<th style='background-color:#ccc; font-size:1em; color:#333;'>Hora</th>
								<th style='background-color:#ccc; font-size:1em; color:#333;'>Nombre completo</th>
								<th style='background-color:#ccc; font-size:1em; color:#333;'>Servicio</th>
								<th style='background-color:#ccc; font-size:1em; color:#333;'>Detalles</td>
								<th style='background-color:#ccc; font-size:1em; color:#333;'>Tel&eacute;fono</th>
							</tr>
						</thead>
						<tbody>";
			
							while($datos = mysqli_fetch_assoc($query)){
							
								$html.="
									<tr >
										<td style='color:#555'>$datos[hora]</td>
										<td style='color:#555'>$datos[nombrecompleto]</td>
										<td style='color:#555'>$datos[servicio]<br>$datos[expediente1]  $datos[expediente2]  $datos[expediente3]  $datos[expediente4]  $datos[expediente5] </td>
										<td style='color:#555'>$datos[detalles]</td>
										<td style='color:#555'>$datos[celular]</td>
									</tr>";
									
							}
				$html."
						</tbody>
					</table> ";
				echo utf8_decode($html) ;
	}
 ?>