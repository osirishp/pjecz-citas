<?php
//if($_SESSION['age_con']==0){header('location:menu.php?acceso=NO');}
//if(isset($_POST['menu_x'])){header("location:menu.php");}
//include("seguridad.php");
include_once("funciones.php");
$link=conectarse(); 

if ($_GET[miFecha]){
	$miFecha = $_GET[miFecha];
	$_SESSION['miFecha']=$miFecha;
}else{
	if(isset($_SESSION['miFecha'])){
		$miFecha=$_SESSION['miFecha'];}
	else{	
		$miFecha = date("Y") . "-" . date("n");
		$_SESSION['miFecha']=$miFecha;
	}
}

$miFecha = explode("-", $miFecha);
$miMes = str_pad($miFecha[1], 2, '0', STR_PAD_LEFT);
$miMesX = $miFecha[1];
$miAnho = $miFecha[0];

/* Condicion para saber si es dia actual */
if ($miMes == date("n")){
	$miDia = date("j");
}else{
	$miDia = '';
}

/*** Arreglo con los meses ***/
$arrMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

/*** Arreglo con los dias de la semana ***/
$arrDSem  = array('Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado');

$sel = $arrMes[abs($miMes)];
if ($miMes == '12'){
	$mesProx = '01';
	$anhoProx = $miAnho + 1;
}else{
	$mesProx = $miMes + 1;
	$anhoProx = $miAnho;
}

if ($miMes == '01'){
	$mesAnt = '12';
	$anhoAnt = $miAnho - 1;
}else{
	$mesAnt = $miMes - 1;
	$anhoAnt = $miAnho;
}

$fechaProx = "$anhoProx-$mesProx";
$fechaAnt = "$anhoAnt-$mesAnt";
$Columnas = count($arrDSem) + 1;
$timestamp = mktime(0, 0, 0, $miMes, 1, $miAnho);
$dia1 = date("w", $timestamp);
?>

<link href="resources/css/fonts.css" rel="stylesheet" type="text/css">
<link href="resources/css/estilos.css" rel="stylesheet" type="text/css">
	
<link type="text/css" rel="stylesheet" href="resources/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT language="JavaScript" type="text/javascript" src="resources/dhtmlgoodies_calendar.js?random=20060118"></script>
<script language="javascript" type="text/javascript">
<!--
	function owin(nameFile){
		panta_ancho = screen.width;
		panta_alto = screen.height;
		centro_ancho = (panta_ancho/2) - (800/2);
		centro_alto = (panta_alto/2) - (600/2);
		props = "top = " + centro_alto + ", left = " + centro_ancho + ", width = 800, height = 600, scrollbars = yes, scrolling = yes, toolbar = no, status = no, location = no";
		url = nameFile;
		window.open(url,"Agenda",props);
	}
