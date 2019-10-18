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
    $nim = strip_tags($_POST['nim']);
    $email = strip_tags($_POST['email']);
    $pass = strip_tags($_POST['pass']);   
    $nama_mhs = strip_tags($_POST['nama_mhs']);   
    $alamat = strip_tags($_POST['alamat']);   
    $no_hp = strip_tags($_POST['no_hp']);   
    $jurusan = strip_tags($_POST['jurusan']);   
    
    if($nim=="")  {
        $error[] = "NIM masih kosong !";    
    }
    else if($email=="") {
        $error[] = "Email masih kosong !";    
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Masukkan alamatemail yang benar !';
    }
    else if($pass=="") {
        $error[] = "Kata Sandi masih kosong !";
    }
    else if(strlen($pass) < 6){
        $error[] = "Kata Sandi harus lebih dari 6 karakter"; 
    }
    else
    {
        try
        {
            $stmt = $crud->runQuery("SELECT nim, email FROM mahasiswa WHERE nim=:nim OR email=:email");
            $stmt->execute(array(':nim'=>$nim, ':email'=>$email));
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
                
            if($row['nim']==$nim) {
                $error[] = "Maaf NIM sudah ada !";
            }
            else if($row['email']==$email) {
                $error[] = "Maaf email sudah ada !";
            }
            else
            {
                $body = "Selamat ! Email anda berhasil terdaftar sebagai Mahasiswa pada aplikasi penilaian kinerja dosen STMIK Banjarbaru.<br><br><u>Informasi Akun anda :</u><br>Username : <b>$nim</b><br>Password : <b>$pass</b><br><br>Silahkan login dengan mengklik link berikut <a href='http://rizal.web.id/ikadstmik/login/'>http://rizal.web.id/ikadstmik/login/</a>";
                if($crud->sendmail($email,"Informasi Akun Mahasiswa STMIK Banjarbaru",$body)) {
                    if($crud->create($nim,$email,$pass,$nama_mhs,$alamat,$no_hp,$jurusan)){  
                        redirect(site_url("admin/mahasiswa/?sukses"));
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
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }   
}


//Ubah data
if(isset($_POST['btn-update']))
    {
        $id = $_GET['edit_id'];
        $nim = strip_tags($_POST['nim']);
        $email = strip_tags($_POST['email']);
        $nama_mhs = strip_tags($_POST['nama_mhs']);   
        $alamat = strip_tags($_POST['alamat']);   
        $no_hp = strip_tags($_POST['no_hp']);   
        $jurusan = strip_tags($_POST['jurusan']);   
            
        if($crud->update($id,$nim,$email,$nama_mhs,$alamat,$no_hp,$jurusan))
        {
            redirect(site_url("admin/mahasiswa/?sukses")); 
        }
        else
        {
            $error[] = "Maaf telah terjadi kesalahan"; 
        }
    }

    if(isset($_GET['edit_id']))
    {
        $id = $_GET['edit_id'];
        extract($crud->getID($id));
    }

//Hapus data
if(isset($_GET['hapus_id']))
{
   if(isset($_POST['id']))
    {
        $id = $_POST['id'];
    //  $id = $_GET['hapus_id'];
        if ($crud->delete($id)) {
            redirect(site_url("admin/mahasiswa/?terhapus"));
        }
        else {
            redirect(site_url("admin/mahasiswa/?gagal"));            
        } 
    }
}

// Validasi NIM
if (isset($_GET['nim'])) {
  $valnim = $_GET['nim'];
  $stmt = $login->runQuery("SELECT nim FROM mahasiswa WHERE nim=:nim");
  $stmt->execute(array(':nim'=>$valnim));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
  if(strtolower($row['nim'])==strtolower($valnim)) {
    var_dump(http_response_code(300)); //kirim respond 300
  }
  else {
    var_dump(http_response_code(200)); //kirim respond 200
  }
}


// Validasi email
if (isset($_GET['email'])) {
  $valemail = $_GET['email'];
  $stmt = $login->runQuery("SELECT email FROM mahasiswa WHERE email=:email");
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
        <span>Data Mahasiswa</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3 id="<?=((isset($_GET['edit_id']))?'ubahdata':'tambahdata')?>"><?=((isset($_GET['edit_id']))?'Ubah data '.$nim.' ('.$nama_mhs.')':'Tambah Data Mahasiswa')?></h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label for="nim">NIM</label>
                <input type="text" name="nim" class="form-control" value="<?=(isset($_GET['edit_id']))?$nim:''?>" pattern="\d*" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/mahasiswa/').'" data-error="NIM sudah digunakan"' ?> data-minlength="12" maxlength="12" data-pattern-error="NIM harus berupa digit angka" data-minlength-error="NIM masih kurang dari 12 karakter" data-required-error="NIM tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
          </div>
            <div class="form-group has-feedback">
                <label for="password">Password</label>
               <input type='password' name='pass' class='form-control' <?=((isset($_GET['edit_id']))?'disabled':'')?>>
                <p class="help-block"><?=((isset($_GET['edit_id']))?'Hanya dapat diganti oleh mahasiswa yang bersangkutan':'')?></p>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="email">Email</label>
                <input type='email' name='email' class='form-control' value="<?=((isset($_GET['edit_id']))?$email:'')?>" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/mahasiswa/').'" data-error="Email sudah digunakan"' ?> data-type-error="Format email tidak benar" data-success="asdasd" data-required-error="Email tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="nama">Nama Mahasiswa</label>
                <input type='text' name='nama_mhs' class='form-control' value="<?=((isset($_GET['edit_id']))?$nama_mhs:'')?>" pattern="[a-zA-Z-,\.\s]+" data-pattern-error="Format nama tidak benar" data-required-error="Nama tidak boleh kosong" required>
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
            <div class="form-group has-feedback">
                <label for="jurusan">Program Studi</label>
                <select id="jurusan" class="form-control" name="jurusan" data-required-error="Silahkan pilih program studi" required>
                    <option value="" selected="selected">Pilih Program Studi</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                </select>
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
        <h3 id="grid-example-basic">Master Data Mahasiswa <a href="<?=((isset($_GET['edit_id']))?'#ubahdata':'#tambahdata')?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah Data':'Tambah Data')?></a></h3>

        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Tgl Daftar</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT * FROM mahasiswa";       
                $crud->dataview($query);
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

    $('[name=jurusan] option').filter(function() { 
       return ($(this).val() == '<?= (isset($_GET['edit_id'])) ? $jurusan : '' ?>');
    }).prop('selected', true);

 });

</script>
</body>
</html>