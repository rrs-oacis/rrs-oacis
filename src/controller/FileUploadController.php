<?php

namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;

class FileUploadController extends AbstractController {
	
	public function post() {
		$uploadDir = "../" . Config::UPLOAD_DIR_NAME;
		
		if (! file_exists ( $uploadDir )) {
			mkdir ( $uploadDir );
		}
		
		echo realpath ( $uploadDir ) . " : ";
		
		echo $_FILES ['userfile'] ['name'] . " : ";
		
		echo $_FILES ['userfile'] ['tmp_name'];
		move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $_FILES ['userfile'] ['name'] );
	}
	
}

?>