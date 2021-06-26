var manageTable;

$(document).ready(function () {
    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchCustomerData',
        'order': []
    });


    $('#edit_credit_limit').on('keyup', function (e) {
        if ($('#edit_credit_limit').val() > 0) {
            if (parseFloat($('#edit_credit_limit').val()) < parseFloat($('#available_limit').val())) {
                toastr["error"]("You can't enter lower than available limit  !");
                $("#btnUpDateCustomer").prop('disabled', true);
            }
            else{
                $("#btnUpDateCustomer").prop('disabled', false);
            }
        }

    });



    // submit the create from 
    $("#createCustomerForm").unbind('submit').on('submit', function () {
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
                    $("#addCustomerModal").modal('hide');

                    // reset the form
                    $("#createCustomerForm")[0].reset();
                    $("#createCustomerForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        $("#addCustomerModal").modal('hide');

                    }
                }
            }
        });

        return false;
    });


});

function editCustomer(id) {
    $.ajax({
        url: 'fetchCustomerDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function (response) {

            $("#edit_customer_name").val(response.vcCustomerName);
            $("#edit_building_number").val(response.vcBuildingNumber);
            $("#edit_street").val(response.vcStreet);
            $("#edit_city").val(response.vcCity);
            $("#edit_contact_no_1").val(response.vcContactNo1);
            $("#edit_contact_no_2").val(response.vcContactNo2);
            $("#edit_credit_limit").val(response.decCreditLimit);
            $("#available_limit").val(response.decAvailableCredit);
            // submit the edit from 
            $("#updateCustomerForm").unbind('submit').bind('submit', function () {
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
                            $("#editCustomerModal").modal('hide');
                            $("#updateCustomerForm")[0].reset();
                            $("#updateCustomerForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editCustomerModal").modal('hide');
                                $("#updateCustomerForm")[0].reset();
                                $("#updateCustomerForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function removeCustomer(id) {
    if (id) {

        // submit the edit from 
        $("#removeCustomerForm").unbind('submit').bind('submit', function () {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intCustomerID: id
                },
                dataType: 'json',
                success: function (response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeCustomerModal").modal('hide');
                        $("#removeCustomerForm")[0].reset();
                        $("#removeCustomerForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeCustomerModal").modal('hide');
                        $("#removeCustomerForm")[0].reset();
                        $("#removeCustomerForm .form-group").removeClass('has-error').removeClass('has-success');
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