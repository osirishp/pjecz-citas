<?php
//if($_SESSION['age_con']==0){header('location:menu.php?acceso=NO');}
//if(isset($_POST['menu_x'])){header("location:menu.php");}
//include("seguridad.php");
include_once("funciones.php");
$link=conectarse(); 
date_default_timezone_set('America/Mexico_City');

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
$Columnas = count($arrDSem) ;
$timestamp = mktime(0, 0, 0, $miMes, 1, $miAnho);
$dia1 = date("w", $timestamp);
?>

	<script language="javascript">
		function nombres(fecha,div,url,juzgado,administrador){
			$.post(
				url,
				{fecha:fecha,
				juzgado:juzgado,
				administrador:administrador},
				function(resp){
					$("#"+div+"").html(resp);	
				}
			);
		}
		
	</script>  


<div class="container-fluid">
	  
	  <header>
	    <h4 class="display-4 mb-4 text-center" style="font-size:3vw;">
	    <?php
			if(isset($_POST['juzgado']) and $_POST['juzgado']!="Todas"){$_secre="&juzgado=$_POST[juzgado]";}else{$_secre="";}
			echo '	
				<a class="linkMes" style="padding-left:30px; padding-right:30px; " href="' . $webURL . 'calendario.php?miFecha=' . $fechaAnt .$_secre. '"><img src="resources/imgs/img_prev.png" border="0" title="Mes Anterior" width="30"></a>'
					 . $sel . ' - ' . $miAnho . 
				'<a class="linkMes" style="padding-left:30px; padding-right:30px; " href="' . $webURL . 'calendario.php?miFecha=' . $fechaProx .$_secre. '"><img src="resources/imgs/img_next.png" border="0" title="Mes Siguiente"  width="30"></a>';
		?>
	        
	    </h4>

		<div class="container-fluid" style="text-align: left" >
				<form name="agenda" method="post" action="calendario.php?miFecha=<?php echo $miAnho."-".$miMesX?>"> 
	            	<input type="hidden" value="" id="IDServicio" />
	            	<input type="hidden" value="" id="IDCita" />
	            
	            	<?php if($_SESSION['administrador']==1){ ?>
	            
					<div class="row">
						<div class="col-md-3 col-md-offset-1 ">
							<div class="fomr-group">	
								<label for="">Juzgado</label> 
								<select name='juzgado' onChange='document.agenda.submit()' class="form-control">
	                            	<option></option>
		                            <?php	
									$sqljuzgados = "select j.*,cit.citas from juzgados j
														left join (select c.id_juzgado,count(id) as citas from citas c  where year(c.fecha)=2020 and month(c.fecha)=05 group by id_juzgado) cit on j.id = cit.id_juzgado  
												
													order by distrito,juzgado";
									$queryjuzgados=mysqli_query($link,$sqljuzgados);
								
									while($arreglo=mysqli_fetch_assoc($queryjuzgados)){
										echo "<option $selected value='$arreglo[id]' >($arreglo[citas]) $arreglo[juzgado]</option>";
									} ?>
								</select>			
							</div>			
						</div>	
					</div>
	            <?php } 
				else{
					$_POST['juzgado'] = $_SESSION['juzgado'] ;
				}?>
		</div>

	    <div class="row d-none d-sm-flex p-1 bg-dark text-white">
	     	<?php
	        for ($d=0; $d < count($arrDSem); $d++){
				echo "<h5 class='col-sm p-1 text-center'>	$arrDSem[$d] </h5>" ;
	        }
			?>
	    </div>
	 </header>


	<div class="row border border-right-0 border-bottom-0" style="padding-bottom: 60px;" >

		<?php
            if(isset($_POST['juzgado']) and $_POST['juzgado']!="Todas"){$_secre="&juzgado=$_POST[juzgado]";}else{$_secre="";}

            for ($c=1; !$ultimaSemana; $c++){
                    if ($c == 1){ $x = $c; }
                    
					for ($d=1; $d<$Columnas+1; $d++){
                        $x = str_pad($x, 2, '0', STR_PAD_LEFT);
                        if (date("t", $timestamp) == $x){ $ultimaSemana = 1; }

                            if (($d > $dia1 and $c == 1) or (checkdate($miMes, $x, $miAnho) and $c != 1)){
								if($d==1 or $d==7){ //SI ES SABADO o DOMINGO
									$tipoDiv = "<div class='day col-sm p-2 border border-left-0 border-top-0 text-truncate ' style='background-color:#efeff1' >" ;		
								}
								else{
									$tipoDiv = "<div class='day col-sm p-2 border border-left-0 border-top-0 text-truncate ' >" ;
								}
									$dia = $x ;
									$x++ ;
								
                            }
                            else{
								$tipoDiv = "<div class='day col-sm p-2 border border-left-0 border-top-0 text-truncate d-none d-sm-inline-block bg-light text-muted' >";                                
								$dia="";
                            }

                            echo $tipoDiv ;

                            echo "<h5 class='row align-items-center'>
                                    <span class='date col-1'>";
									if( $d!=1 and   $d!=7){ /*SI ES SABADO o DOMINGO */
                                      echo "<a class='edit' onclick='nombres(\"$miAnho/$miMes/$dia\",\"myModal\",\"nombres.php\",\"$_SESSION[juzgado]\",\"$_SESSION[administrador]\")'   data-toggle='modal'  data-target='#myModal' href='javascript:void(0);'  />".$dia."</a>	";
									}
									else{
										echo "<a class='edit' href='javascript:void(0);'  />".$dia."</a>	";
										}
                            echo "  <small class='col d-sm-none text-center text-muted'> ",$arrDSem[$d-1],"</small>
                                      <span class='col-1'></span>
                                    </span>
                                </h5>
                                <div id='grupoEv' style='height:auto;'>",
                                    buscarCitas("$miAnho/$miMes/$dia") ,
                                "</div>";
                                
                               
                            
                            echo "</div>"; /*  Termina el Div del dia completo   */
                    }
					echo "    <div class='w-100'></div>" ;

            }
            
        ?>
   	</div>    
	</form>
				<?php
					function buscarCitas($fecha){
						$fechaSQL = date("Y-m-d", strtotime($fecha));
						$link = conectarse(); 
						$_where="";
						if(isset($_POST['juzgado']) and $_POST['juzgado']!=""){
							$_where=" c.id_juzgado='".trim($_POST['juzgado'])."' and ";
						}
						if(isset($_SESSION['id_juzgado']) and $_SESSION['id_juzgado']!=""){
							$_where=" c.id_juzgado='".trim($_SESSION['id_juzgado'])."' and ";
						}
						
						$sql="
								SELECT c.*,concat(u.nombre,' ',u.apPaterno,' ',u.apMaterno) as nombre FROM citas c
								inner join usuario u on c.id_beneficiario = u.id
								
								WHERE ".$_where." fecha <= '$fechaSQL' and fecha>='$fechaSQL' and (c.estatus!='Eliminada' or c.estatus IS NULL )
								order by hora ;";
							//echo $sql;
						$registros=0;
						if(	$res = mysqli_query($link, $sql) ){
							$registros = mysqli_num_rows($res) ;
						}
						if($registros>0){
							while($arreglo = mysqli_fetch_assoc($res)){
								$font="";
								$inicioA ="";
								$finA = "";
								$imagenC ="";

								if($arreglo['estatus']=="Cancelada"){
									$font = "<font color='red'>";
								}
								if( ($arreglo['estatus']=="Agendada" and empty($arreglo['asistio']) and $fechaSQL<date('Y-m-d'))
									or 
									($arreglo['estatus']=="Agendada" and empty($arreglo['asistio']) and $fechaSQL==date('Y-m-d') and substr($arreglo['hora'],0,5) < date("H:i") )
								){
									$font = "<font style='color:#555; font-weight: ligther' >" ;
									$inicioA = "<a href='javascript:void(0);' onclick='pasarCitaID($arreglo[id])' id='verificar_cita'>" ;
									$finA = "</a>" ;
								}
								if($arreglo['estatus']=="Agendada" and $arreglo['asistio']=="SI"){
									$font = "<font style='color:#555; font-weight: ligther'>";
									$imagenC = "<img src='resources/imgs/img_si_asistio.png' width='20' >" ;
								}
								if($arreglo['estatus']=="Agendada" and $arreglo['asistio']=="NO"){
									$font = "<font style='color:#555; font-weight: ligther'>";
									$imagenC = "<img src='resources/imgs/img_no_asistio.png' width='20' >" ;
								}

								/*if($arreglo['estatus']!="Confirmada" and $arreglo['fecha']>=date('Y-m-d')){
									$inicioA = "<a href='javascript:void(0);' onclick='pasarID($arreglo[id])' id='agendar_cita'>" ;
									$finA = "</a>" ;
								}*/
								if($arreglo['estatus']==""){
									$font = "<font style='color:#666; font-weight: ligther'>";
								}
								
								
	
								echo  "
									<div id='eventos'>$imagenC $inicioA $font". substr($arreglo['hora'],0,5) ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". strtoupper($arreglo['nombre']) ." $finA</font> </div>
									";				
								$_SESSION['instruccion']=$sql;
								$cnt+=1;
							}
						}
					}
					
				?>

