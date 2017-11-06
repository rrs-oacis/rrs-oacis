<?php

namespace rrsoacis\component\setting\app;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\exception\HttpMethodNotAllowedException;
use rrsoacis\manager\AppManager;
use rrsoacis\system\Config;

class SettingsAppPage extends AbstractPage
{
	private $app;

	public function controller($params)
	{
		if (count($params) == 2) {
			$this->app = AppManager::getApp($params[0] . "/" . $params[1]);
			$this->setTitle($this->app["name"]);
			$this->printPage();
			return;
		}

		throw new HttpMethodNotAllowedException();
	}

	function body()
	{
		$app = $this->app;

		self::writeContentHeader($app["name"], "Apps setting",
			array('<a href="/settings">Settings</a>', '<a href="/settings-apps">Apps</a>'));

		self::beginContent();
		?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">General</h3>
						<div class="box-tools">
							<?php if ($app["is_plugin"]) { ?>
								<form method="post" action="/settings-app_installer" style="display: inline-block;">
									<input type="hidden" name="user" value="<?= $app["packages_user"] ?>">
									<input type="hidden" name="name" value="<?= $app["packages_name"] ?>">
									<input class="btn btn-sm btn-info" type="submit" value="Pull">
								</form>
							<?php } ?>
							<?php if ($app["enabled"]) { ?>
								<button class="btn btn-sm btn-warning"
												onclick="location.href='<?= Config::$TOP_PATH ?>/settings-app_enable/<?= $app["package"] ?>/0'">
									Disable
								</button>
							<?php } else { ?>
								<button class="btn btn-sm btn-success"
												onclick="location.href='<?= Config::$TOP_PATH ?>/settings-app_enable/<?= $app["package"] ?>/1'">
									Enable
								</button>
							<?php } ?>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive no-padding">
						<table class="table table-hover">
							<tr>
								<th>Name</th>
								<th>Ver.</th>
								<th>Description</th>
								<th>Provider</th>
								<th>Status</th>
							</tr>
							<tr>
								<td><?= $app["name"] ?></td>
								<td><?= $app["version"] ?></td>
								<td><?= $app["description"] ?></td>
								<td><?= $app["packages_user"] ?></td>
								<td>
									<?php if ($app["enabled"]) { ?>
										<span class="label label-success">Enabled</span>
									<?php } else { ?>
										<span class="label label-warning">Disabled</span>
									<?php } ?>
								</td>
							</tr>


						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
		<?php
		self::endContent();
	}
}
