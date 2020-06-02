<?php
	include("funciones.php");
	$link = conectarse();
	error_reporting(0);
?>
   <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color:#900">
<!--          <button type="button" class="close" data-dismiss="modal" value='Cerrar ventana'></button> -->
          <?php /*** Arreglo con los meses ***/
			$arrMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); ?>
          <h4 class="modal-title" style="color:#FFF">Reporte de <?php echo $_POST['tipo']; ?></h4>
        </div>
        <div class="modal-body" style="text-align:left">
        
		
            <div class="table-responsive-sm">
            
                <table class="table table-responsive table-striped"  >
    
    			<?php        
					
							$fechaIni = explode("/",$_POST['fechaIni']) ;
							$fechaIni = $fechaIni[2]."/".$fechaIni[1]."/".$fechaIni[0] ;
							$fechaFin = explode("/",$_POST['fechaFin']) ;
							$fechaFin = $fechaFin[2]."/".$fechaFin[1]."/".$fechaFin[0] ;
							
							$where="" ;
							if($_POST['fechaIni']!=""){$where=" where c.fecha between '$fechaIni' and '$fechaFin' and c.asistente='$_POST[asistente]'" ;}
							
                            $sql = "SELECT concat(u.nombre,' ', u.paterno, ' ', u.materno) as nombrecompleto ,
							s.servicio, c.id_servicio,c.id_beneficiario,c.hora,c.fecha,c.id_juzgado,c.estatus from citas c 
							
							inner join usuarios u on c.id_beneficiario = u.id
							inner join servicios s on c.id_servicio = s.id
							
							$where
							order by c.fecha desc" ;
							$query= mysqli_query($link, $sql);	
							?>
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
            
                 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar ventana</button>
        </div>
      </div>
      
    </div>