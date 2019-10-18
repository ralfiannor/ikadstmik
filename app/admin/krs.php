<?php

require ROOT.'app/admin/session.php';

class krs
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

	public function create($nim,$id_dosenampu,$tahun_akademik,$semester)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO krs(nim, id_dosenampu, tahun_akademik, semester) VALUES(:nim, :id_dosenampu, :tahun_akademik, :semester)");
			$stmt->bindparam(":nim",$nim);
			$stmt->bindparam(":id_dosenampu",$id_dosenampu);
			$stmt->bindparam(":tahun_akademik",$tahun_akademik);
			$stmt->bindparam(":semester",$semester);
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
		$stmt = $this->conn->prepare("SELECT a.id,a.nim,a.id_dosenampu,b.nama_mhs,c.nidn,c.kd_mk,d.nama,e.nama_mk,e.sks,e.jurusan FROM krs as a INNER JOIN mahasiswa as b ON a.nim = b.nim INNER JOIN dosen_ampu as c ON a.id_dosenampu = c.id INNER JOIN dosen as d ON c.nidn = d.nidn INNER JOIN matakuliah as e ON c.kd_mk = e.kd_mk WHERE a.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nim,$id_dosenampu,$tahun_akademik,$semester)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE krs SET nim=:nim, id_dosenampu=:id_dosenampu, tahun_akademik=:tahun_akademik, semester=:semester WHERE id=:id");
			$stmt->bindparam(":nim",$nim);
			$stmt->bindparam(":id_dosenampu",$id_dosenampu);
			$stmt->bindparam(":tahun_akademik",$tahun_akademik);
			$stmt->bindparam(":semester",$semester);
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
		$stmt = $this->conn->prepare("DELETE FROM krs WHERE id=:id");
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
            <td><?= "(".$row['nim'].") ".$row['nama_mhs'] ?></td>
            <td><?= "(".$row['nidn'].") ".$row['nama'] ?></td>
           <td><?= "(".$row['kd_mk'].") ".$row['nama_mk'] ?></td>
            <td><?= $row['sks'] ?></td>
            <td><?= $row['jurusan'] ?></td>
             <td>
	            <a href="<?= site_url('admin/krs/?edit_id='.$row['id'].'#ubahdata') ?>" class="btn btn-xs btn-primary">Ubah</a> 
<a data-toggle="modal" href="<?= site_url('admin/krs/?hapus='.$row['id']) ?>" data-target="#myModal" class="btn hapus btn-danger btn-xs" title="Hapus">Hapus</a>
            </td>
            </tr>
    <?php
		}
		
	}	
	
}

$krs = new krs();


include ROOT . 'views/admin/krs.view.php';