// var manageTable;
var Report = function() {
    this.ReportID = 0;
}

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
        maxDate: new Date(),
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function(start, end) {
        selectedFromDate = start.format('YYYY-MM-DD');
        selectedToDate = end.format('YYYY-MM-DD');
        FilterItems(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    });

    $('#cmbcustomer').on('change', function() {
        if (selectedFromDate == "" && selectedToDate == "") {
            FilterItems(convertToShortDate(monthStartDate), convertToShortDate(date));
        } else {
            FilterItems(selectedFromDate, selectedToDate);
        }
    });

});



function FilterItems(FromDate, ToDate) {
    debugger;
    ProfitTotal = 0;
    var CustomerID = $('#cmbcustomer').val();

    $('#manageTable').DataTable({
        'iDisplayLength': 100,
        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': base_url + 'Report/getCustomerWiseSalesReport/' + CustomerID + '/' + FromDate + '/' + ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[0]).css('text-align', 'left');
            $(nRow.childNodes[1]).css('text-align', 'left');
            $(nRow.childNodes[2]).css('text-align', 'right');
            $(nRow.childNodes[3]).css('text-align', 'right');
            $(nRow.childNodes[4]).css('text-align', 'center');
            $(nRow.childNodes[5]).css('text-align', 'center');
            $(nRow.childNodes[6]).css('text-align', 'right');
            $(nRow.childNodes[7]).css('text-align', 'right');
            $(nRow.childNodes[8]).css('text-align', 'right');
            $(nRow.childNodes[9]).css('text-align', 'right');

            var api = this.api(),
                data;
            // converting to interger to find total
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            var ProfitTotal = api
                .column(9)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            $(api.column(0).footer()).html('Profit Total');
            $(api.column(9).footer()).html(parseFloat(Math.round(ProfitTotal * 100) / 100).toFixed(2));
        }


    });

}


function viewPrintIssueDiv($intIssueHeaderID) {


    $.ajax({
        url: 'PrintIssueDiv/' + $intIssueHeaderID + '/' + 1,
        type: 'post',
        dataType: 'json',
        success: function(response) {
            // alert(response.issueNote);
            document.body.innerHTML = response.issueNote;
            window.print();
            location.reload();
        },
        error: function(data) {}
    });
}