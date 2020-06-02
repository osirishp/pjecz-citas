<?php 
include('funciones.php');
$link = conectarse() ;

$sql = "SELECT concat(cl.nombre,' ', cl.paterno, ' ', cl.materno) as nombrecompleto ,
s.servicio, c.id_servicio,c.id_cliente,c.hora,c.fecha,c.asistente,c.estatus from citas c 

inner join clientes cl on c.id_cliente = cl.id
inner join servicios s on c.id_servicio = s.id

order by c.fecha desc" ;
$query= mysqli_query($link, $sql);

$html = "
        <table style='font-size:10px' >
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
            <tbody>";

                while($datos = mysqli_fetch_assoc($query)){
                    $html.= "
                        <tr>
                            <td>$datos[fecha]</td>
                            <td>$datos[hora]</td>
                            <td>$datos[nombrecompleto]</td>
                            <td>$datos[servicio]</td>
                            <td>$datos[asistente]</td>
                            <td>$datos[estatus]</td>							
                        </tr>
                    ";
                }
$html.="     </tbody>
        </table>
		";
$html = utf8_encode($html) ;
//echo $html; exit ;

error_reporting(E_ALL);
ini_set('display_errors','On');


// Composer's auto-loading functionality
require 'resources/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;

//generate some PDFs!
$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portait');
$dompdf->render();
$dompdf->stream('citas.pdf', array('Attachment'=>1));

?>