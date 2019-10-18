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
    extract($layanan->getID());
    $tahun_akad = explode("/", $tahun_akademik);
}

if(isset($_GET['direktur']))
{
    $term=trim(htmlentities($_GET['direktur'])); 
    try
    {
        $stmt = $login->runQuery("SELECT * FROM dosen WHERE nidn LIKE :term OR nama LIKE :term");
        $stmt->execute(array(':term' => "%".$term."%"));
        if($stmt->rowCount()>0) {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = array(  
                'id' => $row['id'],
                'text' => "(".$row['nidn'].") ".$row['nama']
                );
            }
        }
        else {
            $result[] = array(  
            'id' => 0,
            'text' => 'Data tidak ditemukan'
            );            
        }       
        //show the result in json format
        echo json_encode($result);
        exit();
    }

    catch(PDOException $e)
    {
        echo $e->getMessage();
    }             
}

if(isset($_GET['bendahara']))
{
    $term=trim(htmlentities($_GET['bendahara'])); 
    try
    {
        $stmt = $login->runQuery("SELECT * FROM dosen WHERE nidn LIKE :term OR nama LIKE :term");
        $stmt->execute(array(':term' => "%".$term."%"));
        if($stmt->rowCount()>0) {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = array(  
                'id' => $row['id'],
                'text' => "(".$row['nidn'].") ".$row['nama']);
            }
        }        
        else {
            $result[] = array(  
            'id' => 0,
            'text' => 'Data tidak ditemukan'
            );            
        }       
        //show the result in json format
        echo json_encode($result);
        exit();
    }

    catch(PDOException $e)
    {
        echo $e->getMessage();
    }             
}


//Simpan data
if(isset($_POST['btn-save']))
{
    $tahun1 = $_POST['tahun1'];
    $tahun2 = $_POST['tahun2'];
    $tahunakademik = $tahun1."/".$tahun2;
    $semester = $_POST['semester'];
    $direktur = $_POST['direktur'];
    $bendahara = $_POST['bendahara'];

    if($tahun1=="")  {
        $error[] = "Tahun Akademik masih kosong !";    
    }
    elseif($tahun2=="")  {
        $error[] = "Tahun Akademik masih kosong !";    
    }
    elseif($semester=="")  {
        $error[] = "Semester masih kosong !";    
    }    
    else
    {
        if($layanan->ganti($tahunakademik,$semester,$direktur,$bendahara))
        {
            redirect(site_url('admin/layanan/?sukses'));
        }
        else
        {
            redirect(site_url('admin/layanan/?gagal'));
        }

    }
}




include ROOT."views/layout/admin/header.php";
include ROOT."views/error/alert.view.php";
?>
<link href="<?= site_url('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') ?>" rel="stylesheet">
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
        <span>Pengaturan</span>
        <i class="fa fa-angle-right"></i>
        <span>Layanan Aplikasi</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3>Layanan Aplikasi</h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Tahun Akademik</label>
                <div class="row">
                    <div class="col-sm-4">
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" name="tahun1" value="<?= $tahun_akad[0] ?>" />
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
                            <input type='text' class="form-control" name="tahun2" value="<?= $tahun_akad[1] ?>"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>          
                </div>
            </div>
            <div class="form-group has-feedback">
                <label>Semester</label>
                <select id="semester" class="form-control" name="semester" data-required-error="Silahkan pilih semester" required>
                    <option value="" selected>Pilih Semester</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>Direktur LJM</label>
                <select id="direktur" class="form-control" name="direktur" required>
                </select>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>Sekretaris LJM</label>
                <select id="bendahara" class="form-control" name="bendahara" required>
                </select>
                <div class="help-block with-errors"></div>
            </div>
            <button type="submit" class="btn btn-primary" name="btn-save">
            <span class="glyphicon glyphicon-plus"></span> Simpan
            </button>  
            <a href="<?= site_url('admin/') ?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Batal</a>
        </form>
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
   return ($(this).val() == '<?= $semester ?>');
}).prop('selected', true);


});

</script>
<script type="text/javascript">

$(document).ready( function () {
 
    $("#direktur").select2({
        placeholder: "Masukkan Nama Dosen",
        allowClear: true,
        language: "id",
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('admin/layanan/') ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    direktur: params.term // search term
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

    $("#bendahara").select2({
        placeholder: "Masukkan Nama Dosen",
        allowClear: true,
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('admin/layanan/') ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    bendahara: params.term // search term
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

    $("#direktur").append('<option value="<?= $id_direktur ?>" selected="selected">(<?= $nip_direktur ?>) <?= $nama_direktur ?></option>');
    $("#direktur").trigger('change');

    $("#bendahara").append('<option value="<?= $id_bendahara ?>" selected="selected">(<?= $nip_bendahara ?>) <?= $nama_bendahara ?></option>');
    $("#bendahara").trigger('change');


});
</script>

</body>
</html>