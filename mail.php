<?php

		include_once('resources/correo/class.phpmailer.php');
		include_once('resources/correo/class.smtp.php');

		$para = "carlos.hernandez@coahuila.gob.mx" ;
	 //Este bloque es importante
	    $mail = new PHPMailer();
	    $mail->IsSMTP();
	    $mail->isHTML(true);
	    $mail->SMTPAuth = true;
	    $mail->SMTPDebug = 2;
	    $mail->SMTPSecure = "ssl";
	    $mail->Host = "smtp.sendgrid.net";
	    $mail->Port = 465; // 465

	    //Nuestra cuenta
	    $mail->Username ='apikey';
	    $mail->Password = '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';

	    //Agregar destinatario
	    $mail->AddAddress($para);
	    $mail->Subject = 'Cita confirmada';
	    $mail->Body = "correo de prueba de sendgrid" ;


	    	   
	    //Avisar si fue enviado o no y dirigir al index
	    if($mail->Send()) {
	    	echo "correo enviado" ;
	    }else{
	        echo "error al enviar correo";
	    }

