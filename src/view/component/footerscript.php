<?php 
use adf\Config;
?>

<!-- Bootstrap 3.3.6 -->
<script src="<?= Config::$RESOURCE_PATH?>bootstrap/js/bootstrap.min.js"></script>

<!-- <script src="./bootstrap/js/bootstrap2-toggle.min.js"></script> -->
<!-- iCheck -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/iCheck/icheck.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= Config::$RESOURCE_PATH?>adminlte/js/app.min.js"></script>

<!-- Toastr -->
<script src='<?= Config::$RESOURCE_PATH?>plugins/toastr/toastr.min.js'></script>  

<!-- 必要化ちょっと不明　 -->
<!-- SlimScroll -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/fastclick/fastclick.js"></script>

<?php if(!strpos($_SERVER["REQUEST_URI"],'login.php')){?>

<!-- <script src='./common.js'></script> -->  

<?php }?>

