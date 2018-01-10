<?php
use rrsoacis\system\Config;
use rrsoacis\manager\AccessManager;
?>
<!--<div class="box box-solid">-->
<div class="box-header">
	<h3 class="box-title">
		Version
	</h3>
	<div class="box-tools">
		<?php
		?>
		<?php if (!$internet) { ?>
			<button class="btn" onclick="location.href='<?=Config::$TOP_PATH ?>/settings-general'">No connection</button>
		<?php } else if ($gitcheck_ret == 0) { ?>
			<button id="update-button" class="btn btn-info" onclick="location.href='<?=Config::$TOP_PATH ?>/settings-version_update'">Update</button>
		<?php } else { ?>
			<button class="btn" onclick="event.shiftKey?location.href='<?=Config::$TOP_PATH ?>/settings-version_update':location.href='<?=Config::$TOP_PATH ?>/settings-general'">Latest version</button>
		<?php } ?>
	</div>
	<div class="box-body">
		<?php if ($internet) { ?>
			<b> Current version </b>
			<pre><?= implode("\n", $gitlog_local); ?></pre>

			<b> Latest version </b>
			<pre><?= implode("\n", $gitlog_remote); ?></pre>
		<?php } else { ?>
			<b> Current version </b>
			<pre><?= implode("\n", $gitlog_local); ?></pre>

			<b> Latest version </b>
			<pre>No internet connection



            </pre>
		<?php } ?>
	</div>
</div>
<!--</div>-->

<script>
	$(function(){
		$("#update-button").on('click',function(){
			$("#update-button").html("progressing...").prop('disabled', true);
		}
	});
</script>
