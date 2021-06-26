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

    /* .table td, .table th {
    padding: 0;
    vertical-align: top;
    border:0;
} */

    /* .select2-results__option:nth-child(4) {
        background-color: red !important;
    } */

    .select2-results__option[id*="Test"] {
        color: red;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Issue Return</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Return</a></li>
                        <li class="breadcrumb-item active">Issue Return</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <form role="form" class="add-form" method="post" action="<?= base_url('Issue/SaveIssueReturn') ?>" id="issueNote">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>Issue No</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbIssueNo" name="cmbIssueNo">
                                <option value=" 0" disabled selected hidden>Select Item</option>
                                <?php foreach ($issue_No as $k => $v) { ?>
                                    <option value="<?= $v['intIssueHeaderID'] ?>"><?= $v['vcIssueNo'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Customer</label>
                            <input type="text" class="form-control" id="Customer" name="Customer" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Issued Date</label>
                            <input type="text" class="form-control" id="IssuedDate" name="IssuedDate" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Created Date Time</label>
                            <input type="text" class="form-control" id="CreatedDate" name="CreatedDate" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Created User</label>
                            <input type="text" class="form-control" id="CreatedUser" name="CreatedUser" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Term</label>
                            <input type="text" class="form-control" id="PaymentMode" name="PaymentMode" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Advance Amount</label>
                            <input type="text" class="form-control" id="AdvanceAmount" name="AdvanceAmount" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Sub Total</label>
                            <input type="text" class="form-control" id="SubTotal" name="SubTotal" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Discount</label>
                            <input type="text" class="form-control" id="Discount" name="Discount" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Grand Total</label>
                            <input type="text" class="form-control" id="GrandTotal" name="GrandTotal" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="credit_limit">Reason</label>
                            <input type="text" class="form-control" id="Reason" name="Reason" autocomplete="off" placeholder="Enter Return Reason" required />
                        </div>
                    </div>
                    <table class="table" id="IssueItemTable">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Item Description</th>
                                <th style="width: 100px; text-align:center;">Unit</th>
                                <th style="width: 100px; text-align:center;">Unit Price</th>
                                <th style="width: 100px; text-align:center;">Issued Qty</th>
                                <th style="width: 100px; text-align:center;">Total</th>
                                <th style="width: 100px; text-align:center;">Balance Qty</th>
                                <th style="width: 100px; text-align:center;">Return Qty</th>
                                <th style="width: 100px; text-align:center;">Item Wise Total</th>
                                <th hidden>rv</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>

                    <div class="row" style="border-top:1px solid #dee2e6;">
                        <div class="col-md-6">
                            <input type="text" class="form-control" style="font-weight: 600; text-align:right; font-size:1.5em !important;" id="grandTotal" name="grandTotal" placeholder="0.00" readonly>
                            <p style="color: #c2c7d0;" id="itemCount">Item Count : 0</p>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6">
                            <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right" style="margin-top: 10px;"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('resources/pageJS/issueReturn.js') ?>"></script>