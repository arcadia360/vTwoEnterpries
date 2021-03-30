$(document).ready(function() {

    CalculateItemCount();

    $('#btnCancelAllItems').click(function() {
        debugger;
        arcadiaConfirmAlert("You want to be able to Cancel All Items !", function(button) {

            var form = $("#cancelAllRequestItems");

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        arcadiaSuccessMessage("All Cancelled !", "request/ViewRequest");
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

    $("#btnCancelAllItems").unbind('submit').on('submit', function(e) {});

});

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 1));
}


function IssuedRequestCancelByDetailID(RequestDetailID, ItemID, rv) {
    arcadiaConfirmAlert("You want to be able to Cancel this !", function(button) {

        $.ajax({
            async: true,
            url: base_url + 'request/IssuedRequestCancelByDetailID/' + RequestDetailID + '/' + ItemID + '/' + rv,
            type: 'post',
            dataType: 'json',
            async: true,
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Cancelled !");
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