<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Request</a></li>
                        <li class="breadcrumb-item active">View Request</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

    </section>
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <?php if ($this->session->userdata('Is_main_branch') == false) { ?>
                    <?php if (in_array('createRequestItem', $user_permission) || $isAdmin) { ?>
                        <a href="<?= base_url("request/RequestItem") ?>" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Create Request</a>
                        <!-- <button type="button" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Create GRN</button> -->
                    <?php } ?>
                <?php } ?>

            </div>
            <section class="content">
                <!-- Small boxes (Stat box) -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status :</label>
                                    <select class="custom-select" id="cmbStatus">
                                        <option value="0">All</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Pending</option>
                                        <option value="3">Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Date range -->
                                <div class="form-group">
                                    <label>Date Range :</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" name="daterange">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="box">
                            <div class="box-body">
                                <table id="manageTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Request No</th>
                                            <th>Requested Branch</th>
                                            <th>Requested Date</th>
                                            <th>Created User</th>
                                            <th>Total Items</th>
                                            <th>Prending Items</th>
                                            <th>Rejected Items</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>

                                <hr>
                                Color tags : <span class="badge badge-pill badge-warning">Pending Approvals</span>&nbsp;&nbsp;<span class="badge badge-pill badge-light" style="border: 1px #000000 solid;">All Items Completed</span>&nbsp;&nbsp;<span class="badge badge-pill badge-danger">All Items Rejected</span>

                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- /.row -->
            </section>
        </div>

</div>

</section>
</div>
<script src="<?php echo base_url('resources/pageJS/viewRequest.js') ?>"></script>