<?php
namespace rrsoacis\apps\results;

use rrsoacis\component\common\AbstractController;

class ResultsMainController extends AbstractController
{
    public function get()
    {
        include(dirname(__FILE__).'/ResultsMainView.php');
    }
}
?>
