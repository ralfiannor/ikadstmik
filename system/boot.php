<?php
date_default_timezone_set("Asia/Makassar");

// Definisikan konstanta yg mengarah ke root folder
define('ROOT', str_replace('system', '', __DIR__));

// Import konfigurasi
$config = require ROOT . 'system/config.php';
// Import fungsi-fungsi helper
require ROOT . 'system/helpers.php';

// Ambil Request URI
$uri = trim($_SERVER['REQUEST_URI'], '/');
// Pisahkan URI per segmennya
$url_segments = explode('/', $uri);

// Route menu admin
if ($url_segments[1] === 'admin') {
	if ($url_segments[2] === 'login') {
	    include ROOT . 'app/admin/login.php';
	}
	else if ($url_segments[2] === 'beranda') {
	    include ROOT . 'app/admin/beranda.php';
	}
	else if ($url_segments[2] === 'mahasiswa') {
	    include ROOT . 'app/admin/mahasiswa.php';
	}
	else if ($url_segments[2] === 'dosen') {
	    include ROOT . 'app/admin/dosen.php';
	}
	else if ($url_segments[2] === 'dosen-ampu') {
	    include ROOT . 'app/admin/dosen_ampu.php';
	}
	else if ($url_segments[2] === 'matakuliah') {
	    include ROOT . 'app/admin/matakuliah.php';
	}
	else if ($url_segments[2] === 'kategori-kuesioner') {
	    include ROOT . 'app/admin/kategori_kuesioner.php';
	}
	else if ($url_segments[2] === 'kuesioner') {
	    include ROOT . 'app/admin/kuesioner.php';
	}
	else if ($url_segments[2] === 'aktivitas-dosen') {
	    include ROOT . 'app/admin/aktivitas-dosen.php';
	}
	else if ($url_segments[2] === 'krs') {
	    include ROOT . 'app/admin/krs.php';
	}
	else if ($url_segments[2] === 'monitoring') {
	    include ROOT . 'app/admin/monitoring.php';
	}
	else if ($url_segments[2] === 'laporan-kuesioner') {
	    include ROOT . 'app/admin/laporan-kuesioner.php';
	}
	else if ($url_segments[2] === 'laporan-aktivitas') {
	    include ROOT . 'app/admin/laporan-aktivitas.php';
	}
	else if ($url_segments[2] === 'laporan-ikad') {
	    include ROOT . 'app/admin/laporan-ikad.php';
	}
	else if ($url_segments[2] === 'rekapitulasi-ikad') {
	    include ROOT . 'app/admin/rekapitulasi-ikad.php';
	}
	else if ($url_segments[2] === 'ganti-sandi') {
	    include ROOT . 'app/admin/gantisandi.php';
	}
	else if ($url_segments[2] === 'layanan') {
	    include ROOT . 'app/admin/layanan.php';
	}
	else if ($url_segments[2] === 'logout') {
	    include ROOT . 'app/admin/logout.php';
	}
	else if ($url_segments[2] === NULL){
	    include ROOT . 'app/admin/index.php';
	}
}

// Route menu dosen
else if ($url_segments[1] === 'dosen') {
	if ($url_segments[2] === 'login') {
	    include ROOT . 'app/dosen/login.php';
	}
	else if ($url_segments[2] === 'beranda') {
	    include ROOT . 'app/dosen/beranda.php';
	}
	else if ($url_segments[2] === 'isi-kuesioner') {
	    include ROOT . 'app/dosen/isi-kuesioner.php';
	}
	else if ($url_segments[2] === 'ganti-sandi') {
	    include ROOT . 'app/dosen/gantisandi.php';
	}
	else if ($url_segments[2] === 'logout') {
	    include ROOT . 'app/dosen/logout.php';
	}
	else if ($url_segments[2] === NULL){
	    include ROOT . 'app/dosen/index.php';
	}
}

// Route menu mahasiswa
else {
	if ($url_segments[1] === 'login') {
	    include ROOT . 'app/mahasiswa/login.php';
	}
	else if ($url_segments[1] === 'beranda'){
	    include ROOT . 'app/mahasiswa/beranda.php';
	}
	else if ($url_segments[1] === 'isi-kuesioner'){
	    include ROOT . 'app/mahasiswa/isi-kuesioner.php';
	}
	else if ($url_segments[1] === 'ganti-sandi'){
	    include ROOT . 'app/mahasiswa/gantisandi.php';
	}
	else if ($url_segments[1] === 'logout') {
	    include ROOT . 'app/mahasiswa/logout.php';
	}
	else if ($url_segments[1] === NULL){
	    include ROOT . 'app/mahasiswa/index.php';
	}
}
