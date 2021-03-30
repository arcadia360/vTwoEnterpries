$(document).ready(function() {

    CalculateItemCount();

    $('#itemTable tbody tr').each(function() {
        var value = $(this).closest("tr").find('.itemID').val();
        $("#cmbItem option[value=" + value + "]").remove();
    });

    $('#cmbItem').on('select2:select', function(e) {
        $('#txtQty').focus();
    });

    $("#btnAddToGrid").click(function() {
        AddToGrid();
    });
    //Bind keypress event to textbox
    $('.add-item').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            // getRequestFinishedByItemID();
            AddToGrid();
        }

        event.stopPropagation();
    });

    function CalculateItemCount() {
        var rowCount = $('#itemTable tr').length;
        $("#itemCount").text("Item Count : " + (rowCount - 2));
    }

    remove();

    var row_id = 1;

    function AddToGrid() {
        if ($("input[name=cmbItem]").val(0), $("input[name=txtQty]").val() == "") {
            toastr["error"]("Please fill in all fields !");
        } else {
            if ($("#cmbItem option:selected").val() > 0) {
                var itemID = $("#cmbItem option:selected").val();
                var item = $("#cmbItem option:selected").text();
                var measureUnit = $("input[name=txtMeasureUnit]").val();
                var stockQty = $("input[name=txtStockQty]").val();
                var qty = $("input[name=txtQty]").val();

                $(".first-tr").after('<tr>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty' + row_id + '"  value="' + stockQty + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>');

                row_id++;
                remove();
                $("#cmbItem :selected").remove();

                $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtStockQty], input[name=txtQty]").val("");
                CalculateItemCount();

                $("#cmbItem").focus();
                $("li").attr('aria-selected', false);
                $("#txtQty").focus();


            } else {
                toastr["error"]("Please select valid item !");
                $("#cmbItem").focus();
                $("li").attr('aria-selected', false);
            }
        }
    }



    function remove() {
        $(".red").click(function() {

            var itemID = $(this).closest("tr").find('.itemID').val();
            var itemName = $(this).closest("tr").find('.itemName').val();

            var IsAlreadyIncluded = false;

            $("#cmbItem option").each(function() {
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

    $('#btnSubmit').click(function() {

        if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please choose the receive items !");
            $("#cmbItem").focus();
        } else {
            arcadiaConfirmAlert("You want to be able to edit this !", function(button) {

                var form = $("#editRequest");

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success == true) {
                            arcadiaSuccessMessage("Edited !", "request/ViewRequest");
                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
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
                    error: function(request, status, error) {
                        arcadiaErrorMessage(error);
                    }
                });
            }, this);
        }

    });

    $("#createGRN").unbind('submit').on('submit', function(e) {});

});

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

function getRequestFinishedByItemID() {
    var ItemID = $("#cmbItem").val();
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'request/getRequestFinishedByItemID/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $("#txtMeasureUnit").val(response.vcMeasureunit);
                $("#txtStockQty").val(response.decStockInHand);
                if (response.decStockInHand == 'N/A') {
                    toastr["info"]("New Item Selected");
                }
            },
            error: function(xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                alert(xhr.responseText);
            }
        });
    }
}