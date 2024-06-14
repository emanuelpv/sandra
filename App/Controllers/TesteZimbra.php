<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('App/Libraries/PHPMailer_6.6.3/src/Exception.php');
require_once('App/Libraries/PHPMailer_6.6.3/src/PHPMailer.php');
require_once('App/Libraries/PHPMailer_6.6.3/src/SMTP.php');


class TesteZimbra extends BaseController
{


	public function __construct()
	{
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = '10.54.6.58';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'sandra@hospital.com.br';                     //SMTP username
			$mail->Password   = '*************';                               //SMTP password
			$mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
			$mail->Port       = 25;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//Recipients
			$mail->setFrom('sandra@hospital.com.br', 'Sandra');
			$mail->addAddress('sandra@hospital.com.br', 'Sandra');     //Add a recipient
			$mail->addReplyTo('sandra@hospital.com.br', 'Sandra');

			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);

			$mail->Timeout  = 4; // set the timeout (seconds)
			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'Email enviado pelo Zimbra do CTA';
			$mail->Body    = 'Email enviado pelo Zimbra do CTA';
			$mail->AltBody = 'Email enviado pelo Zimbra do CTA';

			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	public function index()
	{
		// exit()
	}
}
