<!--banner-->   
<div class="grid-form">           
    <?php
    if(isset($_GET['sukses'])) 
    {
        ?>
        <div class="alert alert-info">
        <strong>Sukses!</strong> data telah berhasil disimpan.
        </div>
    <?php
    }

    else if(isset($_GET['gagal']))
    {

    ?>
        <div class="alert alert-danger">
        <strong>Maaf!</strong> telah terjadi kesalahan ketika menginput data !
        </div>
    <?php
    }

    ?>

    <?php
    if(isset($_GET['terhapus']))
    {
        ?>
        <div class="alert alert-success">
        <strong>Sukses!</strong> data telah terhapus... 
        </div>

    <?php
    }
    if(isset($error))
    {
        foreach($error as $error)
        {
             ?>
                <div class="alert bg-danger" role="alert">
                    &nbsp; <?php echo $error; ?><a href="#" class="pull-right" data-dismiss="alert"><i class="fa fa-remove"></i></a>
                </div>
             <?php   
        }
    }
    ?>
</div>