<?php

require ROOT.'app/admin/session.php';

class rekapitulasiikad
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

	
	public function getID($id)
	{
		$stmt = $this->conn->prepare("SELECT a.id,a.nim,a.id_dosenampu,b.nama_mhs,c.nidn,c.kd_mk,d.nama,e.nama_mk,e.sks,e.jurusan FROM laporan-aktivitas as a INNER JOIN mahasiswa as b ON a.nim = b.nim INNER JOIN dosen_ampu as c ON a.id_dosenampu = c.id INNER JOIN dosen as d ON c.nidn = d.nidn INNER JOIN matakuliah as e ON c.kd_mk = e.kd_mk WHERE a.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	

	public function laporan($query,$tahun_akademik,$semester)
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
	            </tr>

    <?php
			}
		}		
	}	

public function cetak($tahun_akademik,$semester,$jurusan)
	{
		$stmt = $this->conn->prepare("SELECT a.id_dosenampu as id, d.nidn, d.nama, c.kd_mk, d.status_dosen, e.jurusan, e.nama_mk, e.sks, a.tahun_akademik, a.semester 
                    from krs a
                    left join dosen_ampu c on
                    a.id_dosenampu = c.id
                    left join dosen d on
                    c.nidn = d.nidn
                    left join matakuliah e on
                    c.kd_mk = e.kd_mk WHERE a.tahun_akademik = '".$tahun_akademik."' AND a.semester = '".$semester."' AND e.jurusan='".$jurusan."' GROUP BY a.id_dosenampu");
		$stmt->execute();
?>
<table border="0" align="center" width="100%">
	<tr>
		<td style="border-bottom:5px double;"><center><img src='<?= site_url('assets/images/kop.png') ?>' alt='[]'></center></td>
	</tr>
</table>
<br><center>REKAPITULASI INDEKS KINERJA DOSEN STMIK BANJARBARU<br>SEMESTER <?= strtoupper($semester) ?> TAHUN AKADEMIK <?= $tahun_akademik ?><br><?= strtoupper($jurusan) ?></center><br><br>
<table border="1" align="center" width="900" cellpadding="3" cellspacing="5" style="border-collapse:collapse;">
	<tr>
		<td rowspan="2">No.</td>
		<td rowspan="2">Nama Dosen</td>
		<td rowspan="2">Status Dosen</td>
		<td rowspan="2">Matakuliah</td>
		<td rowspan="2">Semester</td>
		<td rowspan="2">Program Studi</td>
		<td rowspan="2">Tahun Akademik</td>
		<td colspan="3" align="center">Nilai Rerata</td>
		<td rowspan="2">Total Nilai</td>
		<td rowspan="2">Kesimpulan Akhir</td>
	</tr>
	<tr>
		<td>Kuesioner Mahasiswa</td>
		<td>Kuesioner Teman Sejawat</td>
		<td>Aktivitas Dosen</td>
	</tr>
<?php
	$i = 1;
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
?>
		<tr>
			<td><?= $i ?></td>
			<td><?= $row['nama'] ?></td>
			<td><?= $row['status_dosen'] ?></td>
			<td><?= $row['nama_mk'] ?></td>
			<td><?= $row['semester'] ?></td>
			<td><?= $row['jurusan'] ?></td>
			<td><?= $row['tahun_akademik'] ?></td>
			<td><?= $this->skorkuesionermahasiswa($row['id'],'kriteria')*0.5 ?></td>
			<td><?= $this->skorkuesionerdosen($row['id'],'kriteria')*0.35 ?></td>
			<td><?= $this->skoraktivitasdosen($row['id'],'kriteria',$tahun_akademik,$semester)*0.15 ?></td>
			<td><?php 
					$total = ($this->skorkuesionermahasiswa($row['id'],'kriteria')*0.5)+($this->skorkuesionerdosen($row['id'],'kriteria')*0.35)+($this->skoraktivitasdosen($row['id'],'kriteria',$tahun_akademik,$semester)*0.15);
					echo round($total);
				?>
			</td>
			<td><?php 
					if (round($total) < 1) {
						echo "Sangat Tidak Baik";
					}
					else if (round($total) < 2) {
						echo "Tidak Baik";
					}
					else if (round($total) < 3) {
						echo "Cukup Baik";
					}
					else if (round($total) < 4) {
						echo "Baik";
					}
					else if (round($total) < 5) {
						echo "Sangat Baik";
					}
				?>
			</td>
		</tr>
<?php
	$i++;
	}
echo "</table>";
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
			$total = $row['total_skor'];
			return $total;
		}
		elseif ($tipe == 'kriteria') {
			if ($row['jawaban'] != NULL) {
				foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
					//echo $kriteria['nilai']." / ".$kriteria['kriteria'];
					$kriteria = $kriteria['nilai'];
					return $kriteria;
				}
			}
			else {
				$kriteria = "Data Belum Tersedia";
				return $kriteria;
			}
		}
	}


	public function skorkuesionerdosen($id,$tipe)
	{
		$query = "SELECT count(id) as responden, sum(skor) as total_skor, jawaban FROM kuesioner_sejawat WHERE id_dosenampu = ".$id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		$query2 = "SELECT a.id as id, a.jawaban as jawaban FROM kuesioner_sejawat as a LEFT JOIN dosen_ampu as b on a.id_dosenampu = b.id WHERE a.id_dosenampu=".$id;

		$stmt2 = $this->conn->prepare($query2);
		$stmt2->execute();

		while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
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
			$total = $row['total_skor'];
			return $total;
		}
		elseif ($tipe == 'kriteria') {
			if ($row['jawaban'] != NULL) {
				foreach ($this->kriteria($row['total_skor'],$row['responden']) as $kriteria) {
					//echo $kriteria['nilai']." / ".$kriteria['kriteria'];
					$kriteria = $kriteria['nilai'];
					return $kriteria;
				}
			}
			else {
				$kriteria = "Data Belum Tersedia";
				return $kriteria;
			}
		}
	}

	public function skoraktivitasdosen($id,$tipe,$tahun_akademik,$semester)
	{

		$query = "SELECT count(AD.id) as responden,sum(AD.skor) as total_skor FROM dosen_ampu as DA LEFT JOIN aktivitas_dosen as AD on DA.id = AD.id_dosenampu LEFT JOIN dosen ON dosen.nidn = DA.nidn WHERE AD.tahun_akademik = '".$tahun_akademik."' AND AD.semester = '".$semester."' AND DA.id = ".$id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		$query2 = "SELECT a.id as id,a.jawaban, b.* FROM aktivitas_dosen as a LEFT JOIN dosen_ampu as b on a.id_dosenampu = b.id WHERE a.tahun_akademik = '".$tahun_akademik."' AND a.semester = '".$semester."' AND a.id_dosenampu=".$id;

		$stmt2 = $this->conn->prepare($query2);
		$stmt2->execute();
		if($row['responden']!=0) {
			while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
			{

			 foreach (json_decode($row2['jawaban'],1) as $key => $value) {
			        isset($kat[$key]) or $kat[$key] = 0;
			        $kat[$key] += (int)$value;
			    }
			}

			if ($tipe == 'kategori') {
				return $kat; // menggunakan foreach($kat as $nama_kategori => $nilai)
			}
			elseif ($tipe == 'total') {
				$total = $row['total_skor'];
				return $total;
			}
			elseif ($tipe == 'kriteria') {
					foreach ($this->kriteriaaktivitas($row['total_skor'],$row['responden']) as $kriteria) {
						$kriteria = $kriteria['nilai'];
						return $kriteria;
					}
			}
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

$rekapitulasiikad = new rekapitulasiikad();


include ROOT . 'views/admin/rekapitulasi-ikad.view.php';