<?php
use adf\Config;
?>
<script type="text/javascript">
var PAGESIZE = 1;
</script>
<header class="main-header">
  <!-- Logo -->
  <a href="<?=Config::$TOP_PATH ?>" class="logo"> <!-- mini logo for sidebar mini 50x50 pixels --> <span class="logo-mini"><?= Config::APP_SHORT_NAME?></span> <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><?= Config::APP_NAME ?></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span
      class="icon-bar"></span> <span class="icon-bar"></span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- 通知 Notifications: style can be found in dropdown.less -->
        <!-- <li class="dropdown notifications-menu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell-o"></i> <span class="label label-warning">10</span>
        </a>
          <ul class="dropdown-menu">
            <li class="header">You have 10 notifications</li>
            <li>
              <ul class="menu">
                <li><a href="#"> <i class="fa fa-users text-aqua"></i> 5 new members joined today
                </a></li>
              </ul>
            </li>
            <li class="footer"><a href="#">View all</a></li>
          </ul></li> -->
        <!-- タスク Tasks: style can be found in dropdown.less -->
        <!-- <li class="dropdown tasks-menu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-flag-o"></i> <span id="header-taskout-size-icon" class="label label-danger"></span>
        </a>
          <ul class="dropdown-menu">
            <li id="header-taskout-size-header" class="header">期限切れの予定はありません</li>
            <li>
              <ul class="menu">
                <li>
                </li>
              </ul>
            </li>
          </ul></li> -->
          
        <!-- ユーザーUser Account: style can be found in dropdown.less -->
        <!-- 
        <li class="dropdown user user-menu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="./dist/img/avatar04.png" class="user-image"
            alt="User Image"> <span class="hidden-xs"><?= USER_NAME ?></span>
        </a>
          <ul class="dropdown-menu">
           -->
            <!-- User image -->
            <!-- 
            <li class="user-header"><img src="./dist/img/avatar04.png" class="img-circle" alt="User Image">
              <p>
<?= USER_NAME ?>
<small>Hoge</small>
              </p></li>
            <!-- Menu Footer-->
            <!-- 
            <li class="user-footer">
              <div class="pull-right">
                <form action="./action.php" method="post">
                  <input type="hidden" name="action" value="logout">
                  <button type="submit" class="btn btn-default btn-flat">Hoge</button>
                </form>
              </div>
            </li>
            
          </ul></li>
           -->
      </ul>
    </div>
  </nav>
</header>
