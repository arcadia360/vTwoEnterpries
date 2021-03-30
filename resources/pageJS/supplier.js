var manageTable;
// function fetch(){
//     $.ajax({
//         url: 
//         type: 'POST',
//         dataType: "json",
//         success: function(data){
//             $('#manageTable').DataTable ({
//                 "data" : data.posts,
//                 "columns" : [
//                     {"data" : "intSupplierID"},
//                     {"data" : "vcSupplierName"},
//                     {"data" : "IsActive"}
//                 ]
//              } );

//         }
//     });

// }

$(document).ready(function() {
    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchSupplierData',
        'order': []
    });


    // submit the create from 
    $("#createSupplierForm").unbind('submit').on('submit', function() {
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
                    $("#addSupplierModal").modal('hide');

                    // reset the form
                    $("#createSupplierForm")[0].reset();
                    $("#createSupplierForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        $("#addSupplierModal").modal('hide');

                    }
                }
            }
        });

        return false;
    });


});

function editSupplier(id) {
    $.ajax({
        url: 'fetchSupplierDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_supplier_name").val(response.vcSupplierName);
            $("#edit_address").val(response.vcAddress);
            $("#edit_contact_no").val(response.vcContactNo);
            $("#edit_credit_limit").val(response.decCreditLimit);
            $("#edit_rv").val(response.rv);
            // submit the edit from 
            $("#updateSupplierForm").unbind('submit').bind('submit', function() {
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
                            $("#editSupplierModal").modal('hide');
                            $("#updateSupplierForm")[0].reset();
                            $("#updateSupplierForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editSupplierModal").modal('hide');
                                $("#updateSupplierForm")[0].reset();
                                $("#updateSupplierForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function removeSupplier(id) {
    if (id) {

        // submit the edit from 
        $("#removeSupplierForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intSupplierID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeSupplierModal").modal('hide');
                        $("#removeSupplierForm")[0].reset();
                        $("#removeSupplierForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeSupplierModal").modal('hide');
                        $("#removeSupplierForm")[0].reset();
                        $("#removeSupplierForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}