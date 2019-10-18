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

if(isset($_GET['mahasiswa']))
{
    $term=trim(htmlentities($_GET['mahasiswa'])); 
    try
    {
        $stmt = $login->runQuery("SELECT * FROM mahasiswa WHERE nim LIKE :term OR nama_mhs LIKE :term");
        $stmt->execute(array(':term' => "%".$term."%"));
        if($stmt->rowCount()>0) {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = array(  
                'id' => $row['nim'],
                'text' => "(".$row['nim'].") ".$row['nama_mhs']
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

if(isset($_GET['dosen_ampu']))
{
    $term=trim(htmlentities($_GET['dosen_ampu'])); 
    try
    {
        $stmt = $login->runQuery("SELECT a.id,a.nidn,a.kd_mk,c.jurusan,b.nama,c.nama_mk FROM dosen_ampu as a LEFT JOIN dosen as b ON a.nidn = b.nidn LEFT JOIN matakuliah as c ON a.kd_mk = c.kd_mk WHERE a.nidn LIKE :term OR a.kd_mk LIKE :term OR c.nama_mk LIKE :term OR b.nama LIKE :term");
        $stmt->execute(array(':term' => "%".$term."%"));
        if($stmt->rowCount()>0) {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = array(  
                'id' => $row['id'],
                'text' => "(".$row['nidn'].") ".$row['nama']." - (".$row['kd_mk'].") ".$row['nama_mk']
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

//Simpan data
if(isset($_POST['btn-save']))
{
    $nim = $_POST['nim'];
    $id_dosenampu = $_POST['dosen_ampu'];
    $tahun_akademik = $login->pengaturan('tahun_akademik');    
    $semester = $login->pengaturan('semester');    

    $stmt2 = $login->runQuery("SELECT * FROM krs WHERE id_dosenampu=:id");
    $stmt2->execute(array(":id"=>$id_dosenampu));      
    

    if($nim=="")  {
        $error[] = "NIM masih kosong !";    
    }
    
    else if($id_dosenampu=="") {
        $error[] = "Dosen Ampu masih kosong !";    
    }
    
    else
    {

        while($krsRow=$stmt2->fetch(PDO::FETCH_ASSOC)) {    
            if ($krsRow['nim'] == strip_tags($nim)) {
                redirect(site_url('admin/krs/?gagal'));
                exit();   
            }
        }

        if($krs->create($nim,$id_dosenampu,$tahun_akademik,$semester))
        {
            redirect(site_url('admin/krs/?sukses'));
        }
        else
        {
            redirect(site_url('admin/krs/?gagal'));
        }

    }
}

//Ubah data
if(isset($_POST['btn-update']))
    {
        $id = $_GET['edit_id'];
        $nim = $_POST['nim'];
        $id_dosenampu = $_POST['dosen_ampu'];
        $tahun_akademik = $login->pengaturan('tahun_akademik');    
        $semester = $login->pengaturan('semester');    
            
        if($krs->update($id,$nim,$id_dosenampu,$tahun_akademik,$semester))
        {
            redirect(site_url('admin/krs/?sukses'));
        }
        else
        {
            redirect(site_url('admin/krs/?gagal'));
        }
    }

if(isset($_GET['edit_id']))
{
    $id = $_GET['edit_id'];
    extract($krs->getID($id));
}

// Hapus Data
if (isset($_POST['btn-hapus'])) {
    $id_hapus = $_POST['id_hapus'];
    $krs->delete($id_hapus);
    redirect(site_url('admin/krs/?terhapus'));
}

if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    extract($krs->getID($id));
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Hapus Data</h4>
      </div>
      <div class="modal-body">
        Apakah yakin ingin menghapus data <b><?= $nama ?> - <?= $nama_mk ?></b> untuk nim <b><?= $nim ?></b> ?
      </div>
      <div class="modal-footer">
      <form method="POST">
        <input type="hidden" name="id_hapus" value="<?= $id ?>">
        <button type="submit" class="btn btn-warning" name="btn-hapus">Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </form>
      </div>

<?php
exit();
}

include ROOT."views/layout/admin/header.php";

include ROOT."views/error/alert.view.php";
?>
<link rel="stylesheet" type="text/css" href="<?= site_url('vendor/select2/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= site_url('vendor/select2/css/select2-bootstrap.min.css') ?>">


<!--banner-->   
    <div class="banner">           
        <h2>
        <a href="home.php">Home</a>
        <i class="fa fa-angle-right"></i>
        <span>Transaksi</span>
        <i class="fa fa-angle-right"></i>
        <span>Data KRS</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3 id="<?=((isset($_GET['edit_id']))?'ubahdata':'tambahdata')?>"><?=((isset($_GET['edit_id']))?'Ubah data KRS':'Tambah Data KRS')?></h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Nama Mahasiswa</label>
                <select id="nim" class="form-control" name="nim" required>
                </select>
                <div class="help-block with-errors"></div>
          </div>
            <div class="form-group has-feedback">
                <label>Dosen Ampu</label>
                <select id="dosen_ampu" class="form-control" name="dosen_ampu" required></select>
                <div class="help-block with-errors"></div>
            </div>
     
            <button type="submit" class="btn btn-primary" name="<?=((isset($_GET['edit_id']))?'btn-update':'btn-save')?>">
            <span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah':'Simpan')?>
            </button>  
            <a href="<?= site_url('admin/') ?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Batal</a>
        </form>
    </div>
    <div class="grid-form1">
        <h3 id="grid-example-basic">Transaksi Data KRS <a href="<?=((isset($_GET['edit_id']))?'#ubahdata':'#tambahdata')?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah Data':'Tambah Data')?></a></h3>

        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Mahasiswa</th>
                    <th>Nama Dosen</th>
                    <th>Nama Matakuliah</th>
                    <th>SKS</th>
                    <th>Program Studi</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT a.id,a.nim,b.nama_mhs,c.nidn,c.kd_mk,d.nama,e.nama_mk,e.sks,e.jurusan FROM krs as a INNER JOIN mahasiswa as b ON a.nim = b.nim INNER JOIN dosen_ampu as c ON a.id_dosenampu = c.id INNER JOIN dosen as d ON c.nidn = d.nidn INNER JOIN matakuliah as e ON c.kd_mk = e.kd_mk WHERE tahun_akademik = '".$login->pengaturan('tahun_akademik')."' AND a.semester = '".$login->pengaturan('semester')."'";       
                $krs->dataview($query);
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


<script type="text/javascript">

$(document).ready( function () {
    $('#datatable').DataTable();

    $('#datatable').on('click', '.hapus', function(){
       var url = $(this).attr('href');
       $.get(url, function (data) {
         var modal = $('<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"><div class="modal-dialog" role="document"><div class="modal-content">' + data + '</div></div></div>').modal();
         modal.on("hidden.bs.modal", function () {
           $(this).remove();
         });
         setTimeout(function(){
             modal.modal('hide');
         },15000);
//       });
    });
 });
 

    $("#nim").select2({
        placeholder: "Masukkan NIM atau Nama Mahasiswa",
        allowClear: true,
        language: "id",
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: "<?= site_url('admin/krs/') ?>",
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
            url: "<?= site_url('admin/krs/') ?>",
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