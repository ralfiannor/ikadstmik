<?php

if($login->is_loggedin()=="")
{
    redirect('login/?location='.urlencode(site_url($url_segments[1].'/'.$url_segments[2])));
}

else {
    $dosen_id = $_SESSION['dosen_session'];    
    $stmt = $login->runQuery("SELECT * FROM dosen WHERE id=:dosen_id");
    $stmt->execute(array(":dosen_id"=>$dosen_id));      
    $dosenRow=$stmt->fetch(PDO::FETCH_ASSOC);
}

include ROOT."views/layout/dosen/header.php";
?>

<!--banner-->   
<div class="banner">           
    <h2>
    <a href="home.php">Home</a>
    <i class="fa fa-angle-right"></i>
    <span>Beranda</span>
    </h2>
</div>
<!--//banner-->

      
<!--grid-->
<div class="grid-form">
	<div class="grid-form1">
		<h3>Selamat Datang <b><?= $dosenRow['nama']; ?></b></h3>
        <p class="help" align="justify">&nbsp;&nbsp;&nbsp;Selamat datang di penilaian IKAD (Indeks Kinerja Dosen) berbasis web STMIK Banjarbaru. Adapun tahapan dalam melakukan pengisian kuesioner penilaian kinerja dosen secara online adalah sebagai berikut :
            <ol>
                <li>Login menggunakan username dan password anda.</li>
                <li>Masuk ke menu Isi Kuesioner.</li>
                <li>Akan mucul dosen dan matakuliah yang telah diikuti. Klik Isi Kuesioner pada kolom aksi jika tautan tidak muncul atau bertuliskan 'kuesioner sudah diisi' maka anda sudah mengisi kuesioner tersebut.</li>
                <li>Pastikan identitas anda dan dosen yang dinilai sesuai. Pilih salah satu jawaban kuesioner dari 1, 2, 3, 4, atau 5 pastikan tidak ada jawaban yang kosong.</li>
                <li>Silahkan submit jawaban anda, dan jika terjadi kesalahan periksa kembali isian anda.</li>
            </ol>
        </p>
	</div>      
</div>

<?php 
include ROOT."views/layout/dosen/footer.php";
?>

</body>
</html>