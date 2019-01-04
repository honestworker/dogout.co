<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title">App Reviews</h3>
            <br><br><br><h3 class="box-title">Place: <?php echo $data['place']; ?></h3>
            <br><h3 class="box-title">Address: <?php echo $data['address']; ?></h3>
            <br><h3 class="box-title">Total: <?php echo $data['total']; ?></h3>
            <br><h3 class="box-title"><input type="text" class="kv-gly-star rating-loading" value="<?php echo $data['rating']; ?>" data-size="md" title=""></h3>
            <input type="hidden"  value="<?php echo $data['place']; ?>" id="report_place">
            <input type="hidden"  value="<?php echo $data['address']; ?>" id="report_address">
        </div>
        <!-- /.box-header -->
        <div class="box-body appreview-table">
            <table id="appreview_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>User Name</th>
                <th>Enail</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data['reports'] as $report) {
                ?>
                <tr data-id="<?php echo $report['id']; ?>">
                    <td><?php echo $report['user_name']; ?></td>
                    <td><?php echo $report['user_email']; ?></td>
                    <td><input type="text" class="kv-gly-star rating-loading" value="<?php echo $report['rating']; ?>" data-size="md" title=""></td>
                    <td><?php echo $report['comment']; ?></td>
                    <td><?php echo $report['created_at']; ?></td>
                    <td>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>User Name</th>
                <th>Enail</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<!-- Delete App Review Modal -->
<div class="modal modal-info fade" id="appreview-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete App Review</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte this app review?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-appreview">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>