
// var manageTable;

var Receipt = function () {
    this.intReceiptHeaderID = 0;
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

$('#cmbPayMode').on('change', function () {
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

function viewSettlementDetails($ReceiptHeaderID)
{
    if ($ReceiptHeaderID > 0) {

        $('#IssueItemTable tbody').empty();
    
        var model = new Receipt(); 
        model.intReceiptHeaderID = $ReceiptHeaderID;

        ajaxCall('Receipt/ViewSettlementDetailsToModal', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>'+
                '<td><input type="text" class="form-control" name="txtIssueNo[]" id="txtIssueNo" style="text-align:center;" value="'+response[index].vcIssueNo+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtGrandTotal[]" id="txtGrandTotal" style="text-align:right;" value="'+response[index].decGrandTotal+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].decPaidAmount+'" disabled></td>'+
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
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': 'FilterCustomerReceiptHeaderData/' + PayModeID + '/' + CustomerID + '/' + '/' + FromDate + '/' + ToDate,
    'order': [],
    "bDestroy": true,
    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

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
