<?php

require ROOT.'app/admin/session.php';
require ROOT.'vendor/PHPMailer/PHPMailerAutoload.php';

class crud
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

	public function create($nim,$email,$pass,$nama_mhs,$alamat,$no_hp,$jurusan)
	{
		try
		{
			$new_password = password_hash($pass, PASSWORD_DEFAULT);			
			$stmt = $this->conn->prepare("INSERT INTO mahasiswa(nim,email,password,nama_mhs,jurusan,alamat,no_hp) VALUES(:nim, :email, :pass, :nama_mhs, :jurusan, :alamat, :no_hp)");												  
			$stmt->bindparam(":nim", $nim);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":pass", $new_password);										  
			$stmt->bindparam(":nama_mhs", $nama_mhs);										  
			$stmt->bindparam(":alamat", $alamat);										  
			$stmt->bindparam(":no_hp", $no_hp);										  
			$stmt->bindparam(":jurusan", $jurusan);										  
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
		$stmt = $this->conn->prepare("SELECT * FROM mahasiswa WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nim,$email,$nama_mhs,$alamat,$no_hp,$jurusan)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE mahasiswa SET nim=:nim, email=:email, nama_mhs=:nama_mhs, alamat=:alamat, no_hp=:no_hp, jurusan=:jurusan WHERE id=:id");
			$stmt->bindparam(":nim", $nim);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":nama_mhs", $nama_mhs);										  
			$stmt->bindparam(":alamat", $alamat);										  
			$stmt->bindparam(":no_hp", $no_hp);										  
			$stmt->bindparam(":jurusan", $jurusan);										  
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
		$stmt = $this->conn->prepare("DELETE FROM mahasiswa WHERE id=:id");
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
            <td><?= $row['nim'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['nama_mhs'] ?></td>
            <td><?= $row['jurusan'] ?></td>
            <td><?= $row['tgl_daftar'] ?></td>
            <td>
	            <a href="<?= site_url('admin/mahasiswa/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
				<a href="<?= site_url('admin/mahasiswa/?hapus_id='.$row['id']) ?>" class="hapus btn btn-xs btn-danger" data-id="<?= $row['id'] ?>">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}
	
	
}

$crud = new crud();


include ROOT . 'views/admin/mahasiswa.view.php';