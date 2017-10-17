<?php
use rrsoacis\system\Config;
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Map List</h3>

          <!--
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
        -->
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table id="map_list" class="table table-hover">
        <thead>
          <tr>
              <th>Name</th>
              <th>FullName</th>
              <th>Timestamp</th>
          </tr>
          </thead>
          <tbody>
                
                
                
                
                </tbody>
                <template id="map_list_template">
                <tr>
                    <td class="map_list_name"></td>
                    <td class="map_list_fullname"></td>
                    <td class="map_list_timestamp"></td>
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
        method: 'GET', credentials: "include"
      })
      .then(function(response) {
        return response.json()
      })
      .then(function(json) {
          
        $('#map_list-overlay').hide();
        
        setMapTableData(json);

      });
}

function setMapTableData(data)
{
    var tb = document.querySelector('#map_list tbody');
    while (child = tb.lastChild)
    {
        tb.removeChild(child);
    }

    for(var i=0;i<data.length;i++)
    {
        var t = document.querySelector('#map_list_template');

        t.content.querySelector('.map_list_name').textContent = data[i]['alias'];
        t.content.querySelector('.map_list_fullname').textContent = data[i]['name'];
        t.content.querySelector('.map_list_timestamp').textContent = data[i]['timestamp'];

        var clone = document.importNode(t.content, true);
        tb.appendChild(clone);
    }

}

</script>