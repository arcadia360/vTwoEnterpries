var manageTable;

$(document).ready(function () {

    $("#btnSaveSubCat").click(function () {
        if ($("#main_cat :selected").val() == 0) {
            toastr["error"]("Please select a Main Category !");
            return;
        }
    });

    // $("#btnUpdateSubCat").click(function () {
    //     if ($("#main_cat :selected").val() == 0) {
    //         toastr["error"]("Please select a Main Category !");
    //         return;
    //     }
    // });

    $("#addSubCategoryModal").on("hidden.bs.modal", function () {
        $("#main_cat").val("");

        $('#main_cat').val('0'); // Select the option with a value of '0'
        $('#main_cat').trigger('change'); // Notify any JS components that the value changed

    });

    $("#editSubCategoryModal").on("hidden.bs.modal", function () {
        $("#edit_main_cat").val("");
        $('#edit_main_cat').val('0'); // Select the option with a value of '0'
        $('#edit_main_cat').trigger('change'); // Notify any JS components that the value changed
    });

    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchSubCategoryData',
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        }
    });


    // submit the create from 
    $("#createSubCategory").unbind('submit').on('submit', function () {

        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

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
                    $("#addSubCategoryModal").modal('hide');

                    // reset the form
                    $("#createSubCategory")[0].reset();
                    $("#createSubCategory .form-group").removeClass('has-error').removeClass('has-success');

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
                        $("#addSubCategoryModal").modal('hide');
                        // reset the form
                        $("#createSubCategory")[0].reset();
                        $("#createSubCategory .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            }
        });

        return false;
    });


});

function editSubCategory(id) {


    $.ajax({
        async: false,
        url: 'fetchSubCategoryDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $("#edit_SubCat_name").val(response.vcSubCategory);
            // $('#edit_main_cat').val(response.intSubCategoryID);
            // $('#edit_main_cat').trigger('change');

            // submit the edit from 
            $("#updateSubCategoryForm").unbind('submit').bind('submit', function () {


                if ($("#edit_main_cat :selected").val() == 0) {
                    toastr["error"]("Please select a Main Category !");
                }
                else {
                    var form = $(this);

                    // remove the text-danger
                    $(".text-danger").remove();

                    $.ajax({
                        url: form.attr('action') + '/' + id,
                        type: form.attr('method'),
                        data: form.serialize(), // /converting the form data into array and sending it to server
                        dataType: 'json',
                        success: function (response) {

                            manageTable.ajax.reload(null, false);

                            if (response.success === true) {

                                toastr["success"](response.messages);

                                // hide the modal
                                $("#editSubCategoryModal").modal('hide');
                                $("#updateSubCategoryForm")[0].reset();
                                $("#updateSubCategoryForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                    $("#editSubCategoryModal").modal('hide');
                                    $("#updateSubCategoryForm")[0].reset();
                                    $("#updateSubCategoryForm .form-group").removeClass('has-error').removeClass('has-success');
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

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});


// function removeItem(id) {
//     if (id) {

//         // submit the edit from 
//         $("#removeItemForm").unbind('submit').bind('submit', function () {
//             var form = $(this);

//             // remove the text-danger
//             $(".text-danger").remove();


//             $.ajax({
//                 url: form.attr('action'),
//                 type: form.attr('method'),
//                 data: {
//                     intItemID: id
//                 },
//                 dataType: 'json',
//                 success: function (response) {

//                     manageTable.ajax.reload(null, false);

//                     if (response.success === true) {


//                         toastr["success"](response.messages);

//                         // hide the modal
//                         $("#removeItemModal").modal('hide');
//                         $("#removeItemForm")[0].reset();
//                         $("#removeItemForm .form-group").removeClass('has-error').removeClass('has-success');

//                     } else {


//                         toastr["error"](response.messages);

//                         // hide the modal
//                         $("#removeItemModal").modal('hide');
//                         $("#removeItemForm")[0].reset();
//                         $("#removeItemForm .form-group").removeClass('has-error').removeClass('has-success');
//                     }
//                 }
//             });

//             return false;
//         });
//     }
// }