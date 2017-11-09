<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/23
 * Time: 16:43
 */
use rrsoacis\system\Config;
?>

<div style="margin-bottom: 20px;">
    <?php if ($map["archived"]==0) { ?>

        <button id="btn_archive" class="btn btn-warning">Archive</button>

    <?php } else { ?>

        <button id="btn_comeback" class="btn btn-success">Unarchive</button>

    <?php } ?>
</div>


<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">General</h3>
        <div class="box-tools">


            <a class="btn btn-sm btn-info btn-social" href="/map_download/<?=$map['name']?>">
                <i class="fa fa-file-zip-o"></i> Download Zip
						</a>


        </div>

    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <dl class="dl-horizontal">
            <dt>Alias</dt>
            <dd><?= $map["alias"] ?></dd>
            <dt>Name</dt>
            <dd><?= $map["name"] ?></dd>
            <dt>Archived</dt>
            <dd>
                <?php if ($map["archived"]==1) { ?>
                    True
                <?php } else { ?>
                    False
                <?php } ?>

            </dd>



        </dl>


    </div>
</div>
<!-- /.box-body -->


<script>

    $("#btn_archive").on('click',function(){



        var form = new FormData();
        form.append('parameter_archived', 1);
        form.append('parameter_name','<?= $map["name"]?>');

        $("#btn_archive").html("progressing...").prop('disabled', true);;

        fetch('<?= Config::$TOP_PATH ?>map_archived_change', {
            method: 'POST', credentials: "include",
            body: form
        })
            .then(function (response) {
                return response.json()
            })
            .then(function (json) {

                location.reload();

                //setMapListOptionData(json);

            });

    });

    $("#btn_comeback").on('click',function(){



        var form = new FormData();
        form.append('parameter_archived', 0);
        form.append('parameter_name','<?= $map["name"]?>');

        $("#btn_comeback").html("progressing...").prop('disabled', true);;

        fetch('<?= Config::$TOP_PATH ?>map_archived_change', {
            method: 'POST', credentials: "include",
            body: form
        })
            .then(function (response) {
                return response.json()
            })
            .then(function (json) {

                location.reload();
                //setMapListOptionData(json);

            });

    });


</script>
