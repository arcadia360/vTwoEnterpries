
// var manageTable;
var Issue = function () {
    this.intIssueHeaderID = 0;
}

$(document).ready(function () {

    var date = new Date();
    var monthStartDate = new Date(date.getFullYear(), date.getMonth(), 1);

    var selectedFromDate = "";
    var selectedToDate = "";



    FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));

    $('input[name="daterange"]').daterangepicker({
        opens: 'center',
        startDate: new Date(date.getFullYear(), date.getMonth(), 1),
        endDate: date,
        maxDate: new Date()
    }, function (start, end) {
        selectedFromDate = start.format('YYYY-MM-DD');
        selectedToDate = end.format('YYYY-MM-DD');
        FilterItems(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    });

    $('#cmbpayment').on('change', function () {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });

    $('#cmbcustomer').on('change', function () {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });

});



function FilterItems(FromDate, ToDate) {

    var PaymentType = $('#cmbpayment').val();
    var CustomerID = $('#cmbcustomer').val();
    // PaymentType >>    All         = 0
    // PaymentType >>    Cash        = 1
    // PaymentType >>    Credit      = 2


    $('#manageTable').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': 'FilterIssueHeaderData/' + PaymentType + '/' + CustomerID + '/' + '/' + FromDate + '/' + ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[0]).css('text-align', 'center');
            $(nRow.childNodes[2]).css('text-align', 'center');
            $(nRow.childNodes[3]).css('text-align', 'center');
            $(nRow.childNodes[4]).css('text-align', 'center');
            $(nRow.childNodes[5]).css('text-align', 'center');
            $(nRow.childNodes[6]).css('text-align', 'center');
            $(nRow.childNodes[7]).css('text-align', 'center');
            $(nRow.childNodes[8]).css('text-align', 'center');
            $(nRow.childNodes[9]).css('text-align', 'center');
            $(nRow.childNodes[10]).css('text-align', 'center');
            $(nRow.childNodes[11]).css('text-align', 'center');


        }
    });

}

function viewIssuetWiseSettlementDetails($IssueHeaderID) {

    if ($IssueHeaderID > 0) {

        $('#IssueItemTable tbody').empty();

        var model = new Issue();
        model.intIssueHeaderID = $IssueHeaderID;

        ajaxCall('Issue/viewIssuetWiseSettlementDetails', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>' +
                    '<td><input type="text" class="form-control" name="txtReceiptNo[]" id="txtReceiptNo" style="text-align:center;" value="' + response[index].vcReceiptNo +'" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtChequeNo[]" id="txtChequeNo" style="text-align:right;" value="' + response[index].vcChequeNo + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtRealized[]" id="txtRealized" style="text-align:right;" value="' + response[index].IsRealized + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtPaidAmount[]" id="txtPaidAmount" style="text-align:right;" value="' + response[index].decPaidAmount + '" disabled></td>' +
                    '</tr>');
            }

        });
    }

}

function viewPrintIssueDiv($intIssueHeaderID) {
  

    $.ajax({
        url: 'PrintIssueDiv/' + $intIssueHeaderID + '/' + 1,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            // alert(response.issueNote);
            document.body.innerHTML = response.issueNote;
                        window.print();
                        location.reload();
        },
        error: function (data) { }
    });
}

// function removeGRN(GRNHeaderID){
//     arcadiaConfirmAlert("You want to be able to remove this !", function (button) {

//         $.ajax({
//             async: true,
//             url: base_url + 'GRN/RemoveGRN',
//             type: 'post',
//             data: {
//                 intGRNHeaderID: GRNHeaderID
//             },
//             dataType: 'json',
//             success: function (response) {
//                 if (response.success == true) {
//                     arcadiaSuccessMessage("Deleted !", "GRN/ViewGRN");
//                 } else {
//                     toastr["error"](response.messages);
//                 }
//             },
//             error: function (request, status, error) {
//                 arcadiaErrorMessage(error);
//             }
//         });
//     }, this);
// }