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

    static function writeContentHeader($contentTitle, $contentDiscription, $breadcrumbArray)
    {
        ?>
        <section class="content-header">
            <h1>
                <?= $contentTitle ?>
                <small><?= $contentDiscription ?></small>
            </h1>
            <ol class="breadcrumb">
        <?php
        for ($i = 0; $i < count($breadcrumbArray); $i++) {
            if (($i +1) == count($breadcrumbArray)) {
                print('<li>'.$breadcrumbArray[$i].'</li>');
            } else {
                print('<li class="active">'.$breadcrumbArray[$i].'</li>');
            }
        }
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