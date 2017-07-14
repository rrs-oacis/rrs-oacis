<?php
use adf\Config;
use adf\apps\competition\SessionManager;
?>

<?php

$data = SessionManager::getSessions();

for($i=0;$i<count($data);$i++){

?>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">
      <?=$data[$i]["alias"]?>
      <small><?=$data[$i]["name"]?>
      <?php  if($data[$i]["precursor"]!=null)  echo ",  precursor " . $data[$i]["precursor"]?>
      </small>
    </h3>
    <div class="box-tools pull-right">
        <?php if($data[$i]["precursor"]==null){?>
          <a href="<?= Config::$TOP_PATH ?>results-download/<?=$data[$i]["name"]?>"
        <?php }else{ ?>
          <a href="<?= Config::$TOP_PATH ?>results-download/<?=$data[$i]["name"]?>/<?= $data[$i]["precursor"]?>"
        <?php }?>
        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Download result html file">
          <i class="fa fa-download"></i></button>
          </a>
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
          <i class="fa fa-minus"></i>
        </button>
     </div>
  </div>
  <!-- /.box-header -->
  
  <div style="margin: 10px;">
  
  <?php if($data[$i]["precursor"]==null){?>
    <iframe src="<?= Config::$TOP_PATH ?>results-result/<?=$data[$i]["name"]?>"
  <?php }else{ ?>
    <iframe src="<?= Config::$TOP_PATH ?>results-result/<?=$data[$i]["name"]?>/<?= $data[$i]["precursor"]?>"
  <?php }?>
    frameborder="0"
    height="100%"
    width="100%"
    
    style="
      overflow:hidden;
      overflow-x:hidden;
      overflow-y:hidden;
      height:100%;
      width:100%;
      position:relative;
      top:0px;
      left:0px;
      right:0px;
      bottom:0px;"
  >
    
  </iframe>

  </div>

  <div id="add_parameter-form-overlay" class="overlay" style="display: none;">
    <i class="fa fa-refresh fa-spin"></i>
  </div>
</div>
<!-- /.box -->

<?php
}
?>

<script>

$('iframe')
.on('load', function(){
  try {  
    $(this).height(0);
    $(this).height(this.contentWindow.document.documentElement.scrollHeight);
  } catch (e) {
  }
})
.trigger('load');
  
</script>




