<?php
use rrsoacis\system\Config;
use rrsoacis\manager\AppManager;
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
      <!-- <li class="header">MAIN NAVIGATION</li> -->
      <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
      <li><a href="<?=Config::$TOP_PATH ?>agents"><i class="fa fa-android"></i> <span>Agents</span></a></li>
      <li><a href="<?=Config::$TOP_PATH ?>maps"><i class="fa fa-map"></i> <span>Maps</span></a></li>
        <?php
        foreach (AppManager::getConnectedApps() as $appDisplayOnSidebar) {

            $packageName = preg_split('/[\\/]/',$appDisplayOnSidebar['package'])[1];

            echo '<li><a href="' . Config::$TOP_PATH . $packageName . '"><i class="fa ' . $appDisplayOnSidebar['icon'] . '"></i> <span>' . $appDisplayOnSidebar['name'] . '</span></a></li>';
        }
        ?>
      <li><a href="<?=Config::$TOP_PATH ?>settings"><i class="fa fa-gear"></i> <span>Settings</span></a></li>
       <li><a href="javascript:void(0);" onclick="window.open('http://'+location.host.replace(location.port,3000));"><i class="fa fa-external-link"></i> <span>OACIS</span></a></li>
      
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
