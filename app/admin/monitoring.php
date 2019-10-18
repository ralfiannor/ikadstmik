<?php

require ROOT.'app/admin/session.php';

class monitoring
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


	public function create($nim,$id_dosenampu)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO monitoring(nim, id_dosenampu) VALUES(:nim, :id_dosenampu)");
			$stmt->bindparam(":nim",$nim);
			$stmt->bindparam(":id_dosenampu",$id_dosenampu);
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
		$stmt = $this->conn->prepare("SELECT a.id,a.nim,a.id_dosenampu,b.nama_mhs,c.nidn,c.kd_mk,d.nama,e.nama_mk,e.sks,e.jurusan FROM monitoring as a INNER JOIN mahasiswa as b ON a.nim = b.nim INNER JOIN dosen_ampu as c ON a.id_dosenampu = c.id INNER JOIN dosen as d ON c.nidn = d.nidn INNER JOIN matakuliah as e ON c.kd_mk = e.kd_mk WHERE a.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nim,$id_dosenampu)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE monitoring SET nim=:nim, id_dosenampu=:id_dosenampu WHERE id=:id");
			$stmt->bindparam(":nim",$nim);
			$stmt->bindparam(":id_dosenampu",$id_dosenampu);
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
                <td>[<?= $row['nidn'] ?>] <?= $row['nama'] ?></td>
                <td>[<?php print($row['kd_mk']); ?>] <?php print($row['nama_mk']); ?></td>
                <td><?php print($row['sks']); ?></td>
                <td><?php print($row['semester']); ?></td>
                <td><?php print($row['jurusan']); ?></td>
	                <td>
	                <?php
		                $responden = $this->responden($row['id'],'mhs') + $this->responden($row['id'],'dosen');
	    				$total = $this->total($row['id'],$row['nidn'],'mhs') + $this->total($row['id'],$row['nidn'],'dosen');
	    				$hasil = $responden / $total * 100;
	                echo $responden." / ".$total;
	                ?>
	                </td>
	                <td><?= round($hasil,2)."%"; ?></td>
             <td>
	            <a href="<?= site_url('admin/monitoring/?lihat_id='.$row['id']) ?>" class="btn btn-xs btn-primary">Lihat</a> 
            </td>
            </tr>

    <?php
		}
		
	}	

	public function cetak($id)
	{
		$stmt = $this->conn->prepare("SELECT a.id_dosenampu as id, c.nidn, d.nama, c.kd_mk, e.nama_mk, e.sks, count(a.nim) as total_mhs, count(b.id_krs) as responden, sum(b.skor) as total_skor, b.jawaban as jawaban
                    from krs a
                    left join kuesioner_mahasiswa b
                    on a.id = b.id_krs
                    left join dosen_ampu c on
                    a.id_dosenampu = c.id
                    left join dosen d on
                    c.nidn = d.nidn
                    left join matakuliah e on
                    c.kd_mk = e.kd_mk WHERE a.id_dosenampu=".$id." GROUP BY a.id_dosenampu");
		$stmt->execute();
		$rowmhs=$stmt->fetch(PDO::FETCH_ASSOC);
		$totalmhs = $this->total($rowmhs['id'],$rowmhs['nidn'],'mhs');
		$i = 1;

?>
<table border="1">
	<caption>table title and/or explanatory text</caption>
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Aspek Yang Dinilai</th>
			<th colspan="<?= $totalmhs ?>">Nomor & Jawaban Responden</th>
			<th rowspan="2">Total Nilai</th>
			<th rowspan="2">Rerata</th>
			<th rowspan="2">Ket</th>
		</tr>
		<tr>

<?php while ( $i <= $totalmhs) { ?>
			<th><?= $i;$i++ ?></th>
<?php
}
echo "</tr>";

		$stmt = $this->conn->prepare("SELECT kuesioner.id, kuesioner.nama_kuesioner, kategori.nama_kategori FROM kategori INNER JOIN kuesioner ON kategori.kd_kategori=kuesioner.kd_kategori ORDER BY kategori.kd_kategori, kuesioner.id");
		$stmt->execute();
		$i = 1;
		$lastkategori = '';
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			if($row['nama_kategori'] != $lastkategori) {
				//echo '<div class="panel-heading"><h5 class="panel-title">'.$row['nama_kategori'].'</h5></div>';
			}

			$lastkategori = $row['nama_kategori'];
		}
	}

	public function hasil($query,$id)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		if ($stmt->rowCount() < 1 ) {
			echo "Data belum tersedia. Silahkan klik <a href='".site_url('admin/monitoring')."'>Kembali</a>";
		}
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$persentase = $row['responden'] / $row['total_mhs'] * 100; 
?>
			<div class="row show-grid">
				<div class="col-md-4">Nama Dosen</div>
 		   		<div class="col-md-8"><?php echo $row['nama']; ?></div>
    		</div>
			<div class="row show-grid">
				<div class="col-md-4">Kode Matakuliah</div>
    			<div class="col-md-8"><?php echo $row['kd_mk']; ?></div>
    		</div>

    		<div class="row show-grid">
				<div class="col-md-4">Matakuliah</div>
    			<div class="col-md-8"><?php echo $row['nama_mk']; ?></div>
    		</div>

			<div class="row show-grid">
				<div class="col-md-4">SKS</div>
    			<div class="col-md-8"><?php echo $row['sks']; ?></div>
    		</div>

    		<div class="row show-grid">
				<div class="col-md-4">Total Resonden Mahasiswa</div>
    			<div class="col-md-8">
    			<?php
    				$persen = ROUND($this->responden($row['id'],'mhs') / $this->total($row['id'],$row['nidn'],'mhs') * 100,2);
    				echo $this->responden($row['id'],'mhs')." / ".$this->total($row['id'],$row['nidn'],'mhs')." ($persen%)"; 
    			?>
    			</div>
    		</div>

    		<div class="row show-grid">
				<div class="col-md-4">Total Responden Dosen</div>
    			<div class="col-md-8">
    			<?php
    				$persen = ROUND($responden = $this->responden($row['id'],'dosen') / $this->total($row['id'],$row['nidn'],'dosen') * 100,2);
    				echo $this->responden($row['id'],'dosen')." / ".$this->total($row['id'],$row['nidn'],'dosen')." ($persen%)";
    			?>
    			</div>
    		</div>

    		<div class="row show-grid">
				<div class="col-md-4">Persentase Responden</div>
    			<div class="col-md-8">
    			<?php
	    			$responden = $this->responden($row['id'],'mhs') + $this->responden($row['id'],'dosen');
    				$total = $this->total($row['id'],$row['nidn'],'mhs') + $this->total($row['id'],$row['nidn'],'dosen');
    				$hasil = round($responden / $total * 100,2); 
	    			echo $hasil."% (".$responden." / ".$total.")";
    			?></div>
    		</div>

    		<div class="row show-grid"></div>

	<?php 
			$this->skorkuesionermahasiswa($id);
			$this->skorkuesionerdosen($id);
			$this->skoraktivitasdosen($id);
	?>
	        <a href="<?= site_url('admin/monitoring/') ?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Kembali</a>
	<?php
		}			
	}	

	public function skorkuesionermahasiswa($id)
	{

		$query = "SELECT a.id_dosenampu as id, d.nama, c.kd_mk, e.nama_mk, e.sks, count(a.nim) as total_mhs, count(b.id_krs) as responden, sum(b.skor) as total_skor, b.jawaban as jawaban
                    from krs a
                    left join kuesioner_mahasiswa b
                    on a.id = b.id_krs
                    left join dosen_ampu c on
                    a.id_dosenampu = c.id
                    left join dosen d on
                    c.nidn = d.nidn
                    left join matakuliah e on
                    c.kd_mk = e.kd_mk WHERE a.id_dosenampu=".$id." GROUP BY a.id_dosenampu";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
?>
		<div class="row show-grid">
				<div class="col-sm-12">
	    		<div class="row show-grid">
    				<h3>Skor Kuesioner Mahasiswa</h3>
    				<div class="row">
						<div class="col-xs-12 col-sm-12">
							<b><?= ($row['responden'] > 0) ? "Rerata Kompetensi" : "Tidak Ada Data"?></b>
						</div>
		    		</div>

					<?php
						$query = "SELECT a.id as id,a.jawaban, b.* FROM kuesioner_mahasiswa as a LEFT JOIN krs as b on a.id_krs = b.id WHERE b.id_dosenampu=".$id;

						$stmt = $this->conn->prepare($query);
						$stmt->execute();

						if ($stmt->rowCount() > 0) {
							while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
							{

							 foreach (json_decode($row2['jawaban'],1) as $key => $value) {
							        $a = explode('-', $key,2);
							        isset($kat[$a[1]]) or $kat[$a[1]] = 0;
							        $kat[$a[1]] += (int)$value;
							    }
							}

							foreach ($kat as $key => $val) {
					?>
								<div class="col-md-8"><?= $key ?></div>
				    			<div class="col-md-4"><?= $val / $row['responden'] ?></div>

					<?php 			
							}
					?>

					</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Rerata Nilai Total
						</div>
						<div class="col-xs-4 col-sm-4">
							<?php echo $row['total_skor'] / $row['responden']; ?>
						</div>
		    		</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Kesimpulan Akhir
						</div>
						<div class="col-xs-4 col-sm-4">
			    			<?php
								foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
									echo $kriteria['kriteria'];
									$km = $kriteria['nilai'];
								}
			    			?>
						</div>
    		<?php } ?>
    			</div>
			</div>
		</div>
