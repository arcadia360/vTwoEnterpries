// var manageTable;

var Receipt = function() {
    this.intReceiptHeaderID = 0;
}


$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();

    var date = new Date();
    var monthStartDate = new Date(date.getFullYear(), date.getMonth(), 1);

    var selectedFromDate = "";
    var selectedToDate = "";

    FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));

    $('input[name="daterange"]').daterangepicker({
        opens: 'center',
        startDate: new Date(date.getFullYear(), date.getMonth(), 1),
        endDate: date,
        maxDate: new Date(),
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function(start, end) {
        selectedFromDate = start.format('YYYY-MM-DD');
        selectedToDate = end.format('YYYY-MM-DD');
        FilterItems(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    });

    $('#cmbPayMode').on('change', function() {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });

    $('#cmbcustomer').on('change', function() {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });


});

function customerChequeRealized(CustomerChequeID, ReceiptHeaderID) {
    arcadiaConfirmAlert("You want to be able to realized Cheque !", function(button) {
        // var model = new Receipt();
        // model.intCustomerChequeID = $CustomerChequeID;
        // model.intReceiptHeaderID = $ReceiptHeaderID;
        $.ajax({
            async: true,
            url: base_url + 'Receipt/CustomerChequeRealized',
            type: 'post',
            data: {
                intCustomerChequeID: CustomerChequeID,
                intReceiptHeaderID: ReceiptHeaderID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Realized Cheque !", "Receipt/ViewReceipt");
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

function customerCancelReceipt(ReceiptHeaderID) {
    arcadiaConfirmAlert("You want to be able to Cancel Receipt !", function(button) {
        // var model = new Receipt();
        // model.intCustomerChequeID = $CustomerChequeID;
        // model.intReceiptHeaderID = $ReceiptHeaderID;
        $.ajax({
            async: true,
            url: base_url + 'Receipt/CustomerChequeReceipt',
            type: 'post',
            data: {
                intReceiptHeaderID: ReceiptHeaderID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Cancelled Receipt !", "Receipt/ViewReceipt");
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

function customerReturnCheque(CustomerChequeID, ReceiptHeaderID) {
    arcadiaConfirmAlert("You want to be able to Return Cheque !", function(button) {
        // var model = new Receipt();
        // model.intCustomerChequeID = $CustomerChequeID;
        // model.intReceiptHeaderID = $ReceiptHeaderID;
        $.ajax({
            async: true,
            url: base_url + 'Receipt/CustomerReturnCheque',
            type: 'post',
            data: {
                intCustomerChequeID: CustomerChequeID,
                intReceiptHeaderID: ReceiptHeaderID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Returned Cheque !", "Receipt/ViewReceipt");
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

function customerCancelCheque(CustomerChequeID, ReceiptHeaderID) {
    arcadiaConfirmAlert("You want to be able to Cancel Cheque !", function(button) {
        //    var model = new Receipt();
        // model.intCustomerChequeID = $CustomerChequeID;
        // model.intReceiptHeaderID = $ReceiptHeaderID;

        $.ajax({
            async: true,
            url: base_url + 'Receipt/CustomerCancelCheque',
            type: 'post',
            data: {
                intCustomerChequeID: CustomerChequeID,
                intReceiptHeaderID: ReceiptHeaderID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Cancelled Cheque !", "Receipt/ViewReceipt");
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

function customerCancelRealized(CustomerChequeID, ReceiptHeaderID) {
    arcadiaConfirmAlert("You want to be able to Cancel Realized !", function(button) {
        //    var model = new Receipt();
        // model.intCustomerChequeID = $CustomerChequeID;
        // model.intReceiptHeaderID = $ReceiptHeaderID;

        $.ajax({
            async: true,
            url: base_url + 'Receipt/customerCancelRealized',
            type: 'post',
            data: {
                intCustomerChequeID: CustomerChequeID,
                intReceiptHeaderID: ReceiptHeaderID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Cancelled Realized !", "Receipt/ViewReceipt");
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

function viewSettlementDetails($ReceiptHeaderID) {
    if ($ReceiptHeaderID > 0) {

        $('#IssueItemTable tbody').empty();

        var model = new Receipt();
        model.intReceiptHeaderID = $ReceiptHeaderID;

        ajaxCall('Receipt/ViewSettlementDetailsToModal', model, function(response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>' +
                    '<td><input type="text" class="form-control" name="txtIssueNo[]" id="txtIssueNo" style="text-align:center;" value="' + response[index].vcIssueNo + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtBalance[]" id="txtBalance" style="text-align:right;" value="' + response[index].TotalAmountDue + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtGrandTotal[]" id="txtGrandTotal" style="text-align:right;" value="' + response[index].decGrandTotal + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="' + response[index].decPaidAmount + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtRetunrAmount[]" id="txtRetunrAmount" style="text-align:right;" value="' + response[index].decReturnAmount + '" disabled></td>' +
                    '</tr>');
            }

        });
    }

}

function viewCancelledReceiptDetailsHis($ReceiptHeaderID) {
    if ($ReceiptHeaderID > 0) {

        $('#IssueItemTable tbody').empty();

        var model = new Receipt();
        model.intReceiptHeaderID = $ReceiptHeaderID;

        ajaxCall('Receipt/viewCancelledReceiptDetailsHis', model, function(response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>' +
                    '<td><input type="text" class="form-control" name="txtIssueNo[]" id="txtIssueNo" style="text-align:center;" value="' + response[index].vcIssueNo + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtBalance[]" id="txtBalance" style="text-align:right;" value="' + response[index].TotalAmountDue + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtGrandTotal[]" id="txtGrandTotal" style="text-align:right;" value="' + response[index].decGrandTotal + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="' + response[index].decPaidAmount + '" disabled></td>' +
                    '</tr>');
            }

        });
    }
}


function FilterItems(FromDate, ToDate) {

    var PayModeID = $('#cmbPayMode').val();
    var CustomerID = $('#cmbcustomer').val();
    // PayModeID >>    All         = 0
    // PayModeID >>    Cash        = 1
    // PayModeID >>    Cheque      = 2


    $('#manageTable').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': 'FilterCustomerReceiptHeaderData/' + PayModeID + '/' + CustomerID + '/' + '/' + FromDate + '/' + ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            // if (aData[12] == 1) { // Cancel Receipt
            //     $('td', nRow).css('background-color', '#dc3545');
            // } else if (aData[12] == 2) { // Return Cheque
            //     $('td', nRow).css('background-color', '#6C757D');
            // }
            // else if (aData[13] == 1) { // Realized
            //     $('td', nRow).css('background-color', '#17A2B8');
            // }else if (aData[13] == 0) { // Pending Realizing
            //     $('td', nRow).css('background-color', '#FFC108');
            // }      

            $(nRow.childNodes[0]).css('text-align', 'center');
            $(nRow.childNodes[1]).css('text-align', 'center');
            $(nRow.childNodes[2]).css('text-align', 'center');
            $(nRow.childNodes[3]).css('text-align', 'center');
            $(nRow.childNodes[4]).css('text-align', 'center');
            $(nRow.childNodes[5]).css('text-align', 'center');
            $(nRow.childNodes[6]).css('text-align', 'center');
            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');
            $(nRow.childNodes[9]).css('text-align', 'center');


        }
    });

}