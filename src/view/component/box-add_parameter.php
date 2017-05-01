<?php
use adf\Config;
?>
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title"><?= _l("adf.add_agent_box.add_parameter"); ?></h3>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form id="add_parameter-form" action="./add_parameter" method="POST"
    class="form-horizontal" enctype="multipart/form-data">
    <div class="box-body">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label"><?= _l("adf.add_agent_box.parameter_name"); ?></label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="parameter_name"
            placeholder="<?= _l("adf.add_agent_box.input_name"); ?>"
            required>
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label"><?= _l("adf.add_agent_box.parameter_simulator_id"); ?></label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="parameter_simulator_id"
            placeholder="<?= _l("adf.add_agent_box.input_name"); ?>"
            >
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label"><?= _l("adf.add_agent_box.parameter_map"); ?></label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="parameter_map"
            placeholder="<?= _l("adf.add_agent_box.input_name"); ?>"
            required>
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label"><?= _l("adf.add_agent_box.parameter_agent"); ?></label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="parameter_agent"
            placeholder="<?= _l("adf.add_agent_box.input_name"); ?>"
            required
            autocomplete="on" list="agent_keyword">
        </div>
        <datalist id="agent_keyword">
        </datalist>
        <template id="agent_keyword_option">
          <option value="hoge" />
        </template>
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

  
  $("#add_parameter-form").submit(function(e){

		$('#form-overlay').show();
		e.preventDefault(); 
		var form = document.querySelector('#add_parameter-form');
		fetch('./add_parameter', {
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

		    //dispatchAddAgentEvent();
		    
		  });
	    
		
	 });

  //inputのList
  
  document.addEventListener("adf_add_agent", function(){
    getAgentParameterList();
  }, false);

  
$(function(){
    // 処理
    getAgentParameterList();
      
});

function getAgentParameterList(){

	fetch('<?= Config::$TOP_PATH ?>agents_get', {
        method: 'GET'
      })
      .then(function(response) {
        return response.json()
      })
      .then(function(json) {
          
        setAgentListOptionData(json);

      });
    
}



function setAgentListOptionData(date){

    var tb = document.querySelector('#agent_keyword');
    while (child = tb.lastChild) tb.removeChild(child);

    for(var i=0;i<date.length;i++){
      
      
      var t = document.querySelector('#agent_keyword_option');
      
      t.content.querySelector('option').value = date[i]['name'] +'_'+ date[i]['uuid'];

      var clone = document.importNode(t.content, true);
      tb.appendChild(clone);
      }
      
}
  
</script>