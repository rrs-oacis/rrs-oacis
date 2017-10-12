<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/12
 * Time: 11:23
 */

namespace rrsoacis\component\setting\license;


use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;


class SettingsLicenseController extends AbstractController
{

    public function get()
    {

        $license = file_get_contents(Config::$SRC_REAL_URL.'component/setting/license/license.json');
        $license = mb_convert_encoding($license, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $license = json_decode($license, true);


        include (Config::$SRC_REAL_URL . 'component/setting/license/SettingsLicenseView.php');
    }

}
