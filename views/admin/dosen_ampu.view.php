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

if(isset($_GET['nidn']))
{
    $term=trim(htmlentities($_GET['nidn'])); 
    try
    {
        $stmt = $login->runQuery("SELECT * FROM dosen WHERE nidn LIKE :term OR nama LIKE :term");
        $stmt->execute(array(':term' => "%".$term."%"));
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row == 0) {
                $result[] = array(  
                'id' => 0,
                'text' => "Data tidak ditemukan"
                );            
            }
            else {
                $result[] = array(  
                'id' => $row['nidn'],
                'text' => "[".$row['nidn']."] ".$row['nama']
                );
            }        
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

if(isset($_GET['kd_mk']))
{
    $term=trim(htmlentities($_GET['kd_mk'])); 
    try
    {
        $stmt = $login->runQuery("SELECT * FROM matakuliah WHERE kd_mk LIKE :term OR nama_mk LIKE :term");
        $stmt->execute(array(':term' => "%".$term."%"));
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row == 0) {
                $result[] = array(  
                'id' => 0,
                'text' => "Data tidak ditemukan"
                );            
            }
            else {
                $result[] = array(  
                'id' => $row['kd_mk'],
                'text' => "[".$row['kd_mk']."] ".$row['nama_mk']." - ".$row['jurusan']
                );
            }        
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
    $nidn = $_POST['nidn'];
    $kd_mk = $_POST['kd_mk'];
    
    if($nidn=="")  {
        $error[] = "NIDN masih kosong !";    
    }
    
    else if($kd_mk=="") {
        $error[] = "Matakuliah masih kosong !";    
    }
        
    else
    {
        $stmt = $login->runQuery("SELECT * FROM dosen_ampu WHERE nidn = '".$nidn."' AND kd_mk = '".$kd_mk."'");
        $stmt->execute();


        $stmt2 = $login->runQuery("SELECT * FROM dosen_ampu WHERE nidn = '".$nidn."'");
        $stmt2->execute();

        if($stmt->rowCount() > 0) {
            $error[] = "Dosen tidak dapat mengampu matakuliah yang sama.";
        }
        elseif($stmt2->rowCount() == 3) {
            $error[] = "Maksimal 3 matakuliah yang boleh diampu dosen.";
        }
        else {
            if($dosenampu->create($nidn,$kd_mk)) {
                redirect(site_url('admin/dosen-ampu/?sukses'));
            }
            else {
                redirect(site_url('admin/dosen-ampu/?gagal'));
            }            
        }
    }
}

//Ubah data
if(isset($_POST['btn-update']))
    {
        $id = $_GET['edit_id'];
        $nidn = $_POST['nidn'];
        $kd_mk = $_POST['kd_mk'];            

        if($dosenampu->update($id,$nidn,$kd_mk))
        {
            redirect(site_url('admin/dosen-ampu/?sukses'));
        }
        else
        {
            redirect(site_url('admin/dosen-ampu/?gagal'));
        }
    }

if(isset($_GET['edit_id']))
{
    $id = $_GET['edit_id'];
    extract($dosenampu->getID($id));
}

//Hapus data
if(isset($_GET['hapus_id']))
{
   if(isset($_POST['id']))
    {
        $id = $_POST['id'];
        $dosenampu->delete($id);
        redirect(site_url('admin/dosen-ampu/?terhapus'));
    }
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
        <span>Master</span>
        <i class="fa fa-angle-right"></i>
        <span>Data Dosen Ampu</span>
        </h2>
    </div>
<!--//banner-->

<!--grid-->
<div class="grid-form">
    <div class="grid-form1">    
        <h3 id="<?=((isset($_GET['edit_id']))?'ubahdata':'tambahdata')?>"><?=((isset($_GET['edit_id']))?'Ubah data':'Tambah Data Dosen Ampu')?></h3><br>
            <form method="post" data-toggle="validator" role="form" data-feedback='{"success": "fa-check", "error": "fa-times"}' id="formdata">
            <div class="form-group has-feedback">
                <label>Nama Dosen</label>
                <select id="nidn" class="form-control" name="nidn" required>
                    <option value="">Pilih dosen</option>
                </select>
                <div class="help-block with-errors"></div>
          </div>
            <div class="form-group has-feedback">
                <label>Nama Matakuliah</label>
                <select id="kd_mk" class="form-control" name="kd_mk" required>
                    <option value="">Pilih Matakuliah</option>
                </select>
                <div class="help-block with-errors"></div>
            </div>
     
            <button type="submit" class="btn btn-primary" name="<?=((isset($_GET['edit_id']))?'btn-update':'btn-save')?>">
            <span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah':'Simpan')?>
            </button>  
            <a href="<?= site_url('admin/') ?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Batal</a>
        </form>
    </div>
    <div class="grid-form1">
        <h3 id="grid-example-basic">Master Data Dosen Ampu <a href="<?=((isset($_GET['edit_id']))?'#ubahdata':'#tambahdata')?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=((isset($_GET['edit_id']))?'Ubah Data':'Tambah Data')?></a></h3>

        <table id="datatable" class="table table-striped table-advance table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Dosen</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Jurusan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead> 
            <tbody>
            <?php
                $query = "SELECT a.id,a.nidn,a.kd_mk,b.nama,c.nama_mk,c.sks,c.semester,c.jurusan FROM dosen_ampu as a INNER JOIN dosen as b ON a.nidn = b.nidn INNER JOIN matakuliah as c ON a.kd_mk = c.kd_mk";       
                $dosenampu->dataview($query);
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

    $("#nidn").select2({
        placeholder: "Masukkan NIK/NIDN atau Nama Dosen",
        allowClear: true,
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
        url: "<?= site_url('admin/dosen-ampu/') ?>",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                nidn: params.term // search term
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
    //$("#nidn").select2('data', [{"id":"1001021","text":"[1001021] Muslihuddin, M. Kom"}]);
    <?php
        if (isset($error)) {
            $stmt5 = $login->runQuery("SELECT * FROM dosen WHERE nidn = :nidn");
            $stmt5->execute(array(':nidn' => $nidn));
            $rownidn=$stmt5->fetch(PDO::FETCH_ASSOC);

            echo "$(\"#nidn\").empty().append(\"<option value='$nidn'>"."[".$rownidn['nidn']."] ".$rownidn['nama']."</option>\").val(\"$nidn\").trigger(\"change\");";
        }
    ?>

    $("#kd_mk").select2({
        placeholder: "Masukkan Kode MK atau Nama Matakuliah",
        allowClear: true,
        theme: "bootstrap",
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
        url: "<?= site_url('admin/dosen-ampu/') ?>",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                kd_mk: params.term // search term
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

    <?php
        if (isset($error)) {
            $stmt5 = $login->runQuery("SELECT * FROM matakuliah WHERE kd_mk = :kd_mk");
            $stmt5->execute(array(':kd_mk' => $kd_mk));
            $rowkdmk=$stmt5->fetch(PDO::FETCH_ASSOC);

            echo "$(\"#kd_mk\").empty().append(\"<option value='$kd_mk'>"."[".$rowkdmk['kd_mk']."] ".$rowkdmk['nama_mk']." - ".$rowkdmk['jurusan']."</option>\").val(\"$kd_mk\").trigger(\"change\");";
        }
    ?>


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