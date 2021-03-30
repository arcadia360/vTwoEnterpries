var AdvanceAmount = 0;
var CreditBuyAmount = 0;
var AvailableCredit = 0;
$(document).ready(function () {



    // $('#cmbcustomer').on('select2:select', function (e) {
    //     // getDetailByCustomerID();
    //     $("#itemTable").find("tr:gt(1)").remove();
    //     $('#cmbItem').val('0'); // Select the option with a value of '0'
    //     $('#cmbItem').trigger('change'); // Notify any JS components that the value changed
    //     $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice], input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice],input[name=grandTotal],input[name=subTotal],input[name=txtDiscount]").val("");
    //     CalculateItemCount();
    //     getcmbItemDate();
    // });

    $('#cmbcustomer').on('select2:close', function (e) {
        // getDetailByCustomerID();
        $("#itemTable").find("tr:gt(1)").remove();
        $('#cmbItem').val('0'); // Select the option with a value of '0'
        $('#cmbItem').trigger('change'); // Notify any JS components that the value changed
        $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice], input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice],input[name=grandTotal],input[name=subTotal],input[name=txtDiscount]").val("");
        CalculateItemCount();
        // getcmbItemDate();
    });

    getcmbItemData(); 


    $(document).on('keyup', 'input[type=search]', function (e) {
        $("li").attr('aria-selected', false);
    });

    $("#cmbcustomer").on('select2:close', function (event) {
        getDetailByCustomerID();
    });

    $("#cmbItem").on('select2:close', function (event) {
        
        $("input[name=txtQty],input[name=txtTotalPrice]").val("");
        $('#txtQty').focus();
        getItemDetailsByCustomerID();

    });

    $('#IsAdvancePayment').change(function () {
        if ($(this).is(':checked')) {
            $(this).attr('value', 'true');
        } else {
            $(this).attr('value', 'false');
        }

        //   $("#itemTable").find("tr:gt(1)").remove();
        //   $('#cmbItem').val('0'); 
        //   $('#cmbItem').trigger('change');
        //   $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice], input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice],input[name=grandTotal],input[name=subTotal],input[name=txtDiscount]").val("");
        //   CalculateItemCount();
        //   getcmbItemDate();

    });

    $('#cmbpayment').on('select2:select', function (e) {
        // getDetailByCustomerID();
        $("#itemTable").find("tr:gt(1)").remove();
        $('#cmbItem').val('0'); // Select the option with a value of '0'
        $('#cmbItem').trigger('change'); // Notify any JS components that the value changed
        $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice], input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice],input[name=grandTotal],input[name=subTotal],input[name=txtDiscount]").val("");
        CalculateItemCount();
        // getcmbItemDate();

    });

    // $('#cmbItem').on('select2:select', function (e) {
    //     $("input[name=txtQty],input[name=txtTotalPrice]").val("");
    //     $('#txtQty').focus();
    // });

    $('#txtQty,#txtUnitPrice').keyup(function (event) {
        CalculateTotal();
    });

    $('#txtDiscount').keyup(function (event) {
        CalculateGrandTotal();
    });

    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if ($("#cmbcustomer option:selected").val() == 0) {
                toastr["error"]("Please select customer !");
                return;
            }
            if ($("input[name=txtStockQty]").val() == "N/A" || $("input[name=txtStockQty]").val() == "0.00") {
                toastr["error"]("Please can't Add Stock Qty N/A");
                return;
            }

            if ($("input[name=txtQty]").val() == "") {
                toastr["error"]("Please Enter Issue Qty !");
                return;
            }

            if ($("#cmbpayment option:selected").val() == 2) { //Credit
                if (chkCreditLimit() == false) {
                    toastr["error"]("Customer CreditLimit Exceed !");
                    return;
                }
                else {
                    AddToGrid(true);
                    return;
                }
            }
            else {
                AddToGrid(true);
            }
        }

        event.stopPropagation();
    });

    $('#cmbpayment').on('select2:select', function (e) {
        if ($("#cmbpayment option:selected").val() == 2) {
            if ($("#cmbcustomer option:selected").val() == 0) {
                toastr["error"]("Please select customer !");
                return;
            }
            if (chkCreditLimit() == false) {
                toastr["error"]("Customer CreditLimit Exceed !");
            }
        }
    });


    function CalculateTotal() {
        // getMeasureUnitByItemID();
        var unitPrice = $("#txtUnitPrice").val();
        var qty = $("#txtQty").val()

        if (unitPrice != "" && qty != "") {
            var total = unitPrice * qty;
            $("#txtTotalPrice").val(currencyFormat(total));
        }
    }


    $('#txtQty').on('keyup', function (e) {
        if ($('#txtStockQty').val() == 0) {
            $('#txtQty').val(null);
            toastr["error"]("You can't Issue this. Because this item stock quantity is zero!");
        } else if ($('#txtStockQty').val() > 0) {
            if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
                toastr["error"]("You can't exceed stock quantity  !");
            }
        }

    });

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

    remove();

    $("#btnAddToGrid").click(function () {

        if ($("#cmbcustomer option:selected").val() == 0) {
            toastr["error"]("Please select customer !");
            return;
        }
        if ($("input[name=txtStockQty]").val() == "N/A" || $("input[name=txtStockQty]").val() == "0.00") {
            toastr["error"]("Please can't Add Stock Qty N/A !");
            return;
        }

        if ($("input[name=txtQty]").val() == "") {
            toastr["error"]("Please Enter Issue Qty !");
            return;
        }

        if ($("#cmbpayment option:selected").val() == 2) { //Credit
            if (chkCreditLimit() == false) {
                toastr["error"]("Customer CreditLimit Exceed !");
            }
            else {

                AddToGrid(false);
            }
        }
        else {
            AddToGrid(false);
        }
    });


    var row_id = 1;

    function AddToGrid(IsMouseClick = false) {

        if ($("#cmbcustomer option:selected").val() == 0) {
            toastr["error"]("Please select customer !");
            return;
        }
        
        else {
            if ($("#txtQty").val() > 0) {

                if ($('#txtStockQty').val() == 0) {
                    if (IsMouseClick) {
                        $('#txtQty').val(null);
                        toastr["error"]("You can't dispatch this. Because this item stock quantity is zero!");
                    }
                } else if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
                    if (IsMouseClick) {
                        toastr["error"]("You can't exceed stock quantity  !");
                    }
                } else {
                    if ($("#cmbItem option:selected").val() > 0) {
                        var itemID = $("#cmbItem option:selected").val();
                        var item = $("#cmbItem option:selected").text();
                        var measureUnit = $("input[name=txtMeasureUnit]").val();
                        var stockQty = $("input[name=txtStockQty]").val();
                        var unitPrice = $("input[name=txtUnitPrice]").val();
                        var qty = $("input[name=txtQty]").val();
                        var Rv = $("input[name=txtRv]").val();
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
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQty + '" readonly>' +
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
                            '<td hidden>' +
                            '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + Rv + '" readonly>' +
                            '</td>' +
                            '<td class="static">' +
                            '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                            '</td>' +
                            '</tr>');

                        row_id++;
                        remove();
                        $("#cmbItem :selected").remove();

                        $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice],input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice]").val("");
                        // $("input[name=txtTotalPrice]").val("0.00");
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
            debugger;
            $("#subTotal").val("0.00");
            $("#txtDiscount").val("0.00");
            $("#grandTotal").val("0.00");
        }
    }
});

