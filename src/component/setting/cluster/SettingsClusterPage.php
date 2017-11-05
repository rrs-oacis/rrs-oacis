<?php

namespace rrsoacis\component\setting\cluster;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\exception\HttpMethodNotAllowedException;
use rrsoacis\manager\ClusterManager;
use rrsoacis\manager\component\Cluster;
use rrsoacis\system\Config;

class SettingsClusterPage extends AbstractPage
{
	private $cluster;
	private $fileName;

	public function controller($params)
	{
		$this->cluster = ClusterManager::getCluster($params[0]);
		if ($this->cluster == null) {
			throw new HttpMethodNotAllowedException();
		}

		$this->printPage();
	}

	function body()
	{
		$cluster = $this->cluster;

		self::writeContentHeader($cluster["name"], "Clusters setting",
			['<a href="/settings">Settings</a>', '<a href="/settings-clusters">Clusters</a>']);
		?>
		<section class="content" id="main-contents">
		</section>
		<script type="text/javascript">
			var refreshContents = function () {
				document.getElementById("main-contents").innerHTML = ' <div style="margin-bottom: 2em;"> <div>'
					+ '<button class="btn btn-danger" '
					+ 'onclick="location.href=\'/settings-cluster_remove/'
					+ '<?= $cluster["name"] ?>\'">Remove</button> &nbsp; '
					<?php if ($cluster["check_status"] == 3) { ?>
					+ '<button class="btn btn-success" '
					+ 'onclick="location.href=\'/settings-cluster_enable/'
					+ '<?= $cluster["name"] ?>/1\'">Enable</button>'
					<?php } else { ?>
					+ '<button class="btn btn-warning" '
					+ 'onclick="location.href=\'/settings-cluster_enable/'
					+ '<?= $cluster["name"] ?>/0\'">Disable</button>'
					<?php } ?>
					+ '</div> </div> <h1 class="text-center"> <i class="fa fa-refresh fa-spin"></i> </h1>';
				simpleimport("main-contents","/settings-cluster_contents/<?= $cluster["name"] ?>");
			}
			refreshContents();
		</script>
		<?php
	}

	function footer()
	{
		?>
		<footer class="main-footer">
			<button class="btn btn-default btn-xs" onclick="window.open('/settings-cluster_livelog/<?= $this->cluster["name"] ?>?list','ll','menubar=no, toolbar=no, scrollbars=no')">
				<i class="fa fa-terminal"></i> LiveLog
			</button>
			<div class="pull-right hidden-xs">
				<b>Version</b> <?= Config::APP_VERSION ?>
			</div>
			<br>
		</footer>
		</div>
		<!-- ./wrapper -->
		<?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>
		</body>
		</html>
		<?php
	}
}
