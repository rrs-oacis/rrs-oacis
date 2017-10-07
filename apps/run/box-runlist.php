<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 15:07
 */
use rrsoacis\system\Config;
?>

<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Run List</h3>
        <div class="box-tools pull-right">
            <button id="run_list_update" type="button" class="btn btn-box-tool" data-toggle="tooltip" data-original-title="Reload">
                <i class="fa fa-refresh"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>

        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="simulation_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Agent</th>
                <th>Map</th>
                <th>Tags</th>
                <th>Progress</th>
                <th>Status</th>
                <th>Score</th>
                <th>TimeStamp</th>
                <th>Log</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <template id="run_list_template">
                <tr class="run_list_tr">
                    <td class="run_name">fewnxbcsiern</td>
                    <td class="run_agent">AIT-Rescue</td>
                    <td class="run_map">Sakae39</td>
                    <td class="run_tag">None</td>
                    <td>
                        <div class="run_progress progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                        </div>
                    </td>
                    <td class="run_status"><span class="label　label-default">None</span></td>
                    <td class="run_score">3220</td>
                    <td class="run_time">3220</td>
                    <td ><a class="run_download" target="_blank" download="Log">Download</a></td>
                </tr>
            </template>
        </table>
    </div>

    <div id="run_list-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>

<script>

    $(function () {

        //$('#simulation_table').DataTable();

        getRunList();


        $('#run_list_update').on('click',function(){
            getRunList();
        });


    });



    function getRunList() {

        //$('#run_list-overlay').show();
        document.querySelector('#run_list-overlay').style.display = '';

        fetch('<?= Config::$TOP_PATH ?>run-get_runlist', {
            method: 'GET'
        })
            .then(function (response) {
                return response.json()
            })
            .then(function (json) {

                //$('#run_list-overlay').hide();
                document.querySelector('#run_list-overlay').style.display = 'none';
                $('#simulation_table').DataTable().destroy();

                setRunTableData(json);


                var table = $('#simulation_table').DataTable({
                    destroy: true,
                    "columnDefs": [
                        {"targets": 0, "searchable": false},    //検索対象外に設定
                        {"targets": 1, "searchable": false},
                        {"targets": 2, "searchable": false},
                        {"targets": 4, "searchable": false},
                        {"targets": 5, "searchable": false},
                        {"targets": 6, "searchable": false},
                        {"targets": 7, "searchable": false},
                        {"targets": 8, "searchable": false},
                        {"targets": [0,3,4,5,8],"orderable": false}
                    ],
                    "order": [ 7, 'desc' ]
                });

                table.draw();

                //table
                    //.rows()
                    //.invalidate()
                    //.draw();

                //$('#simulation_table').DataTable();

                /*$('#simulation_table').DataTable({

                    aoColumns: [
                        { mData: "name", sDefaultContent: "" },
                        { mData: "agent", sDefaultContent: "" },
                        { mData: "map", sDefaultContent: "" }
                    ],

                    bDeferRender: true,

                    sServerMethod: 'GET',

                    sAjaxDataProp: 'data',






                });*/

            });
    }

    function setRunTableData(data)
    {
        var tb = document.querySelector('#simulation_table tbody');
        while (child = tb.lastChild)
        {
            tb.removeChild(child);
        }

        for(var i=0;i<data.length;i++)
        {
            var t = document.querySelector('#run_list_template');

            var simulation = data[i]['simulation'];
            var runId = data[i]['runId'];
            var paramId = data[i]['paramId'];

            var bassURL = location.href.split('/')[2];
            bassURL = bassURL.split(':')[0];


            t.content.querySelector('.run_list_tr').id = data[i]['name'];

            t.content.querySelector('.run_name').innerHTML = '<a target="_blank" href="http://'+bassURL+':3000/runs/'+runId+'">' + data[i]['name'] + '</a>';


            t.content.querySelector('.run_agent').textContent = data[i]['agent'];
            t.content.querySelector('.run_map').textContent = data[i]['map'];
            if(data[i]['tag']!=null || data[i]['tag']!=0){
                t.content.querySelector('.run_tag').textContent = data[i]['tag'];
            }

            t.content.querySelector('.run_score').textContent = data[i]['score'];


            if(data[i]['status']=='created'){
                t.content.querySelector('.run_status').innerHTML = "<span class='label label-warning'>created</span>";
                t.content.querySelector('.run_progress').innerHTML = "<div class='progress-bar progress-bar-striped progress-bar-warning' style='width: 10%'></div>";
                t.content.querySelector('.run_progress').classList.add('active');
            }else if(data[i]['status']=='submitted'){
                t.content.querySelector('.run_status').innerHTML = "<span class='label label-primary'>submitted</span>";
                t.content.querySelector('.run_progress').innerHTML = "<div class='progress-bar progress-bar-striped progress-bar-primary' style='width: 25%'></div>";
                t.content.querySelector('.run_progress').classList.add('active');
            } else if(data[i]['status']=='running'){
                t.content.querySelector('.run_status').innerHTML = "<span class='label label-primary'>running</span>";
                t.content.querySelector('.run_progress').innerHTML = "<div class='progress-bar progress-bar-striped progress-bar-primary' style='width: 50%'></div>";
                t.content.querySelector('.run_progress').classList.add('active');
            }else if(data[i]['status']=='failed'){
                t.content.querySelector('.run_status').innerHTML = "<span class='label label-danger'>failed</span>";
                t.content.querySelector('.run_progress').innerHTML = "<div class='progress-bar progress-bar-danger' style='width: 100%'></div>";
            }else if(data[i]['status']=='finished'){
                t.content.querySelector('.run_status').innerHTML = "<span class='label label-success'>finished</span>";
                t.content.querySelector('.run_progress').innerHTML = "<div class='progress-bar progress-bar-success' style='width: 100%'></div>";
            }


            t.content.querySelector('.run_time').textContent = data[i]['timestamp'];



            t.content.querySelector('.run_download').href = "http://"+bassURL+":3000/Result_development/"+simulation+"/"+paramId+"/"+runId+".tar.bz2";

            //t.content.querySelector('.map_list_fullname').textContent = data[i]['name'];
            //t.content.querySelector('.map_list_timestamp').textContent = data[i]['timestamp'];

            var clone = document.importNode(t.content, true);
            tb.appendChild(clone);
        }

    }

</script>

<style>

    .table>thead:first-child>tr:first-child>th:focus {
        outline: 0;
    }

</style>