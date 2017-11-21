<?php
use rrsoacis\system\Config;
?>

<!-- Bootstrap 3.3.6 -->
<script src="<?= Config::$RESOURCE_PATH?>bootstrap/js/bootstrap.min.js"></script>
<!-- DataTable -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= Config::$RESOURCE_PATH?>plugins/datatables/dataTables.bootstrap.js"></script>
<!-- iCheck -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/iCheck/icheck.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= Config::$RESOURCE_PATH?>adminlte/js/app.min.js"></script>
<!-- Toastr -->
<script src='<?= Config::$RESOURCE_PATH?>plugins/toastr/toastr.min.js'></script>
<!-- Select2 -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/select2/select2.full.js"></script>

<!-- linked-row -->
<script type="text/javascript">
    $(document).ready(function($){
        $(".linked-row").click(function() { location.href = $(this).data("href"); });
    });
</script>

<!-- OACISLINK -->
<script type="text/javascript">
	$(document).ready(function($){
		var elements = document.getElementsByClassName("OACISLINK");
		for (var i=0;i<elements.length;i++)
		{
			var element = elements[i];
			var datahref = element.dataset.href;
			datahref = (datahref == undefined ? "" : datahref);
			element.href = "http://" + location.host.replace(":"+location.port, ":"+3000) + "/" + datahref;
		}
	});
</script>

<?php if(!strpos($_SERVER["REQUEST_URI"],'login.php')){?>

<?php }?>

