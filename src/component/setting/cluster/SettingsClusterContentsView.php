<?php

use rrsoacis\system\Config;

?>
<!--<section class="content" id="main-content">-->
<div style="margin-bottom: 2em;">
	<div>
		<button class="btn btn-danger"
						onclick="location.href='<?= Config::$TOP_PATH ?>/settings-cluster_remove/<?= $cluster["name"] ?>'">Remove
		</button> &nbsp;
		<?php if ($cluster["check_status"] == 3) { ?>
			<button class="btn btn-success"
							onclick="location.href='<?= Config::$TOP_PATH ?>/settings-cluster_enable/<?= $cluster["name"] ?>/1'">
				Enable
			</button>
		<?php } else { ?>
			<button class="btn btn-warning"
							onclick="location.href='<?= Config::$TOP_PATH ?>/settings-cluster_enable/<?= $cluster["name"] ?>/0'">
				Disable
			</button>
		<?php } ?>
	</div>
</div>

<!-- BEGIN : Clusters -->
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Clusters</h3>
				<div class="box-tools">
					<button class="btn btn-info" onclick="refreshContents()">Update</button>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body table-responsive no-padding">
				<table class="table">
					<tr>
						<th></th>
						<th>Host</th>
						<th>Message</th>
					</tr>
					<tr>
						<th style="color:<?= ($isDisabled ? "orange" : ($hasError["S"] ? "red" : "green")) ?>;">Server</th>
						<td><?= $cluster["s_host"] ?></td>
						<td><?= $message["S"] ?></td>
					</tr>
					<tr>
						<th style="color:<?= ($isDisabled ? "orange" : ($hasError["A"] ? "red" : "green")) ?>;">Ambulance</th>
						<td><?= $cluster["a_host"] ?></td>
						<td><?= $message["A"] ?></td>
					</tr>
					<tr>
						<th style="color:<?= ($isDisabled ? "orange" : ($hasError["F"] ? "red" : "green")) ?>;">Fire</th>
						<td><?= $cluster["f_host"] ?></td>
						<td><?= $message["F"] ?></td>
					</tr>
					<tr>
						<th style="color:<?= ($isDisabled ? "orange" : ($hasError["P"] ? "red" : "green")) ?>;">Police</th>
						<td><?= $cluster["p_host"] ?></td>
						<td><?= $message["P"] ?></td>
					</tr>
				</table>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
</div>
<!-- END : Clusters -->

<!-- BEGIN : Setup -->
<div class="row">
	<div class="col-xs-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">
					Setup
				</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<form id="post-form" action="<?= Config::$TOP_PATH . "settings-cluster_update" ?>" method="POST"
							class="form-horizontal">
					<div class="input-group pull-right">
						<input type="hidden" name="name" value="<?= $cluster["name"] ?>">
						<input type="hidden" name="s_host" value="<?= $cluster["s_host"] ?>">
						<input type="hidden" name="a_host" value="<?= $cluster["a_host"] ?>">
						<input type="hidden" name="f_host" value="<?= $cluster["f_host"] ?>">
						<input type="hidden" name="p_host" value="<?= $cluster["p_host"] ?>">
						<input type="hidden" name="archiver" value="<?= $cluster["archiver"] ?>">
						<input class="form-control" placeholder="Hosts password" name="hosts_pass" type="password" value="">
						<span class="input-group-btn"> <input class="btn" type="submit" value="Run"> </span>
					</div>
				</form>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
</div>
<!-- END : Setup -->

<!-- BEGIN : Control -->
<div class="row">
	<div class="col-xs-12">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">
					Control
				</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<form action="<?= Config::$TOP_PATH . "settings-cluster_kill/" . $cluster["name"] ?>" method="POST"
							class="form-horizontal">
					<div class="input-group">
						<input class="btn btn-danger" type="submit" value="Send kill signal">
					</div>
				</form>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
</div>
<!-- END : Control -->

<!-- BEGIN : Check Message -->
<div class="row">
	<div class="col-xs-12">
		<div class="box box-warning collapsed-box">
			<div class="box-header with-border">
				<h3 class="box-title">
					Check Massage
					<small>Raw outputs of cluster checker</small>
				</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
					</button>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
      	<textarea class="form-control" rows="8" readonly><?= $checkMessage ?></textarea>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
</div>
<!-- END : Check Message -->
<!--</section>-->
<!-- /.content -->
