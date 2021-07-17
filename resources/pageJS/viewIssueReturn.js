
// var manageTable;
var Issue = function () {
    this.intIssueHeaderID = 0;
}
$(document).ready(function () {
 
    FilterItems();

});


function FilterItems() {

    $('#manageTable').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': 'FilterIssueReturnHeaderData',
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
            

        }
    });

}

function viewIssueReturnWiseDetails($IssueReturnHeaderID) {

    if ($IssueReturnHeaderID > 0) {

        $('#IssueItemTable tbody').empty();

        var model = new Issue();
        model.intIssueReturnHeaderID = $IssueReturnHeaderID;

        ajaxCall('Issue/viewIssueReturnWiseDetails', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {
                $("#IssueItemTable tbody").append('<tr>' +
                    '<td><input type="text" class="form-control" name="txtReceiptNo[]" id="txtReceiptNo" style="text-align:center;" value="' + response[index].vcItemName +'" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtChequeNo[]" id="txtChequeNo" style="text-align:right;" value="' + response[index].decUnitPrice + '" disabled></td>' +
                    '<td><input type="text" class="form-control" name="txtRealized[]" id="txtRealized" style="text-align:right;" value="' + response[index].decReturnQty + '" disabled></td>' +
                    '</tr>');
            }

        });
    }

}
