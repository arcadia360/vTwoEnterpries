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
                <h1>Cancel Supplier Credit Settlement</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Supplier Credit Settlement</a></li>
                        <li class="breadcrumb-item active">Cancel Supplier Credit Settlement</li>
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
                <form role="form" class="add-form" method="post" action="<?= base_url('Supplier/SaveCancelSupplierCreditSettlement') ?>" id="cancelSupplierCreditSettlement">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>Supplier Settlement No</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbSupplierSettlementNo" name="cmbSupplierSettlementNo">
                                <option value=" 0" disabled selected hidden>Select Item</option>
                                <?php foreach ($settlement_No as $k => $v) { ?>
                                    <option value="<?= $v['intSupplierSettlementHeaderID'] ?>"><?= $v['vcSupplierSettlementNo'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Supplier</label>
                            <input type="text" class="form-control" id="Supplier" name="Supplier" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Settlement Date</label>
                            <input type="text" class="form-control" id="SettlementDate" name="SettlementDate" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
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
                            <label for="credit_limit">Payment Mode</label>
                            <input type="text" class="form-control" id="PaymentMode" name="PaymentMode" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-2">
                            <label for="credit_limit">Cheque No</label>
                            <input type="text" class="form-control" id="ChequeNo" name="ChequeNo" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Bank Name</label>
                            <input type="text" class="form-control" id="BankName" name="BankName" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">PO Date</label>
                            <input type="text" class="form-control" id="PODate" name="PODate" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Paid Amount</label>
                            <input type="text" class="form-control" id="PaidAmount" name="PaidAmount" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Remark</label>
                            <input type="text" class="form-control" id="Remark" name="Remark" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                        <div class="form-group col-md-2">
                            <label for="credit_limit">Reason</label>
                            <input type="text" class="form-control" id="Reason" name="Reason" autocomplete="off" placeholder="Enter Cancel Reason" required/>
                        </div>
                    </div>
                    <table class="table" id="IssueItemTable">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Invoice No / GRN No</th>
                                <th style="width: 100px; text-align:center;">Paid Amount</th>
                                <th hidden>rv</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>

                    <div class="row" style="border-top:1px solid #dee2e6;">
                        <div class="col-md-6">
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

<script src="<?php echo base_url('resources/pageJS/cancelSupplierCreditSettlement.js') ?>"></script>