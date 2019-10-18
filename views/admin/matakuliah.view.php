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
    $kdmk = $_POST['kd_matakuliah'];
    $mk = $_POST['matakuliah'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];
    $jurusan = $_POST['jurusan'];
    
    if($kdmk=="")  {
        $error[] = "Kode matakuliah masih kosong !";    
    }
    
    else if($mk=="") {
        $error[] = "Nama matakuliah masih kosong !";    
    }
    
    else if($sks=="") {
        $error[] = "SKS harus diisi !";
    }
    
    else if($semester=="") {
    $error[] = "Semester harus diisi";
    }
    
    else
    {
        if($matakuliah->create($kdmk,$mk,$sks,$semester,$jurusan))
        {
            redirect(site_url('admin/matakuliah/?sukses'));
        }
        else
        {
            redirect(site_url('admin/matakuliah/?gagal'));
        }

    }
}

//Ubah data
if(isset($_POST['btn-update']))
    {
        $id = $_GET['edit_id'];
        $kdmk = $_POST['kd_matakuliah'];
        $mk = $_POST['matakuliah'];
        $sks = $_POST['sks'];
        $semester = $_POST['semester'];
        $jurusan = $_POST['jurusan'];
            
        if($matakuliah->update($id,$kdmk,$mk,$sks,$semester,$jurusan))
        {
            redirect(site_url('admin/matakuliah/?sukses'));
        }
        else
        {
            redirect(site_url('admin/matakuliah/?gagal'));
        }
    }

if(isset($_GET['edit_id']))
{
    $id = $_GET['edit_id'];
    extract($matakuliah->getID($id));
}

//Hapus data
if(isset($_GET['hapus_id']))
{
   if(isset($_POST['id']))
    {
        $id = $_POST['id'];
        $matakuliah->delete($id);
        redirect(site_url('admin/matakuliah/?terhapus'));
    }
}

// Validasi kode MK
if (isset($_GET['kd_mk'])) {
  $valkdmk = $_GET['kd_mk'];
  $stmt = $login->runQuery("SELECT nidn FROM matakuliah WHERE kd_mk=:kd_mk");
  $stmt->execute(array(':kd_mk'=>$valkdmk));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
  if(strtoupper($row['kd_mk'])==strtoupper($valkdmk)) {
    var_dump(http_response_code(300)); //kirim respond 300
  }
  else {
    var_dump(http_response_code(200)); //kirim respond 200
  }
}



include ROOT."views/layout/admin/header.php";
include ROOT."views/error/alert.view.php";
?>
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
        <span>Master</span>
        <i class="fa fa-angle-right"></i>
        <span>Data Matakuliah</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3 id="<?=((isset($_GET['edit_id']))?'ubahdata':'tambahdata')?>"><?=((isset($_GET['edit_id']))?'Ubah data '.$nama_mk.' ('.$kd_mk.')':'Tambah Data Matakuliah')?></h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Kode Matakuliah</label>
                <input type="text" name="kd_matakuliah" class="form-control" value="<?=((isset($_GET['edit_id']))?$kd_mk:'')?>" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/matakuliah/').'" data-error="Kode matakuliah sudah digunakan"' ?> maxlength="25" data-required-error="Kode matakuliah tidak boleh kosong" onkeydown="upperCase(this)" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
          </div>
            <div class="form-group has-feedback">
                <label>Nama Matakuliah</label>
               <input type='text' name='matakuliah' class='form-control' value="<?=((isset($_GET['edit_id']))?$nama_mk:'')?>" maxlength="125" data-required-error="Nama matakuliah tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>SKS</label>
                <select id="sks" class="form-control" name="sks" data-required-error="Silahkan pilih SKS" required>
                    <option value="" selected="selected">Pilih SKS</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>Semester</label>
                <select id="semester" class="form-control" name="semester" data-required-error="Silahkan pilih semester" required>
                    <option value="" selected>Pilih Semester</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                </select>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>Jurusan</label>
                <select id="jurusan" class="form-control" name="jurusan" data-required-error="Silahkan pilih jurusan" required>
                    <option value="" selected>Pilih Jurusan</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                </select>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <button type="submit" class="btn btn-primary" name="<?=((isset($_GET['edit_id']))?'btn-update':'btn-save')?>">
            <span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah':'Simpan')?>
            </button>  
            <a href="<?=((isset($_GET['edit_id'])) ? site_url('admin/matakuliah'):'#')?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Batal</a>
        </form>
    </div>
    <div class="grid-form1">
        <h3 id="grid-example-basic">Master Data Matakuliah <a href="<?=((isset($_GET['edit_id']))?'#ubahdata':'#tambahdata')?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah Data':'Tambah Data')?></a></h3>

        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode MK</th>
                    <th>Nama MK</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Jurusan</th>
                    <th>Tgl Dibuat</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT * FROM matakuliah";       
                $matakuliah->dataview($query);
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
<script type="text/javascript">

$(document).ready( function () {
    $('#datatable').DataTable();

    // Konfirmasi Hapus data
    $('#datatable').on('click', '.hapus', function(e){
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

$(function() {
     $('[name=sks] option').filter(function() { 
           return ($(this).val() == '<?= (isset($_GET['edit_id'])) ? $sks : '' ?>');
        }).prop('selected', true);

     $('[name=semester] option').filter(function() { 
           return ($(this).val() == '<?= (isset($_GET['edit_id'])) ? $semester : '' ?>');
        }).prop('selected', true);

     $('[name=jurusan] option').filter(function() { 
           return ($(this).val() == '<?= (isset($_GET['edit_id'])) ? $jurusan : '' ?>');
        }).prop('selected', true);

});

</script>
</body>
</html>