var manageTable;

$(document).ready(function() {

    $('#item_type').on('change', function() {
        GenerateUnitPriceTextBox();
    });

    $('#edit_item_type').on('change', function() {
        EditGenerateUnitPriceTextBox();
    });

    $('#cmbItemType').on('change', function () {
        FilterItems()
    
    });

    $("#btnSaveItem").click(function () {
        if ($("#measure_unit :selected").val() == 0) {
            toastr["error"]("Please select a Measure Unit !");
            return;
        }
        if ($("#item_type :selected").val() == 0) {
            toastr["error"]("Please select a Item Type !");
            return;
        }
   
    });

    $("#addItemModal").on("hidden.bs.modal", function(){
        $("#Item_name").val("");
        $("#unit_price").val("");
        $("#re_order").val("");


        $('#measure_unit').val('0'); // Select the option with a value of '0'
        $('#measure_unit').trigger('change'); // Notify any JS components that the value changed
        $('#item_type').val('0'); // Select the option with a value of '0'
        $('#item_type').trigger('change'); // Notify any JS components that the value changed
    });
    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchItemData',
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');
        }
    });


    // submit the create from 
    $("#createitemForm").unbind('submit').on('submit', function() {

      
        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(response) {

                manageTable.ajax.reload(null, false);

                if (response.success === true) {

                    toastr["success"](response.messages);

                    // hide the modal
                    $("#addItemModal").modal('hide');

                    // reset the form
                    $("#createitemForm")[0].reset();
                    $("#createitemForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        $("#addItemModal").modal('hide');
                        // reset the form
                        $("#createitemForm")[0].reset();
                        $("#createitemForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            }
        });

        return false;
    });


});

function editItem(id) {
    
    $.ajax({
        async : false,
        url: 'fetchItemDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_item_name").val(response.vcItemName);
            $('#edit_measure_unit').val(response.intMeasureUnitID);
            $('#edit_measure_unit').trigger('change');

            $('#edit_item_type').val(response.intItemTypeID);
            $('#edit_item_type').trigger('change');

            $("#edit_re_order").val(response.decReOrderLevel);
            $("#edit_rv").val(response.rv);
            if (response.intItemTypeID) {
                EditGenerateUnitPriceTextBox();
            }
            $('#edit_unit_price').val(response.decUnitPrice);

            // submit the edit from 
            $("#updateItemForm").unbind('submit').bind('submit', function() {
                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: form.attr('action') + '/' + id,
                    type: form.attr('method'),
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    success: function(response) {

                        manageTable.ajax.reload(null, false);

                        if (response.success === true) {

                            toastr["success"](response.messages);

                            // hide the modal
                            $("#editItemModal").modal('hide');
                            $("#updateItemForm")[0].reset();
                            $("#updateItemForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editItemModal").modal('hide');
                                $("#updateItemForm")[0].reset();
                                $("#updateItemForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function GenerateUnitPriceTextBox() {
    var Item_Type = $('#item_type').val();

    var htmlElements = "";

    if (Item_Type == 2) {
        htmlElements = '<div class="form-group">' +
            '							<label for="txtItemName">Unit Price</label>' +
            '							<input type="text" class="form-control only-decimal" id="unit_price" name="unit_price" placeholder="Enter Unit Price">' +
            '						</div>';
    } else {
        htmlElements = "";

    }

    $("#GenerateUnitPriceTextBox").html("");
    $("#GenerateUnitPriceTextBox").append(htmlElements);

}
// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

function EditGenerateUnitPriceTextBox() {
    var Item_Type = $('#edit_item_type').val();

    var htmlElements = "";

    if (Item_Type == 2) {
        htmlElements = '<div class="form-group">' +
            '							<label for="txtItemName">Unit Price</label>' +
            '							<input type="text" class="form-control only-decimal" id="edit_unit_price" name="edit_unit_price" placeholder="Enter Unit Price">' +
            '						</div>';
    } else {
        htmlElements = "";

    }

    $("#EditGenerateUnitPriceTextBox").html("");
    $("#EditGenerateUnitPriceTextBox").append(htmlElements);

}

function FilterItems() {
    var itemTypeID = $('#cmbItemType').val();

    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchItemDataByItemTypeID/' + itemTypeID,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');
        }
    });

}

function removeItem(id) {
    if (id) {

        // submit the edit from 
        $("#removeItemForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intItemID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeItemModal").modal('hide');
                        $("#removeItemForm")[0].reset();
                        $("#removeItemForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeItemModal").modal('hide');
                        $("#removeItemForm")[0].reset();
                        $("#removeItemForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}