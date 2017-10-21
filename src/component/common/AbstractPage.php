<?php

namespace rrsoacis\component\common;

use rrsoacis\system\Config;

abstract class AbstractPage
{
    public abstract function controller();

    function printPage()
    {
        $this->header();
        $this->sideber();
        ?> <div class="content-wrapper"> <?php
        $this->body();
        ?> </div> <?php
        $this->footer();
    }

    public function getData()
    {
        return array();
    }

    protected $_pageTitle;
    public function setTitle($title)
    {
        $this->_pageTitle = $title;
    }

    function header()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <?php $title = $this->_pageTitle; ?>
            <?php include Config::$SRC_REAL_URL . 'component/common/head.php';?>
        </head>
        <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
        <?php include Config::$SRC_REAL_URL . 'component/common/main-header.php';?>
        <?php
    }

    function body()
    {
    }

    function footer()
    {
        ?>
        <?php include Config::$SRC_REAL_URL . 'component/common/main-footer.php';?>
        <!-- =============================================== -->
        </div>
        <!-- ./wrapper -->
        <?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>
        </body>
        </html>
        <?php
    }

    function sideber()
    {
        ?>
        <?php include Config::$SRC_REAL_URL . 'component/common/main-sidebar.php';?>
        <?php
    }

    static function writeContentHeader($contentTitle, $contentDiscription = "", $breadcrumbArray = array())
    {
        ?>
        <section class="content-header">
            <h1>
                <?= $contentTitle ?>
                <small><?= $contentDiscription ?></small>
            </h1>
            <ol class="breadcrumb">
        <?php
        print('<li><a href="<?= Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i>RRS-OACIS</a></li>');
        foreach ($breadcrumbArray as $breadcrumb) {
            print('<li>'.$breadcrumb.'</li>');
        }
        print('<li class="active">'.$contentTitle.'</li>');
        ?>
            </ol>
        </section>
        <?php
    }

    static function beginContent()
    {
        ?>
        <section class="content">
        <?php
    }

    static function endContent()
    {
        ?>
        </section>
        <?php
    }
}

?>