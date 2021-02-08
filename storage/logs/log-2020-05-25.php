<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-05-25 21:18:38 --> Email could not been sent. Mailer Error (Line 179): SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting
ERROR - 2020-05-25 21:18:38 --> #0 /var/www/citas/html/application/controllers/Appointments.php(577): EA\Engine\Notifications\Email->sendAppointmentDetails(Array, Array, Array, Array, Array, Object(EA\Engine\Types\Text), Object(EA\Engine\Types\Text), Object(EA\Engine\Types\Url), Object(EA\Engine\Types\Email), Object(EA\Engine\Types\Text))
#1 /var/www/citas/html/system/core/CodeIgniter.php(532): Appointments->ajax_register_appointment()
#2 /var/www/citas/html/index.php(353): require_once('/var/www/citas/...')
#3 {main}
