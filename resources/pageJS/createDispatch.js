$(document).ready(function () {
    $(document).on('keyup', 'input[type=search]', function (e) {
        $("li").attr('aria-selected', false);
    });

    $("#btnAddToGrid").click(function () {
        AddToGrid(true);
    });

    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            AddToGrid();
            $("#cmbItem li").attr('aria-selected', false);
        event.preventDefault()
        }
        event.stopPropagation();
    });

    $('#cmbItem').on('select2:select', function (e) {
        getCuttingOrdersByItemID();
        // $("#cmbCuttingOrder").focus();
        // $("#cmbCuttingOrder li").attr('aria-selected', false);
    });

    $('#cmbCuttingOrder').on('select2:select', function (e) {
        $('#txtQty').focus();
    });

    $('#txtQty').on('keyup', function (e) {
        if ($('#txtStockQty').val() == 0) {
            $('#txtQty').val(null);
            toastr["error"]("You can't dispatch this. Because this item stock quantity is zero!");
        } else if ($('#txtStockQty').val() > 0) {
            if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
                toastr["error"]("You can't exceed stock quantity  !");
            }
        }

    });
});

var Dispatch = function () {
    this.intCuttingOrderHeaderID = 0;
}

function getCuttingOrdersByItemID() {
    getItemData();
    $('#txtQty').val(null);
    var ItemID = $("#cmbItem").val();

    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'Utilities/getCuttingOrdersByItemID/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#cmbCuttingOrder").empty();
                $("#cmbCuttingOrder").append('<option value=" 0" disabled selected hidden>Select Cutting Order</option>');
                for (let index = 0; index < response.length; index++) {
                    $("#cmbCuttingOrder").append('<option value="' + response[index].intCuttingOrderHeaderID + '">' + response[index].vcOrderName + '</option>');
                }

                $("#cmbCuttingOrder").focus();
                $("#cmbCuttingOrder li").attr('aria-selected', false);
            },
            error: function (xhr, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }
}

function getItemData() {
    var ItemID = $("#cmbItem").val();
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'Item/fetchItemDataById/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                var stockQty = 0;
                if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
                    // var discount = $("#txtDiscount").val();
                    $('#itemTable tbody tr').each(function () {
                        if ($(this).closest("tr").find('.itemID').val() == ItemID) {
                            var value = parseInt($(this).closest("tr").find('.stockQty').val());
                            if (!isNaN(value)) {
                                stockQty += value;
                            }
                        }
                    });

                }


                $("#txtMeasureUnit").val(response.vcMeasureUnit);
                $("#txtStockQty").val((response.decStockInHand) - stockQty);
                $("#txtRv").val(response.rv);

            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}

var row_id = 1;

