<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/08
 * Time: 16:38
 */

?>
<div id="map_image_box" class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Map image</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

			<img id="map_image" src="/map_image/<?=$map['name']?>"　alt="Map image" />

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

</style>
