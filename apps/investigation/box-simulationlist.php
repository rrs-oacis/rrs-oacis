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
        <h3 class="box-title">Simulation List</h3>
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
                <th>Log</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>fewnxbcsiern</td>
                <td>AIT-Rescue</td>
                <td>Sakae39</td>
                <td>ait,test</td>
                <td>
                    <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-danger" style="width: 25%"></div>
                    </div>
                </td>
                <td><span class="label label-success">Success</span></td>
                <td>3220</td>
                <td><a>Download</a></td>
            </tr>

            <tr>
                <td>rcuniweuiexn</td>
                <td>Ri-one</td>
                <td>Kobe1</td>
                <td>test</td>
                <td>
                    <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                    </div>
                </td>
                <td><span class="label label-success">Error</span></td>
                <td>2192</td>
                <td><a>Download</a></td>
            </tr>


            </tbody>
        </table>
    </div>
</div>

<script>

    $(function () {
        $('#simulation_table').DataTable();
    });

</script>
