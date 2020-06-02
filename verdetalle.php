<?php
if(isset($_POST['menu_x'])){header("location:agenda.php");}
//include("seguridad.php");
include("funciones.php");
$link=conectarse(); 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
	@import "styles/estilos.css";
-->
</style>
</head>
<body>
	<div id="docMain">
		<div id="docHeader">
			<table cellspacing="0" cellpadding="0" width="100%" height="100%" border="0">
				<tr>
					<td width="34"><img src="img/hdleft.jpg"></td>
					<td width="150" class="enk"><img src="img/hdlogo.jpg"></td>
					<td width="*" class="enk"><img src="img/hdtitulo.jpg"><br><font color='white' size='4' face="Trebuchet MS"><?php echo $_SESSION['secretaria'];?></font></td>
					<td width="34"><img src="img/hdright.jpg"></td>
				</tr>
			</table>
		</div>
		<div id="docContent">
			<div id="docWorkArea" align="center">
	<div id="docWorkArea" align="center">
	<form name="forma" method="post" action="verdetalle.php">
	<table cellpadding="20" align="center" border="0" width="50%">
		<tr>
			<td>
				<?php
					$fecha = $_GET['miFecha'];
					$diaSemana = $_GET['diaSem'];
					$arrDSem[1] = 'Domingo';
					$arrDSem[] = 'Lunes';
					$arrDSem[] = 'Martes';
					$arrDSem[] = 'Mi&eacute;rcoles';
					$arrDSem[] = 'Jueves';
					$arrDSem[] = 'Viernes';
					$arrDSem[] = 'Sábado';
					$arrMes[1]='Enero';
					$arrMes[]='Febrero';
					$arrMes[]='Marzo';
					$arrMes[]='Abril';
					$arrMes[]='Mayo';
					$arrMes[]='Junio';
					$arrMes[]='Julio';
					$arrMes[]='Agosto';
					$arrMes[]='Septiembre';
					$arrMes[]='Octubre';
					$arrMes[]='Noviembre';
					$arrMes[]='Diciembre';
					$fechaSQL = date("Y-m-d", strtotime($fecha));
					$link = conectarse(); 
					$sql="
							SELECT * FROM citas c
							inner join clientes cl on c.cliente = cl.id
							WHERE fecha_ini <= '$fechaSQL' and fecha_fin>= '$fechaSQL'
							ORDER BY hora;";
					$res = mysql_query($sql, $link);
					$miVar = 1;
					echo '<table width="100%">';
					echo '<tr><td colspan="2" class="hd" align="center">' . $arrDSem[$diaSemana] . ' ' . date("j \d\e ", strtotime($fecha)) .$arrMes[date("n",strtotime($fecha))]. '</td></tr>';
					if (mysql_num_rows($res)){
						while($row = mysql_fetch_array($res)){
							if (($miVar % 2) != 0){
								echo '<tr>';
							}
							$miFechaIni = explode("-", $row['fecha_ini']);
							$miFechaFin = explode("-", $row['fecha_fin']);
							$anhoIni = intval($miFechaIni[0]);
							$mesIni = intval($miFechaIni[1]);
							$diaIni = intval($miFechaIni[2]);
							$anhoFin = intval($miFechaFin[0]);
							$mesFin = intval($miFechaFin[1]);
							$diaFin = intval($miFechaFin[2]);
							$miMes[1] = "Enero";
							$miMes[] = "Febrero";
							$miMes[] = "Marzo";
							$miMes[] = "Abril";
							$miMes[] = "Mayo";
							$miMes[] = "Junio";
							$miMes[] = "Julio";
							$miMes[] = "Agosto";
							$miMes[] = "Septiembre";
							$miMes[] = "Octubre";
							$miMes[] = "Noviembre";
							$miMes[] = "Diciembre";
							$mesIni = $miMes[$mesIni];
							$mesFin = $miMes[$mesFin];							
							echo '<td width="50%">';
							echo	 '<div style="margin:5px;">';
							echo 		'<div id="detalleEv">';
							echo			'<div id="Contexto"><em>' . $row['hora'] . ' Hrs.</em><br>';
							echo 		'</div>';
							echo 		'';
							echo 			$row['nombre'] . '<br>';
							echo 		'</div>';
							echo 	'</div>';
							echo '</td>';
							$miVar++;
							if (($miVar % 2) != 0){
								echo '</tr>';
							}
							
						}
					}else{
						echo '<tr><td><div>No hay eventos programados para este d&iacute;a</div></td></tr>';
					}
					echo '</table>';
				?>
			</td>
		</tr>
		<tr>
			<td align="right"><br>
				<input type='image' name='menu' src='img/btn_calendar.png' alt='Regresar a la Agenda de Trabajo'> 
			</td>
		</tr>
		
	</table>
	</form>
</div>			
			</div>
			<div id="docFooter" style="background-color: transparent;">
				&copy;2020 Derechos Reservados  Poder Judicial del Estado de Coahuila			</div>
		</div>
	</div>
</body>
</html>

<?php 
// echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>"; 
?> 
