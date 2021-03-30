var manageTable;

$(document).ready(function () {

    CalculateItemCount();

    $('#cmbItem').on('select2:select', function (e) {
        $('#txtQty').focus();
    });
    
    manageTable = $('#manageTable').DataTable({
        'ajax': 'GetCuttingOrderHeaderData',
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[0]).css('text-align', 'center');
            $(nRow.childNodes[1]).css('text-align', 'center');
            $(nRow.childNodes[2]).css('text-align', 'center');
        }
    });

    // on first focus (bubbles up to document), open the menu
    $(document).on('keyup', 'input[type=search]', function (e) {
        $("li").attr('aria-selected', false);
    });

    $("#btnAddToGrid").click(function () {
        AddToGrid();
    });
    //Bind keypress event to textbox
    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            // getRequestFinishedByItemID();
            AddToGrid();
        }

        event.stopPropagation();
    });



    function remove() {
        $(".red").click(function () {

            // $(this).closest("tr").remove();
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
        });
    }

    remove();

    var row_id = 1;
    function AddToGrid() {

        if($("input[name=cutting_order_name]").val() == "")
        {
            toastr["error"]("Please Enter Cutting Order Name !");
            return;
        }
        if ($("#cmbItem option:selected").val() == 0) {
            toastr["error"]("Please select Item !");
            return;
        }
        if ($("input[name=txtQty]").val() == "") {
            toastr["error"]("Please Enter Qty !");
            return;
        }
        else {
            if ($("#cmbItem option:selected").val() > 0) {
                var itemID = $("#cmbItem option:selected").val();
                var itemName = $("#cmbItem option:selected").text();
                var qty = $("input[name=txtQty]").val();
                $(".first-tr").after('<tr>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + itemName + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control disable-typing" style="text-align:right;" name="qty[]" id="qty_' + row_id + '"  value="' + qty + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>');

                row_id++;
                remove();
                $("#cmbItem :selected").remove();

                $("input[name=txtQty]").val("");
                CalculateItemCount();

                $("li").attr('aria-selected', false);
                $("#cmbItem").focus();

            } else {
                toastr["error"]("Please select valid item !");
                $("#cmbItem").focus();
                $("li").attr('aria-selected', false);
            }
        }
    }


    $('#btnSubmit').click(function () {
        if($("input[name=cutting_order_name]").val() == "")
        {
            toastr["error"]("Please Enter Cutting Order Name !");
            return;
        }
        if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please add the cutting order !");
            $("#cmbItem").focus();
            return
        } else {
            arcadiaConfirmAlert("You want to be able to save this !", function (button) {

                var form = $("#createCuttingOrder");

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    async: true,
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

                                arcadiaErrorMessage(response.messages);
                            }
                        }

                    }
                });
            }, this);
        }

    });

    $("#createCuttingOrder").unbind('submit').on('submit', function (e) { });

});


function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}



$('#btnEditSubmit').click(function () {

    if ($("input[name=cutting_order_name]").val() == "") {
        toastr["error"]("Please enter the cutting order name !");
        $("#cutting_order_name").focus();
    } else if ($('#itemTable tr').length == 2) {
        toastr["error"]("Please enter the Description !");
        $("#txtOrderDescription").focus();
    } else {
        arcadiaConfirmAlert("You want to be able to edit this !", function (button) {

            var form = $("#editCuttingOrder");

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        arcadiaSuccessMessage("Edited !", "Utilities/cuttingOrder");
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

                },
                error: function (request, status, error) {
                    arcadiaErrorMessage(error);
                }
            });
        }, this);
    }

});

$("#editCuttingOrder").unbind('submit').on('submit', function (e) { });

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

function RemoveCuttingOrder(CuttingOrderHeaderID) {
    arcadiaConfirmAlert("You want to be able to remove this !", function (button) {

        $.ajax({
            async: true,
            url: base_url + 'Utilities/RemoveCuttingOrder',
            type: 'post',
            data: {
                intCuttingOrderHeaderID: CuttingOrderHeaderID
            },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Deleted !", "Utilities/cuttingOrder");
                } else {
                    toastr["error"](response.messages);
                }
            },
            error: function (request, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }, this);
}