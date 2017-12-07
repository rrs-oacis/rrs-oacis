<?php

?>
<div id="map_image_box" class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Preview</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

			<img id="map_image" src="/map_image/<?=$map['name']?>" alt="Map image of <?=$map['name']?>" />

		</div>


    <div id="map_image-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<!-- /.box -->

<script>

    $(function () {

    });

</script>

<style>

    #map_image{
				display:block;
				border: solid 1px;
        width: 100%;
        height: 100%;
				min-height: 100px;
    }

		img#map_image:after{
				content: "Not generated";
				display: block;
				padding: 20px 40%;
			font-size: 22px;
		}

</style>
