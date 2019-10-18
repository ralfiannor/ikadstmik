<?php

if($login->is_loggedin()=="")
{
    redirect('login/?location='.urlencode(site_url($url_segments[1].'/'.$url_segments[2])));
}

else {
    $mahasiswa_id = $_SESSION['mahasiswa_session'];    
    $stmt = $login->runQuery("SELECT * FROM mahasiswa WHERE id=:mahasiswa_id");
    $stmt->execute(array(":mahasiswa_id"=>$mahasiswa_id));      
    $mahasiswaRow=$stmt->fetch(PDO::FETCH_ASSOC);
}


if(isset($_GET['krs_id']))
{
    $idkrs = $_GET['krs_id'];

    try
    {
        $stmt = $login->runQuery("SELECT id,status FROM krs WHERE id = ".$idkrs);
        $stmt->execute();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['status'] == 1) {
                redirect(site_url('isi-kuesioner/?notpermit'));
            }
        }        
    }

    catch(PDOException $e)
    {
        echo $e->getMessage();
    }             
}

//Simpan data
if(isset($_POST['btn-save']))
{
    $idkrs = $_GET['krs_id'];
    $arrkuesioner = $_POST['kuesioner'];
    $jawaban = json_encode($arrkuesioner);
    $skor = array_sum($arrkuesioner);
    $totalsoal = count($arrkuesioner);
    $tahun_akademik = $login->pengaturan('tahun_akademik');    
    $semester = $login->pengaturan('semester');    

    if($isikuesioner->create($idkrs,$jawaban,$skor,$tahun_akademik,$semester))
    {
        $isikuesioner->update($idkrs);
        redirect(site_url('isi-kuesioner/?sukses'));
    }
    else
    {
        redirect(site_url('isi-kuesioner/?gagal'));
    }
}


include ROOT."views/layout/mahasiswa/header.php";
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
            <span>Isi Kuesioner</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">
        <h3 id="grid-example-basic">Isi Kuesioner</h3>

<?php
if(isset($_GET['krs_id'])) {
$id = strip_tags($_GET['krs_id']);
$query = "SELECT krs.id, matakuliah.kd_mk, matakuliah.nama_mk, matakuliah.sks, dosen_ampu.nidn, dosen.nama, krs.status FROM krs INNER JOIN dosen_ampu ON krs.id_dosenampu=dosen_ampu.id INNER JOIN matakuliah ON matakuliah.kd_mk=dosen_ampu.kd_mk INNER JOIN dosen ON dosen.nidn=dosen_ampu.nidn WHERE krs.id=".$id." GROUP BY krs.id";
$isikuesioner->identitas($query);
$isikuesioner->formkuesioner();
} else {
?>
        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Dosen</th>
                    <th>Kode Matakuliah</th>
                    <th>Nama Matakuliah</th>
                    <th>SKS</th>
                    <th>Kuesioner</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $nim = $mahasiswaRow['nim'];
                $query = "SELECT krs.id, matakuliah.kd_mk, matakuliah.nama_mk, matakuliah.sks, dosen_ampu.nidn, dosen.nama, krs.status FROM krs INNER JOIN dosen_ampu ON krs.id_dosenampu=dosen_ampu.id INNER JOIN matakuliah ON matakuliah.kd_mk=dosen_ampu.kd_mk INNER JOIN dosen ON dosen.nidn=dosen_ampu.nidn WHERE krs.tahun_akademik = '".$login->pengaturan('tahun_akademik')."' AND krs.semester = '".$login->pengaturan('semester')."' AND krs.nim='".$nim."'";       
                $isikuesioner->dataview($query);
            ?>
            </tbody>
        </table>

<?php
    }
?>
    </div>
</div>

<?php 
include ROOT."views/layout/mahasiswa/footer.php";
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
            url: "<?= site_url('mahasiswa/monitoring/') ?>",
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
            url: "<?= site_url('mahasiswa/monitoring/') ?>",
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