-->
</script>
</head>
<body>
<?php
if(isset($_POST['reporte_x'])){
	$fechaI = date('Y-m-d',mktime(0,0,0,substr($_POST['fecha_ini'], 3, 2),substr($_POST['fecha_ini'],0,2),substr($_POST['fecha_ini'],6,4)));
	$fechaF = date('Y-m-d',mktime(0,0,0,substr($_POST['fecha_fin'], 3, 2),substr($_POST['fecha_fin'],0,2),substr($_POST['fecha_fin'],6,4)));			
	if($fechaI<=$fechaF and $_POST['fecha_ini']!="" and $_POST['fecha_fin']!=""){
			$ventana="<script>ventana=owin('agprintrango.php?miFecha=$miAnho-$miMesX"; 
				if(isset($_POST['secretaria']) and $_POST['secretaria']!="Todas"){$ventana.="&secretaria=$_POST[secretaria]";}
				$ventana.="&finicial=$fechaI&ffinal=$fechaF";
			$ventana.="')</script>";
			echo $ventana;
	}
	else{ 
		$ventana= "<script>ventana=owin('agprintmens.php?miFecha=$miAnho-$miMesX"; 
			if(isset($_POST['secretaria']) and $_POST['secretaria']!=="Todas"){$ventana.="&secretaria=$_POST[secretaria]";}
		$ventana.="')</script>";
		echo $ventana;
	}
}
?>

	<script language="javascript">
		function nombres(fecha,div,url){
			$.post(
				url,
				{fecha:fecha},
				function(resp){
					$("#"+div+"").html(resp);	
				}
			);
		}
		
	</script>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
 
 
 
  </div>
  

	<div id="docMain" style="">
		<div id="docContent" >

			<div id="docWorkArea" align="center">
			<form name="agenda" method="post" action="calendario.php?miFecha=<?php echo $miAnho."-".$miMesX?>"> 
				<table cellpadding="15" align="center" width="100%" border="0">
					<tr>
						<td style='font-size:20px; font-weight:600; padding:20px;'  bgcolor='#DFEEE4'>
							Asistente&nbsp;&nbsp; 
							<select name='asistente' onChange='document.agenda.submit()'>
                            <?php
							echo "<option"; if($_POST['asistente']=="Todas"){echo " selected ";}echo ">Todas</option>";
							
							$sqlAsistentes = "select * from asistentes order by orden";
							$queryAsistentes=mysqli_query($link,$sqlAsistentes);
							
							
							if(isset($_GET['asistente']) and $_GET['asistente']!="Todas"){
								$_POST['asistente']=$_GET['asistente'];
								$_SESSION['Sasistente']=$_GET['asistente'];}
							else{
								if(isset($_POST['asistente']) and $_POST['asistente']!="Todas"){
									$_SESSION['Sasistente']=$_POST['asistente'];}
								else{
									if(isset($_POST['asistente']) and $_POST['asistente']=="Todas"){
										$_SESSION['Sasistente']="Todas";}
									else{
										if(isset($_SESSION['Ssecretaria'])){
											$_POST['asistente']=$_SESSION['Sasistente'];}
									}
								}
							}
							while($arreglo=mysqli_fetch_assoc($queryAsistentes)){
								echo "<option";if($_POST['asistente']==$arreglo['asistente']){echo " selected ";$_SESSION['Sasistente']==$_POST['asistente'];}echo ">$arreglo[asistente]</option>";
							}
							echo "</select>";
							echo "</td>";
						 ?>
					</tr>
				</table>
				<table cellpadding="5" align="center" border="0" width="100%" >		
					<tr>
						<td>
							<?php
								echo '<table align="center" border="0" cellspacing="0" cellpadding="10" bordercolor="#090" class="calendario" width="90%">';
								echo '<tr>';
								//echo '<td class="detalle">&nbsp;</td>';
								if(isset($_POST['asistente']) and $_POST['asistente']!="Todas"){$_secre="&asistente=$_POST[asistente]";}else{$_secre="";}
								echo '<td align="center" class="titulo"><a class="linkMes" href="' . $webURL . 'calendario.php?miFecha=' . $fechaAnt .$_secre. '"><img src="resources/imgs/img_prev.png" border="0" title="Mes Anterior"></a></td>';
								echo '<td align="center" colspan="5"><div id="mesEv">' . $sel . ' - ' . $miAnho . '</div></td>';
								echo '<td align="center" class="titulo"><a class="linkMes" href="' . $webURL . 'calendario.php?miFecha=' . $fechaProx .$_secre. '"><img src="resources/imgs/img_next.png" border="0" title="Mes Siguiente"></a></td></tr>';
								for ($c=0; !$ultimaSemana; $c++){
									if ($c == 0){
										echo '<tr>';
										for ($d=0; $d < count($arrDSem); $d++){
												if ($d == 0){
													//echo '<td class="detalle">&nbsp;</td>';
												}
												if ($arrDSem[$d] == 'Domingo' or $arrDSem[$d] == 'Sábado'){
													echo '<td class="hd" >' . $arrDSem[$d] . '</td>';
												}else{
													echo '<td class="hd">' . $arrDSem[$d] . '</td>';
												}
										}
										echo '</tr>';
									}else{
										echo '<tr>';
										if ($c == 1){ $x = $c; }
										for ($d=0; $d<$Columnas; $d++){
											$x = str_pad($x, 2, '0', STR_PAD_LEFT);
											if (date("t", $timestamp) == $x){ $ultimaSemana = 1; }
											if ($d == 0){
												//echo '<td class="detalle" height="100"><a href="' . $webURL . 'hub.php?modulo=detalle&miFecha=' . $miAnho . '/' . $miMes . '/' . $x . '"><img src="img/detalle.gif" border="0"></a></td>';
											}else{
												if (($d > $dia1 and $c == 1) or (checkdate($miMes, $x, $miAnho) and $c != 1)){
													if ($x == $miDia){
														$class = 'hoy';
													}else{
														$class = 'cal';
													}
													if($d==1){
														$class = "domingo"	;
													}
													echo '<td class="' . $class . '" width="150" height="100">';
													echo 	"<div class='dia'>	
																<a class='edit' onclick='nombres(\"$miAnho/$miMes/$x\",\"myModal\",\"nombres.php\")'  data-toggle='modal'  data-target='#myModal' href='javascript:void(0);' />".$x."</a>" ;
															//	<a class="edit" href="verdetalle.php?miFecha=' . $miAnho . '/' . $miMes . '/' . $x . '&diaSem=' . $d . '">' . $x . '</a><br>
													echo '</div>
															';
													echo 	'<div id="grupoEv">';
													echo  		buscarCitas("$miAnho/$miMes/$x");
													echo 	'</div>';
													echo '</td>';
													$x++;
												}else{
													echo '<td align="center" class="cal" width="100" height="100">&nbsp;</td>';
												}
											}
										}
									}
									echo '</tr>';
								}
							?>
						</td>
					</tr>
				</table>
				
			</form>
				<?php
					function buscarCitas($fecha){
						$fechaSQL = date("Y-m-d", strtotime($fecha));
						$link = conectarse(); 
						if(isset($_POST['asistente']) and $_POST['asistente']<>"Todas"){$_where=" asistente='".trim($_POST['asistente'])."' and ";}else{$_where="";}
						$sql="
								SELECT c.*,concat(cl.nombre,' ',cl.paterno,' ',cl.materno) as nombre FROM citas c
								inner join clientes cl on c.id_cliente = cl.id
								
								WHERE ".$_where." fecha <= '$fechaSQL' and fecha>='$fechaSQL' 
								order by hora ;";
//								echo $sql;
						$registros=0;
						if(	$res = mysqli_query($link, $sql) ){
							$registros = mysqli_num_rows($res) ;
						}
						if($registros>0){
							while($arreglo = mysqli_fetch_assoc($res)){
								if($arreglo['asistencia']=="Si"){$color="<font color='red'>";$colorf="</font>";}else{$color="";$colorf="";}
								if($_SESSION['usuario']=="ADMIN" or $_SESSION['cod_sec']=="100" or $_SESSION['cod_sec']=="109"){
									$verdetalle="<a href='act_con.php?id=$arreglo[id]' title='Ver Detalle' class='URLnombre'>";
								}
								else{
									if($arreglo['asistente']==$_SESSION['asistente']){
										$verdetalle="<a href='act_con.php?id=$arreglo[id]' title='Ver Detalle' class='URLnombre'>";
									}
									else{
										$verdetalle="";
									}
								}
	
								echo "<div id='eventos'> $verdetalle <font style='color:#333'>". substr($arreglo['hora'],0,5) ."</font>". $color."  ". substr($arreglo['nombre'],0,20)."..." .$colorf. " </a></div>";				
								$_SESSION['instruccion']=$sql;
								$cnt+=1;
							}
						}
					}
					
				?>
			</div>

		</div>
	</div>
    <br>
    <br>
</body>
</html>

<?php 
// echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>"; 
?> 
