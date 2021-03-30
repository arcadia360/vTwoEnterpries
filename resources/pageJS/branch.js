var manageTable;

$(document).ready(function() {


    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchBranchData',
        'order': []
    });


    // submit the create from 
    $("#createBranchForm").unbind('submit').on('submit', function() {
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
                    $("#addBranchModal").modal('hide');

                    // reset the form
                    $("#createBranchForm")[0].reset();
                    $("#createBranchForm .form-group").removeClass('has-error').removeClass('has-success');

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
                    }
                }
            }
        });

        return false;
    });


});

function editBranch(id) {
    $.ajax({
        url: 'fetchBranchDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_branch_name").val(response.vcBranchName);
            $("#edit_address").val(response.vcAddress);
            $("#edit_contact_no").val(response.vcContactNo);
            // submit the edit from 
            $("#updateBranchForm").unbind('submit').bind('submit', function() {
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
                            $("#editBranchModal").modal('hide');
                            $("#updateBranchForm")[0].reset();
                            $("#updateBranchForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editBranchModal").modal('hide');
                                $("#updateBranchForm")[0].reset();
                                $("#updateBranchForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function removeBranch(id) {
    if (id) {

        // submit the edit from 
        $("#removeBranchForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intBranchID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);


                        // hide the modal
                        $("#removeBranchModal").modal('hide');
                        $("#removeBranchForm")[0].reset();
                        $("#removeBranchForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["warning"](response.messages);

                        // hide the modal
                        $("#removeBranchModal").modal('hide');
                        $("#removeBranchForm")[0].reset();
                        $("#removeBranchForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}