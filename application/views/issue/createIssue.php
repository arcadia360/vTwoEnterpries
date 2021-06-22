<style>
    /* body {
        background: #f6f6f6;
    } */

    /* #table {
        position: relative;
        overflow: hidden;
        margin-top: 50px;
    } */

    /* table {
        background: black;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.5);
    } */



    .first-tr {
        /* background-color: #c2c7d0; */
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

    /* tr>td {
        position: relative;
    } */

    .static {
        position: static !important;
    }

    .center-items {
        /* align-items: center; */
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

    .select2-results span[lowstock="true"] {
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
                    <h1>Create Issue</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Issue</a></li>
                        <li class="breadcrumb-item active">Create Issue</li>
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
                <form role="form" class="add-form" method="post" action="<?= base_url('Issue/SaveIssue') ?>" id="createIssue">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="customer">Customer</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbcustomer" name="cmbcustomer">
                                <option value="0" disabled selected hidden>Select Customer</option>
                                <?php foreach ($customer_data as $k => $v) { ?>
                                    <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="customer">Sales Rep</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbSalesRep" name="cmbSalesRep">
                                <option value="0" disabled selected hidden>Select Sales Rep</option>
                                <?php foreach ($sales_rep_data as $k => $v) { ?>
                                    <option value="<?= $v['intSalesRepID'] ?>"><?= $v['vcSalesRepName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Credit Limit</label>
                            <input type="text" class="form-control" id="credit_limit" name="credit_limit" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="credit_limit">Credit Balace</label>
                            <input type="text" class="form-control" id="available_limit" name="available_limit" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <!-- <div class="form-group col-md-3">
                            <input type="checkbox" class="form-check-input" id="IsAdvancePayment" name="IsAdvancePayment">
                            <label class="form-check-label" for="IsAdvancePayment">Advance Payment</label>
                            <input type="text" class="form-control" id="advance_payment" name="advance_payment" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div> -->
                        <div class="form-group col-md-2">
                        <input type="checkbox" class="form-check-input" id="IsAdvancePayment" name="IsAdvancePayment"  style="margin-left: 0; padding-right: 1em; position:relative; float:left;">
                            <label style="margin-left: 1em;" for="advance_payment">Advance Payment</label>
                            <input type="text" class="form-control" id="advance_payment" name="advance_payment" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Payment Mode :</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbpayment" name="cmbpayment">
                                <!-- <option value="0" selected hidden>All Payments</option> -->
                                <?php foreach ($payment_data as $k => $v) { ?>
                                    <option value="<?= $v['intPaymentTypeID'] ?>"><?= $v['vcPaymentType'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Issued Date</label>
                            <div class="input-group date" id="dtReceivedDate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="issuedDate" name="issuedDate" placeholder="Select Issue Date" style="pointer-events: none !important;" />
                                <div class="input-group-append" data-target="#dtReceivedDate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="credit_limit">Remark</label>
                            <input type="text" class="form-control" id="txtRemark" name="txtRemark" autocomplete="off" placeholder="Not Required"/>
                        </div>
                    </div>

                    <table class="table arcadia-table" id="itemTable">
                        <thead>
                            <tr>
                                <th hidden>Item ID</th>
                                <th hidden>GRN Detail ID</th>
                                <th style="text-align:center;">Item</th>
                                <th style="width: 200px; text-align:center; width:80px;">Unit Price</th>
                                <th style="width: 200px; text-align:center; width:60px;">Stock Qty</th>
                                <th style="width: 100px; text-align:center;">Unit</th>
                                <th style="width: 100px; text-align:center;">Qty</th>
                                <th style="width: 100px; text-align:center;  width:40px;">Dis.(%)</th>
                                <th hidden>withotDiscount</th>
                                <th style="width: 200px; text-align:center;">Total Price</th>
                                <th hidden>rv</th>
                                <th style="width: 100px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="first-tr">
                                <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                <td class="static">
                                    <!-- <input type="text" class="form-control" name="txtItem"> -->
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem">
                                        <option value="0" disabled selected hidden>Select Item</option>

                                        <!-- <?php foreach ($item_data as $k => $v) {
                                            if ($v['decReOrderLevel'] >= $v['decStockInHand']) { 
                                                $lowstock = "true";
                                            } else {
                                                $lowstock = "false";
                                            }

                                        ?>
                                            <option value="<?= $v['intItemID'] ?>" lowstock="<?= $lowstock ?>"><?= $v['vcItemName'] ?></option>
                                        <?php } ?> -->
                                    </select>
                                </td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtUnitPrice" id="txtUnitPrice" style="text-align:right; width:80px;" disabled></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtStockQty" id="txtStockQty" style="text-align:right; width:60px;" disabled></td>
                                <td class="static"><input type="text" class="form-control add-item" name="txtMeasureUnit" id="txtMeasureUnit" style="text-align:center;" disabled></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtDiscountPercentage" id="txtDiscountPercentage" maxlength="2" style="text-align:right;  width:40px;"></td>
                                <td class="static" hidden><input type="text" class="form-control only-decimal add-item" name="txtWithouDiscount" id="txtWithouDiscount" style="text-align:right;"></td>
                                <td class="static"><input type="text" class="form-control only-decimal" name="txtTotalPrice" id="txtTotalPrice" placeholder="0.00" style="text-align:right;" disabled></td>
                                <td class="static" hidden><input type="text" class="form-control" name="txtRv" id="txtRv"></td>
                                <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row" style="border-top:1px solid #dee2e6;">
                        <div class="col-6">
                            <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                        </div>
                        <!-- /.col -->
                        <div class="col-6" style="padding-right:100px;">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="border-top:0 !important; width:180px;">Sub Total:</th>
                                        <td>
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right;" id="subTotal" name="subTotal" placeholder="0.00" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="border-top:0 !important;">Discount:</th>
                                        <td style="border-top:0 !important;">
                                            <input type="text" class="form-control" name="txtDiscount" id="txtDiscount" placeholder="0.00" style="font-weight: 600; text-align:right;" readonly>
                                        </td>
                                    </tr>
                                    <tr style="border-top:2px solid #dee2e6; border-bottom:2px solid #dee2e6;">
                                        <th style="font-size:1.5em;">Grand Total:</th>
                                        <td>
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right; font-size:1.5em !important;" id="grandTotal" name="grandTotal" placeholder="0.00" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
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


<script src="<?php echo base_url('resources/pageJS/createIssue.js') ?>"></script>
<script>
function formatState (state) {
  if(!state.element) return;
  var os = $(state.element).attr('lowstock');
  return $('<span lowstock="' + os + '">' + state.text + '</span>');
}

$(document).ready(function() {
    $('select').select2({
      templateResult: formatState
    });
});


</script>

<!-- <script>
          $("#IsAdvancePayment").is(':checked', function(){
              $("#IsAdvancePayment").attr('value', 'true');
          });
</script> -->