var totalAllocatedAmount = 0;
var totalPayAmount = 0;
var totalAvailableAmount = 0;

var Customer = function () {
    this.intCustomerID = 0;
}
var Issue = function () {
    this.intIssueHeaderID = 0;
}

var row_id = 1;



$(document).ready(function () {
    $("#btnSubmit").prop('disabled', true);
    $("#cmbPayMode").prop('disabled', true);
    $("#txtAmount").prop('disabled', true);
    $("#txtAmount").css('background-color', '#eee');
    $("#cmbBank").prop('disabled', true);
    $("#txtChequeNo").prop('disabled', true);
    $("#txtChequeNo").css('background-color', '#eee');
    $("#PDDate").prop('disabled', true);
    $("#PDDate").css('background-color', '#eee');
    $("#txtRemark").prop('disabled', true);
    $("#txtRemark").css('background-color', '#eee');

    $('#cmbCustomer').on('select2:close', function (e) {
        ResetGrid();
        setCustomerIssueDetails();
    });

    $('#cmbPayMode').on('select2:close', function (e) {
        ResetGrid();
        $("#txtAmount").val("");

        if ($('#cmbPayMode').val() == 1) { // Cash

            $("#cmbBank").val(0); // Select the option with a value of '0'
            $('#cmbBank').trigger('change'); // Notify any JS components that the value changed
            $("#cmbBank").prop('disabled', true);

            $("#txtChequeNo").val("");
            $("#txtChequeNo").prop('disabled', true);
            $("#txtChequeNo").css('background-color', '#eee');

            $("#PDDate").prop('disabled', true);
            $("#PDDate").css('background-color', '#eee');

            $('#dtPDDate').datetimepicker({
                defaultDate: new Date()
            });

            $("#txtAmount").focus();

        } else if ($('#cmbPayMode').val() == 2) { // Cheque

            $("#cmbBank").val('0');
            $("#cmbBank").prop('disabled', false);

            $("#txtChequeNo").val("");
            $("#txtChequeNo").prop('disabled', false);
            $("#txtChequeNo").css('background-color', '#FFFFFF');

            $("#PDDate").prop('disabled', false);
            $("#PDDate").css('background-color', '#FFFFFF');

            $("#txtAmount").focus();

        }
    });

    $('#txtAmount').on('keyup', function (e) {
        ResetGrid();
        if (parseFloat($("#txtTotalOutstanding").val()) < parseFloat($("#txtAmount").val())) {
            $("#txtAmount").val("");
            toastr["error"]("You can't enter more than customer outstanding amount !");
        }
        calculateTotalAllocatedAndAvailableAmount();
    });

    $('#cmbBank').on('select2:close', function (e) {
        $("#txtChequeNo").focus();
    });

    $('#cmbIssueNo').on('select2:close', function (e) {
        if ($('#cmbCustomer').val() > 0) {
            if ($("#cmbIssueNo").val() > 0) {
                if (checkPayModeSelected()) {
                    var model = new Issue();
                    model.intIssueHeaderID = $('#cmbIssueNo').val();
                    ajaxCall('Receipt/getIssueNotePaymentDetails', model, function (response) {
                        $("#txtTotalAmount").val(parseFloat(response.decGrandTotal).toFixed(2));
                        $("#txtPaidAmount").val(parseFloat(response.decPaidAmount).toFixed(2));
                        $("#txtOutstrandingAmount").val((parseFloat(response.decGrandTotal) - parseFloat(response.decPaidAmount)).toFixed(2));
                        $("#txtRv").val(response.rv);

                        $("#txtPayAmount").focus();
                    });
                }
            }
        } else {
            toastr["error"]("Please Select Customer First !");
            $('#cmbCustomer').focus();
        }
    });

    $('#txtPayAmount').on('keyup', function (e) {
        if ($("#cmbIssueNo option:selected").val() == 0) {
            toastr["error"]("Please select issue no !");
            $("#txtPayAmount").val("");
            $('#cmbIssueNo').focus();
        } else if ($("#txtPayAmount").val() != "") {
            if (parseFloat($("#txtPayAmount").val()) > parseFloat($("#txtOutstrandingAmount").val())) {
                toastr["error"]("You can't exceed outstanding amount!");
                $("#txtPayAmount").val("");
            } else if (parseFloat($("#txtPayAmount").val()) > totalAvailableAmount) {
                toastr["error"]("You can't exceed available amount!");
                $("#txtPayAmount").val("");
            }
        }
    });

    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if ($("#cmbIssueNo option:selected").val() == 0) {
                toastr["error"]("Please select issue no !");
            } else if ($("#txtPayAmount").val() == "") {
                toastr["error"]("Please enter pay amount first !");
            } else {
                AddToGrid();
            }
        }

        event.stopPropagation();
    });


    $("#btnAddToGrid").click(function () {
        if ($("#cmbIssueNo option:selected").val() == 0) {
            toastr["error"]("Please select issue no !");
        } else if ($("#txtPayAmount").val() == "") {
            toastr["error"]("Please enter pay amount first !");
        } else {
            AddToGrid();
        }
    });



  

});

