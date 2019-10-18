<?php

require ROOT.'app/admin/session.php';

class laporankuesioner
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
			$stmt = $this->conn->prepare("INSERT INTO laporan-kuesioner(nim, id_dosenampu) VALUES(:nim, :id_dosenampu)");
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
		$stmt = $this->conn->prepare("SELECT a.id,a.nim,a.id_dosenampu,b.nama_mhs,c.nidn,c.kd_mk,d.nama,e.nama_mk,e.sks,e.jurusan FROM laporan-kuesioner as a INNER JOIN mahasiswa as b ON a.nim = b.nim INNER JOIN dosen_ampu as c ON a.id_dosenampu = c.id INNER JOIN dosen as d ON c.nidn = d.nidn INNER JOIN matakuliah as e ON c.kd_mk = e.kd_mk WHERE a.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($id,$nim,$id_dosenampu)
	{
			try
		{
			$stmt=$this->conn->prepare("UPDATE laporan-kuesioner SET nim=:nim, id_dosenampu=:id_dosenampu WHERE id=:id");
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

	public function laporandosen($query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$no = 1;
		if ($stmt->rowCount() < 1) {
			echo "<tr>
			<td>Data tidak tersedia</td>
			</tr>";
		}
		else {
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
	            <tr>
	            <td><?= $no++; ?></td>
	                <td><?php print($row['nama']); ?></td>
	                <td>[<?php print($row['kd_mk']); ?>] <?php print($row['nama_mk']); ?></td>
	                <td><?php print($row['sks']); ?></td>
	                <td><?php print($row['semester']); ?></td>
	                <td><?php print($row['jurusan']); ?></td>
	             <td>
		            <a href="<?= site_url('admin/laporan-kuesioner/?dosen&cetak='.$row['id']) ?>" target="_blank" class="btn btn-xs btn-primary" <?= ($row['telah_diisi'] == NULL) ? 'disabled' : ''?>>Cetak</a> 
	            </td>
	            </tr>

    <?php
			}
		}		
	}	

		
	public function laporanmahasiswa($query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$no = 1;
		if ($stmt->rowCount() < 1) {
			echo "<tr>
			<td>Data tidak tersedia</td>
			</tr>";
		}
		else {
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
	            <tr>
	            <td><?= $no++; ?></td>
                <td><?php print($row['nama']); ?></td>
                <td>[<?php print($row['kd_mk']); ?>] <?php print($row['nama_mk']); ?></td>
                <td><?php print($row['sks']); ?></td>
                <td><?php print($row['semester']); ?></td>
                <td><?php print($row['jurusan']); ?></td>
	             <td>
		            <a href="<?= site_url('admin/laporan-kuesioner/?mahasiswa&cetak='.$row['id']) ?>" target="_blank" class="btn btn-xs btn-primary" <?= ($row['status'] == 0) ? 'disabled' : ''?>>Cetak</a> 
	            </td>
	            </tr>

    <?php
			}
		}		
	}	

	public function detailmahasiswa($id,$tahun_akademik,$semester)
	{
		$stmt = $this->conn->prepare("SELECT a.id_dosenampu as id, e.semester, e.jurusan, c.nidn, d.nama, d.status_dosen, c.kd_mk, e.nama_mk, e.sks, a.tahun_akademik, count(a.nim) as total_mhs, count(b.id_krs) as responden, sum(b.skor) as total_skor, b.jawaban as jawaban
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

		$stmt2 = $this->conn->prepare("SELECT a.* FROM kuesioner_mahasiswa as a LEFT JOIN krs as b ON a.id_krs = b.id WHERE b.id_dosenampu=".$id);
		$stmt2->execute();

		$query3 = "SELECT kuesioner.id, kuesioner.nama_kuesioner, kategori.nama_kategori FROM kategori INNER JOIN kuesioner ON kategori.kd_kategori=kuesioner.kd_kategori ORDER BY kategori.kd_kategori, kuesioner.id";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->execute();

?>
<table border="0" align="center" width="100%">
	<tr>
		<td style="border-bottom:5px double;"><center><img src='<?= site_url('assets/images/kop.png') ?>' alt='[]'></center></td>
	</tr>
</table>
<table border="1">
	<caption>table title and/or explanatory text</caption>
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Aspek Yang Dinilai</th>
			<th colspan="<?= $rowmhs['responden'] ?>">No. dan Jawaban Responden</th>
			<th rowspan="2">Total Nilai</th>
			<th rowspan="2">Rerata</th>
			<th rowspan="2">Ket</th>
		</tr>
		<tr>
<?php
	$i = 1;
	while($i <= $rowmhs['responden']) {
		echo "<th>".$i."</th>";
		$i++;
	}
?>
		</tr>
	</thead>
	<tbody>
<?php
		$i = 1;
		$lastkategori = '';
		while($rowsoal=$stmt3->fetch(PDO::FETCH_ASSOC))
		{
			if($rowsoal['nama_kategori'] != $lastkategori) {
				echo '<tr><td>A.</td><td>'.$rowsoal['nama_kategori'].'</td><td colspan="'.($rowmhs['responden']+3).'">';
			}
			else {
				echo '<tr><td>'.$i.'</td><td>'.$rowsoal['nama_kuesioner'].'</td>';				
	
				while($rowkuesioner=$stmt2->fetch(PDO::FETCH_ASSOC)) {
					echo '<td>'.$rowkuesioner['skor'].'</td>';
				}
	
				echo '<td>a</td><td>aa</td><td>aaa</td>';
			}

			echo "</tr>";
			$lastkategori = $rowsoal['nama_kategori'];

		}
?>
	</tbody>
</table>	
<?php
}


	public function cetakmahasiswa($id,$tahun_akademik,$semester,$tipe)
	{
		$stmt = $this->conn->prepare("SELECT a.id_dosenampu as id, e.semester, e.jurusan, c.nidn, d.nama, d.status_dosen, c.kd_mk, e.nama_mk, e.sks, a.tahun_akademik, count(a.nim) as total_mhs, count(b.id_krs) as responden, sum(b.skor) as total_skor, b.jawaban as jawaban
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

		if ($tipe == "kuesioner") {
?>
<table border="0" align="center" width="100%">
	<tr>
		<td style="border-bottom:5px double;"><center><img src='<?= site_url('assets/images/kop.png') ?>' alt='[]'></center></td>
	</tr>
</table>
<table border="1" align="center" width="900" cellpadding="3" cellspacing="5" style="border-collapse:collapse;">
	<caption><br>HASIL PENILAIAN KINERJA DOSEN STMIK BANJARBARU<br>SEMESTER <?= strtoupper($semester) ?> TAHUN AKADEMIK <?= $tahun_akademik ?><br>
(Menurut Persepsi Mahasiswa)<br><br>
</caption>
		<tr>
			<td>Nama Dosen</td>
			<td><?= $rowmhs['nama'] ?></td>
		</tr>
		<tr>
			<td>Status Dosen</td>
			<td><?= $rowmhs['status_dosen'] ?></td>
		</tr>
		<tr>
			<td>Nama Matakuliah Yang Diasuh</td>
			<td><?= $rowmhs['nama_mk'] ?></td>
		</tr>
		<tr>
			<td>Pada Semester</td>
			<td><?= $rowmhs['semester'] ?></td>
		</tr>
		<tr>
			<td>Program Studi</td>
			<td><?= $rowmhs['jurusan'] ?></td>
		</tr>
		<tr>
			<td>Tahun Akademik</td>
			<td><?= $rowmhs['tahun_akademik'] ?></td>
		</tr>
		<tr>
			<td>Jumlah Responden</td>
			<td><?= $rowmhs['responden'] ?> dari <?= $this->total($rowmhs['id'],$rowmhs['nidn'],'mhs') ?> orang mahasiswa</td>
		</tr>
		<tr>
			<td>Nilai Rerata Kompetensi :</td>
			<td></td>
		</tr>
		<?php
		$i = 1;
		foreach ($this->skorkuesionermahasiswa($id,'kategori') as $key => $value) {
			$rntkategori = $value / $rowmhs['responden'];
			echo "<tr valign='middle'>
					<td><ol type='A'><li value='$i'>$key</li></ol></td>
					<td>$rntkategori</td>
				</tr>";
			$i++;
		}	
		?>
</ol>		<tr>
			<td>Rerata Nilai Total (RNT)</td>
			<td><?= $this->skorkuesionermahasiswa($id,'total') ?></td>
		</tr>
		<tr>
			<td>Kesimpulan Akhir</td>
			<td><?= $this->skorkuesionermahasiswa($id,'kriteria') ?></td>
		</tr>
</table>
<?php
	}
	elseif ($tipe == "kriteria"){
?>
		<div class="footer">
		    <table border="1" style="margin-right: 10%;margin-left: 10%; border-collapse:collapse;">
					<tr>
						<td colspan="2" align="center">KRITERIA PENILAIAN TOTAL</td>
					</tr>
					<tr align="center">
						<td width="50%">KATEGORI</td>
						<td width="50%">RNT</td>
					</tr>
						<?= $this->skorkuesionermahasiswa($id,'daftarkriteria') ?>
			</table>
		</div>
<?php
	}
}


	public function cetakdosen($id,$tahun_akademik,$semester,$tipe)
	{
		$stmt = $this->conn->prepare("SELECT dosen_ampu.id, matakuliah.kd_mk, matakuliah.nama_mk, matakuliah.sks, matakuliah.semester, matakuliah.jurusan, dosen.nidn, dosen.nama, dosen.status_dosen, krs.tahun_akademik, dosen_ampu.telah_diisi FROM krs INNER JOIN dosen_ampu ON krs.id_dosenampu = dosen_ampu.id INNER JOIN matakuliah ON matakuliah.kd_mk=dosen_ampu.kd_mk INNER JOIN dosen ON dosen.nidn=dosen_ampu.nidn WHERE dosen_ampu.id=".$id." AND krs.tahun_akademik = '".$tahun_akademik."' AND krs.semester = '".$semester."' GROUP BY krs.id_dosenampu");
		$stmt->execute();
		$rowdosen=$stmt->fetch(PDO::FETCH_ASSOC);

		if ($tipe == "kuesioner") {

?>
<table border="0" align="center" width="100%">
	<tr>
		<td style="border-bottom:5px double;"><center><img src='<?= site_url('assets/images/kop.png') ?>' alt='[]'></center></td>
	</tr>
</table>
<table border="1" align="center" width="900" cellpadding="3" cellspacing="5" style="border-collapse:collapse;">
	<caption><br>HASIL PENILAIAN KINERJA DOSEN STMIK BANJARBARU<br>SEMESTER <?= strtoupper($semester) ?> TAHUN AKADEMIK <?= $tahun_akademik ?><br>
(Menurut Persepsi Teman Sejawat)<br>
</caption>
		<tr>
			<td>Nama Dosen</td>
			<td><?= $rowdosen['nama'] ?></td>
		</tr>
		<tr>
			<td>Status Dosen</td>
			<td><?= $rowdosen['status_dosen'] ?></td>
		</tr>
		<tr>
			<td>Nama Matakuliah Yang Diasuh</td>
			<td><?= $rowdosen['nama_mk'] ?></td>
		</tr>
		<tr>
			<td>Pada Semester</td>
			<td><?= $rowdosen['semester'] ?></td>
		</tr>
		<tr>
			<td>Program Studi</td>
			<td><?= $rowdosen['jurusan'] ?></td>
		</tr>
		<tr>
			<td>Tahun Akademik</td>
			<td><?= $rowdosen['tahun_akademik'] ?></td>
		</tr>
		<tr>
			<td>Jumlah Responden</td>
			<td><?= $this->responden($id,'dosen'); ?> dari <?= $this->total($rowdosen['id'],$rowdosen['nidn'],'dosen') ?> orang dosen</td>
		</tr>
		<tr>
			<td>Nilai Rerata Kompetensi :</td>
			<td></td>
		</tr>
		<?php
		$i = 1;
		foreach ($this->skorkuesionerdosen($id,'kategori') as $key => $value) {
			$rntkategori = $value / $this->responden($id,'dosen');
			echo "<tr>
					<td><ol type='A'><li value='$i'>$key</li></td>
					<td>$rntkategori</td>
				</tr>";
			$i++;
		}	
		?>
</ol>		<tr>
			<td>Rerata Nilai Total (RNT)</td>
			<td><?=$this->skorkuesionerdosen($id,'total') ?></td>
		</tr>
		<tr>
			<td>Kesimpulan Akhir</td>
			<td><?= $this->skorkuesionerdosen($id,'kriteria') ?></td>
		</tr>
</table>
<?php
	}
		elseif ($tipe == "kriteria"){
?>
<div class="footer">
    <table border="1" style="margin-right: 10%;margin-left: 10%; border-collapse:collapse;">
			<tr>
				<td colspan="2" align="center">KRITERIA PENILAIAN TOTAL</td>
			</tr>
			<tr align="center">
				<td width="50%">KATEGORI</td>
				<td width="50%">RNT</td>
			</tr>
				<?= $this->skorkuesionermahasiswa($id,'daftarkriteria') ?>
	</table>
</div>
<?php
		}
}

	public function skorkuesionermahasiswa($id,$tipe)
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
                    c.kd_mk = e.kd_mk WHERE a.id_dosenampu=".$id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		$query = "SELECT a.id as id,a.jawaban, b.* FROM kuesioner_mahasiswa as a LEFT JOIN krs as b on a.id_krs = b.id WHERE b.id_dosenampu=".$id;

		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
		{

		 foreach (json_decode($row2['jawaban'],1) as $key => $value) {
		        $a = explode('-', $key,2);
		        isset($kat[$a[1]]) or $kat[$a[1]] = 0;
		        $kat[$a[1]] += (int)$value;
		    }
		}

		if ($tipe == 'kategori') {
			return $kat;
		}
		elseif ($tipe == 'total') {
			$rerata = $row['total_skor'] / $row['responden'];
			return $rerata;
		}
		elseif ($tipe == 'kriteria') {
			if ($row['jawaban'] != NULL) {
				foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
					//echo $kriteria['nilai']." / ".$kriteria['kriteria'];
					$kriteria = $kriteria['kriteria'];
					return $kriteria;
				}
			}
			else {
				$kriteria = "Data Belum Tersedia";
				return $kriteria;
			}
		}
		elseif ($tipe == 'daftarkriteria') {
			$this->daftarkriteria($row['total_skor']);
		}
}


	public function skorkuesionerdosen($id,$tipe)
	{

		$query = "SELECT count(KS.id) as responden,sum(KS.skor) as total_skor, KS.jawaban FROM dosen_ampu as DA LEFT JOIN kuesioner_sejawat as KS on DA.id = KS.id_dosenampu LEFT JOIN dosen ON dosen.nidn = DA.nidn WHERE DA.id = ".$id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$query = "SELECT a.id as id,a.jawaban, b.* FROM kuesioner_sejawat as a LEFT JOIN dosen_ampu as b on a.id_dosenampu = b.id WHERE a.id_dosenampu=".$id;

		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
		{

		 foreach (json_decode($row2['jawaban'],1) as $key => $value) {
		        $a = explode('-', $key,2);
		        isset($kat[$a[1]]) or $kat[$a[1]] = 0;
		        $kat[$a[1]] += (int)$value;
		    }
		}

		if ($tipe == 'kategori') {
			return $kat;
		}
		elseif ($tipe == 'total') {
			$rerata = $row['total_skor'] / $row['responden'];
			return $rerata;
		}
		elseif ($tipe == 'kriteria') {
			if ($row['jawaban'] != NULL) {
				foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
					//echo $kriteria['nilai']." / ".$kriteria['kriteria'];
					$kriteria = $kriteria['kriteria'];
					return $kriteria;
				}
			}
			else {
				$kriteria = "Data Belum Tersedia";
				return $kriteria;
			}
		}
		elseif ($tipe == 'daftarkriteria') {
			$this->daftarkriteria($row['total_skor']);
		}
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
							<?php
								$rerata = $row['total_skor'] / $row['responden'];
								echo $rerata;
							?>
						</div>
		    		</div>
    				<div class="row">
						<div class="col-xs-8 col-sm-8">
							Kesimpulan Akhir
						</div>
						<div class="col-xs-4 col-sm-4">
			    			<?php
								foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
									echo $kriteria['nilai']." / ".$kriteria['kriteria'];
									$km = $kriteria['nilai'];
								}
			    			?>
						</div>
		    		</div>
    			</div>
			</div>

