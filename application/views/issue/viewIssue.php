<!-- <style type="text/css">
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    } 
</style> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Issue</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Issue</a></li>
                        <li class="breadcrumb-item active">View Issue</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Credit Term :</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbpayment" name="cmbpayment">
                                <option value="0" selected hidden>All Payments</option>
                                <?php foreach ($payment_data as $k => $v) { ?>
                                    <option value="<?= $v['intPaymentTypeID'] ?>"><?= $v['vcPaymentType'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="customer">Customer</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbcustomer" name="cmbcustomer">
                                <option value="0" selected hidden>All Customers</option>
                                <?php foreach ($customer_data as $k => $v) { ?>
                                    <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                <?php } ?>
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
                        <!-- <div>
                            Toggle column: <a class="toggle-vis" data-column="0">ID</a> - <a class="toggle-vis" data-column="1">GRN No</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
                        </div> -->
                        <table id="manageTable" class="table table-bordered table-striped">
                            <!-- style="display:block !important;" -->
                            <thead>
                                <tr>
                                    <th>Issue No</th>
                                    <th>Customer Name</th>
                                    <th>Issue Date</th>
                                    <th>Created Date</th>
                                    <th>Created User</th>
                                    <th>Payment Type</th>
                                    <th>Sub Total</th>
                                    <th>Discount</th>
                                    <th>Grand Total</th>
                                    <th>Remark</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <hr>
                        <!-- Color tags : <span class="badge badge-pill badge-warning">Pending Approvals</span> <span class="badge badge-pill badge-light" style="border: 1px #000000 solid;">Approved GRNs</span> <span class="badge badge-pill badge-danger">Rejected GRNs</span> -->
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

<script src="<?php echo base_url('resources/pageJS/viewIssue.js') ?>"></script>