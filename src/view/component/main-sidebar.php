<?php
use adf\Config;
?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    
    <!-- search form -->
    <!-- 
    <form action="./search.php" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="検索..."> <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat">
            <i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form>
     -->
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
      <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> <span><?= _l("adf.dashboard"); ?></span></a></li>
      <li><a href="<?=Config::$TOP_PATH ?>agents"><i class="fa fa-book"></i> <span><?= _l("adf.agent_list"); ?></span></a></li>
      <li><a href="<?=Config::$TOP_PATH ?>setting"><i class="fa fa-gear"></i> <span><?= _l("adf.setting"); ?></span></a></li>
       <li><a href="<?=getOacisURL();?>" target="_blank"><i class="fa fa-external-link"></i> <span><?= _l("adf.oacis"); ?></span></a></li>
      
      <!-- 
      <li><a href="./week.php"> <i class="fa fa-table"></i> <span>予定</span> <span class="pull-right-container"> 
            <small id="sidebar-week-label-green" class="label pull-right bg-green">-</small>
            <small id="sidebar-week-label-red" class="label pull-right bg-red">-</small>
            <small  id="sidebar-week-label-blue"class="label pull-right bg-blue">-</small>
        </span>
      </a></li>
      
      <li><a href="./month.php"> <i class="fa fa-calendar"></i> <span>hoge</span> <span class="pull-right-container"> 
            <small id="sidebar-month-label-green" class="label pull-right bg-green">-</small>
            <small id="sidebar-month-label-red" class="label pull-right bg-red">-</small>
            <small id="sidebar-month-label-blue" class="label pull-right bg-blue">-</small>
        </span>
      </a></li>
       -->
           
      <!-- 
      <li class="header">ラベル</li>
      <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>達成</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>期限</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-green"></i> <span>達成</span></a></li>
       -->
      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>