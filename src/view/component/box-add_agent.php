<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title"><?= _l("adf.add_agent_box.add_agent"); ?></h3>
    <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
     </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form id="post-form" action="./agent_upload" method="POST"
    class="form-horizontal" enctype="multipart/form-data">
    <div class="box-body">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label"><?= _l("adf.add_agent_box.agent_name"); ?></label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="agent_name"
            id="inputTitle"
            placeholder="<?= _l("adf.add_agent_box.input_name"); ?>"
            required>
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label"><?= _l("adf.add_agent_box.agent_file"); ?></label>

        <div class="col-sm-10">
          <div style="position: relative;">
            <input type="hidden" name="MAX_FILE_SIZE" value="1073741824" />
            <input id="lefile" type="file" class="form-control"
              name="userfile" style="position: absolute;" required />
            <div class="input-group" style="position: absolute;">
              <input type="text" id="photoCover"
                class="form-control readonly"
                placeholder="<?= _l("adf.add_agent_box.input_not_file"); ?>"
                disabled> 
                <span class="input-group-btn">
                  <button
                    type="button" class="btn btn-info"
                    onclick="$('input[id=lefile]').click();"><?= _l("adf.add_agent_box.input_browse"); ?>
                  </button>
                </span>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <!-- <button type="submit" class="btn btn-default">キャンセル</button> -->
      <button type="submit" class="btn btn-info pull-right"><?= _l("adf.add_agent_box.input_add"); ?></button>
    </div>
    <!-- /.box-footer -->
    <input type="hidden" name="action" value="create">
  </form>
  <div id="form-overlay" class="overlay" style="display: none;">
    <i class="fa fa-refresh fa-spin"></i>
  </div>
</div>
<!-- /.box -->

<script>

$(".readonly").keydown(function(e){
    e.preventDefault();
});
  $('input[id=lefile]').change(function() {
    $('#photoCover').val($(this).val());
  });

  $("#post-form").submit(function(e){

	    

		$('#form-overlay').show();
		e.preventDefault(); 
		var form = document.querySelector('#post-form');
		fetch('./agent_upload', {
		    method: 'POST',
		    body: new FormData(form)
		  })
		  .then(function(response) {
		    return response.json()
		  })
		  .then(function(json) {
			  console.log(json);
			  $('#form-overlay').hide();
	      if(json["result"]=="success"){
	    	  //toastr.success(json["title"],"登録完了");
	    	  //var form = document.querySelector('#post-form');
	    	  //$(form).find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
	      }
		    console.log(json);

            if(json['status']){
            	toastr["success"](
                        "<?= _l("adf.add_agent_box.toastr_addagent"); ?>",
    		    		"<?= _l("adf.add_agent_box.toastr_success"); ?>");
            }else{
            	toastr["error"](
                        "<?= _l("adf.add_agent_box.toastr_invalid_addagent"); ?>",
    		    		"<?= _l("adf.add_agent_box.toastr_error"); ?>");
            }
		    
		    dispatchAddAgentEvent();
		    
		  });
	    
		
	 });



function dispatchAddAgentEvent(){
	var customEvent = document.createEvent("HTMLEvents");
	customEvent.initEvent("adf_add_agent", true, false);
	//fire!!
	document.dispatchEvent(customEvent); 
}
</script>