<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title" style="margin-top: 10px;">&nbsp;&nbsp;&nbsp;<b>New DogFriendly</b></h3>
            <br><br>
            <dl class="dl-horizontal">
                <dt>Place:</dt>
                <dd><?php echo $data['place']; ?></dd>
                <dt>Address:</dt>
                <dd><?php echo $data['address']; ?></dd>
                <dt>Total:</dt>
                <dd><?php echo $data['total']; ?></dd>
            </dl>
            <input type="hidden"  value="<?php echo $data['place']; ?>" id="report_place">
            <input type="hidden"  value="<?php echo $data['address']; ?>" id="report_address">
        </div>
        <!-- /.box-header -->
        <div class="box-body nondogfriendly-table">
            <table id="nondogfriendly_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>User Name</th>
                <th>Enail</th>
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
                    <td><?php echo $report['comment']; ?></td>
                    <td><?php echo $report['created_at']; ?></td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="viewDeleteReportModal('nondogfriendly', $(this).parent().parent().data('id'))"><i class="fa fa-trash"></i></button>
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

<!-- Delete Non DogFriendl Modal -->
<div class="modal modal-info fade" id="nondogfriendly-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete Non DogFriendly</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte this non dogfriendly?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-nondogfriendly" onclick="deleteReport('nondogfriendly')">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>