function AddToGrid(IsMouseClick = false) {
    debugger;
    if ($("#cmbItem option:selected").val() == 0 || $("#cmbCuttingOrder option:selected").val() == 0 || $("#txtQty").val() == "") {
        toastr["error"]("Please fill in all fields !");
    } else {
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
                    var cuttingOrderId = $("#cmbCuttingOrder option:selected").val();
                    var cuttingOrderName = $("#cmbCuttingOrder option:selected").text();
                    var qty = $("input[name=txtQty]").val();
                    var Rv = $("input[name=txtRv]").val(); 
 
                    var model = new Dispatch(); 
                    model.intCuttingOrderHeaderID = cuttingOrderId;
 

                    ajaxCall('Utilities/getCuttingOrderDetailsByCuttingOrderHeaderID', model, function (response) {
                        var cuttingOrderHTML = '<thead><tr><th style="text-align:center; background-color:#a5d6a7 !Important; color:#000000;">Output Cutting Items</th><th style="text-align:center; background-color:#a5d6a7 !Important; color:#000000;">Qty</th><tr></thead><tbody>';
                        for (let index = 0; index < response.length; index++) {
                            cuttingOrderHTML += '<tr> <td style="text-align:center;">'+response[index].vcItemName+'</td><td style="text-align:center;">'+ (response[index].decQty * qty)+'</td> </tr>';
                        }
                        $(".first-tr").after('<tr data-toggle="collapse" data-target="#collapse' + row_id + '" aria-expanded="true" aria-controls="collapse' + row_id + '" style="cursor: pointer;" name="gridItem" class="row'+row_id+'">' +
                        '<td hidden>' +
                            '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '"  readonly>' +
                        '</td>' +
                        '<td>' +
                            '<input type="text" style="cursor: pointer;" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
                        '</td>' +
                        '<td>' +
                        '   <input type="text" style="cursor: pointer;" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                        '</td>' +
                        '<td>' +
                        '   <input type="text" style="cursor: pointer;" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQty + '" readonly>' +
                        '</td>' +
                        '<td hidden>' +
                        '<input type="text" style="cursor: pointer;" class="form-control cuttingOrderId disable-typing" name="cuttingOrderId[]" id="cuttingOrderId_' + row_id + '" value="' + cuttingOrderId + '" readonly>' +
                        '</td>' +
                        '<td>' +
                        '<input type="text" style="cursor: pointer;" class="form-control cuttingOrderName disable-typing" name="cuttingOrderName[]" id="cuttingOrderName_' + row_id + '" value="' + cuttingOrderName + '" readonly>' +
                        '</td>' +
                        '<td>' +
                        '<input type="text" style="cursor: pointer;" class="form-control stockQty disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
                        '</td>' +
                        '<td hidden>' +
                        '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + Rv + '" readonly>' +
                        '</td>' +
                        '<td class="static">' +
                        '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                        '</td>' +
                            '<tr class="row' + row_id +'" style="border:0; margin:0; padding:0; background-color:#c8e6c9;">' +
                                '<td colspan="6" style="border:0; margin:0; padding:0;">'+
                                    '<div id="collapse' + row_id + '" class="collapse" aria-labelledby="heading' + row_id + '" data-parent="#accordion">' +
                                        '<table style="margin-bottom:0; width:100%;">'+
                                            cuttingOrderHTML+'</tbody>'+
                                            // '<thead style="text-align:center; background-color:#a5d6a7 !Important;"><tr><th>Cutting Size</th><th style="text-align:center;">Qty</th><tr></thead><tbody><tr> <td style="text-align:center;">ee</td><td style="text-align:center;">34.00</td> </tr><tr> <td style="text-align:center;">dy</td><td style="text-align:center;">5.00</td> </tr></tbody>'+
                                            //'<thead style="text-align:center; background-color:#a5d6a7 !Important;"><th>Cutting Size</th><th style="text-align:center;">Qty</th></thead>'+
                                            //'<tr> <td style="text-align:center;"> 12 x 12 </td><td style="text-align:center;"> 20 </td> </tr>'+
                                            //'<tr> <td style="text-align:center;"> 2 x 4 </td><td style="text-align:center;"> 54 </td> </tr>'+
                                        '</table>'+
                                    '</div>' +
                                '</td>'+
                            '</tr>' +
                        '</tr>');

 

                    row_id++;
                    remove();

                    $("input[name=cmbItem],input[name=cmbCuttingOrder], input[name=txtMeasureUnit],input[name=txtStockQty],input[name=txtQty]").val("");
                    CalculateItemCount();
                    $("#cmbCuttingOrder").empty();
                    $("#cmbCuttingOrder").append('<option value=" 0" disabled selected hidden>Select Cutting Order</option>');
                    $("#cmbItem").focus();
                    $("#cmbItem li").attr('aria-selected', false);
                    });

                    

//                     $(".first-tr").after('<tr data-toggle="collapse" data-target="#collapse' + row_id + '" aria-expanded="true" aria-controls="collapse' + row_id + '" style="cursor: pointer;" class="gridItem">' +
//                         '<td hidden>' +
//                             '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '"  readonly>' +
//                         '</td>' +
//                         '<td>' +
//                             '<input type="text" style="cursor: pointer;" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
//                         '</td>' +
//                         '<td>' +
//                         '   <input type="text" style="cursor: pointer;" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
//                         '</td>' +
//                         '<td>' +
//                         '   <input type="text" style="cursor: pointer;" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQty + '" readonly>' +
//                         '</td>' +
//                         '<td hidden>' +
//                         '<input type="text" style="cursor: pointer;" class="form-control cuttingOrderId disable-typing" name="cuttingOrderId[]" id="cuttingOrderId_' + row_id + '" value="' + cuttingOrderId + '" readonly>' +
//                         '</td>' +
//                         '<td>' +
//                         '<input type="text" style="cursor: pointer;" class="form-control cuttingOrderName disable-typing" name="cuttingOrderName[]" id="cuttingOrderName_' + row_id + '" value="' + cuttingOrderName + '" readonly>' +
//                         '</td>' +
//                         '<td>' +
//                         '<input type="text" style="cursor: pointer;" class="form-control stockQty disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
//                         '</td>' +
//                         '<td hidden>' +
//                         '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + Rv + '" readonly>' +
//                         '</td>' +
//                         '<td class="static">' +
//                         '<button class="button red center-items"><i class="fas fa-times" onclick="removeCuttingOrder(' + row_id + ')"></i></span>' +
//                         '</td>' +
//                             '<tr id="' + row_id + '" style="border:0; margin:0; padding:0; background-color:#c8e6c9;">' +
//                                 '<td colspan="6" style="border:0; margin:0; padding:0;">'+
//                                     '<div id="collapse' + row_id + '" class="collapse" aria-labelledby="heading' + row_id + '" data-parent="#accordion">' +
//                                         '<table class="table" style="margin-bottom:0; width:100%;">'+
//                                             cuttingOrderHTML+'</tbody>'+

// // '<thead style="text-align:center; background-color:#a5d6a7 !Important;"><tr><th>Cutting Size</th><th style="text-align:center;">Qty</th><tr></thead><tbody><tr> <td style="text-align:center;">ee</td><td style="text-align:center;">34.00</td> </tr><tr> <td style="text-align:center;">dy</td><td style="text-align:center;">5.00</td> </tr></tbody>'+

//                                             //'<thead style="text-align:center; background-color:#a5d6a7 !Important;"><th>Cutting Size</th><th style="text-align:center;">Qty</th></thead>'+
//                                             //'<tr> <td style="text-align:center;"> 12 x 12 </td><td style="text-align:center;"> 20 </td> </tr>'+
//                                             //'<tr> <td style="text-align:center;"> 2 x 4 </td><td style="text-align:center;"> 54 </td> </tr>'+
//                                         '</table>'+
//                                     '</div>' +
//                                 '</td>'+
//                             '</tr>' +
//                         '</tr>');


//                     //  $('table .cuttingOrderDetails').innerHTML = cuttingOrderHTML;

//                     row_id++;
//                     remove();
//                     // $("#cmbItem :selected").remove();

//                     $("input[name=cmbItem],input[name=cmbCuttingOrder], input[name=txtMeasureUnit],input[name=txtStockQty],input[name=txtQty]").val("");
//                     CalculateItemCount();
//                     $("#cmbCuttingOrder").empty();
//                     $("#cmbCuttingOrder").append('<option value=" 0" disabled selected hidden>Select Cutting Order</option>');
//                     $("#cmbItem").focus();
//                     $("#cmbItem li").attr('aria-selected', false);
                } else {
                    toastr["error"]("Please select valid item !");
                    $("#cmbItem").focus();
                    // $("li").attr('aria-selected', false);
                    $("#cmbItem li").attr('aria-selected', false);
                }
            }
        } else {
            toastr["error"]("Please enter dispatch qty !");
        }

    }
}

function CalculateItemCount() {
    $("#itemCount").text("Item Count : 0");
    var rowCount = $('tr[name ="gridItem"]').length;
    $("#itemCount").text("Item Count : " + rowCount);
}

function remove() {
    $(".red").click(function () {

        var row = $(this).closest("tr").attr('class');
        // alert(row);
        
        $("."+row).remove();


        // $(this).closest("tr").remove();

        // $(this).closest(".row" + id).remove();


        CalculateItemCount();
        $("#cmbItem li").attr('aria-selected', false);
    });
}

// function removeCuttingOrder(id) {
//     // alert("2");
//     // $(".row" + id).remove();
// }

$('#btnSubmit').click(function () {

    if ($('#itemTable tr').length == 2) {
        toastr["error"]("Please add the dispatch items !");
        $("#cmbItem").focus();
    } else {
        arcadiaConfirmAlert("You want to be able to create this !", function (button) {

            var form = $("#createDispatch");

            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        arcadiaSuccessAfterDispatchPrint("Dispatch No : "+ response.vcDispatchNo, response.intDispatchHeaderID);
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