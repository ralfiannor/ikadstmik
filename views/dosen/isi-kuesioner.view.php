<?php

if($login->is_loggedin()=="")
{
    redirect(site_url('dosen/login/?location='.urlencode(site_url($url_segments[1].'/'.$url_segments[2]))));
}

else {
    $dosen_id = $_SESSION['dosen_session'];    
    $stmt = $login->runQuery("SELECT * FROM dosen WHERE dosen.id=:dosen_id");
    $stmt->execute(array(":dosen_id"=>$dosen_id));      
    $dosenRow=$stmt->fetch(PDO::FETCH_ASSOC);
}


if(isset($_GET['dosenampu_id']))
{
    $iddosenampu = $_GET['dosenampu_id'];

    try
    {
        $stmt = $login->runQuery("SELECT id,nidn,kd_mk,telah_diisi FROM dosen_ampu WHERE id = ".$iddosenampu);
        $stmt->execute();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $telahdiisi = json_decode($row['telah_diisi']);
            if ($row['telah_diisi'] != NULL) { 
                foreach ($telahdiisi as $pengisi) {
                    if ($pengisi == $dosenRow['nidn']) {
                        redirect(site_url('isi-kuesioner/?notpermit'));
                    }            
                }
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

    $nidn = $dosenRow['nidn'];
    $iddosenampu = $_GET['dosenampu_id'];
    $arrkuesioner = $_POST['kuesioner'];
    $jawaban = json_encode($arrkuesioner);
    $skor = array_sum($arrkuesioner);
    $totalsoal = count($arrkuesioner);
    $tahun_akademik = $login->pengaturan('tahun_akademik');    
    $semester = $login->pengaturan('semester');    

    if($isikuesioner->create($iddosenampu,$jawaban,$skor,$tahun_akademik,$semester))
    {
        $isikuesioner->update($nidn,$iddosenampu);
        redirect(site_url('dosen/isi-kuesioner/?sukses'));
    }
    else
    {
        redirect(site_url('dosen/isi-kuesioner/?gagal'));
    }
}


include ROOT."views/layout/dosen/header.php";
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
if(isset($_GET['dosenampu_id'])) {
$id = strip_tags($_GET['dosenampu_id']);
$query = "SELECT dosen_ampu.id, matakuliah.kd_mk, matakuliah.nama_mk, matakuliah.sks, dosen.nidn, dosen.nama, dosen_ampu.telah_diisi FROM dosen_ampu INNER JOIN matakuliah ON matakuliah.kd_mk=dosen_ampu.kd_mk INNER JOIN dosen ON dosen.nidn=dosen_ampu.nidn WHERE dosen_ampu.nidn <> ".$dosenRow['nidn']." AND dosen_ampu.id =".$id;
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
               ($login->pengaturan('semester') == 'Ganjil') ? $semester=1 : $semester=0;
               $query = "SELECT dosen_ampu.id, matakuliah.kd_mk, matakuliah.nama_mk, matakuliah.sks, dosen.nidn, dosen.nama, dosen_ampu.telah_diisi FROM dosen_ampu INNER JOIN matakuliah ON matakuliah.kd_mk=dosen_ampu.kd_mk INNER JOIN dosen ON dosen.nidn=dosen_ampu.nidn WHERE MOD(matakuliah.semester, 2) = ".$semester." AND dosen_ampu.nidn <> ".$dosenRow['nidn'];       
                $isikuesioner->dataview($dosenRow['nidn'],$query);
            ?>
            </tbody>
        </table>

<?php
    }
?>
    </div>
</div>

<?php 
include ROOT."views/layout/dosen/footer.php";
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

    $("#nidn").select2({
        placeholder: "Masukkan nidn atau Nama dosen",
        allowClear: true,
        language: "id",
        theme: "bootstrap",
        minidnumInputLength: 2,
        minidnumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('dosen/monitoring/') ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    dosen: params.term // search term
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
        minidnumInputLength: 2,
        minidnumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('dosen/monitoring/') ?>",
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
    $("#nidn").append('<option value="<?= $nidn ?>" selected="selected">(<?= $nidn ?>) <?= $nama_mhs ?></option>');
    $("#nidn").trigger('change');

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