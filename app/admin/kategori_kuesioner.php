<?php

require ROOT.'app/admin/session.php';

class katkuesioner
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

	public function create($kd_kategori,$nama_kategori)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO kategori(kd_kategori, nama_kategori) VALUES(:kd_kategori, :nama_kategori)");
			$stmt->bindparam(":kd_kategori",$kd_kategori);
			$stmt->bindparam(":nama_kategori",$nama_kategori);
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
		$stmt = $this->conn->prepare("SELECT * FROM kategori WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$kd_kategori,$nama_kategori)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE kategori SET kd_kategori=:kd_kategori, nama_kategori=:nama_kategori WHERE id=:id");
			$stmt->bindparam(":kd_kategori",$kd_kategori);
			$stmt->bindparam(":nama_kategori",$nama_kategori);
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
		$stmt = $this->conn->prepare("DELETE FROM kategori WHERE id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		return true;
	}
		
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
            <td><?= $row['kd_kategori'] ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td>
	            <a href="<?= site_url('admin/kategori-kuesioner/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
				<a href="<?= site_url('admin/kategori-kuesioner/?hapus_id='.$row['id']) ?>" class="hapus btn btn-xs btn-danger" data-id="<?= $row['id'] ?>">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}
	
	
}

$katkuesioner = new katkuesioner();


include ROOT . 'views/admin/kategori_kuesioner.view.php';