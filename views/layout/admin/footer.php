<div class="copy">
            <p> &copy; 2017 IKAD STMIK Banjarbaru. All Rights Reserved | Develop by <a href="rizal.web.id" target="_blank">Rizal Alfiannor</a></p></div>
        </div>
        </div>
        <div class="clearfix"> </div>
       </div>

<script src="<?= site_url('assets/js/jquery.min.js') ?>"> </script>
<script src="<?= site_url('assets/js/bootstrap.min.js') ?>"> </script>
<!-- Mainly scripts -->
<script src="<?= site_url('assets/js/jquery.metisMenu.js') ?>"></script>
<script src="<?= site_url('assets/js/jquery.slimscroll.min.js') ?>"></script>

<script src="<?= site_url('assets/js/custom.js') ?>"></script>
<script src="<?= site_url('assets/js/screenfull.js') ?>"></script>
        <script>
        $(function () {
            $('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

            if (!screenfull.enabled) {
                return false;
            }

            

            $('#toggle').click(function () {
                screenfull.toggle($('#container')[0]);
            });
            

            
        });
        </script>

<!----->

<!--scrolling js-->
    <script src="<?= site_url('assets/js/jquery.nicescroll.js') ?>"></script>
    <script src="<?= site_url('assets/js/scripts.js') ?>"></script>
<!--//scrolling js-->