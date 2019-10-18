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
    $id = $admin_id;
    $upass = $_POST['password_baru_konfirmasi'];
    
    if($upass=="")  {
        $error[] = "Kata sandi masih kosong !";    
    }
    
    else
    {
        if($gantisandi->ganti($id,$upass))
        {
            redirect(site_url('admin/ganti-sandi/?sukses'));
        }
        else
        {
            redirect(site_url('admin/ganti-sandi/?gagal'));
        }

    }
}


// Validasi
if (isset($_GET['password_lama'])) {
    $upass = $_GET['password_lama'];

    $stmt = $login->runQuery("SELECT id,username,password FROM admins WHERE username=:uname");
    $stmt->execute(array(':uname'=>$adminRow['username']));
    $gantisandiRow=$stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() == 1)
    {
        if(password_verify($upass, $gantisandiRow['password']))
        {
            var_dump(http_response_code(200)); //kirim respond 200
        }
        else
        {
            var_dump(http_response_code(300)); //kirim respond 300
        }
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
        <span>Pengaturan</span>
        <i class="fa fa-angle-right"></i>
        <span>Ganti Kata Sandi</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3>Ganti Kata Sandi</h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Kata Sandi Lama</label>
                <input type="password" name="password_lama" class="form-control" <?= (isset($_GET['edit_id'])) ? '' : 'data-remote="'.site_url('admin/ganti-sandi/').'" data-error="Kata sandi tidak benar"' ?> maxlength="155" data-required-error="Kata sandi tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>Kata Sandi Baru</label>
               <input type='password' name='password_baru' id="password_baru" class='form-control' maxlength="125" data-required-error="Kata Sandi Baru tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label>Konfirmasi Kata Sandi Baru</label>
               <input type='password' name='password_baru_konfirmasi' data-match="#password_baru" class='form-control' maxlength="125" data-match-error="Kata sandi tidak cocok" data-required-error="Konfirmasi kata Sandi Baru tidak boleh kosong" required>
                <span class="fa form-control-feedback"></span>
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