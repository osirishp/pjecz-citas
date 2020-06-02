<?php
//require 'vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
require("SendGrid/sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases

//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

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("alan.hernandez@coahuila.gob.mx", "DOS");
$email->setSubject("prueba de sendgrid");
$email->addTo("carlos_hdz57@hotmail.com", "HP");
$email->addContent("text/plain", "Hola, prueba de send grid");
$email->addContent(
    "text/html", "<strong>prueba exitosa hp</strong>"
);
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    echo "<PRE>";
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
        echo "/<PRE>";
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
}