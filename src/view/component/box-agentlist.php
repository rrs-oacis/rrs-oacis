<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">エージェントリスト</h3>

        <div class="box-tools">
          <div class="input-group input-group-sm" style="width: 150px;">
            <input type="text" name="table_search"
              class="form-control pull-right" placeholder="Search">

            <div class="input-group-btn">
              <button type="submit" class="btn btn-default">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
          <tr>
            <th>UUID</th>
            <th>エージェント名</th>
            <th>アップロード日</th>
            <th>Status</th>
          </tr>
                <?php foreach($agents as $agent){?>
                <tr>
                  <td><?= $agent["uuid"]?></td>
                  <td><?= $agent["name"]?></td>
                  <td><?= $agent["upload_date"]?></td>
                  <td><span class="label label-success">Approved</span></td>
                </tr>
                <?php }?>
                
              </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>