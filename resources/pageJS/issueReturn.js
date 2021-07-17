$(document).ready(function () {


});

$('#cmbIssueNo').on('select2:close', function (e) {
    getIssuedHeaderData();
    getIssuedDetails();

});


var Issue = function () {
    this.intIssueHeaderID = 0;
}

function getIssuedHeaderData() {
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

function CalculateGrandTotal() {
    if ($('#IssueItemTable tr').length > 1) { // Because table header row in here

        var Total = 0;

        $('#IssueItemTable tbody tr').each(function () {
            var value = parseFloat($(this).closest("tr").find('#total').val());
            if (!isNaN(value)) {
                Total += value;
            }
           
        });

        $("#grandTotal").val(currencyFormat(Total));

    } else {
      
        $("#grandTotal").val("0.00");
    }
}

function getIssuedDetails() {
    var IssueHeaderID = $("#cmbIssueNo").val();
    if (IssueHeaderID > 0) {

        $('#IssueItemTable tbody').empty();

        var total = 0;
        var model = new Issue();
        model.intIssueHeaderID = IssueHeaderID;

        ajaxCall('Issue/ViewIssueDetailsToReturnData', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {


                $("#IssueItemTable tbody").append('<tr>' +
                    '<td hidden><input type="number" class="form-control" name="txtGRNDetailID[]" value="' + response[index].intGRNDetailID + '"></td>' +
                    '<td hidden><input type="number" class="form-control" name="txtIssueDetailID[]" value="' + response[index].intIssueDetailID + '"></td>' +
                    '<td hidden><input type="number" class="form-control itemID" name="txtItemID[]" value="' + response[index].intItemID + '"></td>' +
                    '<td hidden><input type="text" class="form-control txtUnitPrice" name="txtUnitPrice[]" id="txtUnitPrice" style="text-align:right;" value="' + response[index].decUnitPrice + '"></td>' +
                    '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="' + response[index].vcItemName.replace(/"/g, "\'\'") + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" value="' + response[index].vcMeasureUnit + '" disabled></td>' +
                    '<td><input type="text" class="form-control" unitPrice name="decUnitPrice[]" id="decUnitPrice" style="text-align:right;" value="' + response[index].decUnitPrice + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="decIssueQty[]" id="decIssueQty" style="text-align:right;" value="' + response[index].decIssueQty + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="decTotalPrice[]" id="decTotalPrice" style="text-align:right;" value="' + response[index].decTotalPrice + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtBalanceQty[]" id="txtBalanceQty" style="text-align:right;" value="' + response[index].decBalaceReturnQty + '" disabled></td>' +
                    '<td><input type="text" class="form-control only-decimal" returnQty name="txtReturnQty[]" id="txtReturnQty" style="text-align:center;" placeholder="_ _ _ _ _" onkeyup="validateReturnQty(this)" onkeypress="return isNumber(event,this)" ></input></td>' +
                    '<td><input type="text" total class="form-control" id="total" name="total[]" value="' + total + '" disabled></td>' +
                    '<td hidden><input type="text" class="form-control" name="txtRv[]" id="txtRv" value="' + response[index].rv + '" ></td>' +
                    '</tr>');
            }
            CalculateItemCount();

        });
    }
}




function validateReturnQty(evnt) {


    CalItemWiseTotal(evnt);

    var BalanceQty = $(evnt).closest("tr").find('#txtBalanceQty').val();

    if (parseFloat(BalanceQty) == 0) {
        $(evnt).closest("tr").find('#txtReturnQty').val(null);
        toastr["error"]("You can't return this. Because this item already fully returned !");
        $(evnt).closest("tr").find('#total').val(0);
    } else if (parseFloat(BalanceQty) > 0) {
        if (parseFloat($(evnt).closest("tr").find('#txtReturnQty').val()) > parseFloat(BalanceQty)) {
            toastr["error"]("You can't exceed balance quantity  !");
            $(evnt).closest("tr").find('#txtReturnQty').val(null);
            $(evnt).closest("tr").find('#total').val(0);
        }
    }

    CalculateGrandTotal();


}


function CalItemWiseTotal(evnt) {

    var unitPrice = $(evnt).closest("tr").find('#txtUnitPrice').val();
    var qty = $(evnt).closest("tr").find('#txtReturnQty').val();

    var total = 0;
    if (qty != "") {
        var total = (unitPrice * qty);
    }
    // $("#total").val(total);
    // $("#total").val(parseFloat(total).toFixed(2));
    $(evnt).closest("tr").find('#total').val(parseFloat(total).toFixed(2));
}

function CalculateItemCount() {
    var itemCount = 0;
    var lastItemID = 0;
    $('#IssueItemTable tbody tr').each(function () {
        var value = parseFloat($(this).closest("tr").find('.itemID').val());
        if (!isNaN(value) && lastItemID != value) {
            itemCount++;
            lastItemID = value;
        }
    });

    $("#itemCount").text("Item Count : " + (itemCount));

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


