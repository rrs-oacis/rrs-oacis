<?php
use rrsoacis\system\Config;
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Config</h3>

    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="agent_config_list" class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <template id="agent_config_list_template">
                <tr>

                    <td class="agent_config_list_name"></td>
                    <td class="agent_config_list_value"></td>

                </tr>
            </template>
        </table>

    </div>
    <div id="agent_config-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>
<!-- /.box-body -->

<script>

    $(function(){
        getAgentConfig();
    });

    function getAgentConfig(){

        $('#agent_config-overlay').show();

        fetch('<?= Config::$TOP_PATH ?>agent_config/<?= $agent["name"]?>', {
            method: 'GET', credentials: "include"
        })
            .then(function(response) {
                return response.text()
            })
            .then(function(text) {

                $('#agent_config-overlay').hide();


                var arr = text.split(/\r\n|\r|\n/);
                setTableConfigData(arr);

            });
    }

    function setTableConfigData(data)
    {

        var tb = document.querySelector('#agent_config_list tbody');
        while (child = tb.lastChild)
        { tb.removeChild(child); }

        var datas = {};

        for(let i=0;i<data.length;i++)
        {
            let cData = data[i].split(':');

            if(cData[0]=='')continue;

            datas[cData[0]]=cData[1];

        }

        for(let k in datas) {

            var t = document.querySelector('#agent_config_list_template');

            t.content.querySelector('.agent_config_list_name').textContent = k;
            t.content.querySelector('.agent_config_list_value').textContent = datas[k];

            var clone = document.importNode(t.content, true);
            tb.appendChild(clone);
        }

    }

</script>
