<?php
use rrsoacis\system\Config;
use rrsoacis\apps\competition\SessionManager;
use rrsoacis\manager\AgentManager;
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Presentation</h3>
    <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
     </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form id="form" action="./presentation-set_score" method="POST"
    class="form-horizontal" enctype="multipart/form-data">
    <div class="box-body">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Session</label>

        <div class="col-sm-10">
		<select class="form-control" name="session" id="session">
			<option value="" selected></option>
		<?php
			$sessions = SessionManager::getSessions();
			foreach ($sessions as $session) {
		?>	
			<option value="<?= $session['alias']?>"><?= $session['alias']?></option>
		<?php } ?>	
		</select>
        </div>
      </div>

	  <?php
  		$agents = AgentManager::getAgents();
		foreach ($agents as $agent) {
	  ?>
		<div class="form-group">
		  <label for="inputEmail3" class="col-sm-2 control-label"><?= $agent['alias']?></label>
		  <div class="col-sm-10">
			  <input type="text" key="<?= $agent['alias']?>" name="<?= base64_encode($agent['alias'])?>" placeholder="score">
		  </div>
		</div>
	  <?php } ?>

    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <!-- <button type="submit" class="btn btn-default">キャンセル</button> -->
      <button type="submit" class="btn btn-info pull-right">Add</button>
    </div>
    <!-- /.box-footer -->
    <input type="hidden" name="action" value="create">
  </form>
  <div id="add_parameter-form-overlay" class="overlay" style="display: none;">
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
$('#add_parameter-form-overlay').show();
$("#add_parameter-form").submit();
 });

//inputのList

document.addEventListener("adf_add_agent", function(){
getAgentParameterList();
}, false);

document.addEventListener("adf_add_map", function(){
    getAgentParameterList();
}, false);

let sessions = {<?php
	foreach ($sessions as $session) {
?>
'<?= base64_encode($session['alias'])?>': '<?= $session['alias']?>',
	<?php } ?>
};

let params = 
<?php
	$present = SessionManager::getPresentations();
?>
<?= json_encode($present);?>
;

let agents = (function() {
	let elem = document.querySelectorAll('input[key]');
	let agents = [];
	for (let i = 0, len = elem.length; i < len; i++) {
		let box = elem[i];
		console.log(box);
		if ('attributes' in box) {
			agents.push(box.attributes.key.value);
		}
	}
	return agents;
})();


document.getElementById('session').addEventListener('change', function(evt) {
	console.log(evt);
	let target = evt.target;
	let item = target.selectedIndex;
	let value = target[item].value;
	for (let key in agents) {
		let elem = document.querySelector('[key="'+ key +'"]');
		if (elem) {
			elem.placeholder = "score";
			console.log(key, elem)
		}
	}

	if (value in params) {
		for (let key in params[value]) {
			let elem = document.querySelector('[key="'+ key +'"]');
			if (elem) {
				elem.placeholder = params[value][key];
			}
			else console.log(key);
		}
	}
});


</script>




