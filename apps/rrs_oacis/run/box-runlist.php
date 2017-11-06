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
            <button id="run_list_update" type="button" class="btn btn-box-tool" data-toggle="tooltip"
                    data-original-title="Reload">
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
                            <div class="progress-bar progress-bar-striped progress-bar-danger" style="width: 0%"></div>
                        </div>
                    </td>
                    <td class="run_status"><span class="label　label-default">None</span></td>
                    <td class="run_score">3220</td>
                    <td class="run_time">3220</td>
                    <td><a class="run_download" target="_blank" download="Log">Download</a></td>
                </tr>
            </template>
        </table>
    </div>

    <div id="run_list-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>

<script>

    var runUpdateList = new Set();

    $(function () {

        //$('#simulation_table').DataTable();

        getRunList();


        $('#run_list_update').on('click', function () {
            getRunList();
        });


        setInterval(updateCellData, 12000);


    });

    function updateCellData(quick){
        for (let item of runUpdateList) {


            //console.log(item);

            var sTime = quick ? Math.floor( Math.random()*100):Math.floor( Math.random()*10000 + Math.random()*1000);

            setTimeout( function () {


                fetch('<?= Config::$TOP_PATH ?>run-get_run/'+item, {
                    method: 'GET', credentials: "include"
                }).then(function (response) {
                    return response.json()
                }).then(function (json) {

                    //console.log(json);

                    setProgress(
                        document.querySelector('#runid'+item+' .run_status'),
                        document.querySelector('#runid'+item+' .run_progress .progress-bar'),
                        json['status'],
                        json['host']
                    );

                    if(json['status'] == 'failed' || json == 'finished'){
                        runUpdateList.delete(item);
                    }


                    document.querySelector('#runid'+item+' .run_score').textContent = json['score'];

                    var runId = json['runId'];
                    var bassURL = location.href.split('/')[2];
                    bassURL = bassURL.split(':')[0];

                    if(runId != null){
                        document.querySelector('#runid'+item+' .run_name').innerHTML = '<a target="_blank" href="http://' + bassURL + ':3000/runs/' + runId + '">' + json['name'] + '</a>';
                    }else{
                        document.querySelector('#runid'+item+' .run_name').innerHTML = json['name'];
                    }

                });


            } , sTime );

            //runUpdateList.splice(i, 1);

        }
    }


    function getRunList() {

        //$('#run_list-overlay').show();
        document.querySelector('#run_list-overlay').style.display = '';

        fetch('<?= Config::$TOP_PATH ?>run-get_runlist', {
            method: 'GET', credentials: "include"
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
                        {"targets": [0, 3, 4, 5, 8], "orderable": false}
                    ],
                    "order": [7, 'desc'],
                    responsive: true
                });



                table.on( 'draw', function (e) {

                    //var rowNode = table.rows();

                    runUpdateList = new Set();

                    $('#simulation_table').find("tr:visible").each(function (){

                        var cellData = $(this).find(".run_name").text();
                        var cellstatus = $(this).find(".label").text();

                        if (!(cellstatus == 'failed' || cellstatus == 'finished')) {

                            if (cellData != '') runUpdateList.add(cellData);

                        }

                    });

                    console.log(runUpdateList);


                } );

                table.draw();

                updateCellData(true);
                
            });
    }

    function setRunTableData(data) {
        var tb = document.querySelector('#simulation_table tbody');
        while (child = tb.lastChild) {
            tb.removeChild(child);
        }

        for (var i = 0; i < data.length; i++) {
            var t = document.querySelector('#run_list_template');

            var simulation = data[i]['simulation'];
            var runId = data[i]['runId'];
            var paramId = data[i]['paramId'];

            var bassURL = location.href.split('/')[2];
            bassURL = bassURL.split(':')[0];


            t.content.querySelector('.run_list_tr').id = 'runid'+data[i]['name'];

            if(runId != null){
                t.content.querySelector('.run_name').innerHTML = '<a target="_blank" href="http://' + bassURL + ':3000/runs/' + runId + '">' + data[i]['name'] + '</a>';
            }else{
                t.content.querySelector('.run_name').innerHTML = data[i]['name'];
            }

            t.content.querySelector('.run_agent').innerHTML =
                '<a target="_blank" href="<?= Config::$TOP_PATH ?>agent/'+data[i]['agent'] + '">' +  data[i]['agent'] + "</a>";

            t.content.querySelector('.run_map').innerHTML =
                '<a target="_blank" href="<?= Config::$TOP_PATH ?>map/'+data[i]['map'] + '">' +  data[i]['map'] + "</a>";

            if (data[i]['tag'] != null || data[i]['tag'] != 0) {
                t.content.querySelector('.run_tag').textContent = data[i]['tag'];
            }

            t.content.querySelector('.run_score').textContent = data[i]['score'];

            setProgress(
                t.content.querySelector('.run_status'),
                t.content.querySelector('.run_progress .progress-bar'),
                data[i]['status'],
                null
            );


            t.content.querySelector('.run_time').textContent = data[i]['timestamp'];


            t.content.querySelector('.run_download').href = "http://" + bassURL + ":3000/Result_development/" + simulation + "/" + paramId + "/" + runId + ".tar.bz2";

            //t.content.querySelector('.map_list_fullname').textContent = data[i]['name'];
            //t.content.querySelector('.map_list_timestamp').textContent = data[i]['timestamp'];

            var clone = document.importNode(t.content, true);
            tb.appendChild(clone);


            //SetUpdate
            if (!(data[i]['status'] == 'failed' || data[i]['status'] == 'finished')) {
                //runUpdateList.add(data[i]['name']);
            }

        }

    }

    function setProgress(pElementsS,pElementsP,pStatus,pHost){

        pElementsP.parentNode.classList.remove('active');

        pElementsP.classList.remove('progress-bar-warning');
        pElementsP.classList.remove('progress-bar-primary');
        pElementsP.classList.remove('progress-bar-danger');
        pElementsP.classList.remove('progress-bar-success');


        if (pStatus == 'created') {
            pElementsS.innerHTML = "<span class='label label-warning'>created</span>";
            pElementsP.classList.add('progress-bar-warning');
            pElementsP.style.width = '10%';
            //pElementsP.innerHTML = "<div class='progress-bar progress-bar-striped progress-bar-warning' style='width: 10%'></div>";
            pElementsP.parentNode.classList.add('active');
        } else if (pStatus == 'submitted') {
            pElementsS.innerHTML = "<span class='label label-primary'>submitted</span>";
            pElementsP.classList.add('progress-bar-primary');
            pElementsP.style.width = '25%';
            //pElementsP.innerHTML = "<div class='progress-bar progress-bar-striped progress-bar-primary' style='width: 25%'></div>";
            pElementsP.parentNode.classList.add('active');
        } else if (pStatus == 'running') {
            if(pHost==null){
                pElementsS.innerHTML = "<span class='label label-primary'>running</span>";
            }else{
                pElementsS.innerHTML = "<span class='label label-primary'>running</span>"+
                    '<button class="btn btn-default btn-xs" onclick="opneLog("'+pHost+'")">' +
                    '<i class="fa fa-terminal"></i> LiveLog' +
                    '</button>';
            }

            pElementsP.classList.add('progress-bar-primary');
            pElementsP.style.width = '50%';
            //pElementsP.innerHTML = "<div class='progress-bar progress-bar-striped progress-bar-primary' style='width: 50%'></div>";
            pElementsP.parentNode.classList.add('active');
        } else if (pStatus == 'failed') {
            pElementsS.innerHTML = "<span class='label label-danger'>failed</span>";
            pElementsP.classList.add('progress-bar-danger');
            pElementsP.style.width = '100%';
            //pElementsP.innerHTML = "<div class='progress-bar progress-bar-danger' style='width: 100%'></div>";
        } else if (pStatus == 'finished') {
            pElementsS.innerHTML = "<span class='label label-success'>finished</span>";
            pElementsP.classList.add('progress-bar-success');
            pElementsP.style.width = '100%';
            //pElementsP.innerHTML = "<div class='progress-bar progress-bar-success' style='width: 100%'></div>";
        } else {
            pElementsS.innerHTML = "<span class='label label-success'>none</span>";
            pElementsP.classList.add('progress-bar-warning');
            pElementsP.style.width = '0%';
        }

    }
    
    function opneLog(host) {
        window.open('/settings-cluster_livelog/'+host+'?list','ll','menubar=no, toolbar=no, scrollbars=no');
    }

</script>

<style>

    .table > thead:first-child > tr:first-child > th:focus {
        outline: 0;
    }

    .run_progress .progress-bar{
        transition-property: width;
        transition-duration:4s;
        transition-timing-function:ease;
    }

    #simulation_table_wrapper .row{
        margin-right: 0px;
        margin-left: 0px;

    }

    #simulation_table_wrapper .col-sm-12{
        overflow-x:scroll;
    }

</style>