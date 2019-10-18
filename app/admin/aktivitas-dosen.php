<?php

require ROOT.'app/admin/session.php';

class aktivitasdosen
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

	public function create($iddosenampu,$jawaban,$skor,$tahun_akademik,$semester)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO aktivitas_dosen(id_dosenampu, jawaban, skor, tahun_akademik, semester) VALUES(:iddosenampu,:jawaban, :skor, :tahun_akademik, :semester)");
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
	
	public function getID($id)
	{
		$stmt = $this->conn->prepare("SELECT a.id,a.nim,a.id_dosenampu,b.nama_mhs,c.nidn,c.kd_mk,d.nama,e.nama_mk,e.sks,e.jurusan FROM aktivitasdosen as a INNER JOIN mahasiswa as b ON a.nim = b.nim INNER JOIN dosen_ampu as c ON a.id_dosenampu = c.id INNER JOIN dosen as d ON c.nidn = d.nidn INNER JOIN matakuliah as e ON c.kd_mk = e.kd_mk WHERE a.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function update($iddosenampu)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE dosen_ampu SET status=1 WHERE id=:iddosenampu");
			$stmt->bindparam(":iddosenampu",$iddosenampu);
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
                <td><?php print($row['nama']); ?></td>
                <td>[<?php print($row['kd_mk']); ?>] <?php print($row['nama_mk']); ?></td>
                <td><?php print($row['sks']); ?></td>
                <td><?php print($row['semester']); ?></td>
                <td><?php print($row['jurusan']); ?></td>
             <td>
	            <?= ($row['status']==1) ? 'Penilaian Sudah Dilakukan': '<a href="'.site_url('admin/aktivitas-dosen/?lihat_id='.$row['id']).'"><i class="glyphicon glyphicon-edit"></i> Isi Penilaian</a>' ?>
            </td>
            </tr>

    <?php
		}
		
	}	

	public function isi($query,$id)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
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
				<div class="col-md-2">Semester</div>
    			<div class="col-md-10"><?php echo $row['semester']; ?></div>
    		</div>
			<div class="row show-grid">
				<div class="col-md-2">Jurusan</div>
    			<div class="col-md-10"><?php echo $row['jurusan']; ?></div>
    		</div>

    		<div class="row show-grid"></div>
			<form method="POST">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Keaktipan Dosen untuk mengajar di Kelas ditentukan dengan skala sebagai berikut</h5>
					</div>
					<table class="table">
						<tr>
							<td width="10">1.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen untuk mengajar di Kelas]" value="4" type="radio"> <b>Tingkat kehadiran 100% (tanpa  kuliah tambahan). Skor =  4</b>
							</td>
						</tr>
						<tr>
							<td width="10">2.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen untuk mengajar di Kelas]" value="3" type="radio"> <b>Tingkat kehadiran  100% (kuliah tambahan 1-2 kali). Skor = 3</b>
							</td>
						</tr>
						<tr>
							<td width="10">3.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen untuk mengajar di Kelas]" value="2" type="radio"> <b>Tingkat kehadiran 100% (kuliah tambahan 3-4 kali). Skor = 2</b>
							</td>
						</tr>
						<tr>
							<td width="10">4.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen untuk mengajar di Kelas]" value="1" type="radio"> <b>Tingkat kehadiran 100% (kuliah tambahan > 4 kali). Skor = 1</b>
							</td>
						</tr>
						<tr>
							<td width="10">5.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen untuk mengajar di Kelas]" value="0" type="radio"> <b>Tidak pernah hadir dalam perkuliahan. Skor = 0</b>
							</td>
						</tr>
					</table>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Keaktipan Dosen dalam mengikuti rapat-rapat dengan skala sebagai berikut</h5>
					</div>
					<table class="table">
						<tr>
							<td width="10">1.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen dalam mengikuti rapat-rapat]" value="4" type="radio"> <b>Kehadiran mengikuti sebanyak > 5 kali rapat. Skor = 4</b>
							</td>
						</tr>
						<tr>
							<td width="10">2.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen dalam mengikuti rapat-rapat]" value="3" type="radio"> <b>Kehadiran mengikuti sebanyak 4 kali rapat. Skor	= 3</b>
							</td>
						</tr>
						<tr>
							<td width="10">3.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen dalam mengikuti rapat-rapat]" value="2" type="radio"> <b>Kehadiran  mengikuti sebanyah 3 kali rapat. Skor = 2</b>
							</td>
						</tr>
						<tr>
							<td width="10">4.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen dalam mengikuti rapat-rapat]" value="1" type="radio"> <b>Kehadiran  mengikuti sebanyak 2 kali rapat. Skor = 1</b>
							</td>
						</tr>
						<tr>
							<td width="10">5.</td>
							<td>
								<input name="jawaban[Keaktipan Dosen dalam mengikuti rapat-rapat]" value="0" type="radio"> <b>Tidak pernah menghadiri rapat. Skor	= 0</b>
							</td>
						</tr>
					</table>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Ketepatan dosen dalam mengumpulkan nilai ujian dengan skala sebagai berikut</h5>
					</div>
					<table class="table">
						<tr>
							<td width="10">1.</td>
							<td>
								<input name="jawaban[Ketepatan dosen dalam mengumpulkan nilai ujian]" value="4" type="radio"> <b>Tepat waktu (minimal terlambat 1-3 hari) dengan skor = 4</b>
							</td>
						</tr>
						<tr>
							<td width="10">2.</td>
							<td>
								<input name="jawaban[Ketepatan dosen dalam mengumpulkan nilai ujian]" value="3" type="radio"> <b>Terlambat waktu (4-5 hari) dengan skor = 3</b>
							</td>
						</tr>
						<tr>
							<td width="10">3.</td>
							<td>
								<input name="jawaban[Ketepatan dosen dalam mengumpulkan nilai ujian]" value="2" type="radio"> <b>Terlambat waktu (6-7 hari) dengan skor = 2</b>
							</td>
						</tr>
						<tr>
							<td width="10">4.</td>
							<td>
								<input name="jawaban[Ketepatan dosen dalam mengumpulkan nilai ujian]" value="1" type="radio"> <b>Terlambat waktu (8-9 hari) dengan skor = 1</b>
							</td>
						</tr>
						<tr>
							<td width="10">5.</td>
							<td>
								<input name="jawaban[Ketepatan dosen dalam mengumpulkan nilai ujian]" value="0" type="radio"> <b>Terlambat waktu (lebih dari 9 hari) dengan skor =  0</b>
							</td>
						</tr>
					</table>
				</div>

			<center>
				<input type="submit" name="btn-save" value="Isi Penilaian" class="btn btn-primary">&nbsp;
				<input type="reset" value="Reset" class="btn btn-warning">&nbsp;&nbsp;&nbsp;
				<a href="<?= site_url('admin/aktivitas-dosen') ?>" class="btn btn-default">Batal</a></center>
					<br>
			</form>
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
							<b>Rerata Kompetensi</b>
						</div>
		    		</div>

					<?php
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


	public function skorkuesionerdosen($id)
	{

		$query = "SELECT count(KS.id) as responden,sum(KS.skor) as total_skor FROM dosen_ampu as DA LEFT JOIN kuesioner_sejawat as KS on DA.id = KS.id_dosenampu LEFT JOIN dosen ON dosen.nidn = DA.nidn WHERE DA.id = ".$id;
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
						$query = "SELECT a.id as id,a.jawaban, b.* FROM kuesioner_sejawat as a LEFT JOIN dosen_ampu as b on a.id_dosenampu = b.id WHERE a.id_dosenampu=".$id;

						$stmt = $this->conn->prepare($query);
						$stmt->execute();
						if($stmt->rowCount()>0) {
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

		$max = ($jumlahsoal*$responden) * ($jumlahkriteria);
		$range = $max - ($jumlahsoal*$responden);
		$interval = $range / ($jumlahkriteria);

		$kriteria = array();
		$value = array();

		if ($total < ($max-$interval-$interval)+0.1) {
			$value['kriteria'] = "Sangat Tidak Baik";
			$value['nilai'] = 1; 
		}
		elseif ($total < ($max-$interval-$interval)+0.1) {
			$value['kriteria'] = "Tidak Baik";
			$value['nilai'] = 2;
		}
		elseif ($total < ($max-$interval-$interval)+0.1) {
			$value['kriteria'] = "Cukup Baik";
			$value['nilai'] = 3;
		}
		elseif ($total < ($max-$interval)+0.1) {
			$value['kriteria'] = "Baik";
			$value['nilai'] = 4;
		}
		else {
			$value['kriteria'] = "Sangat Baik";
			$value['nilai'] = 5;
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

$aktivitasdosen = new aktivitasdosen();


include ROOT . 'views/admin/aktivitas-dosen.view.php';