var manageTable;

$(document).ready(function() {
    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchUserData',
        'order': []
    });

    // submit the create from 
    $("#createUserForm").unbind('submit').on('submit', function() {
        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
            async: true,
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(response) {

                manageTable.ajax.reload(null, false);

                if (response.success === true) {


                    toastr["success"](response.messages);

                    // hide the modal
                    $("#addUserModal").modal('hide');

                    // reset the form
                    $("#createUserForm")[0].reset();
                    $("#createUserForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        $("#addUserModal").modal('hide');
                        $("#createUserForm")[0].reset();
                    }
                }
            }
        });

        return false;
    });


});

function editUser(id) {
    $.ajax({
        async: true,
        url: 'fetchUserDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_user_name").val(response.vcUserName);
            // $("#edit_password").val(response.vcPassword);
            $('#edit_full_name').val(response.vcFullName);
            $("#edit_email").val(response.vcEmail);
            $('#edit_contact_no').val(response.vcContactNo);
            $('#edit_user_group').val(response.intUserGroupID);
            $('#edit_user_group').trigger('change');
            $('#edit_branch').val(response.intBranchID);
            $('#edit_branch').trigger('change');
            if (response.IsAdmin == 1) {
                document.getElementById("edit_IsAdmin").checked = true;
            } else {
                document.getElementById("edit_IsAdmin").checked = false;
            }

            // submit the edit from 
            $("#editUserForm").unbind('submit').bind('submit', function() {
                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    async: false,
                    url: form.attr('action') + '/' + id,
                    type: form.attr('method'),
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    success: function(response) {

                        manageTable.ajax.reload(null, false);

                        if (response.success === true) {

                            toastr["success"](response.messages);

                            // hide the modal
                            $("#editUserModal").modal('hide');
                            $("#editUserForm")[0].reset();
                            $("#editUserForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editUserModal").modal('hide');
                                $("#editUserForm")[0].reset();
                                $("#editUserForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function removeUser(id) {
    if (id) {

        // submit the edit from 
        $("#removeUserForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                async: true,
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intUserID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeUserModal").modal('hide');
                        $("#removeUserForm")[0].reset();
                        $("#removeUserForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeUserModal").modal('hide');
                        $("#removeUserForm")[0].reset();
                        $("#removeUserForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}