$(document).ready(function () {

    $('#cmbSupplierSettlementNo').on('select2:close', function (e) {
        getSupplierSettlementHeaderData();
        getSupplierSettlementDetails();
    });


});

var Supplier = function () {
    this.intSupplierSettlementHeaderID = 0;
}

function getSupplierSettlementHeaderData()
{
    var SupplierSettlementHeaderID = $("#cmbSupplierSettlementNo").val();
    if (SupplierSettlementHeaderID > 0) {
        var model = new Supplier(); 
        model.intSupplierSettlementHeaderID = SupplierSettlementHeaderID;
        ajaxCall('Supplier/getSupplierSettlementHeaderData', model, function (response) {
                $("#Supplier").val(response.vcSupplierName);
                $("#SettlementDate").val(response.dtPaidDate);
                $("#CreatedDate").val(response.dtCreatedDate);
                $("#CreatedUser").val(response.vcFullName);
                $("#PaymentMode").val(response.vcPayMode);
                $("#ChequeNo").val(response.vcChequeNo);
                $("#BankName").val(response.vcBankName);
                $("#PODate").val(response.dtPDDate);
                $("#PaidAmount").val(response.decAmount);
                $("#Remark").val(response.vcRemark);
        });
    }
}

function getSupplierSettlementDetails()
{
    var SupplierSettlementHeaderID = $("#cmbSupplierSettlementNo").val();
    if (SupplierSettlementHeaderID > 0) {

        $('#IssueItemTable tbody').empty();
        
        var model = new Supplier(); 
        model.intSupplierSettlementHeaderID = SupplierSettlementHeaderID;

        ajaxCall('Supplier/ViewSettlementDetailsToModal', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>'+
                '<td><input type="text" class="form-control" name="txtGRNNo[]" id="txtGRNNo" style="text-align:center;" value="'+response[index].vcGRNNo+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtPaidAmount[]" id="txtPaidAmount" style="text-align:right;" value="'+response[index].PaidAmount+'" disabled></td>'+
                '<td hidden><input type="text" class="form-control" name="txtRv[]" id="txtRv"></td>'+
            '</tr>');
            }
      
        });
    }
}


$('#btnSubmit').click(function () {

    if ($("#cmbSupplierSettlementNo option:selected").val() == 0) {
        toastr["error"]("Please select Settlement No !");
        $("#cmbIssueNo").focus();
        return;
    }
    if ($("input[name=Reason]").val() == "") {
        toastr["error"]("Please Enter Return Reason !");
        $("#Reason").focus();
        return;
    }
        arcadiaConfirmAlert("You want to be able to return this issue note !", function (button) {

            var form = $("#cancelSupplierCreditSettlement");

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