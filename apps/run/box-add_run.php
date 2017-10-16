<?php

use rrsoacis\system\Config;

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Add Run</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="add_simulation-form" action="./run-add_run" method="POST"
          class="form-horizontal" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Agents</label>

                <div class="col-sm-10">

                    <select class="form-control select2" name="parameter_agents[]" multiple="multiple"
                            data-placeholder="select a agent"
                            style="width: 100%;" required>
                        <?php
                        foreach ($agents as $agent) {
                            ?>
                            <option><?= $agent['alias'] ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </div>
            </div>
            <div id="simulation_maps" class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Maps</label>

                <div class="col-sm-10">

                    <select class="form-control select2_map" name="parameter_maps[]" multiple="multiple"
                            data-placeholder="select a map"
                            style="width: 100%;" required>
                        <?php
                        foreach ($maps as $map) {
                            ?>
                            <option><?= $map['alias'] ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </div>
            </div>
            <div id="simulation_tags" class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tags</label>

                <div class="col-sm-10">
                    <!--<input type="text" class="form-control" name="parameter_tags"
                           placeholder="keyword"
                    >-->
                    <select class="form-control select2_tag" name="parameter_tags[]" multiple="multiple"
                            data-placeholder="keyword"
                            style="width: 100%;" required>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Count</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="parameter_count"
                           placeholder="simulation count"
                           required
                           autocomplete="on" list="map_keyword"
                            value="1" min="1" >
                </div>
                <datalist id="map_keyword">
                </datalist>
                <template id="map_keyword_option">
                    <option value="hoge"/>
                </template>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <!-- <button type="submit" class="btn btn-default">キャンセル</button> -->
                <button type="submit" class="btn btn-info pull-right">Add</button>
            </div>
            <!-- /.box-footer -->
            <input type="hidden" name="action" value="create">
        </div>
    </form>
    <div id="add_simulation-form-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<!-- /.box -->

<script>


    $("#add_simulation-form").submit(function (e) {

        $('#add_simulation-form-overlay').show();
        e.preventDefault();
        var form = document.querySelector('#add_simulation-form');
        fetch('./run-add_run', {
            method: 'POST',
            body: new FormData(form)
        }).then(function (response) {

            return response.json()

        }).then(function (json) {

            console.log(json);
            //location.reload();


            setTimeout(function () {
                $('#add_simulation-form-overlay').hide();
                getRunList();
            },2000);


        });


    });


    document.addEventListener('DOMContentLoaded', function() {

    });

    $(function () {
        // 処理

        $(".select2").select2();

        $(".select2_map").select2({

            dropdownCssClass:'dropdown_maps'

        });



        $(".select2-search__field").css({'padding': '0px 6px', "border": "none"});


        $('.select2_tag').select2({
            data: [],
            tags: true,
            tokenSeparators: [',',' '],
            placeholder: "Add your tags here",
            dropdownCssClass:'dropdown_tags'
        });

    });

    //inputのList

    document.addEventListener("adf_add_agent", function () {
        getAgentParameterList();
    }, false);

    document.addEventListener("adf_add_map", function () {
        getAgentParameterList();
    }, false);


    $(function () {
        // 処理
        //getAgentParameterList();
        //getMapParameterList();



    });

    function getAgentParameterList() {

        fetch('<?= Config::$TOP_PATH ?>agents_get', {
            method: 'GET'
        })
            .then(function (response) {
                return response.json()
            })
            .then(function (json) {

                setAgentListOptionData(json);

            });

    }


    function setAgentListOptionData(date) {

        var tb = document.querySelector('#agent_keyword');
        while (child = tb.lastChild) tb.removeChild(child);

        for (var i = 0; i < date.length; i++) {


            var t = document.querySelector('#agent_keyword_option');

            t.content.querySelector('option').value = date[i]['name'] + '_' + date[i]['uuid'];

            var clone = document.importNode(t.content, true);
            tb.appendChild(clone);
        }

    }

    //Map
    function getMapParameterList() {

        fetch('<?= Config::$TOP_PATH ?>maps_get', {
            method: 'GET'
        })
            .then(function (response) {
                return response.json()
            })
            .then(function (json) {

                setMapListOptionData(json);

            });

    }


    function setMapListOptionData(date) {

        var tb = document.querySelector('#map_keyword');
        while (child = tb.lastChild) tb.removeChild(child);

        for (var i = 0; i < date.length; i++) {


            var t = document.querySelector('#map_keyword_option');

            t.content.querySelector('option').value = date[i]['name'] + '_' + date[i]['uuid'];

            var clone = document.importNode(t.content, true);
            tb.appendChild(clone);
        }

    }

</script>

<style type="text/css">
    #simulation_maps .select2-selection__choice {
        background-color: #00a65a;
        border-color: #008d4c;
    }

    .dropdown_maps > span > ul > .select2-results__option--highlighted[aria-selected=false] {
        background-color: #008d4c;
    }

    #simulation_tags .select2-selection__choice {
        background-color: #f39c12;
        border-color: #e08e0b;
    }

    .dropdown_tags > span > ul > .select2-results__option--highlighted[aria-selected=false] {
        background-color: #e08e0b !important;
    }

</style>