function getDetailByCustomerID() {
    var customerID = $("#cmbcustomer option:selected").val();
    if (customerID > 0) {
        $.ajax({
            async: false,
            url: base_url + 'customer/fetchCustomerDataById/' + customerID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#credit_limit").val(response.decCreditLimit);
                $("#available_limit").val(response.decAvailableCredit);
                $("#advance_payment").val(response.decAdvanceAmount);

                AdvanceAmount = parseFloat(response.decAdvanceAmount)
                CreditBuyAmount = parseFloat(response.decCreditBuyAmount)
                AvailableCredit = parseFloat(response.decAvailableCredit)

                if (AdvanceAmount > 0) {
                    document.getElementById("IsAdvancePayment").checked = true;
                }
                else {
                    document.getElementById("IsAdvancePayment").checked = false;
                }
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                alert(xhr.responseText);
            }
        });
    }
}


function getItemDetailsByCustomerID() {

    var ItemID = $("#cmbItem").val();
    var customerID = $("#cmbcustomer").val();

    if (ItemID > 0) {
        $.ajax({
            async: false,
            url: base_url + 'item/fetchItemDetailsByCustomerID/' + ItemID + '/' + customerID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#txtUnitPrice").val(response.decUnitPrice);
                $("#txtStockQty").val(response.decStockInHand);
                $("#txtMeasureUnit").val(response.vcMeasureUnit);
                $("#txtRv").val(response.rv);

                // if (response.decStockInHand == 'N/A') {
                //     toastr["error"]("Please Check Stock");
                // }
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}

function chkCreditLimit() {
    var canAdd = false;
    debugger;

    var discount = $("#txtDiscount").val();
    var total = 0;
    var toBeSettlement = 0;
    $('#itemTable tbody tr').each(function () {
        var value = parseFloat($(this).closest("tr").find('.total').val());
        if (!isNaN(value)) {
            total += value;
        }
    });

    discount == "" ? discount = 0 : discount;
    total = (total - discount);
    if ($('#itemTable tr').length == 2) {
        var currency = $("#txtTotalPrice").val();
        // var number = 
        total = Number(currency.replace(/[^0-9.-]+/g, ""));

    }
    else {
        total += parseFloat($("#txtTotalPrice").val());
    }
    // total == 0 ? total = $("#txtTotalPrice").val() : total;

    var customerID = $("#cmbcustomer").val();
    debugger;

    var IsAdvancePayment = document.getElementById("IsAdvancePayment");

    if (customerID > 0) {

        $.ajax({
            async: false,
            url: base_url + 'customer/fetchCustomerDataById/' + customerID,
            type: 'post',
            dataType: 'json',
            success: function (response) {

                if (IsAdvancePayment.checked) {

                    if ((parseFloat(response.decAvailableCredit) + AdvanceAmount) < total) {
                        canAdd = false;
                    }
                    else {
                        canAdd = true;
                    }
                }
                else {
                    if (parseFloat(response.decAvailableCredit) < total) {
                        canAdd = false;
                    }
                    else {
                        canAdd = true;
                    }
                }

            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                alert(xhr.responseText);
            }
        });
    }
    return (canAdd == true) ? true : false;
}

function getcmbItemData() {
    $.ajax({
        async: false,
        url: 'getOnlyFinishItemData',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $("#cmbItem").empty();
            $("#cmbItem").append('<option value=" 0" disabled selected hidden>Select Issue Item</option>');
            for (let index = 0; index < response.length; index++) {
                $("#cmbItem").append('<option value="' + response[index].intItemID + '">' + response[index].vcItemName + '</option>');
            }

            // $("#cmbItem").focus();
            $("#cmbItem li").attr('aria-selected', false);
        },
        error: function (xhr, status, error) {
            arcadiaErrorMessage(error);
        }
    });

}

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}

$('#btnSubmit').click(function () {
    var IsAdvancePayment = document.getElementById("IsAdvancePayment");

    if ($("#cmbcustomer option:selected").val() == 0) {
        toastr["error"]("Please select customer !");
        $("#cmbcustomer").focus();
        return;
    }
    if ($('#itemTable tr').length == 2) {
        toastr["error"]("Please add the issue items !");
        $("#cmbItem").focus();
    } else {
        $("input[name=txtQty],input[name=txtTotalPrice]").val("");
        if ($("#cmbpayment option:selected").val() == 2 && chkCreditLimit() == false) {
            toastr["error"]("Customer CreditLimit Exceed !");
            return;
        } if (IsAdvancePayment.checked) {
            if ($("input[name=grandTotal]").val() < AdvanceAmount) {
                toastr["error"]("Please Enter more than Advance Payment !");
                return;
            }
        }
        if ($("#cmbpayment option:selected").val() == 2) {
            if (IsAdvancePayment.checked) {
                if (CreditBuyAmount < $("input[name=grandTotal]").val()) {
                    toastr["error"]("Customer CreditLimit Exceed !");
                    return;
                }
            }
            else {
                if (AvailableCredit < $("input[name=grandTotal]").val()) {
                    toastr["error"]("Customer CreditLimit Exceed ! Please Try Apply Advance Amount!");
                    return;
                }
            }
        }


        arcadiaConfirmAlert("You want to be able to create this !", function (button) {
            var form = $("#createIssue");
     
            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    debugger;
                    if (response.success == true) {
                        debugger;
                        // arcadiaSuccessAfterIssuePrint("Issue No : " + response.vcIssueNo, response.intIssueHeaderID);
                        // arcadiaSuccessMessage("Issue No : " + response.vcIssueNo);
                        document.body.innerHTML = response.issueNote;
                        window.print();
                        location.reload();
            
                        // $('#printpage', window.parent.document).hide();
                    } else {
                        toastr["error"](response.messages);
                    }

                }
            });
        }, this);
    }

});


// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});