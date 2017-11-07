<style>

    .content{
        overflow:hidden;
    }

    .addForm{
        border: dashed 2px rgba(130, 130, 130, 0.84) !important;
        background-color: transparent;
        box-shadow: none;
        height:210px;

        cursor: copy;

    }

    .addForm div{
        text-align: center;

    }

    .addForm div i{
        transform: translateY(100%);
        color: rgb(130, 130, 130);
    }

    .addForm:hover{
        background-color: #fdfdfd;
        border: dashed 2px rgba(34, 34, 34, 0.84) !important;

    }

    .addForm:hover div i{
        color: rgba(34, 34, 34, 0.84) !important;
    }


    .addForm div p{
        transform: translateY(300%);
    }

    .addForm div input{
        height: 0px;
        width: 0px;
        cursor: copy;
    }

    .form-area{

    }

</style>

<!-- /.box -->

<div class="col-md-12" style="margin-bottom: 2em;">
    <button id="add-all" class="btn btn-success pull-right bt-all-add">Add all</button>

</div>

<div class="form-area">


<template id="agent_form_template">
<div class="col-sm-12 col-md-6">
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Add Agent</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form action="./agent_upload" method="POST"
          class="form-horizontal add-agent-form" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control a-title" name="agent_name"
                           id="inputTitle-1"
                           placeholder="agent name"
                           required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Zip File</label>

                <div class="col-sm-10">
                    <div style="position: relative;">
                        <input type="hidden" name="MAX_FILE_SIZE" value="1073741824" />
                        <input id="lefile-1" type="file" class="form-control a-file"
                               name="userfile" accept="application/zip" style="position: absolute;" required multiple/>
                        <div class="input-group" style="position: absolute;">
                            <input type="text" id="photoCover-1"
                                   class="form-control readonly a-cover"
                                   placeholder="file name"
                                   disabled>
                            <span class="input-group-btn">
                  <button type="button" class="btn btn-info a-button">
                      Browse
                  </button>
                </span>
                        </div>
                    </div>
                    <input class="a-buffer" type="hidden" name="fileBuffer" value=null />
                </div>
            </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <!-- <button type="submit" class="btn btn-default">キャンセル</button> -->
            <button type="submit" class="btn btn-info pull-right">Add</button>
        </div>
        <!-- /.box-footer -->
        <input type="hidden" name="action" value="create">
    </form>
    <div id="form-overlay" class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
</div>
</template>
<!-- /.box -->


<div id="add_agent_form_area" class="col-sm-12 col-md-6">
    <div class="box box-solid addForm">
        <div class="box-body">
            <input id="addFile" type="file" multiple>
            <i class="fa fa-4x fa-upload"></i>
            <p>Drag and drop or click to upload agents</p>

        </div>
    </div>
</div>

</div>

<script>

    $(function(){

        //initForm();

        var dropZone = document.getElementById('add_agent_form_area');
        dropZone.addEventListener('dragover', handleDragOver, false);
        dropZone.addEventListener('drop', handleFileSelect, false);

        var dropZoneFile = document.getElementById('addFile');

        dropZone.addEventListener("click",function () {


            dropZoneFile.click();

        });

        $(dropZoneFile).change(function() {

            for(let i=0;i< $(this).prop('files').length;i++){

                initForm($(this).prop('files')[i]);

            }

        });

        var addAll = document.getElementById('add-all');

        addAll.addEventListener("click",function () {

            var forms = document.getElementsByClassName("add-agent-form");

            for(let i=0; i<forms.length; i++){

                setTimeout(() => { $(forms[i]).submit(); }, 2000*i);

            }

        });

    });

    //InitForm
    function initForm(file){

        var t = document.querySelector('#agent_form_template');

        var clone = document.importNode(t.content, true);

        var inputFile = clone.querySelector('.a-file');

        var inputFileButton = clone.querySelector('.a-button');

        var hotoCover = clone.querySelector('.a-cover');

        var aTitle = clone.querySelector('.a-title');

        var fileBuffer = clone.querySelector('.a-buffer');

        var fileObjectBuffer;

        var formE = clone.querySelector('form');

        var overlayE = clone.querySelector('.overlay');

        if(file!=null){

            $(aTitle).val(file.name.match(/(.*)(?:\.([^.]+$))/)[1]);

            $(fileBuffer).val(file);

            $(hotoCover).val(file.name);

            fileObjectBuffer = file;

            $(inputFile).prop('required', false);

        }

        inputFileButton.addEventListener("click",function () {


            inputFile.click();

        });

        $(inputFile).change(function() {

            $(hotoCover).val($(this).prop('files')[0].name);

            if ($(aTitle).val() == '')
            {
                var name = $(this).prop('files')[0].name.match(/(.*)(?:\.([^.]+$))/)[1];
                $(aTitle).val(name);
            }

            $(fileBuffer).val('');
            $(inputFile).prop('required', true);
            fileObjectBuffer = null;

            for(let i=1;i< $(this).prop('files').length;i++){

                initForm($(this).prop('files')[i]);

            }

        });

        //Submit
        $(formE).submit(function(e){


            $(overlayE).show();
            e.preventDefault();

            var form = formE;//document.querySelector('#post-form-001');

            var formData = new FormData(form);

            if($(fileBuffer).val()!=null || $(fileBuffer).val()!=''){

                //console.log(inputFile.name);

                console.log(fileObjectBuffer);

                formData.set(inputFile.name, fileObjectBuffer );
                $(fileBuffer).val('');

            }

            fetch('./agent_upload', {
                method: 'POST', credentials: "include",
                body: formData
            })
                .then(function(response) {
                    return response.json()
                })
                .then(function(json) {
                    console.log(json);

                    $(overlayE).hide();
                    if(json["result"]=="success"){
                        //toastr.success(json["title"],"登録完了");
                        //var form = document.querySelector('#post-form');
                        //$(form).find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
                    }
                    console.log(json);

                    if(json['status']){
                        toastr["success"](
                            "Add agent",
                            "Success");

                        form.reset();
                    }else{
                        toastr["error"](
                            "Add agent",
                            "Error");
                    }


                });


        });


        var addF = document.querySelector('#add_agent_form_area');

        addF.parentNode.insertBefore(clone, addF);


    }


    $(".readonly").keydown(function(e){
        e.preventDefault();
    });



    $("#post-form-1").submit(function(e){


        $('#form-overlay').show();
        e.preventDefault();
        var form = document.querySelector('#post-form-001');
        fetch('./agent_upload', {
            method: 'POST', credentials: "include",
            body: new FormData(form)
        })
            .then(function(response) {
                return response.json()
            })
            .then(function(json) {
                console.log(json);
                $('#form-overlay').hide();
                if(json["result"]=="success"){
                    //toastr.success(json["title"],"登録完了");
                    //var form = document.querySelector('#post-form');
                    //$(form).find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
                }
                console.log(json);

                if(json['status']){
                    toastr["success"](
                        "Add agent",
                        "Success");

                    document.querySelector('#post-form').reset();
                }else{
                    toastr["error"](
                        "Add agent",
                        "Error");
                }


            });


    });

    function handleFileSelect(evt) {
        evt.stopPropagation();
        evt.preventDefault();

        var files = evt.dataTransfer.files; // FileList object.

        for (var i = 0, f; f = files[i]; i++) {

            initForm(f);

        }
    }

    function handleDragOver(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        evt.dataTransfer.dropEffect = 'copy';
    }


</script>


