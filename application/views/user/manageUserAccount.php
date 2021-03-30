<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container "">
    <section class=" content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User Accounts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Utilities</a></li>
                    <li class="breadcrumb-item active">User Accounts</li>
                </ol>
            </div>
        </div>
    </div>
    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <?php if (in_array('createUser', $user_permission) || $isAdmin) { ?>
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addUserModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add User</button>
                <?php } ?>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Contact No</th>
                                    <th>Branch</th>
                                    <th>User Group</th>
                                    <th>User Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.row -->
    </section>

    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModal">Add User Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('user/createUser') ?>" method="post" id="createUserForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_name">User Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter User Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter Full Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="contact_no">Contact No</label>
                            <input type="number" class="form-control" id="contact_no" name="contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Branch</label>
                            <select class="form-control select2" style="width: 100%;" id="branch" name="branch">
                                <option value="" disabled selected hidden>Select Branch</option>
                                <?php foreach ($branch as $row) { ?>
                                    <option value="<?= $row->intBranchID ?>"><?= $row->vcBranchName ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>User Group</label>
                            <select class="form-control select2" style="width: 100%;" id="user_group" name="user_group">
                                <option value="" disabled selected hidden>Select User Group</option>
                                <?php foreach ($userGroup as $row) { ?>
                                    <option value="<?= $row->intUserGroupID ?>"><?= $row->vcGroupName ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="IsAdmin" name="IsAdmin">
                            <label class="form-check-label" for="IsAdmin">Admin</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save User</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- edit User modal -->
<?php if (in_array('editUser', $user_permission) || $isAdmin) { ?>
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModal">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" action="<?php echo base_url('user/editUser') ?>" method="post" id="editUserForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_name">User Name</label>
                            <input type="text" class="form-control" id="edit_user_name" name="edit_user_name" placeholder="Enter User Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="edit_password" placeholder="Enter password" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="edit_full_name" placeholder="Enter Full Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="edit_email" name="edit_email" placeholder="Enter Email" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="contact_no">Contact No</label>
                            <input type="number" class="form-control" id="edit_contact_no" name="edit_contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Branch</label>
                            <select class="form-control select2" style="width: 100%;" id="edit_branch" name="edit_branch">
                                <!-- <option value="" disabled selected hidden>Select Branch</option> -->
                                <?php foreach ($branch as $row) { ?>
                                    <option value="<?= $row->intBranchID ?>"><?= $row->vcBranchName ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>User Group</label>
                            <select class="form-control select2" style="width: 100%;" id="edit_user_group" name="edit_user_group">
                                <!-- <option value="" disabled selected hidden>Select User Group</option> -->
                                <?php foreach ($userGroup as $row) { ?>
                                    <option value="<?= $row->intUserGroupID ?>"><?= $row->vcGroupName ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="edit_IsAdmin" name="edit_IsAdmin">
                            <label class="form-check-label" for="edit_IsAdmin">Admin</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update User</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    </div>
<?php } ?>
<!-- /.content-wrapper -->
<?php if (in_array('deleteUser', $user_permission) || $isAdmin) { ?>
    <!-- remove brand modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeUserModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeUserModal">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('user/removeUser') ?>" method="post" id="removeUserForm">
                    <div class="modal-body">
                        <p>Do you really want to remove?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete User</button>
                    </div>
                </form>


            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php } ?>

<script src="<?php echo base_url('resources/pageJS/user.js') ?>"></script>