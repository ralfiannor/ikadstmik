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


if(isset($_POST['btn-save']))
{
    $nidn = strip_tags($_POST['nidn']);
    $email = strip_tags($_POST['email']);
    $pass = strip_tags($_POST['pass']);   
    $nama = strip_tags($_POST['nama']);   
    $alamat = strip_tags($_POST['alamat']);   
    $no_hp = strip_tags($_POST['no_hp']);   
    
    if($nidn=="")  {
        $error[] = "NIDN masih kosong !";    
    }
    else if($email=="") {
        $error[] = "Email masih kosong !";    
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Masukkan alamat email yang benar !';
    }
    else if($pass=="") {
        $error[] = "Kata Sandi masih kosong !";
    }
    else if(strlen($pass) < 6){
        $error[] = "Kata Sandi harus lebih dari 6 karakter"; 
    }
    else
    {
        $body = "Selamat ! Email anda berhasil terdaftar sebagai Dosen pada aplikasi penilaian kinerja dosen STMIK Banjarbaru.<br><br><u>Informasi Akun anda :</u><br>Username : <b>$nidn</b><br>Password : <b>$pass</b><br><br>Silahkan login dengan mengklik link berikut <a href='http://rizal.web.id/ikadstmik/login/'>http://rizal.web.id/ikadstmik/login/</a>";
        if($crud->sendmail($email,"Informasi Akun Mahasiswa STMIK Banjarbaru",$body)) {
            if($dosen->create($nidn,$email,$pass,$nama,$alamat,$no_hp)){  
                $dosen->redirect(site_url("admin/dosen/?sukses"));
            }
            else {
                $error[] = "Terjadi kesalahan penginputan data. Harap periksa kembali";
            }
        }
        else {
            $error[] = "Email tidak terkirim. Karena masalah koneksi internet atau Mail Server tidak terpasang.";                    
        }
    }   
}


//Ubah data
if(isset($_POST['btn-update']))
    {
        $id = $_GET['edit_id'];
        $nidn = strip_tags($_POST['nidn']);
        $email = strip_tags($_POST['email']);
        $pass = strip_tags($_POST['pass']);   
        $nama = strip_tags($_POST['nama']);   
        $alamat = strip_tags($_POST['alamat']);   
        $no_hp = strip_tags($_POST['no_hp']);   
            
        if($dosen->update($id,$nidn,$email,$nama,$alamat,$no_hp))
        {
            $dosen->redirect(site_url("admin/dosen/?sukses")); 
        }
        else
        {
            $error[] = "Maaf telah terjadi kesalahan"; 
        }
    }

    if(isset($_GET['edit_id']))
    {
        $id = $_GET['edit_id'];
        extract($dosen->getID($id));
    }

//Hapus data
if(isset($_GET['hapus_id']))
{
   if(isset($_POST['id']))
    {
        $id = $_POST['id'];
    //  $id = $_GET['hapus_id'];
        if ($dosen->delete($id)) {
            $dosen->redirect(site_url("admin/dosen/?terhapus"));
        }
        else {
            $dosen->redirect(site_url("admin/dosen/?gagal"));            
        } 
    }
}

// Validasi nidn
if (isset($_GET['nidn'])) {
  $valnidn = $_GET['nidn'];
  $stmt = $login->runQuery("SELECT nidn FROM dosen WHERE nidn=:nidn");
  $stmt->execute(array(':nidn'=>$valnidn));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
  if(strtolower($row['nidn'])==strtolower($valnidn)) {
    var_dump(http_response_code(300)); //kirim respond 300
  }
  else {
    var_dump(http_response_code(200)); //kirim respond 200
  }
}


// Validasi email
if (isset($_GET['email'])) {
  $valemail = $_GET['email'];
  $stmt = $login->runQuery("SELECT email FROM dosen WHERE email=:email");
  $stmt->execute(array(':email'=>$valemail));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
  if(strtolower($row['email'])==strtolower($valemail)) {
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
        <span>Data dosen</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3 id="<?=((isset($_GET['edit_id']))?'ubahdata':'tambahdata')?>"><?=((isset($_GET['edit_id']))?'Ubah data '.$nama.' ('.$nidn.')':'Tambah Data Dosen')?></h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label for="nidn">NIK / NIP</label>
                <input type="text" name="nidn" class="form-control" value="<?=((isset($_GET['edit_id']))?$nidn:'')?>" pattern="\d*" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/dosen/').'" data-error="NIK / NIP sudah digunakan"' ?> data-minlength="6" maxlength="25" data-pattern-error="NIK / NIP harus berupa digit angka" data-minlength-error="NIK/NIP  masih kurang dari 6 karakter" data-required-error="NIK/NIP tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
          </div>
            <div class="form-group has-feedback">
                <label for="password">Password</label>
               <input type='password' name='pass' class='form-control' <?=((isset($_GET['edit_id']))?'disabled':'')?>>
                <p class="help-block"><i class="fa fa-info-circle"></i> <?=((isset($_GET['edit_id']))?'Hanya dapat diganti oleh dosen yang bersangkutan':'Kosongkan password maka akan terisi password default (sama dengan NIK/NIP)')?></p>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="email">Email</label>
                <input type='email' name='email' class='form-control' value="<?=((isset($_GET['edit_id']))?$email:'')?>" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/dosen/').'" data-error="Email sudah digunakan"' ?> data-type-error="Format email tidak benar" data-success="asdasd" data-required-error="Email tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="nama">Nama Dosen</label>
                <input type='text' name='nama' class='form-control' value="<?=((isset($_GET['edit_id']))?$nama:'')?>" pattern="[a-zA-Z-,\.\s]+" data-pattern-error="Format nama tidak benar" data-required-error="Nama tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="nama">Alamat</label>
                <textarea cols="140" name='alamat' class='form-control'><?=((isset($_GET['edit_id']))?$alamat:'')?></textarea>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="hp">Nomor HP</label>
                <input type='text' name='no_hp' class='form-control' value="<?=((isset($_GET['edit_id']))?$no_hp:'')?>">
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
        <h3 id="grid-example-basic">Master Data Dosen <a href="<?=((isset($_GET['edit_id']))?'#ubahdata':'#tambahdata')?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah Data':'Tambah Data')?></a></h3>

        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIK/NIP</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Tgl Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT * FROM dosen";       
                $dosen->dataview($query);
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
</body>
</html>