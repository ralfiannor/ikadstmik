<?php

require ROOT.'app/admin/session.php';

class laporanaktivitas
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
	

	public function laporandosen($query,$tahun_akademik,$semester)
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
	                <td><?= $this->skoraktivitasdosen($row['id'],'total',$tahun_akademik,$semester); ?></td>
	                <td><?= $this->skoraktivitasdosen($row['id'],'kriteria',$tahun_akademik,$semester); ?></td>
	            </tr>

    <?php
			}
		}		
	}	

public function cetak($tahun_akademik,$semester,$jurusan)
	{
		$stmt = $this->conn->prepare("SELECT dosen_ampu.id, matakuliah.kd_mk, matakuliah.nama_mk, matakuliah.sks, matakuliah.semester, matakuliah.jurusan, dosen.nidn, dosen.nama, dosen.status_dosen FROM aktivitas_dosen as a INNER JOIN dosen_ampu ON a.id_dosenampu = dosen_ampu.id INNER JOIN dosen ON dosen_ampu.nidn = dosen.nidn INNER JOIN matakuliah ON dosen_ampu.kd_mk = matakuliah.kd_mk WHERE a.tahun_akademik = '".$tahun_akademik."' AND a.semester = '".$semester."' AND matakuliah.jurusan = '".$jurusan."' GROUP BY a.id_dosenampu");
		$stmt->execute();
?>
<table border="0" align="center" width="100%">
	<tr>
		<td style="border-bottom:5px double;"><center><img src='<?= site_url('assets/images/kop.png') ?>' alt='[]'></center></td>
	</tr>
</table>
<table border="0" align="center" width="95%" cellpadding="1" cellspacing="2">
<caption><br>TABEL  SKOR NILAI AKTIVITAS DOSEN DALAM PENGAJARAN (AD)<br><br></caption>
	<tr>
		<td width="150">Semester</td>
		<td>: </td>
		<td><?= $semester ?></td>
	</tr>
	<tr>
		<td>Tahun Akademik</td>
		<td>: </td>
		<td><?= $tahun_akademik ?></td>
	</tr>
	<tr>
		<td>Jurusan</td>
		<td>: </td>
		<td><?= $jurusan ?></td>
	</tr>
</table>

<table border="1" align="center" width="95%" cellpadding="3" cellspacing="5" style="border-collapse:collapse;">
	<thead>
		<tr align="center">
			<td rowspan="2">NO.</td>
			<td rowspan="2">Nama Dosen</td>
			<td rowspan="2">Status**)</td>
			<td colspan="3">Komponen Penilaian</td>
			<td rowspan="2">Nilai Total</td>
			<td rowspan="2">Nilai Rerata</td>
		</tr>
		<tr>
			<td>Kehadiran di Kelas</td>
			<td>Kehadiran Rapat</td>
			<td>Penyerahan Nilai Ujian</td>
		</tr>
	</thead>

<?php
	$i=1;
	while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
?>
	<tbody>
		<tr>
			<td><?= $i ?></td>
			<td><?= $row['nama'] ?></td>			
			<td><?= $row['status_dosen'] ?></td>
<?php foreach($this->skoraktivitasdosen($row['id'],'kategori',$tahun_akademik,$semester) as $kategori => $nilai) { ?>
			<td><?= $nilai ?></td>
<?php } ?>
			<td><?= $this->skoraktivitasdosen($row['id'],'total',$tahun_akademik,$semester) ?></td>
			<td><?= $this->skoraktivitasdosen($row['id'],'kriteria',$tahun_akademik,$semester) ?></td>
		</tr>
	</tbody>
<?php
		$i++;
	}
	echo "</table>";
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
						$kriteria = $row['total_skor']." / ".$kriteria['kriteria'];
						return $kriteria;
					}
			}
		}
	}

	public function kriteriaaktivitas($total) {
		/*
			$total = total skor
			Fungsi Generete Kriteria Dari Total Skor Kuesionoer Mahasiswa
			Gunakan foreach ($this->kriteriamahasiswa($total) as $kriteria)
		 */
		$jumlahkriteria = 4;
		$jumlahsoal = 3;

		$max = $jumlahkriteria * $jumlahsoal;
		$range = $max - 0;
		$interval = $range / $jumlahkriteria;

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
		$query1 = "SELECT count(a.id) as total FROM aktivitas_mahasiswa as a LEFT JOIN krs ON a.id_krs = krs.id WHERE krs.id_dosenampu = ".$iddosenampu;
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->execute();
		$respondenmhs=$stmt1->fetch(PDO::FETCH_ASSOC);

		$query2 = "SELECT count(a.id) as total FROM aktivitas_sejawat as a LEFT JOIN dosen_ampu as b ON a.id_dosenampu = b.id WHERE a.id_dosenampu = ".$iddosenampu;
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

$laporanaktivitas = new laporanaktivitas();


include ROOT . 'views/admin/laporan-aktivitas.view.php';