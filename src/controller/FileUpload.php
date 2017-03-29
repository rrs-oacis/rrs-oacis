<?php

use adf\Config;

$uploadDir = "../" . Config::UPLOAD_DIR_NAME;

if (! file_exists ( $uploadDir )) {
	mkdir ( $uploadDir );
}

echo realpath($uploadDir) . " : ";

echo $_FILES ['userfile'] ['name'] ." : ";

echo $_FILES ['userfile'] ['tmp_name'];
move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $_FILES ['userfile'] ['name'] );

?>