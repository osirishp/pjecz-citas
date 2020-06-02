<?php 
		include_once('class.phpmailer.php');
		include_once('class.smtp.php');

		$para = "carlos.hernandez@coahuila.gob.mx" ;

	    $mail = new PHPMailer();
	    $mail->IsSMTP();
	    $mail->isHTML(true);
	    $mail->SMTPAuth = true;
	    $mail->SMTPDebug = 2;
	    $mail->SMTPSecure = "ssl";
	    $mail->Host = "smtp.gmail.com";
	    $mail->Port = 465; // 465

	    $mail->Username ='informaticapjecz@gmail.com';
	    $mail->Password = 'desarrollopjecz_';

	    $mail->AddAddress($para);
	    $mail->Subject = 'Cita confirmada';
	    $mail->Body = utf8_encode("
	    	<html>
	    		<body>

	    		<table>
							<tr>
								<td style='background-color:#efeff1: border-bottom:4px solid #555; text-align:center' colspan=2>
									<h3>Poder Judicial del Estado de Coahuila</h3>
								</td>
							</tr>
						
							<tr>	
								<td colspan=2>
									<h3>Su cita ha sido agendada correctamente</h3>
									Gracias por utilizar nuestro servicio para agendar su cita. Debajo encontrar&oacute; los detalles de su cita.
									<h3>Detalles de la cita</h3>
								</td>
							</td>
							<tr>
								<td>
									<label>Folio</label>
								</td>
								<td>
									<label>7458375834</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Distrito</label>
								</td>
								<td>
									<label>distrito</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Juzgado</label>
								</td>
								<td>
									<label>juzgado</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Inicio</label>
								</td>
								<td>
									<label>inicia</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Final</label>
								</td>
								<td>
									<label>final</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Asunto al que asiste</label>
								</td>
								<td>
									<label>detalles</label>
								</td>
							</tr>

							<tr>
								<td colspan=2>
									<h3>Detalle del beneficiario</h3>
								</td>
							</tr>

							<tr>
								<td>
									<label>Nombre</label>
								</td>
								<td>
									<label>nomre</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Correo electr&oacute;nico</label>
								</td>
								<td>
									<label>correo</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>N&uacute;mero de tel&eacute;fono</label>
								</td>
								<td>
									<label>numero</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>Domicilio</label>
								</td>
								<td>
									<label>domicilio</label>
								</td>
							</tr>																																																																												
						<tr>
							<td colspan=2 style='text-align:center'>
								<a href='https://pjecz.gob.mx'>Sitio del Poder Judicial del Estado de Coahuila</a>
							</td>
						</tr>
	    		</table>
	    	</body>
	    </html>
	    		");

	    //Avisar si fue enviado o no y dirigir al index
	    if($mail->Send()) {
	    	echo "correo enviado" ;
	    }else{
	        echo "error al enviar correo";
	    }



 ?>