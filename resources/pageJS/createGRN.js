$(document).ready(function () {

    // $("#dtReceivedDate").datepicker().datepicker("setDate", new Date());
    // $('#dtReceivedDate').datepicker('setDate', 'today');
    // $("#dtReceivedDate").val(formatDate('dd-M-y', new Date()));

    $('#supplier').on('select2:close', function (e) {
        getDetailBySupplierID();
    });


    $(document).on('keyup', 'input[type=search]', function (e) {
        $("li").attr('aria-selected', false);
    });


    $('#cmbItem').on('select2:select', function (e) {
        $('#txtUnitPrice').focus();
    });

    $('#txtQty,#txtUnitPrice').keyup(function (event) {
        CalculateTotal();
    });

    $('#txtDiscount').keyup(function (event) {
        CalculateGrandTotal();
    });
    $("#btnAddToGrid").click(function () {
        AddToGrid();
    });
    //Bind keypress event to textbox
    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            AddToGrid();
        }
        //Stop the event from propogation to other handlers
        //If this line will be removed, then keypress event handler attached 
        //at document level will also be triggered
        event.stopPropagation();
    });

    function CalculateTotal() {
        getMeasureUnitByItemID();
        var unitPrice = $("#txtUnitPrice").val();
        var qty = $("#txtQty").val()

        if (unitPrice != "" && qty != "") {
            var total = unitPrice * qty;
            $("#txtTotalPrice").val(currencyFormat(total));
        }
    }

    function CalculateGrandTotal() {
        if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
            var discount = $("#txtDiscount").val();
            var total = 0;
            $('#itemTable tbody tr').each(function () {
                var value = parseFloat($(this).closest("tr").find('.total').val());
                if (!isNaN(value)) {
                    total += value;
                }

            });

            discount == "" ? discount = 0 : discount;

            $("#subTotal").val(currencyFormat(total));
            $("#grandTotal").val(currencyFormat(total - discount));

        } else {
            $("#subTotal").val("0.00");
            $("#txtDiscount").val("0.00");
            $("#grandTotal").val("0.00");
        }
    }

    function CalculateItemCount() {
        var rowCount = $('#itemTable tr').length;
        $("#itemCount").text("Item Count : " + (rowCount - 2));
    }

    remove();

    var row_id = 1;

    function AddToGrid() {
        if ($("#cmbItem option:selected").val() == 0 || $("input[name=txtUnitPrice]").val()=="" || $("input[name=txtQty]").val() == "") {
            toastr["error"]("Please fill in all fields !");
        } else {
            if ($("#cmbItem option:selected").val() > 0) {
                var itemID = $("#cmbItem option:selected").val();
                var item = $("#cmbItem option:selected").text();
                var measureUnit = $("input[name=txtMeasureUnit]").val();
                var unitPrice = $("input[name=txtUnitPrice]").val();
                var qty = $("input[name=txtQty]").val();
                var total = unitPrice * qty;

                $(".first-tr").after('<tr>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control disable-typing" style="text-align:right;" name="unitPrice[]" id="unitPrice_' + row_id + '" value="' + parseFloat(unitPrice).toFixed(2) + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="totalPrice[]" id="totalPrice_' + row_id + '"  value="' + parseFloat(total).toFixed(2) + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>');

                row_id++;
                remove();
                $("#cmbItem :selected").remove();

                $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice], input[name=txtQty]").val("");
                $("input[name=txtTotalPrice]").val("0.00");
                CalculateItemCount();
                CalculateGrandTotal();
                $("#cmbItem").focus();
                $("li").attr('aria-selected', false);
            } else {
                toastr["error"]("Please select valid item !");
                $("#cmbItem").focus();
                $("li").attr('aria-selected', false);
            }
        }
    }
    // $(".add-form").on('submit', function(e) {
    //     e.preventDefault();
    //     if ($("input[name=cmbItem]").val(0), $("input[name=txtUnitPrice]").val(), $("input[name=txtQty]").val() == "") {
    //         toastr["error"]("Please fill in all fields !");
    //     } else {
    //         if ($("#cmbItem option:selected").val() > 0) {
    //             var itemID = $("#cmbItem option:selected").val();
    //             var item = $("#cmbItem option:selected").text();
    //             var unitPrice = $("input[name=txtUnitPrice]").val();
    //             var qty = $("input[name=txtQty]").val();
    //             var total = unitPrice * qty;

    //             // $("#grnItemForm").append('<tr><td class="itemID" hidden>' + itemID + '</td><td class="itemName">' + item + '</td><td style="text-align:right;">' + parseFloat(unitPrice).toFixed(2) + '</td><td style="text-align:right;">' + qty + '</td><td class="total" style="text-align:right;">' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');

    //             $(".first-tr").after('<tr><td class="itemID" hidden>' + itemID + '</td><td class="itemName">' + item + '</td><td style="text-align:right;">' + parseFloat(unitPrice).toFixed(2) + '</td><td style="text-align:right;">' + qty + '</td><td class="total" style="text-align:right;">' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');
    //             remove();
    //             $("#cmbItem :selected").remove();

    //             $("input[name=cmbItem], input[name=txtUnitPrice], input[name=txtQty]").val("");
    //             $("input[name=txtTotalPrice]").val("0.00");
    //             CalculateItemCount();
    //             CalculateGrandTotal();
    //             $("#cmbItem").focus();
    //         } else {
    //             toastr["error"]("Please select valid item !");
    //             $("#cmbItem").focus();
    //         }


    //     }
    // });



    function remove() {
        $(".red").click(function () {
            // var itemID = $(this).closest("tr").find('td.itemID').text();
            // var itemName = $(this).closest("tr").find('td.itemName').text();

            var itemID = $(this).closest("tr").find('.itemID').val();
            var itemName = $(this).closest("tr").find('.itemName').val();

            var IsAlreadyIncluded = false;

            $("#cmbItem option").each(function () {
                if (itemID == $(this).val()) {
                    IsAlreadyIncluded = true;
                    return false;
                }
            });

            if (!IsAlreadyIncluded) {
                var cmbItem = $('#cmbItem');
                cmbItem.append(
                    $('<option></option>').val(itemID).html(itemName)
                );
                $(this).closest("tr").remove();
            }
            CalculateItemCount();
            CalculateGrandTotal();
        });
    }

    $('#btnSubmit').click(function () {

        if ($('#supplier').val() == null) {
            toastr["error"]("Please select a supplier !");
            $("#supplier").focus();
        } else if (jQuery.trim($("#invoice_no").val()).length == 0) {
            toastr["error"]("Please enter invoice no !");
            $("#invoice_no").focus();
        } else if (isNaN(Date.parse($("#receivedDate").val()))) {
            toastr["error"]("Please select received date !");
        } else if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please choose the receive items !");
            $("#cmbItem").focus();
        } else {
            arcadiaConfirmAlert("You want to be able to save this !", function (button) {

                var form = $("#createGRN");

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            arcadiaSuccessMessage("Saved !");
                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function (index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-group')
                                        .removeClass('has-error')
                                        .removeClass('has-success')
                                        .addClass(value.length > 0 ? 'has-error' : 'has-success');

                                    id.after(value);

                                });
                            } else {
                                toastr["error"](response.messages);
                                // arcadiaErrorMessage(response.messages);
                                // $(button).prop('disabled', false);
                            }
                        }

                    }
                });
            }, this);
        }


        // jQuery.trim($("#receivedDate").val()).length == 0 ||


        // if (isNaN(Date.parse($("#dtReceivedDate").val()))) {
        //     toastr["error"]("Please Select Received Date !");
        // } else {
        //     alert($("#dtReceivedDate").val());

        // }

    });


    $("#createGRN").unbind('submit').on('submit', function (e) {

        // var form = $(this);

        // $.ajax({
        //     type: form.attr('method'),
        //     url: form.attr('action'),
        //     data: form.serialize(),
        //     dataType: 'json',
        //     success: function (response) {
        //         alert(response.success);
        //     }
        // });
        // e.preventDefault();
    });
    //  });

});

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

function getDetailBySupplierID() {
    var supplierID = $("#supplier option:selected").val();
    if (supplierID > 0) {
        $.ajax({
            async: false,
            url: base_url + 'supplier/getDetailBySupplierID/' + supplierID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#credit_limit").val(response.decCreditLimit);
                $("#available_limit").val(response.decAvailableCredit);


                // CreditBuyAmount = parseFloat(response.decCreditBuyAmount)
                // AvailableCredit = parseFloat(response.decAvailableCredit)

            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                alert(xhr.responseText);
            }
        });
    }
}


function getMeasureUnitByItemID() {
    var ItemID = $("#cmbItem").val();
    if (ItemID > 0) {
        $.ajax({
            url: 'getMeasureUnitByItemID/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#txtMeasureUnit").val(response.vcMeasureUnit);
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}

// // steal focus during close - only capture once and stop propogation
// $('select.select2').on('select2:closing', function(e) {
//     $(e.target).data("select2").$selection.one('focus focusin', function(e) {
//         e.stopPropagation();
//     });
// });