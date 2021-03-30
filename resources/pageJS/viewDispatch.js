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
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });

});


function FilterItems(FromDate, ToDate) {

    var StatusType = $('#cmbStatus').val();
    // StatusType >>    All         = 0
    // StatusType >>    Approved    = 1
    // StatusType >>    Pending     = 2
    // StatusType >>    Rejected    = 3

    $('#manageTable').DataTable({
        'ajax': 'FilterDispatchHeaderData/' + StatusType + '/' + FromDate + '/' + ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[7] == "N/A" && aData[8] == "N/A") { // To Be Received
                $('td', nRow).css('background-color', '#FFC108');
            } else if (aData[5] != "N/A") {  // Cancled
                $('td', nRow).css('background-color', '#dc3545');
            }

            $(nRow.childNodes[0]).css('text-align', 'center');
            $(nRow.childNodes[2]).css('text-align', 'center');
            $(nRow.childNodes[3]).css('text-align', 'center');
            $(nRow.childNodes[4]).css('text-align', 'center');
            $(nRow.childNodes[5]).css('text-align', 'center');
            $(nRow.childNodes[6]).css('text-align', 'center');
            $(nRow.childNodes[7]).css('text-align', 'center');


            $(nRow.childNodes[8]).css('padding', '0');
            $(nRow.childNodes[8]).css('text-align', 'center');

        }
    });

}