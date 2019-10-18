<?php

require ROOT.'app/admin/session.php';

class kuesioner
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

	public function create($kd_kategori,$nama_kuesioner)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO kuesioner(kd_kategori, nama_kuesioner) VALUES(:kd_kategori, :nama_kuesioner)");
			$stmt->bindparam(":kd_kategori",$kd_kategori);
			$stmt->bindparam(":nama_kuesioner",$nama_kuesioner);
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
		$stmt = $this->conn->prepare("SELECT * FROM kuesioner WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nama_kuesioner,$kd_kategori)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE kuesioner SET kd_kategori=:kd_kategori, nama_kuesioner=:nama_kuesioner WHERE id=:id");
			$stmt->bindparam(":kd_kategori",$kd_kategori);
			$stmt->bindparam(":nama_kuesioner",$nama_kuesioner);
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
		$stmt = $this->conn->prepare("DELETE FROM kuesioner WHERE id=:id");
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
            <td><?= $row['nama_kuesioner'] ?></td>
            <td>
	            <a href="<?= site_url('admin/kuesioner/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
				<a href="<?= site_url('admin/kuesioner/?hapus_id='.$row['id']) ?>" class="hapus btn btn-xs btn-danger" data-id="<?= $row['id'] ?>">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}
	
	public function pilihkategori($query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?= $row['kd_kategori'] ?>"><?= $row['nama_kategori'] ?></option>
    <?php
		}
		
	}

	
}

$kuesioner = new kuesioner();


include ROOT . 'views/admin/kuesioner.view.php';