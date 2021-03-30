$(document).ready(function() {

    CalculateItemCount();

    $('#btnAddtAllItems').click(function() {
        debugger;
        arcadiaConfirmAlert("You want to be able to Accept and Ignore the Rejected Items !", function(button) {

            var form = $("#acceptAllRequestItems");

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        arcadiaSuccessMessage("All Accepted !", "request/ViewRequest");
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

    $("#btnAddtAllItems").unbind('submit').on('submit', function(e) {});

});

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 1));
}


function acceptRequestByDetailID(RequestDetailID, ItemID) {
    arcadiaConfirmAlert("You want to be able to Accept this !", function(button) {

        $.ajax({
            async: true,
            url: base_url + 'request/AcceptRequestByDetailID/' + RequestDetailID + '/' + ItemID,
            type: 'post',
            dataType: 'json',
            async: true,
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Accepted !");
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