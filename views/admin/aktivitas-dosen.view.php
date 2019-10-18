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


//Simpan data
if(isset($_POST['btn-save']))
{
    $iddosenampu = $_GET['lihat_id'];
    $arrjawaban = $_POST['jawaban'];
    $jawaban = json_encode($arrjawaban);
    $skor = array_sum($arrjawaban);
    $totalsoal = count($arrjawaban);
    $tahun_akademik = $login->pengaturan('tahun_akademik');    
    $semester = $login->pengaturan('semester');    

    if($aktivitasdosen->create($iddosenampu,$jawaban,$skor,$tahun_akademik,$semester))
    {
        $aktivitasdosen->update($iddosenampu);
        redirect(site_url('admin/aktivitas-dosen/?sukses'));
    }
    else
    {
        redirect(site_url('admin/aktivitas-dosen/?gagal'));
    }
}

include ROOT."views/layout/admin/header.php";
include ROOT."views/error/alert.view.php";
?>
<link rel="stylesheet" type="text/css" href="<?= site_url('vendor/select2/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= site_url('vendor/select2/css/select2-bootstrap.min.css') ?>">


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
        <span>Transaksi</span>
        <i class="fa fa-angle-right"></i>
        <span>Penilaian Aktivitas Dosen</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">
        <h3 id="grid-example-basic">Data Penilaian Aktivitas Dosen</h3>

<?php
if(isset($_GET['lihat_id'])) {
$id = strip_tags($_GET['lihat_id']);
$query = "SELECT DA.id, dosen.nama, dosen.nidn, MK.kd_mk, MK.nama_mk, MK.sks, MK.semester, MK.jurusan, DA.status FROM dosen_ampu as DA LEFT JOIN dosen on DA.nidn = dosen.nidn LEFT JOIN matakuliah as MK ON DA.kd_mk = MK.kd_mk WHERE DA.id=".$id." GROUP BY DA.id";
$aktivitasdosen->isi($query,$id);
} else {
($login->pengaturan('semester') == 'Ganjil') ? $semester=1 : $semester=0;

?>
        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Dosen</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT DA.id, dosen.nama, MK.kd_mk, MK.nama_mk, MK.sks, MK.semester, MK.jurusan, DA.status FROM dosen_ampu as DA LEFT JOIN dosen on DA.nidn = dosen.nidn LEFT JOIN matakuliah as MK ON DA.kd_mk = MK.kd_mk WHERE MOD(MK.semester, 2) = ".$semester;    
                $aktivitasdosen->dataview($query);
            ?>
            </tbody>
        </table>

<?php
    }
?>
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


<script type="text/javascript">

$(document).ready( function () {
    $('#datatable').DataTable();

    // Konfirmasi Hapus data
    $('a.hapus').on('click', function(e){
        e.preventDefault();
        $('.modal#konfirmasi').find('form').attr('action', $(this).attr('href'));
        $('.modal#konfirmasi').find('.delete-id').val($(this).data('id'));
        $('.modal#konfirmasi').modal('show');
    });
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