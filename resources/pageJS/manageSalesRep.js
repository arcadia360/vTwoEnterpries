var manageTable;

$(document).ready(function () {
    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchSalesRepData',
        'order': []
    });


    // submit the create from 
    $("#createSalesRepForm").unbind('submit').on('submit', function () {
        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        const length = $("#contact_no").val().length;

        // remove the text-danger
        $(".text-danger").remove();
        if (jQuery.trim($("#salesRep_name").val()).length == 0) {
            toastr["error"]("Please Enter Name !");
            // return;
        }
        else if (jQuery.trim($("#contact_no").val()).length == 0) {
            toastr["error"]("Please Enter Contact No !");
            // return;
        }
        else if (length != 10) {
            toastr["error"]("Please Enter Valid Contact No !");
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
                        $("#addSalesRepModal").modal('hide');

                        // reset the form
                        $("#createSalesRepForm")[0].reset();
                        $("#createSalesRepForm .form-group").removeClass('has-error').removeClass('has-success');

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
                            $("#addSalesRepModal").modal('hide');

                        }
                    }
                }
            });
        }

        return false;
    });


});

function editSalesRep(id) {
    $.ajax({
        url: 'fetchSalesRepDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function (response) {

            $("#edit_salesRep_name").val(response.vcSalesRepName);
            $("#edit_contact_no").val(response.vcContactNo);
            $("#edit_address").val(response.vcAddress);

            // submit the edit from 
            $("#updateSalesRepForm").unbind('submit').bind('submit', function () {
                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                const length = $("#edit_contact_no").val().length;

                // remove the text-danger
                $(".text-danger").remove();
                if (jQuery.trim($("#edit_salesRep_name").val()).length == 0) {
                    toastr["error"]("Please Enter Name !");
                    // return;
                }
                else if (jQuery.trim($("#edit_contact_no").val()).length == 0) {
                    toastr["error"]("Please Enter Contact No !");
                    // return;
                }
                else if (length != 10) {
                    toastr["error"]("Please Enter Valid Contact No !");
                    // return;
                }
                else {

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
                                $("#editSalesRepModal").modal('hide');
                                $("#updateSalesRepForm")[0].reset();
                                $("#updateSalesRepForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                    $("#editSalesRepModal").modal('hide');
                                    $("#updateSalesRepForm")[0].reset();
                                    $("#updateSalesRepForm .form-group").removeClass('has-error').removeClass('has-success');
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

function removeSalesRep(id) {
    if (id) {

        // submit the edit from 
        $("#removeSalesRepForm").unbind('submit').bind('submit', function () {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intSalesRepID: id
                },
                dataType: 'json',
                success: function (response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeSalesRepModal").modal('hide');
                        $("#removeSalesRepForm")[0].reset();
                        $("#removeSalesRepForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeSalesRepModal").modal('hide');
                        $("#removeSalesRepForm")[0].reset();
                        $("#removeSalesRepForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}

function testToast() {


    toastr["warning"]("My name is Inigo Montoya. You killed my father. Prepare to die!");


}