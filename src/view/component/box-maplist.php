<?php
use adf\Config;
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?= _l("adf.map_list"); ?></h3>

        <div class="box-tools">
          <div class="input-group input-group-sm" style="width: 150px;">
            <input type="text" name="table_search"
              class="form-control pull-right" placeholder="Search">

            <div class="input-group-btn">
              <button type="submit" class="btn btn-default">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table id="map_list" class="table table-hover">
        <thead>
          <tr>
            <th><?= _l("adf.maps_list_box.uuid"); ?></th>
            <th><?= _l("adf.maps_list_box.name"); ?></th>
            <th><?= _l("adf.maps_list_box.upload_day"); ?></th>
            <th><?= _l("adf.maps_list_box.status"); ?></th>
            <th><?= _l("adf.maps_list_box.link"); ?></th>
          </tr>
          </thead>
          <tbody>
                
                
                
                
                </tbody>
                <template id="map_list_template">
                <tr>
                  <td class="map_list_uuid">
                  </td>
                  <td class="map_list_name"></td>
                  <td class="map_list_upload_date"></td>
                  <td><span class="map_list_status label">Approved</span></td>
                  <td>
                  <a href="<?= "" //Config::$TOP_PATH ?>map/<?= "" //$map["uuid"]?>">
                  <?= _l("adf.maps_list_box.details"); ?>
                  </a>
                  </td>
                  
                </tr>
                </template>
              </table>
      </div>
      <!-- /.box-body -->
      <div id="map_list-overlay" class="overlay" style="display: none;">
      <i class="fa fa-refresh fa-spin"></i>
      </div>
    </div>
    <!-- /.box -->
    
  </div>
</div>


<script>
document.addEventListener("adf_add_map", function(){
  //alert("custom event fire!!");
	getMapList();
}, false);

$(function(){
  // 処理
  getMapList();
    
});

function getMapList(){

	$('#map_list-overlay').show();
  
	fetch('<?= Config::$TOP_PATH ?>maps_get', {
        method: 'GET'
      })
      .then(function(response) {
        return response.json()
      })
      .then(function(json) {
          
        $('#map_list-overlay').hide();
        
        setMapTableData(json);

      });
}

function setMapTableData(date){

	var tb = document.querySelector('#map_list tbody');
  while (child = tb.lastChild) tb.removeChild(child);

  for(var i=0;i<date.length;i++){
    
	  
	  var t = document.querySelector('#map_list_template');
    
	  t.content.querySelector('.map_list_uuid').textContent = date[i]['uuid'];
	  t.content.querySelector('.map_list_name').textContent = date[i]['name'];
	  t.content.querySelector('.map_list_upload_date').textContent = date[i]['upload_date'];
	  t.content.querySelector('a').href = '<?= Config::$TOP_PATH ?>map/'+date[i]['uuid'];
      if(date[i]['status']){
        t.content.querySelector('.map_list_status').classList.add('label-success');
        t.content.querySelector('.map_list_status').classList.remove('label-danger');
        t.content.querySelector('.map_list_status').textContent = 'Approved';
      }else{
    	t.content.querySelector('.map_list_status').classList.add('label-danger');
    	t.content.querySelector('.map_list_status').classList.remove('label-success');
        t.content.querySelector('.map_list_status').textContent = 'Invalid';
      }
	  

	  var clone = document.importNode(t.content, true);
	  tb.appendChild(clone);
	  }
	  
}

</script>