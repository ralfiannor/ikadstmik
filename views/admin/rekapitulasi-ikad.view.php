<?php

if($login->is_loggedin()=="")
{
    redirect(site_url('admin/login/?location='.urlencode(site_url($url_segments[1].'/'.$url_segments[2]))));
}

else {
    $admin_id = $_SESSION['admin_session'];    
    $stmt = $login->runQuery("SELECT * FROM admins WHERE id=:admin_id");
    $stmt->execute(array(":admin_id"=>$admin_id));      
    $adminRow=$stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['tahun1']) && isset($_GET['tahun2']) && isset($_GET['semester']) && isset($_GET['jurusan']) && isset($_GET['cetak'])) {
$id = $_GET['cetak'];
$tahun_akademik = $_GET['tahun1']."/".$_GET['tahun2'];
$semester = $_GET['semester'];
$jurusan = $_GET['jurusan'];
?>
<HTML> 
    <HEAD>
    <TITLE>Laporan</TITLE>
<style type="text/css" media="print">

html {
  height: 100%;
  box-sizing: border-box;
}

*,
*:before,
*:after {
  box-sizing: inherit;
}

body {
  position: relative;
  margin: 0;
  padding-bottom: 6rem;
  min-height: 100%;
  font-family: "Times New Roman", Arial, sans-serif;
}

/**
 * Footer Styles
 */

.footer {
  padding: 1rem;
  text-align: center;
}
  @media print {
    .footer table {
      page-break-inside: avoid;
    }
  }

  @page {
    size: landscape;
    margin: 2%;
  }

</style>
    </HEAD>
    <BODY>

<?php $rekapitulasiikad->cetak($tahun_akademik,$semester,$jurusan); ?>
<div class="footer">
    <table border="0" width="850" style="margin-right: 10%;margin-left: 10%">
        <tr>
            <td align="left" width="550"></td>
            <td align="left">Banjarbaru, <?= $login->tgl_indo(date("Y-m-d")) ?><br></td>
        </tr>
        <tr>
            <td align="left" width="550">Lembaga Jaminan Mutu (LJM)<br>STMIK BANJARBARU</td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="left" width="550">Direktur,</td>
            <td align="left">Sekretaris,</td>
        </tr>
        <tr>
            <td align="left" width="550"><br><br><br></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="left" width="550"><br><br><br></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="left" width="550">(<?= $login->pengaturan('nama_direktur') ?>)</td>
            <td align="left">(<?= $login->pengaturan('nama_bendahara') ?>)</td>
        </tr>
        <tr>
            <td align="left" width="550">NIP/NIK <?= $login->pengaturan('nip_direktur') ?></td>
            <td align="left">NIP/NIK <?= $login->pengaturan('nip_bendahara') ?></td>
        </tr>
    </table>
</div>
<script type="text/javascript" charset="utf-8">
window.print();
</script>

    </BODY>
</HTML>

<?php
    exit();
}



include ROOT."views/layout/admin/header.php";
include ROOT."views/error/alert.view.php";
?>
<link rel="stylesheet" type="text/css" href="<?= site_url('vendor/select2/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= site_url('vendor/select2/css/select2-bootstrap.min.css') ?>">
<link href="<?= site_url('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') ?>" rel="stylesheet">

<!-- Modal -->
<div class="modal fade" id="konfirmasi">
    <div class="modal-dialog">
        <form method="POST">
        <input type="hidden" name="id" class="delete-id">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Konfirmasi Menghapus Data</h4>
            </div>
            <div class="modal-body">
                Apakah anda yakin ingin menghapus data ini ?
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Hapus</a></button>
    
            </div>
        </div><!-- /.modal-content -->
        </form><!-- /.form -->
    </div><!-- /.modal dialog -->
</div><!-- /.modal -->

<!--banner-->   
    <div class="banner">           
        <h2>
        <a href="home.php">Home</a>
        <i class="fa fa-angle-right"></i>
        <span>Laporan</span>
        <i class="fa fa-angle-right"></i>
        <span>ikad Dosen</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">
        <h3 id="grid-example-basic">Laporan Rekapitulasi IKAD Dosen</h3>

            <form method="get" action="<?= site_url('admin/rekapitulasi-ikad/')?>" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Pilih Tahun Akademik</label>
                <div class="row">
                    <div class="col-sm-4">
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" name="tahun1" value="<?= (isset($_GET['tahun1'])) ? $_GET['tahun1'] : '' ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>          
                    <div align="center" class="col-sm-1">
                    /
                    </div>
                    <div class="col-sm-4">
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' class="form-control" name="tahun2" value="<?= (isset($_GET['tahun2'])) ? $_GET['tahun2'] : '' ?>"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>          
                </div>
            </div>
            <div class="form-group has-feedback">
                <label>Pilih Semester</label>
                <select id="semester" class="form-control" name="semester" data-required-error="Silahkan pilih semester" required>
                    <option value="" selected="selected">Pilih Semester</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            <div class="form-group has-feedback">
                <label>Pilih Program Studi</label>
                <select id="jurusan" class="form-control" name="jurusan" data-required-error="Silahkan pilih semester" required>
                    <option value="" selected="selected">Pilih Program Studi</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                </select>
            </div>
            <button type="submit" value="true" class="btn btn-primary" name="lihat">
            <span class="glyphicon glyphicon-eye-open"></span> Lihat
            </button>  
            
            <?= (isset($_GET['tahun1']) && isset($_GET['tahun2']) && isset($_GET['semester']) && isset($_GET['jurusan']) && isset($_GET['lihat'])) ? '<a href="'.site_url("admin/rekapitulasi-ikad/?cetak&tahun1=".$_GET["tahun1"]."&tahun2=".$_GET["tahun2"]."&semester=".$_GET["semester"]."&jurusan=".$_GET["jurusan"]).'" target="_blank" class="btn btn-large btn-info"><i class="glyphicon glyphicon-print"></i> &nbsp; Cetak</a>' : ''; ?>

            <a href="<?= site_url('admin/rekapitulasi-ikad/') ?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Batal</a>
            </form>


        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Dosen</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Jurusan</th>
                    <th>Total Responden</th>
                    <th>Persentase Responden</th>
                </tr>
            </thead> 
            <tbody>
            <?php