<?php
						}
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

		$max = ($jumlahsoal * $jumlahkriteria);
		$range = $max - $jumlahsoal;
		$interval = $range / $jumlahkriteria;

		$kriteria = array();
		$value = array();

		if (($total/$responden) < ($max-$interval-$interval-$interval-$interval)+0.1) {
			$value['kriteria'] = "Sangat Tidak Baik";
			$value['nilai'] = 0; 
		}		

		elseif (($total/$responden) < ($max-$interval-$interval-$interval)+0.1) {
			$value['kriteria'] = "Tidak Baik";
			$value['nilai'] = 1;
		}

		elseif (($total/$responden) < ($max-$interval-$interval)+0.1) {
			$value['kriteria'] = "Cukup Baik";
			$value['nilai'] = 2;
		}

		elseif (($total/$responden) < ($max-$interval)+0.1) {
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

	public function daftarkriteria($total) {
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

		$max = $jumlahsoal * $jumlahkriteria;
		$range = $max - $jumlahsoal;
		$interval = $range / $jumlahkriteria;

		//$kriteria = array();
		//$value = array();
		$SBatas = $max;
		$Batas = $max-$interval;
		$CBatas = $max-$interval-$interval;
		$KBatas = $max-$interval-$interval-$interval;
		$SKBatas = $max-$interval-$interval-$interval-$interval;
		$akhirbatas = $max-$interval-$interval-$interval-$interval-$interval;

		$SBbawah = $Batas+0.1;
		$Bbawah = $CBatas+0.1;
		$CBbawah = $KBatas+0.1;
		$KBbawah = $SKBatas+0.1;
		$SKBbawah = $akhirbatas+0.1;

		echo "<tr><td>Sangat Tidak Baik</td><td>".$SKBbawah." - ".$SKBatas."</td></tr>";
		echo "<tr><td>Tidak Baik</td><td>".$KBbawah." - ".$KBatas."</td></tr>";
		echo "<tr><td>Cukup Baik</td><td>".$CBbawah." - ".$CBatas."</td></tr>";
		echo "<tr><td>Baik</td><td>".$Bbawah." - ".$Batas."</td></tr>";
		echo "<tr><td>Sangat Baik</td><td>".$SBbawah." - ".$SBatas."</td></tr>";
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

$laporankuesioner = new laporankuesioner();


include ROOT . 'views/admin/laporan-kuesioner.view.php';