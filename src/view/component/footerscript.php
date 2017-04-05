<?php 
use adf\Config;
?>

<!-- jQuery 2.2.3 -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?= Config::$RESOURCE_PATH?>bootstrap/js/bootstrap.min.js"></script>

<!-- <script src="./bootstrap/js/bootstrap2-toggle.min.js"></script> -->
<!-- iCheck -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/iCheck/icheck.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= Config::$RESOURCE_PATH?>adminlte/js/app.min.js"></script>

<!-- Toastr -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'></script>  

<?php if(!strpos($_SERVER["REQUEST_URI"],'login.php')){?>

<!-- <script src='./common.js'></script> -->  

<?php }?>

