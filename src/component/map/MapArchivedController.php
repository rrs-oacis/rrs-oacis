<?php

namespace rrsoacis\component\map;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\MapManager;

class MapArchivedController extends AbstractController
{
    public function post()
    {

        $map = $_POST['parameter_name'];
        $mapArchived = $_POST['parameter_archived'];

        MapManager::setArchived($map,$mapArchived);


        echo '{"result":"success"}';

    }

}