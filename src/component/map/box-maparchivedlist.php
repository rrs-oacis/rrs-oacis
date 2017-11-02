<?php
use rrsoacis\system\Config;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Archived Map List</h3>

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
                <table id="map_archived_list" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>FullName</th>
                        <th>Timestamp</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <template id="map_archived_list_template">
                        <tr>
                            <a>
                                <td class="map_list_name"></td>
                                <td class="map_list_fullname"></td>
                                <td class="map_list_timestamp"></td>
                            </a>
                        </tr>
                    </template>
                </table>
            </div>
            <!-- /.box-body -->
            <div id="map_archived_list-overlay" class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
        <!-- /.box -->

    </div>
</div>


<script>
    document.addEventListener("adf_add_map", function(){
        //alert("custom event fire!!");
        getMapArchivedList();
    }, false);

    $(function(){
        getMapArchivedList();
    });

    function getMapArchivedList(){

        $('#map_archived_list-overlay').show();

        fetch('<?= Config::$TOP_PATH ?>maps_archived_get', {
            method: 'GET', credentials: "include"
        })
            .then(function(response) {
                return response.json()
            })
            .then(function(json) {

                $('#map_archived_list-overlay').hide();

                setTableMapArchivedData(json);

            });
    }

    function setTableMapArchivedData(data)
    {

        var tb = document.querySelector('#map_archived_list tbody');
        while (child = tb.lastChild)
        { tb.removeChild(child); }

        for(var i=0;i<data.length;i++)
        {
            var t = document.querySelector('#map_archived_list_template');

            //t.content.querySelector('.map_list_name').textContent = data[i]['alias'];

            t.content.querySelector('.map_list_name').textContent = data[i]['alias'];
            t.content.querySelector('.map_list_fullname').innerHTML =
                '<a target="_blank" href="<?= Config::$TOP_PATH ?>map/'+data[i]['name'] + '">' +  data[i]['name'] + "</a>";
            t.content.querySelector('.map_list_timestamp').textContent = data[i]['timestamp'];
            t.content.querySelector('a').href = '<?= Config::$TOP_PATH ?>map/'+data[i]['name'];
            /*
            if(data[i]['status'])
            {
                t.content.querySelector('.map_list_status').classList.add('label-success');
                t.content.querySelector('.map_list_status').classList.remove('label-danger');
                t.content.querySelector('.map_list_status').textContent = 'Approved';
            }else{
                t.content.querySelector('.map_list_status').classList.add('label-danger');
                t.content.querySelector('.map_list_status').classList.remove('label-success');
                t.content.querySelector('.map_list_status').textContent = 'Invalid';
            }
            */

            var clone = document.importNode(t.content, true);
            tb.appendChild(clone);
        }

    }

</script>
