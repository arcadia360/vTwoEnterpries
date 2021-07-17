var manageTable;

$(document).ready(function () {

    $('#main_cat').on('change', function () {
        GetMainCatWiseSubCat();
    });

    $('#edit_main_cat').on('change', function () {
        EditGetMainCatWiseSubCat();
    });


    // $("#btnUpdateItem").click(function () {
    //     alert("yuaay");
    //     if ($("#edit_sub_cat :selected").val() < 0) {
    //         toastr["error"]("Please select a sub category !");
    //         return;
    //     }
    // });

    $("#addItemModal").on("hidden.bs.modal", function () {
        $("#Item_name").val("");
        $("#unit_price").val("");
        $("#re_order").val("");


        $('#measure_unit').val('0'); // Select the option with a value of '0'
        $('#measure_unit').trigger('change'); // Notify any JS components that the value changed
        $('#main_cat').val('0'); // Select the option with a value of '0'
        $('#main_cat').trigger('change'); // Notify any JS components that the value changed
        $('#sub_cat').val('0'); // Select the option with a value of '0'
        $('#sub_cat').trigger('change'); // Notify any JS components that the value changed
    });

    $("#editItemModal").on("hidden.bs.modal", function () {
        $("#edit_Item_name").val("");
        $("#edit_unit_price").val("");
        $("#edit_re_order").val("");


        $('#edit_measure_unit').val('0'); // Select the option with a value of '0'
        $('#edit_measure_unit').trigger('change'); // Notify any JS components that the value changed
        $('#edit_main_cat').val('0'); // Select the option with a value of '0'
        $('#edit_main_cat').trigger('change'); // Notify any JS components that the value changed
        $('#edit_sub_cat').val('0'); // Select the option with a value of '0'
        $('#edit_sub_cat').trigger('change'); // Notify any JS components that the value changed
    });

    manageTable = $('#manageTable').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': 'getStockAvailableItemData',
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');
        }
    });


    // submit the create from 
    $("#createitemForm").unbind('submit').on('submit', function () {


        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();
        if ($("#measure_unit :selected").val() == 0) {
            toastr["error"]("Please select a Measure Unit !");
            // return;
        }
        else if ($("#main_cat :selected").val() == 0) {
            toastr["error"]("Please select a main category !");
            // return;
        }
        else if ($("#sub_cat :selected").val() == 0) {
            toastr["error"]("Please select a sub category !");
            // return;
        }
        else {

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(), // /converting the form data into array and sending it to server
                dataType: 'json',
                success: function (response) {

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

                            // hide the modal
                            $("#addItemModal").modal('hide');
                            // reset the form
                            $("#createitemForm")[0].reset();
                            $("#createitemForm .form-group").removeClass('has-error').removeClass('has-success');
                        }
                    }
                }
            });
        }


        return false;
    });


});



function GetMainCatWiseSubCat() {
    var MainCatID = $("#main_cat :selected").val();
    $.ajax({
        url: 'getMainCatWiseSubCat/' + MainCatID,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            var html = "<option value='0'>Select Sub Categorie</option>";
            for (var i = 0; i < response.length; i++) {

                html += "<option value='" + response[i]['intSubcategoryID'] + "'>" + response[i]['vcSubcategory'] + "</option>";
            }
            $("#sub_cat").html("");
            $("#sub_cat").append(html);
        },
        error: function (data) { }
    });
}


function EditGetMainCatWiseSubCat() {
    var MainCatID = $("#edit_main_cat :selected").val();
    $.ajax({
        url: 'getMainCatWiseSubCat/' + MainCatID,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            var html = "<option value='0'>Select Sub Categorie</option>";
            for (var i = 0; i < response.length; i++) {

                html += "<option value='" + response[i]['intSubcategoryID'] + "'>" + response[i]['vcSubcategory'] + "</option>";
            }
            $("#edit_sub_cat").html("");
            $("#edit_sub_cat").append(html);
        },
        error: function (data) { }
    });
}

function editItem(id) {

    $.ajax({
        async: false,
        url: 'fetchItemDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function (response) {

            $("#edit_item_name").val(response.vcItemName);
            $('#edit_measure_unit').val(response.intMeasureUnitID);
            $('#edit_measure_unit').trigger('change');

            $("#edit_re_order").val(response.decReOrderLevel);

            $('#edit_unit_price').val(response.decUnitPrice);

            // submit the edit from 
            $("#updateItemForm").unbind('submit').bind('submit', function () {
                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                 if ($("#edit_sub_cat :selected").val() == 0) {
                    toastr["error"]("Please select a sub category !");
                    // return;
                }
                else 
                {
                    $.ajax({
                        async: false,
                        url: form.attr('action') + '/' + id,
                        type: form.attr('method'),
                        data: form.serialize(), // /converting the form data into array and sending it to server
                        dataType: 'json',
                        success: function (response) {

                            manageTable.ajax.reload(null, false);

                            if (response.success === true) {

                                toastr["success"](response.messages);

                                // hide the modal
                                $("#editItemModal").modal('hide');
                                $("#updateItemForm")[0].reset();
                                $("#updateItemForm .form-group").removeClass('has-error').removeClass('has-success');

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

                                    // hide the modal
                                    $("#editItemModal").modal('hide');
                                    $("#updateItemForm")[0].reset();
                                    $("#updateItemForm .form-group").removeClass('has-error').removeClass('has-success');
                                }
                            }
                        }
                    });

                }


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
        $("#removeItemForm").unbind('submit').bind('submit', function () {
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
                success: function (response) {

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