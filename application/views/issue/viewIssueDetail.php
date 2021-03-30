<style>
    .first-tr {
        border: 2px solid #3d9970;
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

    .red,
    .remove {
        background: #e74c3c;
    }

    .green {
        background: #3d9970;
    }

    .static {
        position: static !important;
    }

    .center-items {
        margin: 0 auto;
    }

    input[type=text]:disabled {
        background: #ffffff;
        border: 1px solid #ced4da !important;

    }

    input[type=text]:read-only {
        background: #ffffff;
        border-color: #ffffff;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Issue Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Issue</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("Issue/ViewIssue"); ?>">View Issue</a></li>
                        <li class="breadcrumb-item active">View Issue Details</li>

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
                <!-- <form role="form" class="add-form" method="post" action="<?= base_url('GRN/EditGRNDetails/' . $grn_header_data['intGRNHeaderID']) ?>" id="editGRN"> -->
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="credit_limit">Customer</label>
                        <input type="text" class="form-control" id="Customer" name="Customer" value="<?= $issue_header_data['vcCustomerName']; ?>" style="cursor: not-allowed; color:#000000;" required disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="credit_limit">Credit Limit</label>
                        <input type="text" class="form-control" id="credit_limit" name="credit_limit" value="<?= $issue_header_data['decCreditLimit']; ?>" style="cursor: not-allowed; color:#000000;" required disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="credit_limit">Credit Balace</label>
                        <input type="text" class="form-control" id="available_limit" name="available_limit" value="<?= $issue_header_data['decAvailableCredit']; ?>" style="cursor: not-allowed; color:#000000;" required disabled>
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-6 col-sm-12">

                        <label for="customer">Payment Mode </label>
                        <!-- <div class="col-sm-10"> -->
                        <div class="form-check col-xs-2">
                            <input class="form-check-input" type="radio" name="paymentmode" id="cash" value="1"  input <?php if ($issue_header_data['intPaymentTypeID'] == 1) {
                                      print ' checked ';
                                    } ?>>
                            <label class="form-check-label" for="gridRadios1">
                                Cash
                            </label>
                        </div>
                        <div class="form-check col-xs-2">
                            <input class="form-check-input" type="radio" name="paymentmode" id="credit" value="2" input <?php if ($issue_header_data['intPaymentTypeID'] == 2) {
                                      print ' checked ';
                                    } ?>>
                            <label class="form-check-label" for="gridRadios2">
                                Credit
                            </label>
                        </div>

                        <!-- </div> -->

                    </div>
                    <div class="form-group col-md-6">
                        <label>Issued Date</label>
                        <div class="input-group date" id="dtReceivedDate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="issuedDate" name="issuedDate" style="cursor: not-allowed; color:#000000;" style="pointer-events: none !important;" />
                            <div class="input-group-append" data-target="#dtReceivedDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>

                    </div>
                </div>
                <table class="table table-striped arcadia-table" id="itemTable">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Item</th>
                            <th style="width: 200px; text-align:center;">Unit Price</th>
                            <!-- <th style="width: 200px; text-align:center;">Stock Qty</th> -->
                            <th style="width: 100px; text-align:center;">Unit</th>
                            <th style="width: 100px; text-align:center;">Issue Qty</th>
                            <th style="width: 200px; text-align:center;">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $row = 0;
                        foreach ($issue_detail_Date as $k => $v) { ?>
                            <tr>
                                <td><?= $v['vcItemName'] ?></td>
                                <td style="text-align:right;"><?= $v['decUnitPrice'] ?></td>
                                <td style="text-align:center;"><?= $v['vcMeasureUnit'] ?></td>
                                <td style="text-align:right;"><?= $v['decIssueQty'] ?></td>
                                <td style="text-align:right;"><?= $v['decTotalPrice'] ?></td>
                            </tr>
                        <?php
                            $row++;
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="row" style="border-top:1px solid #dee2e6;">
                <div class="col-md-6 col-sm-12">
                    <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                </div>
                <!-- /.col -->
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="border-top:0 !important; width:180px;">Sub Total:</th>
                                <td>
                                    <input type="text" class="form-control" style="font-weight: 600; text-align:right;" id="subTotal" name="subTotal" placeholder="0.00" value="<?= number_format($issue_header_data['decSubTotal'], 2, '.', ',') ?>" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th style="border-top:0 !important;">Discount:</th>
                                <td style="border-top:0 !important;">
                                    <input type="text" class="form-control only-decimal" name="txtDiscount" id="txtDiscount" placeholder="0.00" style="font-weight: 600; text-align:right;" value="<?= number_format($issue_header_data['decDiscount'], 2, '.', '') ?>" readonly>
                                </td>
                            </tr>
                            <tr style="border-top:2px solid #dee2e6; border-bottom:2px solid #dee2e6;">
                                <th style="font-size:1.5em;">Grand Total:</th>
                                <td>
                                    <input type="text" class="form-control" style="font-weight: 600; text-align:right; font-size:1.5em;" id="grandTotal" name="grandTotal" placeholder="0.00" value="<?= number_format($issue_header_data['decGrandTotal'], 2, '.', ',') ?>" readonly>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        </div>

        <!-- </form> -->
</div>
</div>
<!-- /.card-body -->

</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
    $(document).ready(function() {
        var rowCount = $('#itemTable tr').length;
        $("#itemCount").text("Item Count : " + (rowCount - 1));

    });
</script>