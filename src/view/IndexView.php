<?php 
use adf\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Index"; ?>
<?php include 'component/head.php';?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include 'component/main-header.php';?>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <?php include 'component/main-sidebar.php';?>
  
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ダッシュボード
        <small>操作画面</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Index</a></li>
        <li><a href="#">ダッシュボード</a></li>
        <li class="active">エージェントを追加</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">エージェントを追加</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form id="post-form" action="./agent/upload" method="POST"  class="form-horizontal" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">エージェント名</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" id="inputTitle" placeholder="name" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">ファイル</label>

                  <div class="col-sm-10">
                  	<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                    <input id="lefile" type="file" class="form-control" name="userfile" style="display:none"  />
                    <div class="input-group">
                    <input type="text" id="photoCover" class="form-control readonly" placeholder="選択されていません" required>
  					<span class="input-group-btn"><button type="button" class="btn btn-info" onclick="$('input[id=lefile]').click();">Browse</button></span>
  					</div>
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default">キャンセル</button>
                <button type="submit" class="btn btn-info pull-right">追加</button>
              </div>
              <!-- /.box-footer -->
              <input type="hidden" name="action" value="create">
            </form>
            <div id="form-overlay" class="overlay" style="display: none;">
              <i class="fa fa-refresh fa-spin"></i>
            </div>   
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

  <?php include 'component/main-footer.php';?>
  
  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include 'component/footerscript.php';?>
<!-- SlimScroll -->
<script src="./plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE for demo purposes -->

<!-- <script src="./dist/js/demo.js"></script> -->

<script>
$(".readonly").keydown(function(e){
    e.preventDefault();
});
  $('input[id=lefile]').change(function() {
    $('#photoCover').val($(this).val());
  });
</script>

<script type="text/javascript">

/*
$("#post-form").submit(function(e){

	$('#form-overlay').show();
	e.preventDefault(); 
	var form = document.querySelector('#post-form');
	fetch('<?=$_SERVER["PHP_SELF"] ?>/../action.php', {
	    method: 'POST',
	    body: new FormData(form)
	  })
	  .then(function(response) {
	    return response.json()
	  })
	  .then(function(json) {
		  $('#form-overlay').hide();
      if(json["result"]=="success"){
    	  toastr.success(json["title"],"登録完了");
    	  var form = document.querySelector('#post-form');
    	  $(form).find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
      }
	    console.log(json);
	    
	  });
    
	
 });
*/


</script>
</body>
</html>


<?php 
/*
<!DOCTYPE htmm">
<html>
<head>
<title>RRS - ADF</title>
</head>

<body>

Hello World!



//$test = new adf\test\Test2 ();

//$test->hello ();

?>

<form enctype="multipart/form-data" action="./agent/upload" method="POST">
    <!-- MAX_FILE_SIZE は、必ず "file" input フィールドより前になければなりません -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- input 要素の name 属性の値が、$_FILES 配列のキーになります -->
    このファイルをアップロード: <input name="userfile" type="file" />
    <input type="submit" value="ファイルを送信" />
</form>
</body>
</html>

 */?>