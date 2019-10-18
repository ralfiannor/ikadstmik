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
    $kd_kategori = $_POST['kd_kategori'];
    $nama_kategori = $_POST['nama_kategori'];
    
    if($kd_kategori=="")  {
        $error[] = "Kode kategori masih kosong !";    
    }
    
    else if($nama_kategori=="") {
        $error[] = "Nama kategori masih kosong !";    
    }
        
    else
    {
        if($katkuesioner->create($kd_kategori,$nama_kategori))
        {
            redirect(site_url('admin/kategori-kuesioner/?sukses'));
        }
        else
        {
            redirect(site_url('admin/kategori-kuesioner/?gagal'));
        }

    }
}

//Ubah data
if(isset($_POST['btn-update']))
    {
        $id = $_GET['edit_id'];
        $kd_kategori = $_POST['kd_kategori'];
        $nama_kategori = $_POST['nama_kategori'];
            
        if($katkuesioner->update($id,$kd_kategori,$nama_kategori))
        {
            redirect(site_url('admin/kategori-kuesioner/?sukses'));
        }
        else
        {
            redirect(site_url('admin/kategori-kuesioner/?gagal'));
        }
    }

if(isset($_GET['edit_id']))
{
    $id = $_GET['edit_id'];
    extract($katkuesioner->getID($id));
}

//Hapus data
if(isset($_GET['hapus_id']))
{
   if(isset($_POST['id']))
    {
        $id = $_POST['id'];
        $katkuesioner->delete($id);
        redirect(site_url('admin/kategori-kuesioner/?terhapus'));
    }
}

// Validasi kode MK
if (isset($_GET['kd_kategori'])) {
  $valkdkategori = $_GET['kd_kategori'];
  $stmt = $login->runQuery("SELECT kd_kategori FROM kategori WHERE kd_kategori=:kd_kategori");
  $stmt->execute(array(':kd_kategori'=>$valkdkategori));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
  if(strtoupper($row['kd_kategori'])==strtoupper($valkdkategori)) {
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
        <span>Data Kategori Kuesioner</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3 id="<?=((isset($_GET['edit_id']))?'ubahdata':'tambahdata')?>"><?=((isset($_GET['edit_id']))?'Ubah data '.$nama_kategori.' ('.$kd_kategori.')':'Tambah Data Kategori Kuesioner')?></h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Kode Kategori</label>
                <input type="text" name="kd_kategori" class="form-control" value="<?=((isset($_GET['edit_id']))?$kd_kategori:'')?>" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/kategori-kuesioner/').'" data-error="Kode kategori sudah digunakan"' ?> maxlength="25" data-required-error="Kode kategori tidak boleh kosong" onkeydown="upperCase(this)" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
          </div>
            <div class="form-group has-feedback">
                <label>Nama Kategori</label>
               <input type='text' name='nama_kategori' class='form-control' value="<?=((isset($_GET['edit_id']))?$nama_kategori:'')?>" maxlength="125" data-required-error="Nama kategori tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
     
            <button type="submit" class="btn btn-primary" name="<?=((isset($_GET['edit_id']))?'btn-update':'btn-save')?>">
            <span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah':'Simpan')?>
            </button>  
            <a href="<?= site_url('admin/') ?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Batal</a>
        </form>
    </div>
    <div class="grid-form1">
        <h3 id="grid-example-basic">Master Data Kategori Kuesioner <a href="<?=((isset($_GET['edit_id']))?'#ubahdata':'#tambahdata')?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah Data':'Tambah Data')?></a></h3>

        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Kategori</th>
                    <th>Nama Kategori</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT * FROM kategori";       
                $katkuesioner->dataview($query);
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

</script>
</body>
</html>