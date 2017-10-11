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
            <a href="<?=Config::$TOP_PATH ?>/settings-general">
            <button class="btn">No connection</button>
            </a>
        <?php } else if ($gitcheck_ret == 0) { ?>
            <a href="<?=Config::$TOP_PATH ?>/settings-version_update">
                <button class="btn btn-info">Update</button>
            </a>
        <?php } else { ?>
            <a href="<?=Config::$TOP_PATH ?>/settings-general">
            <button class="btn">Latest version</button>
            </a>
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
