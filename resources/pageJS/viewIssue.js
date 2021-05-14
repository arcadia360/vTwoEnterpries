
// var manageTable;


$(document).ready(function () {


    // $('#manageTable').DataTable( {
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ]
    // } );




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
        dom: 'Bfrtip',
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

function viewReceiptWiseSettlementDetails($ReceiptHeaderID) {

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