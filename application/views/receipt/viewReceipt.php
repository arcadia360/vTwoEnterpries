<!-- <style>
    .table,
    td {
        border: 1px solid #263238;
    }

    .table th {
        background-color: #263238 !important;
        color: #FFFFFF;
    }

    tbody td {
        padding: 0 !important;
    }

    .button {
        width: 35px;
        height: 35px;
        color: #fff;
        display: flex;
        align-items: center;
        cursor: pointer;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.4);
        border: none;
    }


    .center-items {
        margin: 0 auto;
    }

    input[type=text]:disabled {
        background: #ffffff;
        border: 1px solid #ced4da !important;
        border: none;

    }

    input[type=text]:read-only {
        background: #ffffff;
        border-color: #ffffff;
    }

    .card {
        box-shadow: none;
        margin: 0;
    }

    .select2-results__option[id*="Test"] {
        color: red;
    }


</style> -->


<style>
    .table,
    td {
        border: 1px solid #263238;
    }

    .table th {
        background-color: #263238 !important;
        color: #FFFFFF;
    }

    tbody td {
        padding: 0 !important;
    }

    div.dt-top-container {
        display: grid;
        grid-template-columns: auto auto auto;
    }

    div.dt-center-in-div {
        margin: 0 auto;
    }

    div.dt-filter-spacer {
        margin: 10px 0;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Receipt Settlement</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">View Receipt Settlement</a></li>
                        <li class="breadcrumb-item active">View Receipt Settlement</li>
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
                            <label>Payment Mode :</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbPayMode" name="cmbPayMode">
                                <option value="0" selected hidden>All Payment Modes</option>
                                <?php foreach ($payment_data as $k => $v) { ?>
                                    <option value="<?= $v['intPayModeID'] ?>"><?= $v['vcPayMode'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cmbcustomer">Customer</label>
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
                                    <th>Receipt No</th>
                                    <th>Customer Name</th>
                                    <th>Payment Mode</th>
                                    <th>Paid Amount</th>
                                    <th>Paid Date</th>
                                    <th>Created User</th>
                                    <th>Created Date</th>
                                    <th>Bank</th>
                                    <th>Cheque No</th>
                                    <th>PD Date</th>
                                    <th>Remark</th>
                                    <th>Receipt Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <hr>
                        Color tags : <span class="badge badge-pill badge-danger">Cancelled Receipt</span> <span class="badge badge-pill badge-info">Realized</span> <span class="badge badge-pill badge-warning">Pending Realizing</span> <span class="badge badge-pill badge-secondary">Return Cheque</span>
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


<div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModal">View Settlement Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <table class="table" id="IssueItemTable">
                <thead>
                    <tr>
                        <th style="text-align:center;">Issue No</th>
                        <th style="width: 100px; text-align:center;">Outstanding</th>
                        <th style="width: 100px; text-align:center;">Total Amount</th>
                        <th style="width: 100px; text-align:center;">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="<?php echo base_url('resources/pageJS/viewReceipt.js') ?>"></script>