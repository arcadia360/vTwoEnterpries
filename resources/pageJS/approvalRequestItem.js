$(document).ready(function() {

    CalculateItemCount();

    // $("#btnApproval").click(function () {
    //     var stockInHand = $(this).closest("tr").find('.stockInHand').val();
    //     var RequestQty = $(this).closest("tr").find('.itemQty').val();
    //    alert(RequestQty);
    //     // if (stockInHand == "N/A") {
    //     //     alert("NATHOOO");
    //     // }
    // });

    $('#btnRejectAll').click(function() {

        arcadiaConfirmAlert("You want to be able to reject all this !", function(button) {

            var form = $("#approvalOrRejectRequestItems");

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize() + "&isApproved=0",
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        arcadiaSuccessMessage("All Rejected !", "request/ViewRequest");
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
                            // arcadiaErrorMessage(response.messages);
                            // $(button).prop('disabled', false);
                        }
                    }

                },
                error: function(request, status, error) {
                    arcadiaErrorMessage(error);
                }
            });
        }, this);

    });

    $("#btnRejectAll").unbind('submit').on('submit', function(e) {});

    $('#btnApprovalAll').click(function() {

        arcadiaConfirmAlert("You want to be able to Accept & Issue all this !", function(button) {

            var form = $("#approvalOrRejectRequestItems");

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize() + "&isApproved=1",
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        arcadiaSuccessMessage("All Accept & Issue !", "request/ViewRequest");
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

                        }
                    }

                },
                error: function(request, status, error) {
                    arcadiaErrorMessage(error);
                }
            });
        }, this);

    });

    $("#btnApprovalAll").unbind('submit').on('submit', function(e) {});

});

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 1));
}



function RejectRequestByDetailID(RequestDetailID, ItemID, rv) {

    arcadiaConfirmAlert("You want to be able to Reject this !", function(button) {

        $.ajax({
            url: base_url + 'request/RejectRequestByDetailID/' + RequestDetailID + '/' + ItemID + '/' + rv,
            type: 'post',
            dataType: 'json',
            async: true,
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Rejected !");
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


function ApprovalRequestByDetailID(RequestDetailID, ItemID, rv) {
    arcadiaConfirmAlert("You want to be able to Approval this !", function(button) {

        $.ajax({
            async: true,
            url: base_url + 'request/ApprovalRequestByDetailID/' + RequestDetailID + '/' + ItemID + '/' + rv,
            type: 'post',
            dataType: 'json',
            async: true,
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Approved !");
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