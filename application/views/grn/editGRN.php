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
                    <h1>Edit GRN</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Stock</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("GRN/ViewGRN"); ?>">View GRN</a></li>
                        <li class="breadcrumb-item active">Edit GRN</li>

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
                <form role="form" class="add-form" method="post" action="<?= base_url('GRN/EditGRNDetails/' . $grn_header_data['intGRNHeaderID']) ?>" id="editGRN">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="invoice_no">GRN No</label>
                            <input type="text" class="form-control" id="grn_no" name="grn_no" value="<?= $grn_header_data['vcGRNNo']; ?>" style="cursor: not-allowed; color:#000000;" required disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="supplier">Supplier</label>
                            <select class="form-control select2" style="width: 100%;" id="supplier" name="supplier">
                                <?php foreach ($supplier_data as $k => $v) {
                                    $isSelected = "";
                                    $ReceivedDate = date("m/d/Y", strtotime($grn_header_data['dtReceivedDate']));

                                    if ($grn_header_data['intSupplierID'] == $v['intSupplierID']) {
                                        $isSelected = "selected";
                                    }
                                ?>
                                    <option value="<?= $v['intSupplierID']; ?>" <?= $isSelected; ?>><?= $v['vcSupplierName']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="invoice_no">Invoice No</label>
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Enter Invoice Number" autocomplete="off" required value="<?= $grn_header_data['vcInvoiceNo']; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Received Date</label>
                            <div class="input-group date" id="dtReceivedDate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="receivedDate" name="receivedDate" placeholder="Select Received Date" style="pointer-events: none !important;" value="<?= $ReceivedDate  ?>" />
                                <div class="input-group-append" data-target="#dtReceivedDate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped arcadia-table" id="itemTable">
                                <thead>
                                    <tr>
                                        <th hidden>GRN Detail ID</th>
                                        <th style="text-align:center;">Item</th>
                                        <th style="text-align:center;">Unit Price</th>
                                        <th style="text-align:center;">Unit</th>
                                        <th style="text-align:center;">Qty</th>
                                        <th style="text-align:center;">Total Price</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr class="first-tr">
                                        <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                        <td class="static">
                                            <select class="form-control select2" id="cmbItem" name="cmbItem">
                                                <option value=" 0" disabled selected hidden>Select Item</option>
                                                <?php foreach ($item_data as $k => $v) { ?>
                                                    <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtUnitPrice" id="txtUnitPrice" style="text-align:right;"></td>
                                        <td class="static"><input type="text" class="form-control add-item" name="txtMeasureUnit" id="txtMeasureUnit" style="text-align:center;" disabled></td>
                                        <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                        <td class="static"><input type="text" class="form-control only-decimal" name="txtTotalPrice" id="txtTotalPrice" placeholder="0.00" style="text-align:right;" disabled></td>
                                        <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                                    </tr>
                                    <?php
                                    $row = 0;
                                    foreach ($grn_detail_data as $k => $v) { ?>
                                        <tr>
                                            <td hidden><input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_<?= $row ?>" value="<?= $v['intItemID'] ?>" readonly></td>
                                            <td><input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_<?= $row ?>" value="<?= $v['vcItemName'] ?>" readonly></td>
                                            <td><input type="text" class="form-control disable-typing" style="text-align:right;" name="unitPrice[]" id="unitPrice_<?= $row ?>" value="<?= $v['decUnitPrice'] ?>" readonly></td>
                                            <td><input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_<?= $row ?>" value="<?= $v['vcMeasureUnit'] ?>" readonly></td>
                                            <td><input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_<?= $row ?>" value="<?= $v['decQty'] ?>" readonly></td>
                                            <td><input type="text" class="form-control total disable-typing" style="text-align:right;" name="totalPrice[]" id="totalPrice_<?= $row ?>" value="<?= $v['decTotalPrice'] ?>" readonly></td>
                                            <td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td>
                                        </tr>
                                    <?php
                                        $row++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
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
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right;" id="subTotal" name="subTotal" placeholder="0.00" value="<?= number_format($grn_header_data['decSubTotal'], 2, '.', ',') ?>" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="border-top:0 !important;">Discount:</th>
                                        <td style="border-top:0 !important;">
                                            <input type="text" class="form-control only-decimal" name="txtDiscount" id="txtDiscount" placeholder="0.00" style="font-weight: 600; text-align:right;" value="<?= number_format($grn_header_data['decDiscount'], 2, '.', '') ?>">
                                        </td>
                                    </tr>
                                    <tr style="border-top:2px solid #dee2e6; border-bottom:2px solid #dee2e6;">
                                        <th style="font-size:1.5em;">Grand Total:</th>
                                        <td>
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right; font-size:1.5em;" id="grandTotal" name="grandTotal" placeholder="0.00" value="<?= number_format($grn_header_data['decGrandTotal'], 2, '.', ',') ?>" readonly>
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
<!-- /.content-wrapper -->



<script src="<?php echo base_url('resources/pageJS/editGRN.js') ?>"></script>