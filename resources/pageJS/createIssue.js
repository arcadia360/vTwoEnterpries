var AdvanceAmount = 0;
var CreditBuyAmount = 0;
var AvailableCredit = 0;
var Item = function () {
    this.intItemID = 0;
}
var row_id = 1;
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
        CalItemWiseDiscount();
    });

    $('#txtDiscountPercentage').keyup(function (event) {
        CalItemWiseDiscount();

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
        var total = 0;

        if (unitPrice != "" && qty != "") {
            var total = unitPrice * qty;
        }
        $("#txtWithouDiscount").val(currencyFormat(total));
    }

    function CalItemWiseDiscount() {
        var unitPrice = $("#txtUnitPrice").val();
        var qty = $("#txtQty").val()
        var discountPercentage = $("#txtDiscountPercentage").val();

        if (discountPercentage != "") {
            var DiscoutedPrice = ((unitPrice * qty) - ((discountPercentage / 100) * (unitPrice * qty)));

        }
        else {
            var DiscoutedPrice = unitPrice * qty;
        }
        $("#txtTotalPrice").val(currencyFormat(DiscoutedPrice));
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
                        toastr["error"]("You can't issue this. Because this item stock quantity is zero!");
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
                        var discountPercentage = $("input[name=txtDiscountPercentage]").val();
                        // var total = unitPrice * qty;

                        // var TotalPriceDiscounted =  $("input[name=txtTotalPrice]").val();

                        firstInFirstOut(itemID, item, measureUnit, stockQty, unitPrice, qty, discountPercentage);

                        // $(".first-tr").after('<tr>' +
                        //     '<td hidden>' +
                        //     '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                        //     '</td>' +
                        //     '<td>' +
                        //     '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
                        //     '</td>' +
                        //     '<td>' +
                        //     '<input type="text" class="form-control disable-typing" style="text-align:right;" name="unitPrice[]" id="unitPrice_' + row_id + '" value="' + parseFloat(unitPrice).toFixed(2) + '" readonly>' +
                        //     '</td>' +
                        //     '<td>' +
                        //     '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQty + '" readonly>' +
                        //     '</td>' +
                        //     '<td>' +
                        //     '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                        //     '</td>' +
                        //     '<td>' +
                        //     '<input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
                        //     '</td>' +
                        //     '<td>' +
                        //     '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="totalPrice[]" id="totalPrice_' + row_id + '"  value="' + parseFloat(total).toFixed(2) + '" readonly>' +
                        //     '</td>' +
                        //     '<td hidden>' +
                        //     '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + Rv + '" readonly>' +
                        //     '</td>' +
                        //     '<td class="static">' +
                        //     '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                        //     '</td>' +
                        //     '</tr>');

                        // row_id++;
                        remove();
                        $("#cmbItem :selected").remove();

                        $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice],input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice],input[name=txtDiscountPercentage]").val("");
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

    function remove() {
        $(".red").click(function () {

            alert("yesssssssssssss");

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


    function firstInFirstOut(itemID, item, measureUnit, stockQty, unitPrice, qty, discountPercentage) {
        debugger;
        var model = new Item();
        model.intItemID = itemID;



        ajaxCall('Item/getfirstInFirstOut', model, function (response) {

            // $("#itemCount").text("Item Count : " + response.length);
            var DiscoutedPrice = 0;
            // var discountPercentage = $("#txtDiscountPercentage").val();
            var withOutDiscount = 0;
            



            var itemQty = qty;
            var stockQuantity = stockQty;

            for (let index = 0; index < response.length; index++) {

              

                var htmlElement = '';
                if (itemQty > 0) {
                    if (parseFloat(response[index].decAvailableQty) >= itemQty && itemQty > 0) {
                        if (discountPercentage != "") {
                            DiscoutedPrice = unitPrice - (unitPrice * (discountPercentage / 100));
                            DiscoutedPrice = DiscoutedPrice * itemQty;
                        }
                        else {
                            DiscoutedPrice = unitPrice * itemQty;
                        }
                        withOutDiscount = unitPrice * itemQty;

                        $(".first-tr").after('<tr>' +
                            '<td hidden>' +
                            '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" class="form-control disable-typing" name="grnDetailID[]" id="grnDetailID_' + row_id + '" value="' + response[index].intGrnDetailID + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item.replace(/"/g, "\'\'") + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control unitPrice disable-typing" style="text-align:right;" name="unitPrice[]" id="unitPrice_' + row_id + '" value="' + parseFloat(unitPrice).toFixed(2) + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQuantity  + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control  qty disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + itemQty + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control disable-typing" style="text-align:right;" name="discountPercentage[]" id="discountPercentage_' + row_id + '"  value="' + discountPercentage + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" style="cursor: pointer;" class="form-control withoutDiscount disable-typing" name="withoutDiscount[]" id="withoutDiscount_' + row_id + '" value="' + withOutDiscount + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="totalPrice[]" id="totalPrice_' + row_id + '"  value="' + parseFloat(DiscoutedPrice).toFixed(2) + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + response[index].rv + '" readonly>' +
                            '</td>' +
                            '<td class="static">' +
                            '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                            '</td>' +
                            '</tr>');

                        row_id++;
                        stockQuantity = stockQuantity - itemQty;
                        itemQty = 0;
                    } else {
                        if (discountPercentage != "") {
                            DiscoutedPrice = unitPrice - (unitPrice * (discountPercentage / 100));
                            DiscoutedPrice = DiscoutedPrice * parseFloat(response[index].decAvailableQty);
                        }
                        else {
                            DiscoutedPrice = unitPrice * parseFloat(response[index].decAvailableQty);
                        }

                        withOutDiscount = unitPrice * parseFloat(response[index].decAvailableQty);

                        $(".first-tr").after('<tr>' +
                            '<td hidden>' +
                            '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" class="form-control disable-typing" name="grnDetailID[]" id="grnDetailID_' + row_id + '" value="' + response[index].intGrnDetailID + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item.replace(/"/g, "\'\'") + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control unitPrice disable-typing" style="text-align:right;" name="unitPrice[]" id="unitPrice_' + row_id + '" value="' + parseFloat(unitPrice).toFixed(2) + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQuantity + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control qty disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + parseFloat(response[index].decAvailableQty) + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control disable-typing" style="text-align:right;" name="discountPercentage[]" id="discountPercentage_' + row_id + '"  value="' + discountPercentage + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" style="cursor: pointer;" class="form-control withoutDiscount disable-typing" name="withoutDiscount[]" id="withoutDiscount_' + row_id + '" value="' + withOutDiscount + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="totalPrice[]" id="totalPrice_' + row_id + '"  value="' + parseFloat(DiscoutedPrice).toFixed(2) + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + response[index].rv + '" readonly>' +
                            '</td>' +
                            '<td class="static">' +
                            '<span class="button red center-items" onclick="removeItem(202)"><i class="fas fa-times"></i></span>' +
                            '</td>' +
                            '</tr>');

                        row_id++;
                        itemQty = itemQty - parseFloat(response[index].decAvailableQty);
                        stockQuantity = stockQuantity - parseFloat(response[index].decAvailableQty);

                    }

                }

            }
            CalculateItemCount();
            CalculateGrandTotal();

        });



    }

});

function removeItem(itemID) {
    debugger;
    var itemID = itemID;
    var itemName = "abccc";

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

        $(".first-tr").after(each(function () {
            if (itemID == $(this).closest("tr").find('.itemID').val()) {
                $(this).closest("tr").remove();
            }
        }));


    }
    CalculateItemCount();
    CalculateGrandTotal();
}



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

function CalculateGrandTotal() {
    if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
        // var discount = $("#txtDiscount").val();
        // var unitPrice = $("#txtUnitPrice").val();
        // var qty = $("#txtQty").val();
        // var discountPercentage = $("#txtDiscountPercentage").val();
        var discountedTotal = 0;
        var withoutDiscount = 0;
        $('#itemTable tbody tr').each(function () {
            var value = parseFloat($(this).closest("tr").find('.total').val());
            var without = parseFloat($(this).closest("tr").find('.withoutDiscount').val());
            if (!isNaN(value)) {
                discountedTotal += value;
            }
            if (!isNaN(without)) {
                withoutDiscount += without;
            }
        });


        // discount == "" ? discount = 0 : discount;


        // var DiscoutedPrice = ((discountPercentage / 100) * (unitPrice * qty));

        $("#subTotal").val(currencyFormat(withoutDiscount));
        $("#txtDiscount").val(currencyFormat(withoutDiscount-discountedTotal));
        $("#grandTotal").val(currencyFormat(discountedTotal));


    } else {
        debugger;
        $("#subTotal").val("0.00");
        $("#txtDiscount").val("0.00");
        $("#grandTotal").val("0.00");
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
                $("#txtDiscountPercentage").val(0);
                $("#txtWithouDiscount").val(0);


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
        url: 'getStockAvailableItemData',
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
    // var rowCount = $('#itemTable tr').length;
    // $("#itemCount").text("Item Count : " + (rowCount - 2));

    var itemCount=0;
var lastItemID = 0;
    $('#itemTable tbody tr').each(function () {
        var value = parseFloat($(this).closest("tr").find('.itemID').val());
        if (!isNaN(value) && lastItemID != value) {
            itemCount++;
        }
    });

    $("#itemCount").text("Item Count : " + (itemCount));

}

$('#btnSubmit').click(function () {
    var IsAdvancePayment = document.getElementById("IsAdvancePayment");

    if ($("#cmbcustomer option:selected").val() == 0) {
        toastr["error"]("Please select customer !");
        $("#cmbcustomer").focus();
        return;
    }
    // if ($("#cmbSalesRep option:selected").val() == 0) {
    //     toastr["error"]("Please select Sales Rep !");
    //     $("#cmbSalesRep").focus();
    //     return;
    // }
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