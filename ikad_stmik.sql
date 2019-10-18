-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jul 26, 2017 at 07:57 PM
-- Server version: 10.1.9-MariaDB-log
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikad_stmik`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `joining_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `nama`, `joining_date`) VALUES
(1, 'admin', '$2y$10$LD7FYvus.M5F8Oo5v1d./.8Iz08A/Vg3W2lrIfsv0blsILCWrpmr.', 'admin@mail.com', 'Administrator', '2016-03-15 22:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas_dosen`
--

CREATE TABLE `aktivitas_dosen` (
  `id` int(11) NOT NULL,
  `id_dosenampu` int(11) NOT NULL,
  `jawaban` text NOT NULL,
  `skor` varchar(50) NOT NULL,
  `tahun_akademik` varchar(35) NOT NULL,
  `semester` varchar(35) NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aktivitas_dosen`
--

INSERT INTO `aktivitas_dosen` (`id`, `id_dosenampu`, `jawaban`, `skor`, `tahun_akademik`, `semester`, `tgl_isi`) VALUES
(6, 10, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"4","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '12', '2016/2017', 'Genap', '2017-06-15 23:50:22'),
(7, 11, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"4","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '12', '2016/2017', 'Genap', '2017-06-15 23:51:11'),
(8, 12, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"4","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '12', '2016/2017', 'Genap', '2017-06-15 23:51:35'),
(9, 13, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"3","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '11', '2016/2017', 'Genap', '2017-06-15 23:52:09'),
(10, 14, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"4","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '12', '2016/2017', 'Genap', '2017-06-15 23:52:28'),
(11, 21, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"0","Ketepatan dosen dalam mengumpulkan nilai ujian":"0"}', '4', '2016/2017', 'Genap', '2017-06-15 23:53:08'),
(13, 19, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"3","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '11', '2016/2017', 'Genap', '2017-06-17 01:37:48'),
(14, 20, '{"Keaktipan Dosen untuk mengajar di Kelas":"3","Keaktipan Dosen dalam mengikuti rapat-rapat":"4","Ketepatan dosen dalam mengumpulkan nilai ujian":"4"}', '11', '2016/2017', 'Genap', '2017-07-06 03:15:26'),
(15, 23, '{"Keaktipan Dosen untuk mengajar di Kelas":"4","Keaktipan Dosen dalam mengikuti rapat-rapat":"4","Ketepatan dosen dalam mengumpulkan nilai ujian":"3"}', '11', '2016/2017', 'Genap', '2017-07-15 03:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nidn` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(18) NOT NULL,
  `status_dosen` varchar(255) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `terakhir_login` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `password`, `email`, `nama`, `alamat`, `no_hp`, `status_dosen`, `tgl_daftar`, `terakhir_login`) VALUES
(14, '1001005', '$2y$10$pqRwINOxR/krpB0hXwYCiO1.Ive8sxtFvS6DBqcVzH3dwVsENbbou', 'fitriyadi@mail.com', 'H. Fitriyadi, S.Pi, M.Kom', 'Jl. xxxx', '08888988', 'Tetap', '2017-06-15 15:07:30', NULL),
(13, '1001012', '$2y$10$YHo/DIULy0TDp5SEoTQtzORKakrFLawtNROEu.SnRzQdTk0Np/KFy', 'fatimah@mail.com', 'Siti Fatimah, M.Kom', 'Jl. xxxx', '0874545383434', 'Tetap', '2017-06-15 15:00:59', NULL),
(7, '1001016', '$2y$10$oVDGAqiZ6Hgv3x5UZs7iEeyJwk4ZXFFE18R6K7UXnmjh5f6sQnZDS', 'rahmadi@mail.com', 'Rahmadi, SE', 'Jl. jalan', '089777876789', 'Tetap', '2017-06-11 13:26:58', NULL),
(6, '1001019', '$2y$10$hC45jPDXizAW6/7aA9db.eHW7lmGTJis14dK.aHtXNt1IaI116DOi', 'eka@mail.com', 'Eka Chandra Kirana, M.Kom', 'Jl. flamboyan Banjarmasin', '08889897978', 'Tetap', '2017-06-11 13:24:56', NULL),
(16, '1001021', '$2y$10$IEn9QLvGIQci/3VbRddMpeWXLg.X3pN7lh/U3/iXXGg34nPghS2vK', 'black.tunggul@gmail.com', 'Muslihuddin, M. Kom', 'Jl. Banjarbaru Selatan Loktabat', '083712738121', 'Tetap', '2017-06-15 20:33:53', '2017-07-06 10:11:08'),
(11, '1001023', '$2y$10$AaSri9SHGzlP60yaxYn.YewaCyTaS3YuXoraL6a7t.sfuJ.36oWWC', 'abidah@mail.com', 'Siti Abidah, M. Kom', 'Jl. xxxx', '0888888888888', 'Tetap', '2017-06-15 14:58:36', NULL),
(15, '1001025', '$2y$10$.wFMsFXh2gIZt.B7bx2sv.9VtohaN6si.iKHD6M2m0CPUludkaIbm', 'ahmad.pahdi@mail.com', 'Ahmad Pahdi, M.Kom', 'Jl. Bunayani Braja', '081212991212', 'Tetap', '2017-06-15 20:24:31', NULL),
(4, '1001042', '$2y$10$oIvgmKEey3oga0BXR6XeAOJrjueZNeBcRBrryLC8PbFgJhCK7CcRq', 'ratna@mail.com', 'Ratna Fitriani, ST., M.Kom', 'Jl. Ahmad Yani Km. 17,5 Kota Citra Graha Clauser Flamboyan Blok A No. 3 Banjarbaru Kalimantan Selatan', '081250505225', 'Tetap', '2017-06-11 13:14:47', NULL),
(12, '10020106', '$2y$10$GhKdWvhdT3SlH/oX3Np3QOD3BE3t3LQmjObG52zFkJYND.E.HbhmS', 'soegiarto@mail.com', 'H. Soegiarto, M.Kom', 'Jl. xxxx', '085798349458435', 'Tetap', '2017-06-15 14:59:58', NULL),
(9, '1002037', '$2y$10$Zn80I9QjaNLxQvoWsYvWHuJ5duDMEb9EaGizSgwL9DHUJBfBIWZEq', 'rintana@mail.com', 'Ir. Rintana Arnie, M.Kom', 'Jl. Banjarbaru', '0888898988', 'Tetap', '2017-06-15 14:55:07', NULL),
(8, '1002046', '$2y$10$Vd.Fri7WiEMAvtOT8HiMsegA9R5lW20qcmxp.x2RIDNyUZ.89/M8e', 'bahar@mail.com', 'Bahar A. Rahman, ST., M.Kom', 'Jl. Tesss', '089997998899', 'Tetap', '2017-06-11 13:28:32', NULL),
(1, '1002074', '$2y$10$MYA3of3jvHr08URMkWNJweq3NYDPRIPeLtKIDBwou5Y8BEEGnGAvC', 'taufiq@mail.com', 'Taufiq, M.Kom', 'Jl. Banjarbaru Selatan', '089999999995', 'Tetap', '2016-04-23 20:46:16', '2017-07-06 10:11:20'),
(10, '195607082819850', '$2y$10$qmg9.F9cozaSZZGxJ5lmGe4bhJiYnORK6Xqsiv9UyhZ40brX2DKWq', 'syahib@mail.com', 'Drs.Ec.H.Syahib Natarsyah, MM, M.Kom', 'Jl. xxxx', '088888888888', 'Tetap', '2017-06-15 14:56:52', '2017-07-22 00:33:34'),
(2, '19690401200501', '$2y$10$eMpkuYdHyMmala4z19drD.9xpBeDZS0q7IpBhtQmblm6llA1gpuMO', 'yulia@gmail.com', 'Ir. Yulia Yudihartanti, M.Kom', 'Jl. Banjarbaru Selatan', '08999788766', 'Tetap', '2017-05-15 13:05:49', '2017-07-22 07:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_ampu`
--

CREATE TABLE `dosen_ampu` (
  `id` int(11) NOT NULL,
  `nidn` varchar(50) NOT NULL,
  `kd_mk` varchar(50) NOT NULL,
  `telah_diisi` varchar(350) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dosen_ampu`
--

INSERT INTO `dosen_ampu` (`id`, `nidn`, `kd_mk`, `telah_diisi`, `status`) VALUES
(10, '1002037', 'IKB-129', NULL, 0),
(11, '1001005', 'IKK-337', NULL, 0),
(12, '1001023', 'SKB-317', NULL, 0),
(13, '10020106', 'SPB-250', NULL, 0),
(14, '1002074', 'SKB-496', NULL, 0),
(15, '1002046', 'SKB-319', NULL, 0),
(16, '195607082819850', 'IKK-107', NULL, 0),
(17, '19690401200501', 'SKB-141', NULL, 0),
(18, '19690401200501', 'SKB-143', NULL, 0),
(19, '1001025', 'SKB-315', NULL, 0),
(20, '1001021', 'SKK-511', '["1002074","19690401200501"]', 1),
(21, '1001012', 'IKK-211', NULL, 0),
(22, '1001021', 'IKB-129', '["19690401200501"]', 0),
(23, '1001021', 'IKK-337', NULL, 0),
(24, '19690401200501', 'IKK-337', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kd_kategori` varchar(25) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kd_kategori`, `nama_kategori`, `created_date`) VALUES
(29, 'A-01', 'Kompetensi Pedagogik', '2017-05-09 14:49:20'),
(30, 'A-02', 'Kompetensi Professional', '2017-05-09 14:49:20'),
(32, 'A-03', 'Kompetensi Kepribadian', '2017-05-09 14:49:20'),
(33, 'A-04', 'Kompetensi Sosial', '2017-05-09 14:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `id_dosenampu` int(11) NOT NULL,
  `tahun_akademik` varchar(35) NOT NULL,
  `semester` varchar(35) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `nim`, `id_dosenampu`, `tahun_akademik`, `semester`, `status`) VALUES
(26, '310113012150', 20, '2016/2017', 'Genap', 1),
(27, '310113012163', 20, '2016/2017', 'Genap', 1),
(28, '310113012140', 20, '2016/2017', 'Genap', 1),
(29, '310113012105', 20, '2016/2017', 'Genap', 1),
(30, '310113012107', 20, '2016/2017', 'Genap', 1),
(31, '310113012108', 20, '2016/2017', 'Genap', 1),
(32, '310113012109', 20, '2016/2017', 'Genap', 1),
(33, '310113012115', 20, '2016/2017', 'Genap', 1),
(34, '310111021675', 19, '2016/2017', 'Genap', 0),
(35, '310111021769', 19, '2016/2017', 'Genap', 0),
(36, '310112022167', 19, '2016/2017', 'Genap', 0),
(37, '310113022246', 19, '2016/2017', 'Genap', 0),
(38, '310113022289', 19, '2016/2017', 'Genap', 0),
(39, '310113022298', 21, '2016/2017', 'Genap', 0),
(40, '310113022389', 21, '2016/2017', 'Genap', 0),
(41, '310113022396', 21, '2016/2017', 'Genap', 0),
(42, '310113022414', 21, '2016/2017', 'Genap', 0),
(43, '310113022429', 21, '2016/2017', 'Genap', 0),
(44, '310113012146', 20, '2016/2017', 'Genap', 1),
(45, '310113012158', 20, '2016/2017', 'Genap', 1),
(46, '310113012100', 20, '2016/2017', 'Genap', 0),
(47, '310113012183', 23, '2016/2017', 'Genap', 0),
(48, '310113012183', 22, '2016/2017', 'Genap', 0),
(49, '310113012163', 23, '2016/2017', 'Genap', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner`
--

CREATE TABLE `kuesioner` (
  `id` int(11) NOT NULL,
  `kd_kategori` varchar(50) NOT NULL,
  `nama_kuesioner` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kuesioner`
--

INSERT INTO `kuesioner` (`id`, `kd_kategori`, `nama_kuesioner`) VALUES
(46, 'A-01', 'Kesiapan memberikan kuliah dan/atau praktek/praktikum'),
(47, 'A-01', 'Keteraturan dan keterlibatan penyelenggaraan perkuliahan'),
(48, 'A-01', 'Kemampuan menghidupkan suasana kelas'),
(49, 'A-01', 'Kejelasan penyampaian materi dan jawaban terhadap pertanyaan di kelas'),
(50, 'A-01', 'Pemanfaatan media dan teknologi pembelajaran'),
(51, 'A-01', 'Keanekaragaman cara pengukuran/penilaian hasil belajar'),
(52, 'A-01', 'Pemberian umpan balik terhadap tugas/penilaian'),
(53, 'A-01', 'Kesesuaian materi ujian dan/atau tugas dengan tujuan mata kuliah'),
(54, 'A-01', 'Kesesuaian nilai yang diberikan dengan hasil belajar'),
(55, 'A-02', 'Kemampuan menjelaskan pokok bahasan/topik secara tepat'),
(56, 'A-02', 'Kemampuan memberi contoh relevan dari konsep yang diajarkan'),
(57, 'A-02', 'Kemampuan menjelaskan keterkaitan bidang keahlian yang diajarkan dengan bidang/topik lain'),
(58, 'A-02', 'Kemampuan menjelaskan keterkaitan bidang keahlian yang diajarkan dengan konteks kehidupan'),
(59, 'A-02', 'Penguasaan akan isu-isu mutakhir dalam bidang yang diajarkan (kemutakhiran bahan/referensi kuliah)'),
(60, 'A-02', 'Penguasaan hasil-hasil penelitian untuk meningkatkan kualitas perkuliahan'),
(61, 'A-02', 'Pelibatan mahasiswa dalam penelitian/kajian dan atau pelibatan mahasiswa dalam penelitian/kajian dan atau pengembangan/rekayasa/desain yang dilakukan dosen'),
(62, 'A-02', 'Kemampuan menggunakan beragam teknologi komunikasi'),
(63, 'A-03', 'Kewibaan sebagai pribadi dosen'),
(64, 'A-03', 'Kearifan dalam mengambil keputusan'),
(65, 'A-03', 'Menjadi contoh dalam bersikap dan berprilaku'),
(66, 'A-03', 'Satunya kata dan tindakan'),
(67, 'A-03', 'Kemampuan mengendalikan diri dalam berbagai situasi dan kondisi'),
(68, 'A-03', 'Adil dalam memperlakukan sejawat, karyawan, dan mahasiswa'),
(69, 'A-04', 'Kemampuan menyampaikan pendapat'),
(70, 'A-04', 'Kemampuan menerima kritik, saran, dan pendapat orang lain'),
(71, 'A-04', 'Mengenal dengan baik mahasiswa yang mengikuti kuliahnya'),
(72, 'A-04', 'Mudah bergaul di kalangan sejawat, karyawan, dan mahasiswa'),
(73, 'A-04', 'Toleransi terhadap keberagaman di maasyarakat'),
(74, 'A-01', 'Kesiapan memberi kuliah dan/atau praktek/praktikum');

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_mahasiswa`
--

CREATE TABLE `kuesioner_mahasiswa` (
  `id` int(11) NOT NULL,
  `id_krs` int(50) NOT NULL,
  `skor` varchar(50) NOT NULL,
  `jawaban` text NOT NULL,
  `tahun_akademik` varchar(35) NOT NULL,
  `semester` varchar(35) NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kuesioner_mahasiswa`
--

INSERT INTO `kuesioner_mahasiswa` (`id`, `id_krs`, `skor`, `jawaban`, `tahun_akademik`, `semester`, `tgl_isi`) VALUES
(25, 26, '77', '{"46-Kompetensi Pedagogik":"4","47-Kompetensi Pedagogik":"2","48-Kompetensi Pedagogik":"3","49-Kompetensi Pedagogik":"2","50-Kompetensi Pedagogik":"1","51-Kompetensi Pedagogik":"3","52-Kompetensi Pedagogik":"4","53-Kompetensi Pedagogik":"2","54-Kompetensi Pedagogik":"1","55-Kompetensi Professional":"3","56-Kompetensi Professional":"2","57-Kompetensi Professional":"1","58-Kompetensi Professional":"3","59-Kompetensi Professional":"1","60-Kompetensi Professional":"1","61-Kompetensi Professional":"1","62-Kompetensi Professional":"5","63-Kompetensi Kepribadian":"5","64-Kompetensi Kepribadian":"5","65-Kompetensi Kepribadian":"1","66-Kompetensi Kepribadian":"3","67-Kompetensi Kepribadian":"5","68-Kompetensi Kepribadian":"4","69-Kompetensi Sosial":"3","70-Kompetensi Sosial":"2","71-Kompetensi Sosial":"1","72-Kompetensi Sosial":"4","73-Kompetensi Sosial":"5"}', '2016/2017', 'Genap', '2017-07-06 02:54:02'),
(26, 27, '82', '{"46-Kompetensi Pedagogik":"2","47-Kompetensi Pedagogik":"3","48-Kompetensi Pedagogik":"1","49-Kompetensi Pedagogik":"2","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"3","52-Kompetensi Pedagogik":"2","53-Kompetensi Pedagogik":"1","54-Kompetensi Pedagogik":"5","55-Kompetensi Professional":"3","56-Kompetensi Professional":"4","57-Kompetensi Professional":"1","58-Kompetensi Professional":"3","59-Kompetensi Professional":"2","60-Kompetensi Professional":"4","61-Kompetensi Professional":"5","62-Kompetensi Professional":"4","63-Kompetensi Kepribadian":"5","64-Kompetensi Kepribadian":"2","65-Kompetensi Kepribadian":"2","66-Kompetensi Kepribadian":"4","67-Kompetensi Kepribadian":"1","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"2","70-Kompetensi Sosial":"2","71-Kompetensi Sosial":"4","72-Kompetensi Sosial":"3","73-Kompetensi Sosial":"3"}', '2016/2017', 'Genap', '2017-07-06 02:54:30'),
(27, 28, '86', '{"46-Kompetensi Pedagogik":"2","47-Kompetensi Pedagogik":"2","48-Kompetensi Pedagogik":"2","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"2","51-Kompetensi Pedagogik":"5","52-Kompetensi Pedagogik":"2","53-Kompetensi Pedagogik":"2","54-Kompetensi Pedagogik":"3","55-Kompetensi Professional":"5","56-Kompetensi Professional":"2","57-Kompetensi Professional":"2","58-Kompetensi Professional":"2","59-Kompetensi Professional":"4","60-Kompetensi Professional":"3","61-Kompetensi Professional":"3","62-Kompetensi Professional":"1","63-Kompetensi Kepribadian":"3","64-Kompetensi Kepribadian":"5","65-Kompetensi Kepribadian":"4","66-Kompetensi Kepribadian":"1","67-Kompetensi Kepribadian":"4","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"5","70-Kompetensi Sosial":"3","71-Kompetensi Sosial":"4","72-Kompetensi Sosial":"4","73-Kompetensi Sosial":"1"}', '2016/2017', 'Genap', '2017-07-06 02:54:53'),
(28, 29, '107', '{"46-Kompetensi Pedagogik":"5","47-Kompetensi Pedagogik":"5","48-Kompetensi Pedagogik":"5","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"3","51-Kompetensi Pedagogik":"1","52-Kompetensi Pedagogik":"3","53-Kompetensi Pedagogik":"1","54-Kompetensi Pedagogik":"5","55-Kompetensi Professional":"5","56-Kompetensi Professional":"4","57-Kompetensi Professional":"3","58-Kompetensi Professional":"4","59-Kompetensi Professional":"5","60-Kompetensi Professional":"4","61-Kompetensi Professional":"5","62-Kompetensi Professional":"3","63-Kompetensi Kepribadian":"4","64-Kompetensi Kepribadian":"3","65-Kompetensi Kepribadian":"2","66-Kompetensi Kepribadian":"4","67-Kompetensi Kepribadian":"4","68-Kompetensi Kepribadian":"2","69-Kompetensi Sosial":"4","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"3","73-Kompetensi Sosial":"5"}', '2016/2017', 'Genap', '2017-07-06 02:58:31'),
(29, 30, '103', '{"46-Kompetensi Pedagogik":"1","47-Kompetensi Pedagogik":"3","48-Kompetensi Pedagogik":"5","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"5","51-Kompetensi Pedagogik":"2","52-Kompetensi Pedagogik":"2","53-Kompetensi Pedagogik":"2","54-Kompetensi Pedagogik":"4","55-Kompetensi Professional":"1","56-Kompetensi Professional":"3","57-Kompetensi Professional":"4","58-Kompetensi Professional":"2","59-Kompetensi Professional":"4","60-Kompetensi Professional":"4","61-Kompetensi Professional":"5","62-Kompetensi Professional":"3","63-Kompetensi Kepribadian":"4","64-Kompetensi Kepribadian":"4","65-Kompetensi Kepribadian":"4","66-Kompetensi Kepribadian":"3","67-Kompetensi Kepribadian":"5","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"4","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"5","73-Kompetensi Sosial":"4"}', '2016/2017', 'Genap', '2017-07-06 02:59:06'),
(30, 31, '106', '{"46-Kompetensi Pedagogik":"3","47-Kompetensi Pedagogik":"2","48-Kompetensi Pedagogik":"5","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"5","51-Kompetensi Pedagogik":"5","52-Kompetensi Pedagogik":"5","53-Kompetensi Pedagogik":"5","54-Kompetensi Pedagogik":"5","55-Kompetensi Professional":"5","56-Kompetensi Professional":"3","57-Kompetensi Professional":"1","58-Kompetensi Professional":"2","59-Kompetensi Professional":"4","60-Kompetensi Professional":"1","61-Kompetensi Professional":"5","62-Kompetensi Professional":"4","63-Kompetensi Kepribadian":"4","64-Kompetensi Kepribadian":"2","65-Kompetensi Kepribadian":"3","66-Kompetensi Kepribadian":"5","67-Kompetensi Kepribadian":"3","68-Kompetensi Kepribadian":"4","69-Kompetensi Sosial":"5","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"2","72-Kompetensi Sosial":"5","73-Kompetensi Sosial":"3"}', '2016/2017', 'Genap', '2017-07-06 02:59:40'),
(31, 32, '121', '{"46-Kompetensi Pedagogik":"1","47-Kompetensi Pedagogik":"1","48-Kompetensi Pedagogik":"4","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"5","51-Kompetensi Pedagogik":"5","52-Kompetensi Pedagogik":"5","53-Kompetensi Pedagogik":"3","54-Kompetensi Pedagogik":"5","55-Kompetensi Professional":"2","56-Kompetensi Professional":"3","57-Kompetensi Professional":"5","58-Kompetensi Professional":"5","59-Kompetensi Professional":"5","60-Kompetensi Professional":"4","61-Kompetensi Professional":"5","62-Kompetensi Professional":"5","63-Kompetensi Kepribadian":"5","64-Kompetensi Kepribadian":"5","65-Kompetensi Kepribadian":"5","66-Kompetensi Kepribadian":"5","67-Kompetensi Kepribadian":"3","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"5","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"5","73-Kompetensi Sosial":"5"}', '2016/2017', 'Genap', '2017-07-06 03:00:19'),
(32, 33, '122', '{"46-Kompetensi Pedagogik":"5","47-Kompetensi Pedagogik":"4","48-Kompetensi Pedagogik":"5","49-Kompetensi Pedagogik":"4","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"5","52-Kompetensi Pedagogik":"5","53-Kompetensi Pedagogik":"3","54-Kompetensi Pedagogik":"1","55-Kompetensi Professional":"5","56-Kompetensi Professional":"5","57-Kompetensi Professional":"5","58-Kompetensi Professional":"5","59-Kompetensi Professional":"5","60-Kompetensi Professional":"4","61-Kompetensi Professional":"5","62-Kompetensi Professional":"5","63-Kompetensi Kepribadian":"4","64-Kompetensi Kepribadian":"5","65-Kompetensi Kepribadian":"5","66-Kompetensi Kepribadian":"3","67-Kompetensi Kepribadian":"5","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"2","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"5","73-Kompetensi Sosial":"3"}', '2016/2017', 'Genap', '2017-07-06 03:00:59'),
(33, 44, '117', '{"46-Kompetensi Pedagogik":"5","47-Kompetensi Pedagogik":"5","48-Kompetensi Pedagogik":"5","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"4","52-Kompetensi Pedagogik":"3","53-Kompetensi Pedagogik":"3","54-Kompetensi Pedagogik":"5","55-Kompetensi Professional":"3","56-Kompetensi Professional":"3","57-Kompetensi Professional":"5","58-Kompetensi Professional":"5","59-Kompetensi Professional":"1","60-Kompetensi Professional":"5","61-Kompetensi Professional":"2","62-Kompetensi Professional":"2","63-Kompetensi Kepribadian":"5","64-Kompetensi Kepribadian":"4","65-Kompetensi Kepribadian":"5","66-Kompetensi Kepribadian":"5","67-Kompetensi Kepribadian":"4","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"4","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"5","73-Kompetensi Sosial":"5"}', '2016/2017', 'Genap', '2017-07-06 03:01:38'),
(34, 45, '130', '{"46-Kompetensi Pedagogik":"5","47-Kompetensi Pedagogik":"5","48-Kompetensi Pedagogik":"3","49-Kompetensi Pedagogik":"5","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"3","52-Kompetensi Pedagogik":"3","53-Kompetensi Pedagogik":"5","54-Kompetensi Pedagogik":"4","55-Kompetensi Professional":"5","56-Kompetensi Professional":"5","57-Kompetensi Professional":"5","58-Kompetensi Professional":"5","59-Kompetensi Professional":"5","60-Kompetensi Professional":"5","61-Kompetensi Professional":"5","62-Kompetensi Professional":"5","63-Kompetensi Kepribadian":"4","64-Kompetensi Kepribadian":"5","65-Kompetensi Kepribadian":"5","66-Kompetensi Kepribadian":"5","67-Kompetensi Kepribadian":"5","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"5","70-Kompetensi Sosial":"4","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"5","73-Kompetensi Sosial":"5"}', '2016/2017', 'Genap', '2017-07-06 03:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_sejawat`
--

CREATE TABLE `kuesioner_sejawat` (
  `id` int(11) NOT NULL,
  `id_dosenampu` int(11) NOT NULL,
  `jawaban` text NOT NULL,
  `skor` varchar(50) NOT NULL,
  `tahun_akademik` varchar(35) NOT NULL,
  `semester` varchar(35) NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kuesioner_sejawat`
--

INSERT INTO `kuesioner_sejawat` (`id`, `id_dosenampu`, `jawaban`, `skor`, `tahun_akademik`, `semester`, `tgl_isi`) VALUES
(47, 20, '{"46-Kompetensi Pedagogik":"3","47-Kompetensi Pedagogik":"3","48-Kompetensi Pedagogik":"4","49-Kompetensi Pedagogik":"4","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"4","52-Kompetensi Pedagogik":"5","53-Kompetensi Pedagogik":"4","54-Kompetensi Pedagogik":"4","55-Kompetensi Professional":"4","56-Kompetensi Professional":"4","57-Kompetensi Professional":"4","58-Kompetensi Professional":"4","59-Kompetensi Professional":"3","60-Kompetensi Professional":"3","61-Kompetensi Professional":"3","62-Kompetensi Professional":"4","63-Kompetensi Kepribadian":"3","64-Kompetensi Kepribadian":"3","65-Kompetensi Kepribadian":"3","66-Kompetensi Kepribadian":"3","67-Kompetensi Kepribadian":"3","68-Kompetensi Kepribadian":"3","69-Kompetensi Sosial":"4","70-Kompetensi Sosial":"4","71-Kompetensi Sosial":"4","72-Kompetensi Sosial":"4","73-Kompetensi Sosial":"4"}', '102', '2016/2017', 'Genap', '2017-07-06 03:09:14'),
(48, 20, '{"46-Kompetensi Pedagogik":"4","47-Kompetensi Pedagogik":"3","48-Kompetensi Pedagogik":"5","49-Kompetensi Pedagogik":"4","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"4","52-Kompetensi Pedagogik":"5","53-Kompetensi Pedagogik":"4","54-Kompetensi Pedagogik":"3","55-Kompetensi Professional":"4","56-Kompetensi Professional":"5","57-Kompetensi Professional":"5","58-Kompetensi Professional":"5","59-Kompetensi Professional":"4","60-Kompetensi Professional":"2","61-Kompetensi Professional":"5","62-Kompetensi Professional":"5","63-Kompetensi Kepribadian":"5","64-Kompetensi Kepribadian":"5","65-Kompetensi Kepribadian":"5","66-Kompetensi Kepribadian":"4","67-Kompetensi Kepribadian":"5","68-Kompetensi Kepribadian":"5","69-Kompetensi Sosial":"4","70-Kompetensi Sosial":"5","71-Kompetensi Sosial":"5","72-Kompetensi Sosial":"3","73-Kompetensi Sosial":"5"}', '122', '2016/2017', 'Genap', '2017-07-06 03:13:11'),
(49, 22, '{"46-Kompetensi Pedagogik":"5","47-Kompetensi Pedagogik":"4","48-Kompetensi Pedagogik":"4","49-Kompetensi Pedagogik":"4","50-Kompetensi Pedagogik":"4","51-Kompetensi Pedagogik":"4","52-Kompetensi Pedagogik":"5","53-Kompetensi Pedagogik":"4","54-Kompetensi Pedagogik":"3","74-Kompetensi Pedagogik":"3","55-Kompetensi Professional":"3","56-Kompetensi Professional":"4","57-Kompetensi Professional":"4","58-Kompetensi Professional":"4","59-Kompetensi Professional":"4","60-Kompetensi Professional":"4","61-Kompetensi Professional":"4","62-Kompetensi Professional":"3","63-Kompetensi Kepribadian":"4","64-Kompetensi Kepribadian":"4","65-Kompetensi Kepribadian":"4","66-Kompetensi Kepribadian":"3","67-Kompetensi Kepribadian":"3","68-Kompetensi Kepribadian":"4","69-Kompetensi Sosial":"4","70-Kompetensi Sosial":"4","71-Kompetensi Sosial":"4","72-Kompetensi Sosial":"4","73-Kompetensi Sosial":"4"}', '112', '2016/2017', 'Genap', '2017-07-15 03:40:10');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama_mhs` varchar(255) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(14) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `terakhir_login` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `password`, `email`, `nama_mhs`, `jurusan`, `alamat`, `no_hp`, `tgl_daftar`, `terakhir_login`) VALUES
(21, '310111021675', '$2y$10$af0H2tdaPIXXYK4czuCt9ey5RMZG.dSCWRjgmSTf8QVcgK1UFLwbO', 'h.rahmat@mail.com', 'Haji Rahmat', 'Teknik Informatika', 'Jl. Propinsi', '0876765656111', '2017-06-15 20:19:04', '2017-06-16 22:53:51'),
(22, '310111021769', '$2y$10$f83aqsyTPpTrcKEqO17tPeSjHZrmwrc2BGfHijDYLIvk/3p2Kr/2u', 'aulia.rahman@mail.com', 'Aulia Rahman', 'Teknik Informatika', 'Jl. Said Bajayau', '081233921221', '2017-06-15 20:19:58', '2017-06-16 22:54:25'),
(23, '310112022167', '$2y$10$f22dCST9gQxjuXAP0HSs5u.MIMl4AT6ebPE4oTdvIxwHnbQk9ehoO', 'ahmad.ratailah@mail.com', 'Ahmad Ratailah', 'Teknik Informatika', 'Jl. Loktabat Utara', '08121312121', '2017-06-15 20:21:22', '2017-06-16 22:55:02'),
(26, '310113012100', '$2y$10$TXlPU6fJlE0GvEKbXdN9WelBNgMM1zLDKflcBEzOM200IgQshfB/q', 'tes@mail.com', 'Tes', 'Sistem Informasi', 'Jl. xxx', '0898989988', '2017-07-06 03:28:13', '2017-07-06 10:28:51'),
(20, '310113012105', '$2y$10$N57ixT7KTAhmbOISBJijkec4RkLBxvTGmEMuKDsjPz18C9GhbyaUu', 'ayu.puspita@mail.com', 'Ayu Puspita', 'Sistem Informasi', 'Jl. Komodo Raya', '08579742232221', '2017-06-15 20:14:47', '2017-07-06 09:57:08'),
(19, '310113012107', '$2y$10$324KAfu5ciaDB4oCqRK9YeO8xHEEApLG1A6AWmiaIeMJZp0c3SEXu', 'normy@mail.com', 'Normy Laylatul Q', 'Sistem Informasi', 'Jl. Pembauan Utara', '08124231212121', '2017-06-15 20:13:52', '2017-07-06 09:58:44'),
(18, '310113012108', '$2y$10$bzxAEcSXjtZXxvoAHDc2..xDdRJs9zW3IYwv66kz893YbLQIPK0c2', 'maimunah@mail.com', 'Siti Maimunah', 'Sistem Informasi', 'Jl. Banjarbaru b3', '08121312121212', '2017-06-15 20:12:47', '2017-07-06 09:59:20'),
(17, '310113012109', '$2y$10$GKV0ONNVner9ptcuFFRiuOLgXS8fHuYwXBwRqT7CDdhfA6I02VSeS', 'fitriannor@mail.com', 'Fitriannor', 'Sistem Informasi', 'Jl. Banjarbaru Selatan', '08923423423', '2017-06-15 20:11:43', '2017-07-06 09:59:54'),
(16, '310113012115', '$2y$10$/y3LoeBHJRfEoP4I2tTaMezhC4ofEazWBmV.VOpqvYbD3mI2Q.MwO', 'samiati@mail.com', 'Samiati', 'Sistem Informasi', 'Jl. Banjarbaru Utara', '08123345989', '2017-06-15 20:11:03', '2017-07-06 10:00:36'),
(7, '310113012140', '$2y$10$c8OA/Ba0bs1LmgIbW7pfbu9rLdrskKlxk3yik6.EASiw5sGWPxIle', 'reza@mail.com', 'Reza', 'Sistem Informasi', 'Jl. Nagara', '087778777777', '2017-06-15 01:57:47', '2017-07-06 09:54:43'),
(8, '310113012146', '$2y$10$QgcGWVR/gdpeLKejwJ0gp.W7ZykkWrvNwIfL/gYdHD1yPDI3d5wUa', 'aby@mail.com', 'Aby Ayub', 'Sistem Informasi', 'Jl. Balangan', '089998868778', '2017-06-15 02:00:03', '2017-07-06 10:01:13'),
(9, '310113012150', '$2y$10$w7jfTvAwQ36xTCIbsBK6eO7Km3EvIpX42ZmwElfgra8AxdvDHGea2', 'tommy@gmail.com', 'Tommy Yuri Perdana Ikhwan', 'Sistem Informasi', 'Jl. Tanjung', '08998989798', '2017-06-15 02:00:35', '2017-07-06 09:53:50'),
(10, '310113012158', '$2y$10$sxVxvQwk6jjNKcH23ZZyreo7N4opeTBWt1MytQH0ur8y/SRjTuRru', 'aditya@mail.com', 'Aditya Rifaldi', 'Sistem Informasi', 'Jl. Gambut', '08979898798', '2017-06-15 02:01:03', '2017-07-27 09:51:32'),
(1, '310113012163', '$2y$10$PgTxYqFVhEnYdnKnFEiMd.8zisb6ptbjTfaax3dv7egeITu9G/iye', 'rizalblue@gmail.comm', 'Rizal Alfiannor', 'Sistem Informasi', 'Jl. Angsana Raya No 9', '089999999999', '2016-03-15 21:56:37', '2017-07-22 07:43:06'),
(27, '310113012183', '$2y$10$28VKuHWnr7VBSZ760viVOeKKlVV0ARLLaYdI0ft0T40llugs8O8hy', 'cokorda@mail.com', 'Cokorda Gede Wisnu', 'Sistem Informasi', 'Jl. Banjarbaru', '08575392530', '2017-07-15 03:22:00', '2017-07-27 09:51:56'),
(24, '310113022246', '$2y$10$NMVpmZ.Bgp1duP2xjAnJveQ.DJUS/qhlea0cU7OQp4C9KPzzRM//6', 'safrudin@mail.com', 'Syafrudin', 'Teknik Informatika', 'Jl. Kemayoran Utara', '08121329123812', '2017-06-15 20:22:05', '2017-06-16 22:55:35'),
(25, '310113022289', '$2y$10$oq.DvFPWwrXKx3RDVubpveHeBGpTtppVmn6eN3CQbuEscc62ZdpMG', 'muhammad.saudik@mail.com', 'Muhammad Saudik', 'Teknik Informatika', 'Jl.Propinsi Km.  167', '081298312991', '2017-06-15 20:22:45', '2017-06-16 22:56:07'),
(11, '310113022298', '$2y$10$pj1ic5ScS2Mf.inthwz9L.U6XvMVp6wigCVS2Xo6YNOlBOS2nW13q', 'bayu@mail.com', 'Bayu Rahmatul Akbar', 'Teknik Informatika', 'Jl. Propinsi', '0888898988', '2017-06-15 19:55:29', '2017-06-16 22:18:02'),
(12, '310113022389', '$2y$10$JTJlXN0SJ9MZmhNpgQzbCuuRT6xAUcMQVRtDFe3alHagVWwPVtAqm', 'rahman.tamin@mail.com', 'M. Rahman Tamin', 'Teknik Informatika', 'Jl. Propinsi', '089989898899', '2017-06-15 19:56:36', '2017-06-16 22:21:24'),
(13, '310113022396', '$2y$10$8swLHcyTCioougPz8BoVneAP3L8cAhj/vNL6xrrKthBjxVQWVUHuC', 'ade.akhmad@mail.com', 'Ade Akhmad', 'Teknik Informatika', 'Jl. PLN Lama', '0897564753823', '2017-06-15 20:02:39', '2017-06-16 22:48:39'),
(14, '310113022414', '$2y$10$PdE9Zvp1sZU1KjRxzWuQguE4oKt1fjLygDLbMcVlvswLdPjwqUEi.', 'ridha.anshari@gmail.com', 'M. Ridha Anshari', 'Teknik Informatika', 'Jl. Banjarbaru', '0897323658221', '2017-06-15 20:04:57', '2017-06-16 22:22:43'),
(15, '310113022429', '$2y$10$wzAXT9ZEGpouflfKGT.l1.pTOqzed86HQk598UuI2/ajdAN7MzIxW', 'rifani@mail.com', 'Muhammad Rifani Ardi', 'Teknik Informatika', 'Jl. Ahmad Yani Km. 17,5 Kota Citra Graha Clauser Flamboyan Blok A No. 3 Banjarbaru Kalimantan Selatan', '081250505225', '2017-06-15 20:05:49', '2017-06-16 22:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `id` int(11) NOT NULL,
  `kd_mk` varchar(15) NOT NULL,
  `nama_mk` varchar(150) NOT NULL,
  `sks` varchar(7) NOT NULL,
  `semester` varchar(7) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`id`, `kd_mk`, `nama_mk`, `sks`, `semester`, `jurusan`, `created_date`) VALUES
(3, 'IKB-129', 'Rekayasa Perangkat Lunak', '3', '6', 'Sistem Informasi', '2017-05-09 13:38:30'),
(10, 'IKK-107', 'Statistika', '3', '2', 'Teknik Informatika', '2017-06-15 14:47:54'),
(12, 'IKK-211', 'Struktur Data', '3', '2', 'Teknik Informatika', '2017-06-15 14:51:04'),
(8, 'IKK-337', 'Aljabar Linear', '2', '6', 'Teknik Informatika', '2017-06-15 14:44:02'),
(15, 'SKB-141', 'Sistem Penunjang Keputusan', '2', '2', 'Sistem Informasi', '2017-06-15 15:13:39'),
(7, 'SKB-143', 'Riset Teknologi Informasi', '2', '3', 'Sistem Informasi', '2017-06-11 13:45:21'),
(16, 'SKB-315', 'Basis Data (My SQL)', '3', '2', 'Teknik Informatika', '2017-06-15 20:25:41'),
(11, 'SKB-317', 'Sistem Berkas', '2', '4', 'Teknik Informatika', '2017-06-15 14:48:50'),
(6, 'SKB-319', 'Data Mining', '3', '5', 'Teknik Informatika', '2017-06-11 13:22:29'),
(14, 'SKB-328', 'Sistem Pakar', '2', '4', 'Teknik Informatika', '2017-06-15 14:53:12'),
(4, 'SKB-496', 'Data Mining', '2', '4', 'Sistem Informasi', '2017-05-09 14:00:07'),
(17, 'SKK-511', 'Programming Development PHP', '3', '8', 'Sistem Informasi', '2017-06-15 20:35:12'),
(9, 'SPB-250', 'Teknik Presentasi', '2', '6', 'Teknik Informatika', '2017-06-15 14:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `tahun_akademik` varchar(25) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `id_direktur` int(20) NOT NULL,
  `id_bendahara` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `tahun_akademik`, `semester`, `id_direktur`, `id_bendahara`) VALUES
(1, '2016/2017', 'Genap', 10, 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aktivitas_dosen`
--
ALTER TABLE `aktivitas_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dosenampu` (`id_dosenampu`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`nidn`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `dosen_ampu`
--
ALTER TABLE `dosen_ampu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nidn` (`nidn`),
  ADD KEY `kd_matakuliah` (`kd_mk`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kd_kategori`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_dosenampu` (`id_dosenampu`);

--
-- Indexes for table `kuesioner`
--
ALTER TABLE `kuesioner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kd_kategori` (`kd_kategori`);

--
-- Indexes for table `kuesioner_mahasiswa`
--
ALTER TABLE `kuesioner_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_krs` (`id_krs`);

--
-- Indexes for table `kuesioner_sejawat`
--
ALTER TABLE `kuesioner_sejawat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dosenampu` (`id_dosenampu`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`kd_mk`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas_dosen`
--
ALTER TABLE `aktivitas_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `dosen_ampu`
--
ALTER TABLE `dosen_ampu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `kuesioner`
--
ALTER TABLE `kuesioner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `kuesioner_mahasiswa`
--
ALTER TABLE `kuesioner_mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `kuesioner_sejawat`
--
ALTER TABLE `kuesioner_sejawat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
