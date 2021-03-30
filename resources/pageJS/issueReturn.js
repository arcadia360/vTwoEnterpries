$(document).ready(function () {


    $('#cmbIssueNo').on('select2:close', function (e) {
        getIssuedHeaderData();
        getIssuedDetails();
    });


});

var Issue = function () {
    this.intIssueHeaderID = 0;
}

function getIssuedHeaderData()
{
    var IssueHeaderID = $("#cmbIssueNo").val();
    if (IssueHeaderID > 0) {
        var model = new Issue(); 
        model.intIssueHeaderID = IssueHeaderID;
        ajaxCall('Issue/getIssuedHeaderData', model, function (response) {
                 $("#Customer").val(response.vcCustomerName);
                $("#IssuedDate").val(response.dtIssueDate);
                $("#CreatedDate").val(response.dtCreatedDate);
                $("#CreatedUser").val(response.vcFullName);
                $("#PaymentMode").val(response.vcPaymentType);
                $("#AdvanceAmount").val(response.decAdvanceAmount);
                $("#SubTotal").val(response.decSubTotal);
                $("#Discount").val(response.decDiscount);
                $("#GrandTotal").val(response.decGrandTotal);
      
        });
    }
}

function getIssuedDetails(){
    var IssueHeaderID = $("#cmbIssueNo").val();
    if (IssueHeaderID > 0) {

        $('#IssueItemTable tbody').empty();
    
        var model = new Issue(); 
        model.intIssueHeaderID = IssueHeaderID;

        ajaxCall('Issue/ViewIssueDetailsToTable', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {


                $("#IssueItemTable tbody").append('<tr>'+
                '<td hidden><input type="number" class="form-control" name="txtItemID[]" value="'+response[index].vcItemName+'"></td>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcItemName+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" value="'+response[index].vcMeasureUnit+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].decUnitPrice+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtBalanceQty[]" id="txtBalanceQty" style="text-align:right;" value="'+response[index].decIssueQty +'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtBalanceQty[]" id="txtBalanceQty" style="text-align:right;" value="'+response[index].decTotalPrice +'" disabled></td>'+
                '<td hidden><input type="text" class="form-control" name="txtRv[]" id="txtRv"></td>'+
            '</tr>');
            }
      
        });
    }
}

$('#btnSubmit').click(function () {

    if ($("#cmbIssueNo option:selected").val() == 0) {
        toastr["error"]("Please select Issue No !");
        $("#cmbIssueNo").focus();
        return;
    }
    if ($("input[name=Reason]").val() == "") {
        toastr["error"]("Please Enter Return Reason !");
        $("#Reason").focus();
        return;
    }
        arcadiaConfirmAlert("You want to be able to return this issue note !", function (button) {

            var form = $("#issueNote");

            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(), 
                dataType: 'json',
                success: function (response) {
                    debugger;
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
                            toastr["error"](response.messages);
                            // arcadiaErrorMessage(response.messages);
                            // $(button).prop('disabled', false);
                        }
                    }
                }
            });
        }, this);
    // }

});


