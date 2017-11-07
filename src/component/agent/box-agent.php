<?php
use rrsoacis\system\Config;
?>

<div style="margin-bottom: 20px;">
    <?php if ($agent["archived"]==0) { ?>

        <button id="btn_archive" class="btn btn-warning">Archive</button>

    <?php } else { ?>

        <button id="btn_comeback" class="btn btn-success">Comeback</button>

    <?php } ?>
</div>

<div class="box box-info">
  <div class="box-header with-border">
      <h3 class="box-title">General</h3>
      <div class="box-tools">

          <a class="btn btn-sm btn-info btn-social" href="/agent_download/<?=$agent['name']?>">
              <i class="fa fa-file-zip-o"></i> Zip Download
          </a>

      </div>

  </div>
  <!-- /.box-header -->
  <div class="box-body">

      <dl class="dl-horizontal">
          <dt>Name</dt>
          <dd><?= $agent["name"] ?></dd>
          <dt>Archived</dt>
          <dd>
              <?php if ($agent["archived"]==1) { ?>
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
        form.append('parameter_name','<?= $agent["name"]?>');


        $("#btn_archive").html("progressing...").prop('disabled', true);

        fetch('<?= Config::$TOP_PATH ?>agent_archived_change', {
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
        form.append('parameter_name','<?= $agent["name"]?>');

        $("#btn_comeback").html("progressing...").prop('disabled', true);

        fetch('<?= Config::$TOP_PATH ?>agent_archived_change', {
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
