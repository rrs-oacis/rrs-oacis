<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/08
 * Time: 16:38
 */

?>
<div class="col-md-4">
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

        <canvas id="map_image" width="2000px" height="2000px"></canvas>


    </div>

    <div class="box-footer">
        <input id="scale_range" type="range" min="280"max="400" value="0"/>
    </div>

    <div id="map_image-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<!-- /.box -->

</div>

<script>

    $(function () {

        $( 'input[type=range]' ).on( 'input', function () {
            var val = $(this).val();


            var canvas = document.getElementById("map_image");

            canvas.style.width = val + "px";
            canvas.style.height = val + "px";

        } );


        var canvasW = 2000, canvasH = 2000;

        $('#map_image-overlay').show();

        fetch('/map_gml_get/<?=$map["name"]?>', {
            method: 'Get'
        }).then(function (response) {
            return response.text()
        }).then(function (xml) {
            //console.log(xml);

            $('#map_image-overlay').hide();

            var canvas = document.getElementById("map_image");

            var scaleRange = document.getElementById("scale_range");


            xmlMap = $.parseXML( xml );

            var minX = 0, maxX = 0, minY = 0, maxY = 0;
            var mapW = 0, mapH = 0;
            var centerX = 0, centerY = 0;

            var magX = 1.0, magY = 1.0;

            var nodeList =  xmlMap.getElementsByTagNameNS('http://www.opengis.net/gml','Node');

            var nodeMap = new Map();

            for (let i=0 ; i<nodeList.length ; i++){


                id = nodeList[i].getAttribute('gml:id');
                nodePoints = nodeList[i].getElementsByTagNameNS('http://www.opengis.net/gml','coordinates')[0].textContent;
                nodePoint = {x:nodePoints.split(',')[0],y:nodePoints.split(',')[1]};


                if(parseInt(nodePoint['x'])<minX)minX = parseInt(nodePoint['x']);
                if(parseInt(nodePoint['x'])>maxX)maxX = parseInt(nodePoint['x']);
                if(parseInt(nodePoint['y'])<minY)minY = parseInt(nodePoint['y']);
                if(parseInt(nodePoint['y'])>maxY)maxY = parseInt(nodePoint['y']);

                nodeMap.set(id,nodePoint);

            }

            mapW = maxX - minX;
            mapH = maxY - minY;
            centerX = (mapW/2) - minX;
            centerY = (mapH/2) - minY;


            canvas.width = mapW * 2 ;
            canvas.height = mapH * 2 ;

            scaleRange.max = mapH * 10;

            var ctx = canvas.getContext("2d");

            magX = 2;//parseFloat(canvasW)/parseFloat(mapW);
            magY = 2;//parseFloat(canvasH)/parseFloat(mapH);


            var edgeList  =  xmlMap.getElementsByTagNameNS('http://www.opengis.net/gml','Edge');

            var edgeMap = new Map();

            for (let i=0 ; i<edgeList.length ; i++){


                id = edgeList[i].getAttribute('gml:id');
                edgePoints = edgeList[i].getElementsByTagNameNS('http://www.opengis.net/gml','directedNode');
                edgePoint = {
                    '0':edgePoints[0].getAttribute('xlink:href').slice(1),
                    '1':edgePoints[1].getAttribute('xlink:href').slice(1)
                };

                edgeMap.set(id,edgePoint);

            }

            console.log(edgeMap);



            //Point
            for (var [id, points] of nodeMap) {

                ctx.beginPath();
                ctx.arc( (points['x']-minX)*magX, (points['y']-minY)* magY,1,0,1*Math.PI,true);

                ctx.fill();
            }


            //ライン
            for (var [id, points] of edgeMap) {



                ctx.beginPath();     // 1.Pathで描画を開始する

                let nodePoint1 = nodeMap.get(points[0]);

                ctx.moveTo((nodePoint1['x']-minX)*magX,(nodePoint1['y']-minY)*magY); // 2.描画する位置を指定する

                let nodePoint2 = nodeMap.get(points[1]);

                ctx.lineTo((nodePoint2['x']-minX)*magX,(nodePoint2['y']-minY)*magY); // 3.指定座標まで線を引く
                ctx.stroke();

                //ctx.re

            }

            //ctx.arc(200,200,1,0,1*Math.PI,true);

            //ctx.fill();

            //arc(x座標,y,直径,円弧の描き始めの位置,書き終わりの位置,円弧を描く方向(true:反時計回り))
            //ctx.fill();


            /*console.log(nodeList[0]);
            console.log(nodeList[0].getAttribute('gml:id'));
            console.log(nodeList[0].getElementsByTagNameNS('http://www.opengis.net/gml','coordinates')[0]);
            console.log(
                nodeList[0].getElementsByTagNameNS('http://www.opengis.net/gml','coordinates')[0].textContent
            );*/

            //console.log(nodeMap);

        });


    });




</script>

<style>

    #map_image_box .box-body{
        overflow: scroll;
        height: 220px;
    }

    #map_image{
        width: 280px;
        height: 280px;
    }

</style>