$('#btnSubmit').click(function () {
    if (totalAvailableAmount > 0) {
        toastr["error"]("You have available amount, Please allocate total amount and try again !");
        return;
    }
    if ($("#cmbCustomer option:selected").val() == 0) {
        toastr["error"]("Please select customer !");
        $("#cmbCustomer").focus();
        return;
    }
    if ($('#receiptTable tr').length == 2) {
        toastr["error"]("Please allocate pay amount into issue numbers !");
        $("#cmbIssueNo").focus();
        return;
    } 
    if (checkPayModeSelected()) {

        arcadiaConfirmAlert("You want to be able to create this !", function (button) {
            var form = $("#createReceipt");

            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        // alert(response.messages);
                        arcadiaSuccessMessage(response.messages, "Receipt/CreateReceipt");
                        // $('#printpage', window.parent.document).hide();
                    } else {
                        toastr["error"](response.messages);
                    }

                }
            });
        }, this);
    }
});

function setCustomerIssueDetails() {
    var model = new Customer();
    model.intCustomerID = $('#cmbCustomer').val();
    ajaxCall('Receipt/getCustomerToBeSettleIssueNos', model, function (response) {
        $("#cmbPayMode").prop('disabled', false);
        $("#txtAmount").prop('disabled', false);
        $("#txtAmount").css('background-color', '#FFFFFF');
        $("#txtRemark").prop('disabled', false);
        $("#txtRemark").css('background-color', '#FFFFFF');

        var issueTotal = 0;
        var paidTotal = 0;
        var issueNoHTML = '<option value="0" disabled selected hidden>Select Issue No</option>';

        for (let index = 0; index < response.length; index++) {
            issueNoHTML += '<option value="' + response[index].intIssueHeaderID + '">' + response[index].vcIssueNo + '</option>';
            issueTotal += parseFloat(response[index].decGrandTotal);
            paidTotal += parseFloat(response[index].decPaidAmount);
        }
        $("#txtTotalOutstanding").val(parseFloat(issueTotal - paidTotal).toFixed(2));
        $("#cmbIssueNo").empty();
        $("#cmbIssueNo").append(issueNoHTML);
    });
}

function checkPayModeSelected() {
    if ($("#cmbPayMode").val() == 1) {  // Cash
        if ($("#txtAmount").val() == "") {
            $("#txtAmount").val("");
            $("#txtAmount").focus();
            toastr["error"]("Please Enter Cash Amount First !");
            return false;
        } else {
            if (parseFloat($("#txtAmount").val()) != 0) {
                return true;
            } else {
                $("#txtAmount").val("");
                $("#txtAmount").focus();
                toastr["error"]("Please Enter Cash Amount First !");
                return false;
            }
        }
    } else { // Cheque - 2
        if ($("#txtAmount").val() == "") {
            $("#txtAmount").val("");
            $("#txtAmount").focus();
            toastr["error"]("Please Enter Cheque Amount First !");
            return false;
        } else {
            if (parseFloat($("#txtAmount").val()) == 0) {
                $("#txtAmount").val("");
                $("#txtAmount").focus();
                toastr["error"]("Please Enter Cheque Amount First !");
                return false;
            } else if ($("#cmbBank option:selected").val() == 0) {
                $("#cmbBank").focus();
                toastr["error"]("Please Select Bank !");
                return false;
            } else if ($("#txtChequeNo").val() == "") {
                $("#txtChequeNo").focus();
                toastr["error"]("Please Enter Cheque No !");
                return false;
            } else {
                return true;
            }
        }
    }
}

function ResetGrid() {
    $("#cmbIssueNo").val(0); // Select the option with a value of '0'
    $('#cmbIssueNo').trigger('change'); // Notify any JS components that the value changed
    $("#txtTotalAmount").val("");
    $("#txtPaidAmount").val("");
    $("#txtOutstrandingAmount").val("");
    $("#txtPayAmount").val("");
    $("#txtRv").val("");
    totalOutstanding = 0;
    totalPayAmount = 0
    totalAllocatedAmount = 0;
    totalAvailableAmount = 0;

    $("#receiptTable").find(".generatedRow").remove();
    setCustomerIssueDetails();
    calculateTotalAllocatedAndAvailableAmount();
    $("#btnSubmit").prop('disabled', true);
}

