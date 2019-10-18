<?php

require ROOT.'app/admin/session.php';

class beranda
{
	private $conn;
	
	function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

		
	public function getID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM krs WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}

	public function respondenkuesionermhs($tahun_akademik,$semester)
	{
		$stmt = $this->conn->prepare("SELECT count(id) as total FROM kuesioner_mahasiswa WHERE tahun_akademik='".$tahun_akademik."' AND semester='".$semester."'");
		$stmt->execute();
		
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				echo $row['total'];
			}
		}
	}

	public function ikad($tipe,$tahun_akademik,$semester)
	{
		$stmt = $this->conn->prepare("SELECT a.id_dosenampu as id, count(a.id_dosenampu) as total, d.nidn, d.nama, c.kd_mk, d.status_dosen, e.jurusan, e.nama_mk, e.sks, a.tahun_akademik, a.semester 
                    from krs a
                    left join dosen_ampu c on
                    a.id_dosenampu = c.id
                    left join dosen d on
                    c.nidn = d.nidn
                    left join matakuliah e on
                    c.kd_mk = e.kd_mk WHERE a.tahun_akademik = '".$tahun_akademik."' AND a.semester = '".$semester."' GROUP BY a.id_dosenampu");
		$stmt->execute();

		$stmt2 = $this->conn->prepare("SELECT count(id) AS total FROM dosen_ampu");
		$stmt2->execute();
		$row2=$stmt2->fetch(PDO::FETCH_ASSOC);

		$nama = array();
		$nilai = array();
			while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
				if ($row['total']<1) {
					echo 0;
				}
				else {
					$nama[] = "(".$row['kd_mk'].")".$row['nama_mk']." ".$row['nama'];
					$nilai[] = ($this->skorkuesionermahasiswa($row['id'],'kriteria')*0.5) + ($this->skorkuesionerdosen($row['id'],'kriteria')*0.35) + ($this->skoraktivitasdosen($row['id'],'kriteria',$tahun_akademik,$semester)*0.15);
 				}
			}
		if ($tipe=='nama') {
			return json_encode($nama);
		}
		elseif ($tipe=='nilai') {
			return json_encode($nilai);
		}

	}

	public function aktivitasdosen($tipe,$tahun_akademik,$semester)
	{
		$stmt = $this->conn->prepare("SELECT count(id) AS responden FROM aktivitas_dosen WHERE tahun_akademik='".$tahun_akademik."' AND semester='".$semester."'");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		$stmt2 = $this->conn->prepare("SELECT count(id) AS total FROM dosen_ampu");
		$stmt2->execute();
		$row2=$stmt2->fetch(PDO::FETCH_ASSOC);

		if ($tipe=='responden') {
			if ($row['responden']<1) {
				echo 0;
			}
			else {
				echo $row['responden'];
			}
		}
		elseif ($tipe=='total') {
			echo $row2['total'];
		}
		elseif ($tipe=='persentase') {
			echo $row['responden'] / $row2['total'] * 100;
		}

	}


	public function loginmahasiswa($tipe)
	{
		$stmt = $this->conn->prepare("SELECT count(YEARWEEK(terakhir_login)) AS login FROM mahasiswa WHERE YEARWEEK(terakhir_login)=YEARWEEK(NOW()) GROUP BY YEARWEEK(terakhir_login)");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		$stmt2 = $this->conn->prepare("SELECT count(id) AS total FROM mahasiswa");
		$stmt2->execute();
		$row2=$stmt2->fetch(PDO::FETCH_ASSOC);

		if ($tipe=='login') {
			if ($row['login']<1) {
				echo 0;
			}
			else {
				echo $row['login'];
			}
		}

		elseif ($tipe=='total') {
			echo $row['login'] / $row2['total'] * 100;
		}

	}

	public function logindosen($tipe)
	{
		$stmt = $this->conn->prepare("SELECT count(YEARWEEK(terakhir_login)) AS login FROM dosen WHERE YEARWEEK(terakhir_login)=YEARWEEK(NOW()) GROUP BY YEARWEEK(terakhir_login)");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		$stmt2 = $this->conn->prepare("SELECT count(id) AS total FROM dosen");
		$stmt2->execute();
		$row2=$stmt2->fetch(PDO::FETCH_ASSOC);

		if ($tipe=='login') {
			if ($row['login']<1) {
				echo 0;
			}
			else {
				echo $row['login'];
			}
		}

		elseif ($tipe=='total') {
			echo $row['login'] / $row2['total'] * 100;
		}
	}


	public function totalmhs($jurusan)
	{
		$stmt = $this->conn->prepare("SELECT count(id) as total FROM mahasiswa WHERE jurusan='".$jurusan."'");
		$stmt->execute();
		
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				return $row['total'];
			}
		}
	}

	/* paging */
	
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

		$query = "SELECT count(KS.id) as responden,sum(KS.skor) as total_skor, KS.jawaban FROM dosen_ampu as DA LEFT JOIN kuesioner_sejawat as KS on DA.id = KS.id_dosenampu LEFT JOIN dosen ON dosen.nidn = DA.nidn WHERE DA.id = ".$id;
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

	

$beranda = new beranda();



include ROOT . 'views/admin/beranda.view.php';