if (isset($_GET['tahun1']) && isset($_GET['tahun2']) && isset($_GET['semester']) && isset($_GET['jurusan']) && isset($_GET['lihat'])) {
$tahun_akademik = $_GET['tahun1']."/".$_GET['tahun2'];
$semester = $_GET['semester'];
$jurusan = $_GET['jurusan'];

    $query = "SELECT a.id_dosenampu as id, d.nidn, d.nama, c.kd_mk, d.status_dosen, e.jurusan, e.nama_mk, e.sks, a.tahun_akademik, a.semester, km.jawaban as ks_mhs, ks.jawaban as ks_dosen, ad.jawaban as ad_dosen 
        from krs a
        left join kuesioner_mahasiswa as km on km.id_krs = a.id
        left join kuesioner_sejawat as ks on ks.id_dosenampu = a.id_dosenampu
        left join aktivitas_dosen as ad on ad.id_dosenampu = a.id_dosenampu 
        left join dosen_ampu c on
        a.id_dosenampu = c.id
        left join dosen d on
        c.nidn = d.nidn
        left join matakuliah e on
        c.kd_mk = e.kd_mk WHERE a.tahun_akademik = '".$tahun_akademik."' AND a.semester = '".$semester."' AND e.jurusan='".$jurusan."' GROUP BY a.id_dosenampu";  
        $rekapitulasiikad->laporan($query,$tahun_akademik,$semester);
}
else {
$tahun_akademik = $login->pengaturan('tahun_akademik');    
$semester = $login->pengaturan('semester');    

                $query = "SELECT a.id_dosenampu as id, d.nidn, d.nama, c.kd_mk, d.status_dosen, e.jurusan, e.nama_mk, e.sks, a.tahun_akademik, a.semester 
                    from krs a
                    left join dosen_ampu c on
                    a.id_dosenampu = c.id
                    left join dosen d on
                    c.nidn = d.nidn
                    left join matakuliah e on
                    c.kd_mk = e.kd_mk WHERE a.tahun_akademik = '".$tahun_akademik."' AND a.semester = '".$semester."' GROUP BY a.id_dosenampu";  
                $rekapitulasiikad->laporan($query,$tahun_akademik,$semester);

}

            ?>
            </tbody>
        </table>
    </div>
</div>

<?php 

include ROOT."views/layout/admin/footer.php";
?>

<script src="<?= site_url('assets/js/datatable/jquery.min.js') ?>"> </script>
<script src="<?= site_url('assets/js/datatable/bootstrap.min.js') ?>"> </script>
<script type="text/javascript" charset="utf8" src="<?= site_url('vendor/bootstrap-validator/dist/validator.min.js') ?>"></script>
<script type="text/javascript" src="<?= site_url('vendor/select2/js/select2.min.js') ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= site_url('vendor/select2/js/il8n/id.js') ?>" charset="UTF-8"></script>
<script src="<?= site_url('vendor/bootstrap-datetimepicker/js/moment.min.js') ?>"></script>
<script src="<?= site_url('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script type="text/javascript">
$(function () {
$('#datetimepicker1').datetimepicker({
                viewMode: 'years',
                format: 'YYYY'
            });
$('#datetimepicker2').datetimepicker({
                useCurrent: false,
                viewMode: 'years',
                format: 'YYYY'
            });
 $("#datetimepicker1").on("dp.change", function (e) {
            $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker2").on("dp.change", function (e) {
            $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
        });

$('[name=semester] option').filter(function() { 
   return ($(this).val() == '<?= (isset($_GET['semester'])) ? $_GET['semester'] : '' ?>');
}).prop('selected', true);

$('[name=jurusan] option').filter(function() { 
   return ($(this).val() == '<?= (isset($_GET['jurusan'])) ? $_GET['jurusan'] : '' ?>');
}).prop('selected', true);

});

</script>


<script type="text/javascript" charset="utf-8">
function upperCase(a){
    setTimeout(function(){
        a.value = a.value.toUpperCase();
    }, 1);
}

</script>
</body>
</html>