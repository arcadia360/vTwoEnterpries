
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
     
    $('#cmbStatus').on('change', function () {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        }else{
            FilterItems(selectedFromDate, selectedToDate);
        }
    });

});



function FilterItems(FromDate,ToDate){

    var StatusType = $('#cmbStatus').val();
    // StatusType >>    All         = 0
    // StatusType >>    Approved    = 1
    // StatusType >>    Pending     = 2
    // StatusType >>    Rejected    = 3

    $('#manageTable').DataTable({
        'ajax': 'FilterGRNHeaderData/'+StatusType+'/'+FromDate+'/'+ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[10] == "N/A" && aData[12] == "N/A") { // Pending
                $('td', nRow).css('background-color', '#FFC108');
            } else if (aData[13] != "N/A") {  // Rejected
                $('td', nRow).css('background-color', '#dc3545');
            }

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
            $(nRow.childNodes[12]).css('text-align', 'center');

            $(nRow.childNodes[13]).css('padding', '0');
            $(nRow.childNodes[13]).css('text-align', 'center');

        }
    });

}

function removeGRN(GRNHeaderID){
    arcadiaConfirmAlert("You want to be able to remove this !", function (button) {

        $.ajax({
            async: true,
            url: base_url + 'GRN/RemoveGRN',
            type: 'post',
            data: {
                intGRNHeaderID: GRNHeaderID
            },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Deleted !", "GRN/ViewGRN");
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

function viewGRNWiseSettlementDetails($GRNHeaderID)
{
    
    if ($GRNHeaderID > 0) {

        $('#IssueItemTable tbody').empty();
    
        var model = new supplier(); 
        model.intGRNHeaderID = $GRNHeaderID;

        ajaxCall('Supplier/viewGRNWiseSettlementDetailsToModal', model, function (response) {
           debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcSupplierSettlementNo+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].dtPaidDate+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcPayMode+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" value="'+response[index].vcChequeNo+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].dtPDDate+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].decPaidAmount+'" disabled></td>'+
            '</tr>');
            }
      
        });
    }
}