function calculateTotalAllocatedAndAvailableAmount() {
    if ($("#txtAmount").val() == "") {
        totalPayAmount = 0;
    } else {
        totalPayAmount = parseFloat($("#txtAmount").val());
    }
    totalAllocatedAmount = 0;
    $('#receiptTable tbody tr').each(function () {
        var value = parseFloat($(this).closest("tr").find('.total').val());
        if (!isNaN(value)) {
            totalAllocatedAmount += value;
        }
    });

    totalAvailableAmount = totalPayAmount - totalAllocatedAmount;

    $("#txtTotalAllocated").val(parseFloat(totalAllocatedAmount).toFixed(2));
    $("#txtTotalAvailable").val(parseFloat(totalAvailableAmount).toFixed(2));

    if (totalAvailableAmount == 0) {
        $("#btnSubmit").prop('disabled', false);
    } else {
        $("#btnSubmit").prop('disabled', true);
    }

}


function AddToGrid() {

    if ($("#txtPayAmount").val() > 0) {

        if ($("#cmbIssueNo option:selected").val() > 0) {
            var IssueHeaderID = $("#cmbIssueNo option:selected").val();
            var IssueNo = $("#cmbIssueNo option:selected").text();
            var totalAmount = $("#txtTotalAmount").val();
            var paidAmount = $("#txtPaidAmount").val();
            var outstandingAmount = $("#txtOutstrandingAmount").val();
            var payAmount = $("#txtPayAmount").val();
            var rv = $("#txtRv").val();

            $(".first-tr").after('<tr class="generatedRow">' +
                '<td hidden>' +
                '<input type="text" class="form-control issueHeaderID disable-typing" name="issueHeaderID[]" id="issueHeaderID_' + row_id + '" value="' + IssueHeaderID + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control issueNo disable-typing" name="issueNo[]" id="issueNo_' + row_id + '" value="' + IssueNo + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control disable-typing" style="text-align:right;" name="totalAmount[]" id="totalAmount_' + row_id + '" value="' + parseFloat(totalAmount).toFixed(2) + '" readonly>' +
                '</td>' +
                '<td>' +
                '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="paidAmount[]" id="paidAmount_' + row_id + '"  value="' + paidAmount + '" readonly>' +
                '</td>' +
                '<td>' +
                '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="outstandingAmount[]" id="outstandingAmount_' + row_id + '"  value="' + outstandingAmount + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="payAmount[]" id="payAmount_' + row_id + '"  value="' + payAmount + '" readonly>' +
                '</td>' +
                '<td hidden>' +
                '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + rv + '" readonly>' +
                '</td>' +
                '<td class="static">' +
                '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                '</td>' +
                '</tr>');

            row_id++;
            remove();
            $("#cmbIssueNo :selected").remove();

            $("input[name=txtTotalAmount], input[name=txtPaidAmount],input[name=txtOutstrandingAmount],input[name=txtPayAmount],input[name=txtRv]").val("");

            CalculateItemCount();
            calculateTotalAllocatedAndAvailableAmount();
            $("#cmbIssueNo").val(0);
            $('#cmbIssueNo').trigger('change');
            $("#cmbIssueNo").focus();
        } else {
            toastr["error"]("Please select valid item !");
            $("#cmbIssueNo").val(0);
            $('#cmbIssueNo').trigger('change');
            $("#cmbIssueNo").focus();
        }

    }

}

function remove() {
    $(".red").click(function () {

        var issueHeaderID = $(this).closest("tr").find('.issueHeaderID').val();
        var issueNo = $(this).closest("tr").find('.issueNo').val();

        var IsAlreadyIncluded = false;

        $("#cmbIssueNo option").each(function () {
            if (issueHeaderID == $(this).val()) {
                IsAlreadyIncluded = true;
                return false;
            }
        });

        if (!IsAlreadyIncluded) {
            var cmbIssueNo = $('#cmbIssueNo');
            cmbIssueNo.append(
                $('<option></option>').val(issueHeaderID).html(issueNo)
            );
            $(this).closest("tr").remove();
        }

        $("input[name=txtTotalAmount], input[name=txtPaidAmount],input[name=txtOutstrandingAmount],input[name=txtPayAmount],input[name=txtRv]").val("");

        CalculateItemCount();
        calculateTotalAllocatedAndAvailableAmount();
    });
}



function CalculateItemCount() {
    var rowCount = $('#receiptTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}


// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});