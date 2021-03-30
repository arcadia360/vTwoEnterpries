
// var manageTable;

var supplier = function () {
    this.intSupplierSettlementHeaderID = 0;
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

$('#cmbsupplier').on('change', function () {
    if (selectedFromDate == "" && selectedToDate == "") {
        FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
    } else {
        FilterItems(selectedFromDate, selectedToDate);
    }
});

});

function viewSettlementDetails($SupplierSettlementHeaderID)
{
    if ($SupplierSettlementHeaderID > 0) {

        $('#IssueItemTable tbody').empty();
    
        var model = new supplier(); 
        model.intSupplierSettlementHeaderID = $SupplierSettlementHeaderID;

        ajaxCall('Supplier/ViewSettlementDetailsToModal', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcGRNNo+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" value="'+response[index].TotalAmount+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].PaidAmount+'" disabled></td>'+
            '</tr>');
            }
      
        });
    }
    
}


function FilterItems(FromDate, ToDate) {

var PayModeID = $('#cmbPayMode').val();
var SupplierID = $('#cmbsupplier').val();
// PayModeID >>    All         = 0
// PayModeID >>    Cash        = 1
// PayModeID >>    Cheque      = 2


$('#manageTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': 'FilterSupplierCreditSettlementHeaderData/' + PayModeID + '/' + SupplierID + '/' + '/' + FromDate + '/' + ToDate,
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
