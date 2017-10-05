<?php

namespace rrsoacis\component\map;

use ZipArchive;

use rrsoacis\manager\MapManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class MapFileUploadController extends AbstractController
{
	
	public function post()
    {
		ini_set ( 'display_errors', 1 );

        $uuid = uniqid();
        //アップロードを受け取る処理
        self::receivePost($uuid);


        $ext = pathinfo($_FILES ['userfile'] ['name'], PATHINFO_EXTENSION);
        if($ext == 'zip') {
            self::extractZip($uuid);
        }else if($ext == 'gz'){
            self::extractTarGz($uuid);
        }
        //self::echoFileCheck($uuid);

        $fileName = $_POST['map_name'];
	}
	
	private function receivePost($uuid)
    {
        $tmpDir = Config::$ROUTER_PATH.Config::TMP_DIR_NAME;
        if (! file_exists ( $tmpDir )) { mkdir( $tmpDir ); }
        //phpinfo();

        $echoDate = [];
        $echoDate['uploadDir']  = realpath ( $tmpDir );
        $echoDate['name'] = $_FILES ['userfile'] ['name'];
        $echoDate['tmp_name'] = $_FILES ['userfile'] ['tmp_name'];
        //echo json_encode($echoDate);

        $ext = pathinfo($echoDate['name'], PATHINFO_EXTENSION);


        //move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $_FILES ['userfile'] ['name'] );

        //Rename
        if($ext == 'zip'){
            move_uploaded_file( $_FILES ['userfile'] ['tmp_name'], $tmpDir . "/" . $uuid. ".zip");
        }else if($ext == 'gz'){
            move_uploaded_file( $_FILES ['userfile'] ['tmp_name'], $tmpDir . "/" . $uuid. ".tar.gz");
        }

	}
	
	private function extractZip($uuid)
    {
        $zip = new ZipArchive();
        $tmpDir = Config::$ROUTER_PATH.Config::TMP_DIR_NAME;
        $uploadedFile = $tmpDir."/".$uuid.".zip";
        $mapName = $_POST['map_name'];

        // ZIPファイルをオープン
        $res = $zip->open($uploadedFile);

        // zipファイルのオープンに成功した場合
        if ($res === true)
        {
            $mapDir = Config::$ROUTER_PATH.Config::MAPS_DIR_NAME;
            $approved = false;
            // 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
            $fileDir = $tmpDir."/".$uuid;
            $zip->extractTo($fileDir.'/');
            $zip->close();
            system("rm -f ".$uploadedFile);

            $approved = self::checkMapFile($fileDir);

            if ($approved)
            {
                $fullName = $mapName.'_'.$uuid;
                system('mv "'.$fileDir.'" "'.$mapDir.'/'.$fullName.'"');
                system("rm -rf ".$tmpDir.'/'.$uuid);

                MapManager::addMap($fullName, $mapName);

                echo '{"status":true}';
                return true;
            }

            system("rm -rf ".$tmpDir.'/'.$uuid);
        }

        echo '{"status":false}';
        return false;
	}

    private function extractTarGz($uuid)
    {

        $tmpDir = Config::$ROUTER_PATH.Config::TMP_DIR_NAME;
        $uploadedFile = $tmpDir."/".$uuid.".tar.gz";
        $mapName = $_POST['map_name'];

        $mapDir = Config::$ROUTER_PATH.Config::MAPS_DIR_NAME;
        $approved = false;
        // 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
        $fileDir = $tmpDir."/".$uuid;


        //解凍
        if (! file_exists ( $fileDir )) { mkdir( $fileDir ); }
        $res = exec('tar xvzf ' . $uploadedFile.' -C '.$fileDir);

        system("rm -f ".$uploadedFile);

        $approved = self::checkMapFile($fileDir);

        if ($approved)
        {
            $fullName = $mapName.'_'.$uuid;
            system('mv "'.$fileDir.'" "'.$mapDir.'/'.$fullName.'"');
            system("rm -rf ".$tmpDir.'/'.$uuid);

            MapManager::addMap($fullName, $mapName);

            echo '{"status":true}';
            return true;

        }

        system("rm -rf ".$tmpDir.'/'.$uuid);

        echo '{"status":false}';
        return false;
    }

	private function checkMapFile($fileDir){

        $approved = false;

        if (file_exists($fileDir.'/'.'config')
            && file_exists($fileDir.'/'.'map'))
        {
            $approved = true;
        }
        else
        {
            $files = scandir($fileDir.'/');
            foreach ($files as $file)
            {
                $dir = $fileDir.'/'.$file;
                if (is_dir($dir) && $file !== '.' && $file !== '..')
                {
                    if (file_exists($dir.'/'.'config')
                        && file_exists($dir.'/'.'map'))
                    {
                        $approved = true;
                        $fileDir = $dir;
                        break;
                    }
                }
            }
        }

        return $approved;

    }

}

?>