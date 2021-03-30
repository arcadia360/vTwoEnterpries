var manageTable;


$(document).ready(function() {
    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchUserGroupData',
        'order': []
    });


    // submit the create from 
    $("#createUserGroupForm").unbind('submit').on('submit', function() {
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
                    $("#addUserGroupModal").modal('hide');

                    // reset the form
                    $("#createUserGroupForm")[0].reset();
                    $("#createUserGroupForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        $("#addUserGroupModal").modal('hide');

                    }
                }
            }
        });

        return false;
    });


});

function editUserGroup(id) {
    $.ajax({
        url: 'fetchUserGroupDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_group_name").val(response.vcGroupName);

            $("#updateUserGroupForm").unbind('submit').bind('submit', function() {
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
                            $("#editUserGroupModal").modal('hide');
                            $("#updateUserGroupForm")[0].reset();
                            $("#updateUserGroupForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editUserGroupModal").modal('hide');
                                $("#updateUserGroupForm")[0].reset();
                                $("#updateUserGroupForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function removeUserGroup(id) {
    if (id) {

        // submit the edit from 
        $("#removeUserGroupForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intUserGroupID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeUserGroupModal").modal('hide');
                        $("#removeUserGroupForm")[0].reset();
                        $("#removeUserGroupForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeUserGroupModal").modal('hide');
                        $("#removeUserGroupForm")[0].reset();
                        $("#removeUserGroupForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}