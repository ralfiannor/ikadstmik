<?php

require ROOT.'app/admin/session.php';

class dosenampu
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

	public function create($nidn,$kd_mk)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO dosen_ampu(nidn,kd_mk) VALUES(:nidn, :kd_mk)");
			$stmt->bindparam(":nidn",$nidn);
			$stmt->bindparam(":kd_mk",$kd_mk);
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
		$stmt = $this->conn->prepare("SELECT * FROM dosen_ampu WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nidn,$kd_mk)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE dosen_ampu SET nidn=:nidn, kd_mk=:kd_mk WHERE id=:id");
			$stmt->bindparam(":nidn",$nidn);
			$stmt->bindparam(":kd_mk",$kd_mk);
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
		$stmt = $this->conn->prepare("DELETE FROM dosen_ampu WHERE id=:id");
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
            <td>[<?= $row['nidn'] ?>] <?= $row['nama'] ?></td>
            <td>[<?= $row['kd_mk'] ?>] <?= $row['nama_mk'] ?></td>
            <td><?= $row['sks'] ?></td>
            <td><?= $row['semester'] ?></td>
            <td><?= $row['jurusan'] ?></td>
            <td>
	            <a href="<?= site_url('admin/dosen-ampu/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
				<a href="<?= site_url('admin/dosen-ampu/?hapus_id='.$row['id']) ?>" class="hapus btn btn-xs btn-danger" data-id="<?= $row['id'] ?>">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}
	
	
}

$dosenampu = new dosenampu();


include ROOT . 'views/admin/dosen_ampu.view.php';