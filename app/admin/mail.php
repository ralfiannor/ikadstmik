<?php

require ROOT.'vendor/PHPMailer/PHPMailerAutoload.php';


class beranda
{
	private $conn;
	
	function __construct()
	{
		$mail = new PHPMailer;
	}

		
	public function getID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM krs WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}

	public function send($to,$subject,$body)
	{
		$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'ikadstmik@gmail.com';                 // SMTP username
		$mail->Password = 'rizal444444';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('ikadstmik@gmail.com', 'IKAD STMIK Banjarbaru');
		//$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
		$mail->addAddress($to);               		// Name is optional
		//$mail->addReplyTo('info@example.com', 'Information');
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $body;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
		    return false;
		} else {
		    return true;
		}
	}

}

	

$beranda = new beranda();



include ROOT . 'views/admin/beranda.view.php';