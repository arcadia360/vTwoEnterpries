var manageTable;

$(document).ready(function() {


    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchMeasureUnitData',
        'order': []
    });


    // submit the create from 
    $("#createunitForm").unbind('submit').on('submit', function() {
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
                    $("#addMeasureUnitModal").modal('hide');

                    // reset the form
                    $("#createunitForm")[0].reset();
                    $("#createunitForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        toastr["warning"](response.messages);

                        // hide the modal
                        $("#addMeasureUnitModal").modal('hide');
                        // reset the form
                        $("#createunitForm")[0].reset();
                        $("#createunitForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            }
        });

        return false;
    });


});

function editMeasureUnit(id) {
    $.ajax({
        url: 'fetchMeasureUnitDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_unit_name").val(response.vcMeasureUnit);

            $("#updateMeasureUnitForm").unbind('submit').bind('submit', function() {
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
                            $("#editMeasureUnitModal").modal('hide');
                            $("#updateMeasureUnitForm")[0].reset();
                            $("#updateMeasureUnitForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editMeasureUnitModal").modal('hide');
                                $("#updateMeasureUnitForm")[0].reset();
                                $("#updateMeasureUnitForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

// function removeMeasureUnit(id) {
//     if (id) {
//         // submit the edit from 
//         $("#removeMeasureUnitForm").unbind('submit').bind('submit', function() {
//             var form = $(this);

//             // remove the text-danger
//             $(".text-danger").remove();


//             $.ajax({
//                 url: form.attr('action'),
//                 type: form.attr('method'),
//                 data: {
//                     intMeasureUnitID: id
//                 },
//                 dataType: 'json',
//                 success: function(response) {

//                     manageTable.ajax.reload(null, false);

//                     if (response.success === true) {


//                         toastr["success"](response.messages);

//                         // hide the modal
//                         $("#removeMeasureUnithModal").modal('hide');
//                         $("#removeMeasureUnitForm")[0].reset();
//                         $("#removeSupplierForm .form-group").removeClass('has-error').removeClass('has-success');

//                     } else {


//                         toastr["error"](response.messages);

//                         // hide the modal
//                         $("#removeMeasureUnithModal").modal('hide');
//                         $("#removeMeasureUnitForm")[0].reset();
//                         $("#removeMeasureUnitForm .form-group").removeClass('has-error').removeClass('has-success');
//                     }
//                 }
//             });

//             return false;
//         });
//     }
// }