var manageTable;

$(document).ready(function () {

    $('#cmbCustomer').on('change', function () {
        GetNotConfiguredItems()
    
    });

    $('#cmbCustomerFilter').on('change', function () {
        FilterItems()
    
    });

    // $('#cmbCustomer').on('select2:select', function (e) {
    //     GetNotConfiguredItems()
    //     $('#cmbItem').focus();
    // });

    $('#cmbItem').on('select2:select', function (e) {
        $('#txtUnitPrice').focus();
    });
    

    $("#addConfigModal").on("hidden.bs.modal", function () {
        $('#cmbCustomer').val('0'); // Select the option with a value of '0'
        $('#cmbCustomer').trigger('change'); // Notify any JS components that the value changed
        $('#cmbItem').val('0'); // Select the option with a value of '0'
        $('#cmbItem').trigger('change'); // Notify any JS components that the value changed
        $('#txtUnitPrice').val('');
        $("#itemTable").find("tr:gt(1)").remove();
        CalculateItemCount();
    });

    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchCustomerPriceConfigData',
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');
        }
    });

    function CalculateItemCount() {
        var rowCount = $('#itemTable tr').length;
        $("#itemCount").text("Item Count : " + (rowCount - 2));
    }

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
        });
    }

    remove();

    var row_id = 1;
    function AddToGrid() {
        if ($("#cmbCustomer option:selected").val() == 0) {
            toastr["error"]("Please select a Customer !");
            return;
        }
        if ($("#cmbItem option:selected").val() == 0) {
            toastr["error"]("Please select a Item !");
            return;
        }
        if ($("input[name=txtUnitPrice]").val() == "") {
            toastr["error"]("Please Enter Unit Price !");
            return;
        }
        else {
            if ($("#cmbItem option:selected").val() > 0) {
                var itemID = $("#cmbItem option:selected").val();
                var itemName = $("#cmbItem option:selected").text();
                var unitPrice = $("input[name=txtUnitPrice]").val();
                $(".first-tr").after('<tr>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + itemName + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control unitPrice disable-typing" style="text-align:right;" name="unitPrice[]" id="qty_' + row_id + '"  value="' + unitPrice + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>');

                row_id++;
                remove();
                $("#cmbItem :selected").remove();

                $("input[name=txtUnitPrice]").val("");
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
        if ($("#cmbCustomer option:selected").val() == 0) {
            toastr["error"]("Please select a Customer !");
            return;
        }
        if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please add the price config !");
            $("#txtUnitPrice").focus();
            return
        } else {
            arcadiaConfirmAlert("You want to be able to save this !", function (button) {

                var form = $("#createCustomerPriceConfig");

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

    $("#createCustomerPriceConfig").unbind('submit').on('submit', function (e) { });

});

function GetNotConfiguredItems() {
    var CustomerID = $("#cmbCustomer :selected").val();
    $.ajax({
        url: 'getNotConfiguredItems/' + CustomerID,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            var html = "<option value='0'>Select Item</option>";
            for (var i = 0; i < response.length; i++) {

                html += "<option value='" + response[i]['intItemID'] + "'>" + response[i]['vcItemName'] + "</option>";
            }
            $("#cmbItem").html("");
            $("#cmbItem").append(html);
        },
        error: function (data) { }
    });
}


function FilterItems() {
    var CustomerID = $('#cmbCustomerFilter').val();
    $('#manageTable').DataTable({
        'ajax': 'fetchCustomerPriceConfigData/' + CustomerID,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');

        }
    });

}

function editCustomerUnitPrice(CustomerPriceConfigID)
{
    $.ajax({
        url: 'fetchCustomerPriceConfigById/' + CustomerPriceConfigID,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_customer").val(response.vcCustomerName);
            $("#edit_item").val(response.vcItemName);
            $("#edit_unit_price").val(response.decUnitPrice);

            // submit the edit from 
            $("#updateCustomerPriceConfigForm").unbind('submit').bind('submit', function() {
                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: form.attr('action') + '/' + CustomerPriceConfigID,
                    type: form.attr('method'),
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    success: function(response) {

                        manageTable.ajax.reload(null, false);

                        if (response.success === true) {

                            toastr["success"](response.messages);

                            // hide the modal
                            $("#editCustomerModal").modal('hide');
                            $("#updateCustomerPriceConfigForm")[0].reset();
                            $("#updateCustomerPriceConfigForm .form-group").removeClass('has-error').removeClass('has-success');

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

                                // hide the modal
                                $("#editCustomerModal").modal('hide');
                                $("#updateCustomerPriceConfigForm")[0].reset();
                                $("#updateCustomerPriceConfigForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function RemoveCustomerUnitPrice(CustomerPriceConfigID) {
    arcadiaConfirmAlert("You want to be able to remove this !", function(button) {

        $.ajax({
            async: true,
            url: base_url + 'Customer/RemoveCustomerUnitPrice',
            type: 'post',
            data: {
                intCustomerPriceConfigID: CustomerPriceConfigID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Deleted !", "Customer/manageCustomerUnitPriceConfig");
                } else {
                    toastr["error"](response.messages);
                }
            },
            error: function(request, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }, this);
}


// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