<?php 
// echo "<br><br><hr><h4>Depuracion</h4><pre>" . print_r($GLOBALS,1) . "</pre>"; 
?> 

 <script language="javascript">
		 $(document).on('click', '#agendar_cita', function() {
			$('#ModalAgendarCita').modal('show');
			document.getElementById('divjuzgado').style.display = 'none' ;
			document.getElementById('divHora').style.display = 'none' ;			
			document.getElementById('fecha_nueva').value = '' ;
		});
		
		 $(document).on('click', '#verificar_cita', function() {
			$('#ModalVerificarCita').modal('show');
		});

		 $(document).on('click', '#GuardarCitas', function() {
			 	var valor = document.getElementById('IDServicio').value ;
			 	xajax_reagendarCita();
		});

		 $(document).on('click', '#GuardarCita', function() {
			 	var id = document.getElementById('IDServicio').value ;
				var fecha = document.getElementById('fecha_nueva').value ;
				var hora = document.getElementById('hora').value ;
				var juzgado = document.getElementById('juzgado').value ;
			 	xajax_reagendarCita(id,fecha,juzgado,hora );

		}); 
		
		function pasarID(ID){
			document.getElementById('IDServicio').value = ID ; 	
		}
		

		function pasarCitaID(ID){
			document.getElementById('IDCita').value = ID ; 	
		}
	</script>
    
    <div id="ModalAgendarCita" class="modal fade " role="dialog" > 
    	<div class="modal-dialog modal-lg">
    		<div class="modal-content">
    			<div class="modal-header" style="background-color:#F00; color:#FFF"> 
    				<h4 class="modal-tittle">Re-agendar cita</h4>
    			</div> 
    			<form class="form-horizontal" role="form" id="form-agregar" name="form_agregar">
    				<div class="modal-body col-md-12"> 
                    
 							<div class="row col-md-12" style="border:0px #FF0000 solid;  margin:0 auto; ">
                                <div class="form-group col-md-12">
                                
						            <div class="col-md-3">
                                        <label for="">Dia a agendar</label>
                                         <div class="book_date">
                                            <div class="input-group date">
                                              <input type="text"  name="fecha_nueva" id="fecha_nueva" class="form-control"  onBlur="juzgadoYhora();" onChange="javascript:verjuzgado();"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                            </div>    
                
                                            <!-- Include Date Range Picker -->
                                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
                                             
                                             
                                            <script>
                                                $(document).ready(function(){
                                                    var date_input=$('input[name="fecha_nueva"]'); //our date input has the name "date"
                                                    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                                    date_input.datepicker({
                                                        format: 'dd/mm/yyyy',
                                                        autoclose: true,
														container: container,
                                                        todayHighlight: true,
                                                    })
                                                })
                                                
                                                function verjuzgado(){
													document.getElementById("divjuzgado").style.display = "block";
                                                }
												
												function juzgadoYhora(){
													var x = document.getElementById('hora') ; x.selectedIndex ='0' ; 
													var x = document.getElementById('juzgado') ; x.selectedIndex ='0' ; 
													document.getElementById('divjuzgado').style.display='none' ;	
													document.getElementById('divHora').style.display='none' ;	
												}
                                            </script>
                                        </div>
                                 	</div> 
                            
                                
                                     <div class="col-md-6" id="divjuzgado" style="display:none">
                                        <label for="juzgado">Juzgado</label>
                                            <select name="juzgado" id="juzgado" class="form-control" onchange="xajax_buscarHorarios(this.value,document.getElementById('fecha_nueva').value)">
                                                <option value=""></option>
                                                <?php
                                                $sql = "select * from juzgados where id  = '$_SESSION[id_juzgado]' order by juzgado " ;
                                                $query = mysqli_query($link,$sql);
                                                while($datos = mysqli_fetch_assoc($query)){
                                                    echo "<option value='".utf8_encode($datos['id'])."'>".utf8_encode($datos['juzgado'])."</option>";
                                                    }
                                                ?>
                                            </select>
                                       </div>                        
                                       <div class="col-md-3">
                                            <div id="divHora" style="width:100%; display:none; border: 0px solid #555">
                                                <label for="hora">Hora</label>
                                                <select name="hora" id="hora" class="form-control" onchange="verBotonGuardar(this.value);">
                                                    <option></option>
                                                </select>
                                            </div>
                                       </div>           
                                       
	                                      
                                               
                            	</div> 
                            </div> 
                    
    					
    				</div>
    				<div class="modal-footer">
                    	<div id="divReagendar">

                        </div>
    					<button type="button" class="btn btn-default" data-dismiss="modal">
    						<span class="glyphicon glyphicon-remove"></span><span class="hidden-xs"> Cerrar</span>
    					</button>
    					<button type="button" id="GuardarCita" name="GuardarCita" class="btn btn-primary" style='display:none;' >
    						<span class="fa fa-save"></span><span class="hidden-xs"> Guardar</span>
                          
    					</button>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>   

    <script language="javascript">
		function verBotonGuardar(valor){
			if(valor!=""){
				document.getElementById('GuardarCita').style.display='block' ;
			}
		}

		
	</script>    
    

    <div id="ModalVerificarCita" class="modal fade " role="dialog" > 
    	<div class="modal-dialog modal-md">
    		<div class="modal-content">
    			<div class="modal-header" style="background-color:#efeff1; color:#333"> 
    				<h4 class="modal-tittle">Definir si hubo asistencia a la cita</h4>
    			</div> 
    			<form class="form-horizontal" role="form" id="form-agregar" name="form_agregar">
    				<br>
    				<div class="modal-body col-md-12"> 

 							<div class="row col-md-12" style="border:0px #FF0000 solid;  margin-bottom:20px; ">
 									<center><h1> Asistio el usuario a la cita ?</h1></center>
                            </div>                    
                    
 							<div class="row col-md-12" style="border:0px #FF0000 solid;  margin:0 auto; ">
                                <div class="col-md-5">
									<button type="button" id="CitaSI" class="btn btn-success btn-lg">Si asistio</button>
                            	</div> 

                            	<div class="col-md-5 col-sm-offset-2">
									<button type="button" id="CitaNO" class="btn btn-danger btn-lg">No asistio</button>                             
                            	</div> 
                            </div>                    
   					
    				</div>
    				<br><br>
    				<div class="modal-footer" style="background-color:#efeff1 ">
    					<button type="button" class="btn btn-default" data-dismiss="modal">
    						<span class="glyphicon glyphicon-remove"></span><span class="hidden-xs"> Cerrar</span>
    					</button>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>  

	<script language="javascript">
		 $(document).on('click', '#CitaSI', function() {
			 	var id = document.getElementById('IDCita').value ;
			 	xajax_verificarCita(id,"SI");
		});    
		 $(document).on('click', '#CitaNO', function() {
			 	var id = document.getElementById('IDCita').value ;
			 	xajax_verificarCita(id,"NO");
		});    

	</script>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                           <!--ASIGNAMOS UN ID A ESTE DIV -->
    </div>