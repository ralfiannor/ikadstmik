<?php

require ROOT.'app/admin/session.php';
require ROOT.'vendor/PHPMailer/PHPMailerAutoload.php';

class dosen
{
	private $conn;
	
	function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function redirect($url)
	{
		header("Location: $url");

	}

	public function sendmail($to,$subject,$body)
	{
		//$mail->SMTPDebug = 3;                               // Enable verbose debug output
		$mail = new PHPMailer;
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

	public function create($nidn,$email,$pass,$nama,$alamat,$no_hp)
	{
		try
		{
			$new_password = password_hash($pass, PASSWORD_DEFAULT);			
			$stmt = $this->conn->prepare("INSERT INTO dosen(nidn,email,password,nama,alamat,no_hp) VALUES(:nidn, :email, :pass, :nama, :alamat, :no_hp)");												  
			$stmt->bindparam(":nidn", $nidn);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":pass", $new_password);										  
			$stmt->bindparam(":nama", $nama);										  
			$stmt->bindparam(":alamat", $alamat);										  
			$stmt->bindparam(":no_hp", $no_hp);										  
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
		
	}
	
	public function getID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM dosen WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nidn,$email,$nama,$alamat,$no_hp)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE dosen SET nidn=:nidn, email=:email, nama=:nama, alamat=:alamat, no_hp=:no_hp WHERE id=:id");
			$stmt->bindparam(":nidn", $nidn);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":nama", $nama);										  
			$stmt->bindparam(":alamat", $alamat);										  
			$stmt->bindparam(":no_hp", $no_hp);										  
			$stmt->bindparam(":id",$id);
			$stmt->execute();		
			return true;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}
	
	public function delete($id)
	{
		$stmt = $this->conn->prepare("DELETE FROM dosen WHERE id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		return true;
	}
		
	/* paging */
	public function dataview($query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$no = 1;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
            <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nidn'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['tgl_daftar'] ?></td>
            <td>
	            <a href="<?= site_url('admin/dosen/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
				<a href="<?= site_url('admin/dosen/?hapus_id='.$row['id']) ?>" class="hapus btn btn-xs btn-danger" data-id="<?= $row['id'] ?>">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}
	
	
}

$dosen = new dosen();


include ROOT . 'views/admin/dosen.view.php';