<?php
	}


	public function skorkuesionerdosen($id)
	{

		$query = "SELECT count(id) as responden, sum(skor) as total_skor FROM kuesioner_sejawat WHERE id_dosenampu = ".$id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
?>
		<div class="row show-grid">
				<div class="col-sm-12">
	    		<div class="row show-grid">
    				<h3>Skor Kuesioner Dosen</h3>
    				<div class="row">
						<div class="col-xs-12 col-sm-12">
							<b><?= ($row['responden'] > 0) ? "Rerata Kompetensi" : "Tidak Ada Data"?></b>
						</div>
		    		</div>

					<?php
						$query2 = "SELECT a.id as id,a.jawaban, b.* FROM kuesioner_sejawat as a LEFT JOIN dosen_ampu as b on a.id_dosenampu = b.id WHERE a.id_dosenampu=".$id;

						$stmt2 = $this->conn->prepare($query2);
						$stmt2->execute();
						if($stmt2->rowCount()>0) {
							while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
							{

							 foreach (json_decode($row2['jawaban'],1) as $key => $value) {
							        $a = explode('-', $key,2);
							        isset($kat[$a[1]]) or $kat[$a[1]] = 0;
							        $kat[$a[1]] += (int)$value;
							    }
							}

							foreach ($kat as $key => $val) {
					?>
								<div class="col-md-8"><?= $key ?></div>
				    			<div class="col-md-4"><?= $val / $row['responden'] ?></div>

					<?php 			
							}
					?>

					</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Rerata Nilai Total
						</div>
						<div class="col-xs-4 col-sm-4">
							<?php echo $row['total_skor'] / $row['responden']; ?>
						</div>
		    		</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Kesimpulan Akhir
						</div>
						<div class="col-xs-4 col-sm-4">
			    			<?php
								foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
									echo $kriteria['kriteria'];
									$km = $kriteria['nilai'];
								}
			    			?>
						</div>
    		<?php } ?>
    			</div>
			</div>
		</div>
