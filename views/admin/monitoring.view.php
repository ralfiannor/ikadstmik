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
        <span>Data monitoring</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">
        <h3 id="grid-example-basic">Monitoring Data Penilaian Dosen</h3>

<?php
if(isset($_GET['lihat_id'])) {
$id = strip_tags($_GET['lihat_id']);
$query = "SELECT a.id_dosenampu as id, c.nidn, d.nama, c.kd_mk, e.nama_mk, e.sks, count(a.nim) as total_mhs, count(b.id_krs) as responden, sum(b.skor) as total_skor, b.jawaban as jawaban
                    from krs a
                    left join kuesioner_mahasiswa b
                    on a.id = b.id_krs
                    left join dosen_ampu c on
                    a.id_dosenampu = c.id
                    left join dosen d on
                    c.nidn = d.nidn
                    left join matakuliah e on
                    c.kd_mk = e.kd_mk WHERE a.id_dosenampu=".$id." GROUP BY a.id_dosenampu";
$monitoring->hasil($query,$id);
} else {
$tahun_akademik = $login->pengaturan('tahun_akademik');
$semester = $login->pengaturan('semester');
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
                    <th>Total Responden</th>
                    <th>Persentase Responden</th>
                    <th>Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT DA.id, dosen.nama, dosen.nidn, MK.kd_mk, MK.nama_mk, MK.sks, MK.semester, MK.jurusan, DA.status FROM krs LEFT JOIN dosen_ampu as DA ON krs.id_dosenampu = DA.id LEFT JOIN dosen on DA.nidn = dosen.nidn LEFT JOIN matakuliah as MK ON DA.kd_mk = MK.kd_mk WHERE krs.tahun_akademik = '".$tahun_akademik."' AND krs.semester = '".$semester."' GROUP BY krs.id_dosenampu";       
                $monitoring->dataview($query);
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

    $("#nim").select2({
        placeholder: "Masukkan NIM atau Nama Mahasiswa",
        allowClear: true,
        language: "id",
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('admin/monitoring/') ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    mahasiswa: params.term // search term
                };
            },
            processResults: function (data) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: data
                };
            },
            cache: true
        }    
    });

    $("#dosen_ampu").select2({
        placeholder: "Masukkan Nama Dosen atau Matakuliah",
        allowClear: true,
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('admin/monitoring/') ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    dosen_ampu: params.term // search term
                };
            },
            processResults: function (data) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: data
                };
            },
            cache: true
        }    
    });

<?php if (isset($_GET['edit_id'])) { ?>
    $("#nim").append('<option value="<?= $nim ?>" selected="selected">(<?= $nim ?>) <?= $nama_mhs ?></option>');
    $("#nim").trigger('change');

    $("#dosen_ampu").append('<option value="<?= $id_dosenampu ?>" selected="selected">(<?= $nidn ?>) <?= $nama ?> - (<?= $kd_mk ?>) <?= $nama_mk ?></option>');
    $("#dosen_ampu").trigger('change');

<?php } ?>

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