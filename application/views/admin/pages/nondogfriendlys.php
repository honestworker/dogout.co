<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title">Non DogFriendlys</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body nondogfriendlys-table">
            <table id="nondogfriendlys_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Place</th>
                <th>Address</th>
                <th>Count</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($reports as $report) {
                ?>
                <tr data-place="<?php echo $report->place; ?>" data-address="<?php echo $report->address; ?>">
                    <td><?php echo $report->place; ?></td>
                    <td><?php echo $report->address; ?></td>
                    <td><?php echo $report->count; ?></td>
                    <td><?php echo $report->updated_at; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Place</th>
                <th>Address</th>
                <th>Count</th>
                <th>Updated At</th>
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

<!-- Delete App Reviews Modal -->
<div class="modal modal-info fade" id="nondogfriendlys-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete All App Reviews</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte all these app reviews?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-nondogfriendlys">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>