<?php
	}


	public function skoraktivitasdosen($id)
	{

		$query = "SELECT count(AD.id) as responden,sum(AD.skor) as total_skor FROM dosen_ampu as DA LEFT JOIN aktivitas_dosen as AD on DA.id = AD.id_dosenampu LEFT JOIN dosen ON dosen.nidn = DA.nidn WHERE DA.id = ".$id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
?>
		<div class="row show-grid">
				<div class="col-sm-12">
	    		<div class="row show-grid">
    				<h3>Skor Aktivitas Dosen</h3>
    				<div class="row">
						<div class="col-xs-12 col-sm-12">
							<b><?= ($row['responden'] > 0) ? "Aktivitas Dosen" : "Tidak Ada Data"?></b>
						</div>
		    		</div>

					<?php
						$query = "SELECT a.id as id,a.jawaban, b.* FROM aktivitas_dosen as a LEFT JOIN dosen_ampu as b on a.id_dosenampu = b.id WHERE a.id_dosenampu=".$id;

						$stmt = $this->conn->prepare($query);
						$stmt->execute();
						if($stmt->rowCount()>0) {
							while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
							{

							 foreach (json_decode($row2['jawaban'],1) as $key => $value) {
							        isset($kat[$key]) or $kat[$key] = 0;
							        $kat[$key] += (int)$value;
							    }
							}

							foreach ($kat as $key => $val) {
					?>
								<div class="col-md-8"><?= $key ?></div>
				    			<div class="col-md-4"><?= $val ?></div>

					<?php 			
							}
					?>

					</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Rerata Nilai Total
						</div>
						<div class="col-xs-4 col-sm-4">
							<?php echo $row['total_skor']; ?>
						</div>
		    		</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Kesimpulan Akhir
						</div>
						<div class="col-xs-4 col-sm-4">
			    			<?php
								foreach ($this->kriteriaaktivitas($row['total_skor'],$row['responden']) as $kriteria) {
									echo $kriteria['kriteria'];
									$km = $kriteria['nilai'];
								}
			    			?>
						</div>
		    	<?php } ?>
    			</div>
			</div>
		</div>

<?php
	}


	public function kriteria($total,$responden) {
		/*
			Fungsi Generete Kriteria Dari Total Skor Kuesionoer Mahasiswa
			Gunakan foreach ($this->kriteriamahasiswa($total) as $kriteria)
		 */
		$query = "SELECT a.*,b.nama_kategori FROM `kuesioner` as a RIGHT JOIN kategori as b ON a.kd_kategori = b.kd_kategori ORDER BY a.kd_kategori";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		$jumlahkriteria = 5;
		$jumlahkategori = 0;
		$jumlahsoal = 0;
		$lastkategori = "";
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			if ($lastkategori != $row['kd_kategori']) {
				$jumlahkategori++;
			}

			$lastkategori = $row['kd_kategori'];
			$jumlahsoal++;
		}

		$max = ($jumlahsoal*$responden) * ($jumlahkriteria);
		$range = $max - ($jumlahsoal*$responden);
		$interval = $range / ($jumlahkriteria);

		$kriteria = array();
		$value = array();

		if ($total < ($max-$interval-$interval-$interval-$interval)+0.1) {
			$value['kriteria'] = "Sangat Tidak Baik";
			$value['nilai'] = 0; 
		}
		elseif ($total < ($max-$interval-$interval-$interval)+0.1) {
			$value['kriteria'] = "Tidak Baik";
			$value['nilai'] = 1;
		}
		elseif ($total < ($max-$interval-$interval)+0.1) {
			$value['kriteria'] = "Cukup Baik";
			$value['nilai'] = 2;
		}
		elseif ($total < ($max-$interval)+0.1) {
			$value['kriteria'] = "Baik";
			$value['nilai'] = 3;
		}
		else {
			$value['kriteria'] = "Sangat Baik";
			$value['nilai'] = 4;
		}

		array_push($kriteria,$value);
		return $kriteria;
	}

	public function kriteriaaktivitas($total,$responden) {
		/*
			Fungsi Generete Kriteria Dari Total Skor Kuesionoer Mahasiswa
			Gunakan foreach ($this->kriteriamahasiswa($total) as $kriteria)
		 */
		$nilaimax = 4;
		$nilaimin = 0;
		$jumlahkriteria = 5;
		$jumlahsoal = 3;

		$max = ($jumlahsoal*$responden) * ($nilaimax);

		$range = $max - 0;
		$interval = $range / ($jumlahkriteria);

		$kriteria = array();
		$value = array();

		if ($total < ($max-$interval-$interval-$interval-$interval)+0.1) {
			$value['kriteria'] = "Sangat Tidak Baik";
			$value['nilai'] = 0; 
		}
		elseif ($total < ($max-$interval-$interval-$interval)+0.1) {
			$value['kriteria'] = "Tidak Baik";
			$value['nilai'] = 1;
		}
		elseif ($total < ($max-$interval-$interval)+0.1) {
			$value['kriteria'] = "Cukup Baik";
			$value['nilai'] = 2;
		}
		elseif ($total < ($max-$interval)+0.1) {
			$value['kriteria'] = "Baik";
			$value['nilai'] = 3;
		}
		else {
			$value['kriteria'] = "Sangat Baik";
			$value['nilai'] = 4;
		}
		array_push($kriteria,$value);
		return $kriteria;
	}


	public function responden($iddosenampu,$tipe) {
		$query1 = "SELECT count(a.id) as total FROM kuesioner_mahasiswa as a LEFT JOIN krs ON a.id_krs = krs.id WHERE krs.id_dosenampu = ".$iddosenampu;
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->execute();
		$respondenmhs=$stmt1->fetch(PDO::FETCH_ASSOC);

		$query2 = "SELECT count(a.id) as total FROM kuesioner_sejawat as a LEFT JOIN dosen_ampu as b ON a.id_dosenampu = b.id WHERE a.id_dosenampu = ".$iddosenampu;
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->execute();
		$respondendosen=$stmt2->fetch(PDO::FETCH_ASSOC);

		if ($tipe == 'mhs') {
			return $respondenmhs['total'];
		}
		else if ($tipe=='dosen') {
			return $respondendosen['total'];
		}
	}

	public function total($iddosenampu,$nidn,$tipe) {
		$query1 = "SELECT count(id) as total FROM krs WHERE id_dosenampu = ".$iddosenampu;
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->execute();
		$totalmhs=$stmt1->fetch(PDO::FETCH_ASSOC);

		$query2 = "SELECT count(id) as total FROM dosen WHERE nidn <> ".$nidn;
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->execute();
		$totaldosen=$stmt2->fetch(PDO::FETCH_ASSOC);

		if ($tipe == 'mhs') {
			return $totalmhs['total'];
		}
		else if ($tipe=='dosen') {
			return $totaldosen['total'];
		}
	}

}

$monitoring = new monitoring();


include ROOT . 'views/admin/monitoring.view.php';