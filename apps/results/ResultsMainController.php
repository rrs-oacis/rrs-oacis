<?php
namespace adf\apps\results;

use adf\controller\AbstractController;

class ResultsMainController extends AbstractController
{
    public function get()
    {
        include(dirname(__FILE__).'/ResultsMainView.php');
    }
}
?>
