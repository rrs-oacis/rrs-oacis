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
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width: 25%"></div>
                        </div>
                    </td>
                    <td><span class="label label-success">Success</span></td>
                    <td class="run_score">3220</td>
                    <td class="run_time">3220</td>
                    <td ><a class="run_download">Download</a></td>
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



    });


    function getRunList() {

        $('#run_list-overlay').show();

        $('#simulation_table').DataTable().destroy();

        fetch('<?= Config::$TOP_PATH ?>run-get_runlist', {
            method: 'GET'
        })
            .then(function (response) {
                return response.json()
            })
            .then(function (json) {

                $('#run_list-overlay').hide();

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

            t.content.querySelector('.run_list_tr').id = data[i]['name'];

            t.content.querySelector('.run_name').textContent = data[i]['name'];
            t.content.querySelector('.run_agent').textContent = data[i]['agent'];
            t.content.querySelector('.run_map').textContent = data[i]['map'];
            if(data[i]['tag']!=null || data[i]['tag']!=0){
                t.content.querySelector('.run_tag').textContent = data[i]['tag'];
            }

            t.content.querySelector('.run_score').textContent = data[i]['score'];
            t.content.querySelector('.run_time').textContent = data[i]['timestamp'];

            var simulation = data[i]['simulation'];
            var runId = data[i]['runId'];
            var paramId = data[i]['paramId'];

            t.content.querySelector('.run_download').href = "localhost:3000/Result_development/"+simulation+"/"+paramId+"/"+runId+".tar.bz2";

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