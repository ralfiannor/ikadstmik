<?php

require ROOT.'app/dosen/session.php';

class isikuesioner
{
	private $conn;
	
	function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}
	

	public function create($iddosenampu,$jawaban,$skor,$tahun_akademik,$semester)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO kuesioner_sejawat(id_dosenampu, jawaban, skor, tahun_akademik, semester) VALUES(:iddosenampu, :jawaban, :skor, :tahun_akademik, :semester)");
			$stmt->bindparam(":iddosenampu",$iddosenampu);
			$stmt->bindparam(":jawaban",$jawaban);
			$stmt->bindparam(":skor",$skor);
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
	
	
	public function update($nidn,$iddosenampu)
	{
		$stmt = $this->conn->prepare("SELECT * FROM dosen_ampu WHERE id=:id");
		$stmt->execute(array(":id"=>$iddosenampu));
		if ($stmt->rowCount() > 0) {
	        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
	            if ($row['telah_diisi'] != NULL ) {
	                $pengisiawal = $row['telah_diisi'];                
	            }
	            else {
	            	$pengisiawal = json_encode(array());
	            }
	        }        
	    }
	    else {
	    	$pengisiawal = json_encode(array());
	    }

		$listpengisi = array($nidn);
		$pengisi = json_encode(array_merge($listpengisi,json_decode($pengisiawal, true)));

		try
		{
			$stmt=$this->conn->prepare("UPDATE dosen_ampu SET telah_diisi=:pengisi WHERE id=:iddosenampu");
			$stmt->bindparam(":iddosenampu",$iddosenampu);
			$stmt->bindparam(":pengisi",$pengisi);
			$stmt->execute();		
			return true;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	public function status($iddosenampu,$nidn) {
		$stmt = $this->conn->prepare("SELECT * FROM dosen_ampu WHERE id=".$iddosenampu);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
    		if ($row['telah_diisi'] != NULL) { 
	            $pengisi = json_decode($row['telah_diisi']);
	            $status = 0;
	            foreach ($pengisi as $listpengisi) {
	                if ($listpengisi == $nidn) {
	                    $status = 1;
	                }
	            }
	        }
	        else {
	        	$status = 0;
	        }				
		}		

		return $status;
	}			

	public function dataview($nidn,$query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$no = 1;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
            <tr>
            <td><?= $no++; ?></td>
                <td><?php print($row['nama']); ?></td>
                <td><?php print($row['kd_mk']); ?></td>
                <td><?php print($row['nama_mk']); ?></td>
                <td><?php print($row['sks']); ?></td>
                <td><?php
			            echo (($this->status($row['id'],$nidn)==1) ? 'Sudah Diisi' : '<a href="'.site_url('dosen/isi-kuesioner/?dosenampu_id='.$row['id']).'"><i class="glyphicon glyphicon-edit"></i> Isi kuesioner</a>');
	                ?>
                </td>
            </tr>

    <?php
		}
		
	}	

	public function identitas($query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$no = 1;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<div class="row show-grid">
				<div class="col-md-2">Nama Dosen</div>
 		   		<div class="col-md-10"><?php echo $row['nama']; ?></div>
    		</div>
			<div class="row show-grid">
				<div class="col-md-2">Kode Matakuliah</div>
    			<div class="col-md-10"><?php echo $row['kd_mk']; ?></div>
    		</div>

    		<div class="row show-grid">
				<div class="col-md-2">Matakuliah</div>
    			<div class="col-md-10"><?php echo $row['nama_mk']; ?></div>
    		</div>

			<div class="row show-grid">
				<div class="col-md-2">SKS</div>
    			<div class="col-md-10"><?php echo $row['sks']; ?></div>
    		</div>
			<div class="row show-grid">
				<div class="col-md-2">Keterangan</div>
				<div class="col-md-10">1 = Sangat Tidak Baik <br> 2 = Tidak Baik <br> 3 = Cukup Baik <br> 4 = Baik <br> 5 = Sangat Baik</div>
    		</div>
    <?php
		}
		
	}	

	public function formkuesioner()
	{
		$query = "SELECT kuesioner.id, kuesioner.nama_kuesioner, kategori.nama_kategori FROM kategori INNER JOIN kuesioner ON kategori.kd_kategori=kuesioner.kd_kategori ORDER BY kategori.kd_kategori, kuesioner.id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		$i = 1;
		$lastkategori = '';
		echo '<form method="POST"><div class="panel panel-default">';
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			if($row['nama_kategori'] != $lastkategori) {
				echo '<div class="panel-heading"><h5 class="panel-title">'.$row['nama_kategori'].'</h5></div>';
			}

			$lastkategori = $row['nama_kategori'];
?>
			<table class="table">
				<tr>
					<td width="10"><?php echo $i;?></td>
					<td><?php echo $row['nama_kuesioner']; ?></td>
					<td align="right" width="250">
						<input name="kuesioner[<?= $row['id']."-".$row['nama_kategori'] ?>]" value="1" type="radio" required> <b>1</b>
						<input name="kuesioner[<?= $row['id']."-".$row['nama_kategori'] ?>]" value="2" type="radio" required> <b>2</b>
						<input name="kuesioner[<?= $row['id']."-".$row['nama_kategori'] ?>]" value="3" type="radio" required> <b>3</b>
						<input name="kuesioner[<?= $row['id']."-".$row['nama_kategori'] ?>]" value="4" type="radio" required> <b>4</b>
						<input name="kuesioner[<?= $row['id']."-".$row['nama_kategori'] ?>]" value="5" type="radio" required> <b>5</b>
					</td>
				</tr>
			</table>
    <?php
    		$i++;
		}
		echo '
					<br><center><input type="submit" name="btn-save" value="Isi Kuesioner" class="btn btn-primary">&nbsp;<input type="reset" name="btn-save" value="Reset" class="btn btn-warning">&nbsp;&nbsp;&nbsp;<a href="'.site_url('isi-kuesioner').'" class="btn btn-default">Batal</a></center>
					<br>
			</div></div></form>';
	}	

}

$isikuesioner = new isikuesioner();


include ROOT . 'views/dosen/isi-kuesioner.view.php';