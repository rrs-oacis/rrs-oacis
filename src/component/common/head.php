<?php

use rrsoacis\system\Config;

?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?= rrsoacis\system\Config::APP_NAME ?> | <?= $title ?></title>

<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?= Config::$RESOURCE_PATH?>bootstrap/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="./bootstrap/css/bootstrap2-toggle.min.css"> -->
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= Config::$RESOURCE_PATH?>plugins/fontawesome/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="<?= Config::$RESOURCE_PATH?>plugins/ionicons/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?= Config::$RESOURCE_PATH?>adminlte/css/AdminLTE.min.css">
<!-- iCheck -->
<link rel="stylesheet" href="<?= Config::$RESOURCE_PATH?>plugins/iCheck/square/blue.css">

<!-- AdminLTE Skins. Choose a skin from the css/skins-->
<link rel="stylesheet" href="<?= Config::$RESOURCE_PATH?>adminlte/css/skins/_all-skins.min.css">

<!-- Toaster -->
<link rel='stylesheet' href='<?= Config::$RESOURCE_PATH?>plugins/toastr/toastr.min.css' />  

<!-- jQuery 2.2.3 -->
<script src="<?= Config::$RESOURCE_PATH?>plugins/jQuery/jquery-2.2.3.min.js"></script>


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
<!--
.toast-top-right
{
    top: 64px;
}

table th
{
    background-color: whitesmoke;
}
-->
</style>