<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Branch
        </h1>

    </section>

    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <?php if (in_array('createBranch', $user_permission) || $isAdmin) { ?>
                            <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addBranchModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Branch</button>
                        <?php } ?>
                    </div>
                    <div class="card-body">

                        <div class="box">
                            <div class="box-body">
                                <table id="manageTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Branch Name</th>
                                            <th>Address</th>
                                            <th>Contact No</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- col-md-12 -->
                </div>
                <!-- /.row -->
    </section>

    <div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="addBranchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBranchModal">Add Branch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('branch/create') ?>" method="post" id="createBranchForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="branch_name">Branch Name</label>
                            <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="contact_no">Contact No</label>
                            <input type="number" class="form-control" id="contact_no" name="contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                        </div>
                        <!-- <div class="form-group">
                            <label for="active">Status</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div> -->
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Branch</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- edit Branch modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBranchModal">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('branch/update') ?>" method="post" id="updateBranchForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="branch_name">Branch Name</label>
                        <input type="text" class="form-control" id="edit_branch_name" name="edit_branch_name" placeholder="Enter Branch Name" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="edit_address" placeholder="Enter Address" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No</label>
                        <input type="number" class="form-control" id="edit_contact_no" name="edit_contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Branch</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->

<!-- remove brand modal -->
<?php if (in_array('deleteBranch', $user_permission) || $isAdmin) { ?>
<div class="modal fade" tabindex="-1" role="dialog" id="removeBranchModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeBranchModal">Delete Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('branch/remove') ?>" method="post" id="removeBranchForm">
                <div class="modal-body">
                    <p>Do you really want to remove?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Branch</button>
                </div>
            </form>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } ?>

<script src="<?php echo base_url('resources/pageJS/branch.js') ?>"></script>