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
                    <h1>Create GRN</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">GRN</a></li>
                        <li class="breadcrumb-item active">Create GRN</li>
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
                <form role="form" class="add-form" method="post" action="<?= base_url('GRN/SaveGRN') ?>" id="createGRN">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="supplier">Supplier</label>
                            <select class="form-control select2" style="width: 100%;" id="supplier" name="supplier">
                                <option value="0" disabled selected hidden>Select Supplier</option>
                                <?php foreach ($supplier_data as $k => $v) { ?>
                                    <option value="<?= $v['intSupplierID'] ?>"><?= $v['vcSupplierName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="credit_limit">Credit Limit</label>
                            <input type="text" class="form-control" id="credit_limit" name="credit_limit" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="credit_limit">Credit Balace</label>
                            <input type="text" class="form-control" id="available_limit" name="available_limit" autocomplete="off" style="cursor: not-allowed; color:#000000;" disabled />
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>Payment Mode :</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbpayment" name="cmbpayment">
                                <!-- <option value="0" selected hidden>All Payments</option> -->
                                <?php foreach ($payment_data as $k => $v) { ?>
                                    <option value="<?= $v['intPaymentTypeID'] ?>"><?= $v['vcPaymentType'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="invoice_no">Invoice No</label>
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Enter Invoice Number" autocomplete="off" required />
                        </div>
                        <div class="form-group col-md-4">
                            <label>Received Date</label>
                            <div class="input-group date" id="dtReceivedDate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="receivedDate" name="receivedDate" placeholder="Select Received Date" style="pointer-events: none !important;" />
                                <div class="input-group-append" data-target="#dtReceivedDate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="invoice_no">Remark</label>
                            <input type="text" class="form-control" id="txtRemark" name="txtRemark" autocomplete="off" placeholder="Not Required" />
                        </div>
                    </div>

                    <table class="table arcadia-table" id="itemTable">
                        <thead>
                            <tr>
                                <th hidden>Item ID</th>
                                <th style="text-align:center;">Item</th>
                                <th style="width: 200px; text-align:center;">Unit Price</th>
                                <th style="width: 100px; text-align:center;">Unit</th>
                                <th style="width: 100px; text-align:center;">Qty</th>
                                <th style="width: 200px; text-align:center;">Total Price</th>
                                <th style="width: 100px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="first-tr">
                                <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                <td class="static">
                                    <!-- <input type="text" class="form-control" name="txtItem"> -->
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem" onchange="getMeasureUnitByItemID();"">
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
                                            <input type="text" class="form-control only-decimal" name="txtDiscount" id="txtDiscount" placeholder="0.00" style="font-weight: 600; text-align:right;">
                                        </td>
                                    </tr>
                                    <tr style="border-top:2px solid #dee2e6; border-bottom:2px solid #dee2e6;">
                                        <th style="font-size:1.5em;">Grand Total:</th>
                                        <td>
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right; font-size:1.5em;" id="grandTotal" name="grandTotal" placeholder="0.00" readonly>
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

<script src="<?php echo base_url('resources/pageJS/createGRN.js') ?>"></script>

<script>
    // $(document).ready(function() {

    //     // $("#dtReceivedDate").datepicker().datepicker("setDate", new Date());
    //     // $('#dtReceivedDate').datepicker('setDate', 'today');
    //     // $("#dtReceivedDate").val(formatDate('dd-M-y', new Date()));

    //     $("#dtReceivedDate").datepicker({
    //         minDate: -0,
    //         maxDate: new Date()
    //     });

    //     $('#cmbItem').on('select2:select', function(e) {
    //         $('#txtUnitPrice').focus();
    //     });

    //     $('#txtQty,#txtUnitPrice').keyup(function(event) {
    //         CalculateTotal();
    //     });

    //     $('#txtDiscount').keyup(function(event) {
    //         CalculateGrandTotal();
    //     });
    //     $("#btnAddToGrid").click(function() {
    //         AddToGrid();
    //     });
    //     //Bind keypress event to textbox
    //     $('.add-item').keypress(function(event) {
    //         var keycode = (event.keyCode ? event.keyCode : event.which);
    //         if (keycode == '13') {
    //             AddToGrid();
    //         }
    //         //Stop the event from propogation to other handlers
    //         //If this line will be removed, then keypress event handler attached 
    //         //at document level will also be triggered
    //         event.stopPropagation();
    //     });

    //     function CalculateTotal() {
    //         var unitPrice = $("#txtUnitPrice").val();
    //         var qty = $("#txtQty").val()

    //         if (unitPrice != "" && qty != "") {
    //             var total = unitPrice * qty;
    //             $("#txtTotalPrice").val(parseFloat(total).toFixed(2));
    //         }
    //     }

    //     function CalculateGrandTotal() {
    //         if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
    //             var discount = $("#txtDiscount").val();
    //             var total = 0;
    //             $('#itemTable tbody tr').each(function() {
    //                 var value = parseInt($(this).closest("tr").find('td.total').text());
    //                 if (!isNaN(value)) {
    //                     total += value;
    //                 }

    //             });

    //             discount == "" ? discount = 0 : discount;

    //             $("#subTotal").text(parseFloat(total).toFixed(2));
    //             $("#grandTotal").text(parseFloat(total - discount).toFixed(2));

    //         } else {
    //             $("#subTotal").text("0.00");
    //             $("#txtDiscount").text("0.00");
    //             $("#grandTotal").text("0.00");
    //         }
    //     }

    //     function CalculateItemCount() {
    //         var rowCount = $('#itemTable tr').length;
    //         $("#itemCount").text("Item Count : " + (rowCount - 2));
    //     }

    //     remove();

    //     function AddToGrid() {
    //         if ($("input[name=cmbItem]").val(0), $("input[name=txtUnitPrice]").val(), $("input[name=txtQty]").val() == "") {
    //             toastr["error"]("Please fill in all fields !");
    //         } else {
    //             if ($("#cmbItem option:selected").val() > 0) {
    //                 var itemID = $("#cmbItem option:selected").val();
    //                 var item = $("#cmbItem option:selected").text();
    //                 var unitPrice = $("input[name=txtUnitPrice]").val();
    //                 var qty = $("input[name=txtQty]").val();
    //                 var total = unitPrice * qty;

    //                 $(".first-tr").after('<tr><td class="itemID" hidden>' + itemID + '</td><td class="itemName">' + item + '</td><td style="text-align:right;">' + parseFloat(unitPrice).toFixed(2) + '</td><td style="text-align:right;">' + qty + '</td><td class="total" style="text-align:right;">' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');
    //                 remove();
    //                 $("#cmbItem :selected").remove();

    //                 $("input[name=cmbItem], input[name=txtUnitPrice], input[name=txtQty]").val("");
    //                 $("input[name=txtTotalPrice]").val("0.00");
    //                 CalculateItemCount();
    //                 CalculateGrandTotal();
    //                 $("#cmbItem").focus();
    //             } else {
    //                 toastr["error"]("Please select valid item !");
    //                 $("#cmbItem").focus();
    //             }
    //         }
    //     }
    //     // $(".add-form").on('submit', function(e) {
    //     //     e.preventDefault();
    //     //     if ($("input[name=cmbItem]").val(0), $("input[name=txtUnitPrice]").val(), $("input[name=txtQty]").val() == "") {
    //     //         toastr["error"]("Please fill in all fields !");
    //     //     } else {
    //     //         if ($("#cmbItem option:selected").val() > 0) {
    //     //             var itemID = $("#cmbItem option:selected").val();
    //     //             var item = $("#cmbItem option:selected").text();
    //     //             var unitPrice = $("input[name=txtUnitPrice]").val();
    //     //             var qty = $("input[name=txtQty]").val();
    //     //             var total = unitPrice * qty;

    //     //             // $("#grnItemForm").append('<tr><td class="itemID" hidden>' + itemID + '</td><td class="itemName">' + item + '</td><td style="text-align:right;">' + parseFloat(unitPrice).toFixed(2) + '</td><td style="text-align:right;">' + qty + '</td><td class="total" style="text-align:right;">' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');

    //     //             $(".first-tr").after('<tr><td class="itemID" hidden>' + itemID + '</td><td class="itemName">' + item + '</td><td style="text-align:right;">' + parseFloat(unitPrice).toFixed(2) + '</td><td style="text-align:right;">' + qty + '</td><td class="total" style="text-align:right;">' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');
    //     //             remove();
    //     //             $("#cmbItem :selected").remove();

    //     //             $("input[name=cmbItem], input[name=txtUnitPrice], input[name=txtQty]").val("");
    //     //             $("input[name=txtTotalPrice]").val("0.00");
    //     //             CalculateItemCount();
    //     //             CalculateGrandTotal();
    //     //             $("#cmbItem").focus();
    //     //         } else {
    //     //             toastr["error"]("Please select valid item !");
    //     //             $("#cmbItem").focus();
    //     //         }


    //     //     }
    //     // });

    //     function remove() {
    //         $(".red").click(function() {

    //             var itemID = $(this).closest("tr").find('td.itemID').text();
    //             var itemName = $(this).closest("tr").find('td.itemName').text();

    //             var cmbItem = $('#cmbItem');

    //             cmbItem.append(
    //                 $('<option></option>').val(itemID).html(itemName)
    //             );

    //             $(this).closest("tr").remove();
    //             CalculateItemCount();
    //             CalculateGrandTotal();
    //         });
    //     }

    //     $('#btnSubmit').click(function() {
    //         debugger;
    //         if ($('#supplier').val() == null) {
    //             toastr["error"]("Please select a supplier !");
    //             $("#supplier").focus();
    //         } else if (jQuery.trim($("#invoice_no").val()).length == 0) {
    //             toastr["error"]("Please enter invoice no !");
    //             $("#invoice_no").focus();
    //         } else if (isNaN(Date.parse($("#dtReceivedDate").val()))) {
    //             toastr["error"]("Please select received date !");
    //         } else if ($('#itemTable tr').length == 2) {
    //             toastr["error"]("Please choose the receive items !");
    //             $("#cmbItem").focus();
    //         }


    //         // jQuery.trim($("#receivedDate").val()).length == 0 ||


    //         // if (isNaN(Date.parse($("#dtReceivedDate").val()))) {
    //         //     toastr["error"]("Please Select Received Date !");
    //         // } else {
    //         //     alert($("#dtReceivedDate").val());

    //         // }

    //     });

    // });

    // // on first focus (bubbles up to document), open the menu
    // $(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
    //     $(this).closest(".select2-container").siblings('select:enabled').select2('open');
    // });

    // // // steal focus during close - only capture once and stop propogation
    // // $('select.select2').on('select2:closing', function(e) {
    // //     $(e.target).data("select2").$selection.one('focus focusin', function(e) {
    // //         e.stopPropagation();
    // //     });
    // // });
</script>