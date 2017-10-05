<?php

namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

class AppManager
{
    const APPS_DIR = "apps";
    const APPS_MANIFEST_FILE = "manifest.json";

	/**
	 * generate apps list
	 * @return App[] json
	 *
	 * */
	public static function getApps()
	{
	    $db = self::connectDB();
        $sth = $db->query("select package from connectedApp;");
        $connectedApps = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
             $connectedApps[] = $row['package'];
        }


		if (! file_exists(Config::$SRC_REAL_URL.self::APPS_DIR))
		{
			mkdir(Config::$SRC_REAL_URL.self::APPS_DIR,0777,true);
		}

		$files = scandir(Config::$SRC_REAL_URL.self::APPS_DIR);

		$apps = [];
		foreach ($files as $file)
		{
            if ($file === '.' || $file === '..' ) { continue; }

            $manifestFile = Config::$SRC_REAL_URL.self::APPS_DIR."/".$file."/".self::APPS_MANIFEST_FILE;
			if (file_exists($manifestFile))
			{
				// Jsonデータを取得
				$json = file_get_contents($manifestFile);
				//$json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );

				$app = json_decode($json, true);
                $app['package'] = $file;
                $app['enabled'] = in_array($app['package'], $connectedApps);

				$apps[] = $app;
			}
		}

		return $apps;
	}

    /**
     *
     * */
    public static function getConnectedApps()
	{
        $connectedApps = [ ];
        $db = self::connectDB();
        $sth = $db->query("select package from connectedApp;");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $connectedApps [] = $row['package'];
        }

        $apps = [ ];
        foreach ($connectedApps as $packageName)
        {
            if ($packageName === '.' || $packageName === '..' ) { continue; }

            $manifestFile = Config::$SRC_REAL_URL.self::APPS_DIR."/".$packageName."/".self::APPS_MANIFEST_FILE;
            if (file_exists($manifestFile))
            {
                // Jsonデータを取得
                $json = file_get_contents($manifestFile);
                //$json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );

                $app = json_decode($json, true);
                $app['package'] = $packageName;
                $app['enabled'] = in_array($app['package'], $connectedApps);
                //$obj ['upload_date'] = date( "Y年m月d日 H時i分s秒", $obj ['upload_date'] );

                //$agent = new Agent();
                //$agent->setJson($json);

                $apps [] = $app;
            }
        }

        return $apps;
    }


    /**
     *
     * */
    public static function getApp($packageName)
    {
        if (! file_exists(Config::$SRC_REAL_URL.self::APPS_DIR))
        {
            mkdir(Config::$SRC_REAL_URL.self::APPS_DIR,0777,true);
        }

        $manifestFile = Config::$SRC_REAL_URL.self::APPS_DIR."/".$packageName."/".self::APPS_MANIFEST_FILE;
        if (file_exists($manifestFile))
        {
            // Jsonデータを取得
            $json = file_get_contents($manifestFile);
            //$json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );

            $app = json_decode( $json, true );
            $app['package'] = $packageName;


            $db = self::connectDB();
            $sth = $db->prepare("select count(*) as count from connectedApp where package=:package;");
            $sth->bindValue(':package', $app['package'], PDO::PARAM_STR);
            $sth->execute();
            $app['enabled'] = ($sth->fetch(PDO::FETCH_ASSOC)['count'] == 1);

            //$obj ['upload_date'] = date( "Y年m月d日 H時i分s秒", $obj ['upload_date'] );
            //$agent = new Agent();
            //$agent->setJson($json);
        }

        return $app;
    }

    public static function setEnable($packageName)
    {
        $db = self::connectDB();
        $sth = $db->prepare("delete from connectedApp where package=:package;");
        $sth->bindValue(':package', $packageName, PDO::PARAM_STR);
        $sth->execute();
        $sth = $db->prepare("insert into connectedApp(package) values(:package);");
        $sth->bindValue(':package', $packageName, PDO::PARAM_STR);
        $sth->execute();
    }

    public static function setDisable($packageName)
    {
        $db = self::connectDB();
        $sth = $db->prepare("delete from connectedApp where package=:package;");
        $sth->bindValue(':package', $packageName, PDO::PARAM_STR);
        $sth->execute();
    }

    /**
     *
     * */
    private static function connectDB()
	{
        $db = new PDO('sqlite:'.Config::$ROUTER_PATH.Config::MAIN_DATABASE);
        $connectedAppVersion = 0;
        $sth = $db->query("select value from system where name='connectedAppVersion';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $connectedAppVersion = $row['value'];
        }

        switch ($connectedAppVersion)
        {
            case 0:
                $db->query("insert into system(name,value) values('connectedAppVersion', 1);");
                $db->query("create table connectedApp(package);");
                $version = 1;

                $sth = $db->prepare("update system set value=:value where name='connectedAppVersion';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
	}
}
?>
