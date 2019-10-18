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
    $tahun_akademik = $login->pengaturan('tahun_akademik');    
    $semester = $login->pengaturan('semester');    
}
//var_dump($beranda->ikad("nilai",$tahun_akademik,$semester));
//exit();

include ROOT."views/layout/admin/header.php";
?>

<!--banner-->   
<div class="banner">           
    <h2>
    <a href="home.php">Home</a>
    <i class="fa fa-angle-right"></i>
    <span>Beranda</span>
    </h2>
</div>
<!--//banner-->

    <div class="graph">
        <div class="col-md-12 graph-box1 clearfix"> 
            <div class="grid-1">
                <h4>Total Mahasiswa STMIK Banjarbaru</h4>
                <div class="grid-graph">
                    <div class="grid-graph1">
                        <div id="os-Win-lbl">Sistem Informasi <span><?= $beranda->totalmhs('Sistem Informasi',$tahun_akademik,$semester) ?> mahasiswa</span></div>
                        <div id="os-Mac-lbl">Teknik Informatika <span> <?= $beranda->totalmhs('Teknik Informatika',$tahun_akademik,$semester) ?> mahasiswa</span></div>
                        <div id="total">Total <span> 
                        <?php
                            $ti = $beranda->totalmhs('Teknik Informatika',$tahun_akademik,$semester);
                            $si = $beranda->totalmhs('Sistem Informasi',$tahun_akademik,$semester);
                            $total = (int)$ti + (int)$si;
                            echo $total;
                        ?> mahasiswa</span></div>
                    </div>
                </div>
                <div class="grid-2">
                    <canvas id="pie" height="315" width="470" style="width: 470px; height: 315px;"></canvas>
                </div>
                <div class="clearfix"></div>
            </div>        
        </div>  



            <div class="col-md-4 ">
                <div class="content-top-1">
                <div class="col-md-6 top-content">
                    <h5>Login Mahasiswa</h5><br>
                    <label><?= $beranda->loginmahasiswa("login"); ?></label> orang mahasiswa<br>
                </div>
                <div class="col-md-6 top-content1">    
                    <div id="demo-pie-1" class="pie-title-center" data-percent="<?= $beranda->loginmahasiswa("total"); ?>"> <span class="pie-value"></span> </div>
                </div>
                <div class="col-md-12 top-content">
                    <small>(Dalam 1 minggu terakhir)</small>
                </div>
                 <div class="clearfix"> </div>
                </div>
                <div class="content-top-1">
                <div class="col-md-6 top-content">
                    <h5>Login Dosen</h5><br>
                    <label><?= $beranda->logindosen("login"); ?></label> dosen<br>
                </div>
                <div class="col-md-6 top-content1">    
                    <div id="demo-pie-2" class="pie-title-center" data-percent="<?= $beranda->logindosen("total"); ?>"> <span class="pie-value"></span> </div>
                </div>
                <div class="col-md-12 top-content">
                    <small>(Dalam 1 minggu terakhir)</small>
                </div>
                 <div class="clearfix"> </div>
                </div>
                <div class="content-top-1">
                <div class="col-md-6 top-content">
                    <h5>Pengisian Aktivitas Dosen</h5><br>
                    <label><?= $beranda->aktivitasdosen("responden",$tahun_akademik,$semester); ?></label> dari <?= $beranda->aktivitasdosen("total",$tahun_akademik,$semester); ?>
                </div>
                <div class="col-md-6 top-content1">    
                    <div id="demo-pie-3" class="pie-title-center" data-percent="<?= $beranda->aktivitasdosen("persentase",$tahun_akademik,$semester); ?>"> <span class="pie-value"></span> </div>
                </div>
                <div class="col-md-12 top-content">
                    <small>Dosen Pengampu</small>
                </div>
                 <div class="clearfix"> </div>
                </div>
            </div>
<div class="col-md-8 graph-2">
        <div class="grid-1">
            <h4>Grafik Penilaian Dosen<br>TA. <?= $tahun_akademik ?> | Semester <?= $semester ?></h4>
            <canvas id="line1" height="600" width="750" style="width: 850px; height: 600px;"></canvas>
        </div>
    </div>
    <div class="clearfix"> </div>
</div>
        <!---->

<?php 
include ROOT."views/layout/admin/footer.php";
?>
<script src="<?= site_url('assets/js/pie-chart.js') ?>" type="text/javascript"></script>
<script src="<?= site_url('assets/js/chart.js') ?>" type="text/javascript"></script>
<script type="text/javascript">

        $(document).ready(function () {
            $('#demo-pie-1').pieChart({
                barColor: '#3bb2d0',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-2').pieChart({
                barColor: '#fbb03b',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-3').pieChart({
                barColor: '#ed6498',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
        });

    </script>
<!--skycons-icons-->
<script src="<?= site_url('assets/js/skycons.js') ?>"></script>
<!--//skycons-icons-->
                                <script>
                                        var lineChartData = {
                                            labels : <?= $beranda->ikad("nama",$tahun_akademik,$semester) ?>,
                                            datasets : [
                                                {
                                                    fillColor : "#fff",
                                                    strokeColor : "#1ABC9C",
                                                    pointColor : "#1ABC9C",
                                                    pointStrokeColor : "#1ABC9C",
                                                    data : <?= $beranda->ikad("nilai",$tahun_akademik,$semester) ?>
                                                }
                                            ]
                                            
                                        };
                                        new Chart(document.getElementById("line1").getContext("2d")).Line(lineChartData);
                                </script>
                                <script>
                                    var pieData = [
                                        {
                                            value : <?= $beranda->totalmhs('Sistem Informasi',$tahun_akademik,$semester) ?>,
                                            color : "#1ABC9C"
                                        },
                                        {
                                            value : <?= $beranda->totalmhs('Teknik Informatika',$tahun_akademik,$semester) ?>,
                                            color : "#3BB2D0"
                                        }
                                    
                                    ];
                                    new Chart(document.getElementById("pie").getContext("2d")).Pie(pieData);
                                </script>
</body>
</html>