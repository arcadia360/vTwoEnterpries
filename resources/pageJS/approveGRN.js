$(document).ready(function () {

    CalculateItemCount();
});

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 1));
}

var GRN = function () {
    this.intGRNHeaderID = 0;
}

function approveGRN(GRNHeaderID) {
    arcadiaConfirmAlert("You want to be able to approve this !", function (button) {

        // $.ajax({
        //     async: true,
        //     url: base_url + 'GRN/ApproveGRN',
        //     type: 'post',
        //     data: {
        //         intGRNHeaderID: GRNHeaderID
        //     },
        //     dataType: 'json',
        //     success: function (response) {
        //         if (response.success == true) {
        //             arcadiaSuccessMessage("Approved !", "GRN/ViewGRN");
        //         } else {
        //             toastr["error"](response.messages);
        //         }
        //     },
        //     error: function (request, status, error) {
        //         arcadiaErrorMessage(error);
        //     }
        // });

        var model = new GRN();
        model.intGRNHeaderID = GRNHeaderID;

        // ajaxCall('GRN/ApproveGRN', { 'model': model }, function (response) {
        ajaxCall('GRN/ApproveGRN', model, function (response) {
            if (response.success == true) {
                arcadiaSuccessMessage("Approved !", "GRN/ViewGRN");
            } else {
                toastr["error"](response.messages);
            }
        });


    }, this);
}

function rejectGRN(GRNHeaderID) {
    arcadiaConfirmAlert("You want to be able to reject this !", function (button) {
        $.ajax({
            async: true,
            url: base_url + 'GRN/RejectGRN',
            type: 'post',
            data: {
                intGRNHeaderID: GRNHeaderID
            },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Rejected !", "GRN/ViewGRN");
                } else {
                    toastr["error"](response.messages);
                }
            },
            error: function (request, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }, this);
}