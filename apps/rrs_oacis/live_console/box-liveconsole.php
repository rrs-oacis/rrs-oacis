<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/21
 * Time: 22:16
 */

use rrsoacis\system\Config;

?>

<div class="row">

<?php
foreach($clusters as $clusterData) {

    $cluster = $clusterData["name"];

    ?>


        <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Cluster </h3>
                    <small><?= $cluster ?></small>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box-group" id="accordion<?= $cluster ?>">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion<?= $cluster ?>" href="#collapse-<?= $cluster ?>-One">
                                        STDOut
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse-<?= $cluster ?>-One" class="panel-collapse collapse">
                                <div class="box-body">
                        <pre class="pre_out_<?= $cluster ?>">
<span>#########　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　A</span>
<span># Loading</span>
<span>#########</span>
<div class="line-numbers line-numbers-out-<?= $cluster ?>"><span></span><span></span><span></span></div></pre>
                                </div>
                            </div>
                        </div>
                        <div class="panel box box-danger">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion<?= $cluster ?>" href="#collapse-<?= $cluster ?>-Two">
                                        STDError
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse-<?= $cluster ?>-Two" class="panel-collapse collapse">
                                <div class="box-body">
                        <pre class="pre_error_<?= $cluster ?>">
<span>#########　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　A</span>
<span># Loading</span>
<span>#########</span>
<div class="line-numbers line-numbers-error-<?= $cluster ?>"><span></span><span></span><span></span></div></pre>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>




    <script>

        $(function () {
            getOut<?= $cluster ?>();
            getError<?= $cluster ?>();
        });

        function getOut<?= $cluster ?>() {

            fetch('<?= Config::$TOP_PATH ?>live_console-cluster_get/<?= $cluster ?>/out', {
                method: 'GET', credentials: "include"
            })
                .then(function (response) {
                    return response.text()
                })
                .then(function (texts) {

                    //$('#agent_list-overlay').hide();

                    texts = texts.match(/[^\r\n]*(\r\n|\r|\n|$)/g);


                    var elementOut = document.getElementsByClassName('pre_out_<?= $cluster ?>')[0];

                    elementOut.textContent = null;

                    for (var i = 0; i < texts.length; i++) {

                        var text = texts[i];

                        var span = document.createElement('span');
                        span.textContent = text;

                        elementOut.insertBefore(span, elementOut.firstChild);

                    }


                    var div = document.createElement('div');
                    div.classList.add('line-numbers');
                    div.classList.add('line-numbers-out-<?= $cluster ?>');

                    elementOut.insertBefore(div, elementOut.firstChild);

                    for (var i = 0; i < texts.length; i++) {

                        var span = document.createElement('span');

                        div.insertBefore(span, div.firstChild);

                    }

                    if (texts == "") {
                        elementOut.textContent = 'Not found file.';
                    }


                });
        }

        function getError<?= $cluster ?>() {

            fetch('<?= Config::$TOP_PATH ?>live_console-cluster_get/<?= $cluster ?>/error', {
                method: 'GET', credentials: "include"
            })
                .then(function (response) {
                    return response.text()
                })
                .then(function (texts) {

                    //$('#agent_list-overlay').hide();

                    //setTableData(json);

                    texts = texts.match(/[^\r\n]*(\r\n|\r|\n|$)/g);


                    var elementError = document.getElementsByClassName('pre_error_<?= $cluster ?>')[0];

                    elementError.textContent = null;

                    for (var i = 0; i < texts.length; i++) {

                        var text = texts[i];

                        var span = document.createElement('span');
                        span.textContent = text;

                        elementError.insertBefore(span, elementError.firstChild);
                    }


                    var div = document.createElement('div');
                    div.classList.add('line-numbers');
                    div.classList.add('line-numbers-error-<?= $cluster ?>');

                    elementError.insertBefore(div, elementError.firstChild);

                    for (var i = 0; i < texts.length; i++) {

                        var span = document.createElement('span');

                        div.insertBefore(span, div.firstChild);

                    }

                    if (texts == "") {
                        elementError.textContent = 'Not found file.';
                    }

                });
        }
    </script>


    <style>

        .line-numbers-out-<?= $cluster ?> > span {
            float: left;
            display: block;
            counter-increment: linenumber;
            text-align: right;
            width: 100%;
        }

        .line-numbers-out-<?= $cluster ?> > span::after {
            content: counter(linenumber);
        }

        .line-numbers-error-<?= $cluster ?> > span {
            float: left;
            display: block;
            counter-increment: linenumber;
            text-align: right;
            width: 100%;
        }

        .line-numbers-error-<?= $cluster ?> > span::after {
            content: counter(linenumber);
        }


    </style>

    <?php


}?>

</div>

<style>

    .panel-collapse .box-body {
        height: 300px;
        overflow: scroll;
        white-space: nowrap;
        letter-spacing: 1px;
        line-height: 16px;
        /*background-color: #0f0f0f;
        color: #fbfbfb;*/
    }

    .panel-collapse .box-body > pre {
        background-color: transparent;
        position: relative;
        padding: 6px 40px;
        height: calc(100% - 12px);
    }

    .panel-collapse .box-body > pre > span {
        margin-left: 4px;
    }

    .line-numbers {
        position: absolute;
        top: 0;
        left: 0;
        padding: 6px 5px;
        height: 100%;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border-right: 1px solid #999;
        width: 40px;
    }

</style>
