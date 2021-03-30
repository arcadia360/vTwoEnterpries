var manageTable;

$(document).ready(function () {


    $('#cmbCustomerFilter').on('change', function () {
        FilterItems();
    
    });

    FilterItems();


    $("#addConfigModal").on("hidden.bs.modal", function () {
        $('#cmbCustomer').val('0'); // Select the option with a value of '0'
        $('#cmbCustomer').trigger('change'); // Notify any JS components that the value changed
        $('#advance_amount').val('');

    });

    // manageTable = $('#manageTable').DataTable({
    //     'ajax': 'fetchCustomerAdvancePaymentData',
    //     'order': [],
    //     "bDestroy": true,
    //     "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
    //         $(nRow.childNodes[7]).css('text-align', 'center');
    //         $(nRow.childNodes[8]).css('text-align', 'center');
    //     }
    // });

    $('#btnSubmit').click(function () {
        if ($("#cmbCustomer option:selected").val() == 0) {
            toastr["error"]("Please select a Customer !");
            return;
        } 
        if ($("input[name=advance_amount]").val() == "") {
            toastr["error"]("Please Enter Advance Amount !");
            return;
        }
      else {
            arcadiaConfirmAlert("You want to be able to save this !", function (button) {

                var form = $("#createCustomerAdvancePayment");

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    async: true,
                    success: function (response) {
                        if (response.success == true) {
                            arcadiaSuccessMessage("Saved !");
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
                                // toastr["error"](response.messages);

                                arcadiaErrorMessage(response.messages);
                            }
                        }

                    }
                });
            }, this);
        }

    });

    $("#createCustomerAdvancePayment").unbind('submit').on('submit', function (e) { });

});



function FilterItems() {
    var CustomerID = $('#cmbCustomerFilter').val();
    $('#manageTable').DataTable({
        'ajax': 'fetchCustomerAdvancePaymentData/' + CustomerID,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');

        }
    });

}



function RemoveCustomerAdvancePayment(CustomerAdvancePaymentID,rv) {
    arcadiaConfirmAlert("You want to be able to remove this !", function(button) {

        $.ajax({
            async: true,
            url: base_url + 'Customer/RemoveCustomerAdvancePayment',
            type: 'post',
            data: {
                intCustomerAdvancePaymentID: CustomerAdvancePaymentID,
                rv : rv
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Deleted !", "Customer/manageCustomerAdvancePayment");
                } else {
                    toastr["error"](response.messages);
                }
            },
            error: function(request, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }, this);
}



// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

