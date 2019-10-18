<?php

require ROOT.'app/admin/session.php';

class matakuliah
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

	public function create($kdmk,$mk,$sks,$semester,$jurusan)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO matakuliah(kd_mk, nama_mk, sks, semester, jurusan) VALUES(:kdmk, :mk, :sks, :semester, :jurusan)");
			$stmt->bindparam(":kdmk",$kdmk);
			$stmt->bindparam(":mk",$mk);
			$stmt->bindparam(":sks",$sks);
			$stmt->bindparam(":semester",$semester);
			$stmt->bindparam(":jurusan",$jurusan);
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
		$stmt = $this->conn->prepare("SELECT * FROM matakuliah WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$kdmk,$mk,$sks,$semester,$jurusan)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE matakuliah SET kd_mk=:kdmk, nama_mk=:mk, sks=:sks, semester=:semester, jurusan=:jurusan WHERE id=:id");
			$stmt->bindparam(":kdmk",$kdmk);
			$stmt->bindparam(":mk",$mk);
			$stmt->bindparam(":sks",$sks);
			$stmt->bindparam(":semester",$semester);
			$stmt->bindparam(":jurusan",$jurusan);
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
		$stmt = $this->conn->prepare("DELETE FROM matakuliah WHERE id=:id");
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
            <td><?= $row['kd_mk'] ?></td>
            <td><?= $row['nama_mk'] ?></td>
            <td><?= $row['sks'] ?></td>
            <td><?= $row['semester'] ?></td>
            <td><?= $row['jurusan'] ?></td>
            <td><?= $row['created_date'] ?></td>
            <td>
	            <a href="<?= site_url('admin/matakuliah/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
				<a href="<?= site_url('admin/matakuliah/?hapus_id='.$row['id']) ?>" class="hapus btn btn-xs btn-danger" data-id="<?= $row['id'] ?>">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}
	
	
}

$matakuliah = new matakuliah();


include ROOT . 'views/admin/matakuliah.view.php';