$(document).ready(function() {

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
    }, function(start, end) {
        selectedFromDate = start.format('YYYY-MM-DD');
        selectedToDate = end.format('YYYY-MM-DD');
        FilterItems(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    });

    $('#cmbStatus').on('change', function() {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });





});

function RemoveRequest(RequestHeaderID) {
    arcadiaConfirmAlert("You want to be able to remove this !", function(button) {

        $.ajax({
            async: true,
            url: base_url + 'request/RemoveRequest',
            type: 'post',
            data: {
                intRequestHeaderID: RequestHeaderID
            },
            dataType: 'json',
            success: function(response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Deleted !", "request/ViewRequest");
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

function FilterItems(FromDate, ToDate) {

    var StatusType = $('#cmbStatus').val();
    // StatusType >>    All         = 0
    // StatusType >>    Approved    = 1
    // StatusType >>    Pending     = 2
    // StatusType >>    Rejected    = 3

    $('#manageTable').DataTable({
        'ajax': 'FilterRequestHeaderData/' + StatusType + '/' + FromDate + '/' + ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            // bebugger;
            if (aData[6] != "N/A") {
                if (aData[4] == aData[6]) { // Reject
                    $('td', nRow).css('background-color', '#DC3545');
                }
            }
            if (aData[5] != "N/A") {
                if (aData[5] > 0) { // Pending
                    $('td', nRow).css('background-color', '#FFC108');
                }
            }


            $(nRow.childNodes[0]).css('text-align', 'center');
            $(nRow.childNodes[1]).css('text-align', 'center');
            $(nRow.childNodes[2]).css('text-align', 'right');
            $(nRow.childNodes[3]).css('text-align', 'right');
            $(nRow.childNodes[4]).css('text-align', 'right');
            $(nRow.childNodes[5]).css('text-align', 'center');
            $(nRow.childNodes[6]).css('text-align', 'center');
            $(nRow.childNodes[7]).css('padding', '0');
        }
    });

}