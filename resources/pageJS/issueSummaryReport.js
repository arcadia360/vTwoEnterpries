
// var manageTable;
var Report = function () {
    this.ReportID = 0;
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
        maxDate: new Date(),
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function (start, end) {
        selectedFromDate = start.format('YYYY-MM-DD');
        selectedToDate = end.format('YYYY-MM-DD');
        FilterItems(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
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
    ProfitTotal = 0;
    var CustomerID = $('#cmbcustomer').val();

    // $('#manageTable2 tbody').html('');

    $('#manageTable2').DataTable({
        // "bPaginate": false,
        // "bLengthChange": false,
        // "bFilter": true,
        'iDisplayLength': 100,
        // dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ],
        'ajax': base_url + 'Report/FilterIssueSummaryReport/' + FromDate + '/' + ToDate + '/' + CustomerID,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[0]).css('text-align', 'left');
            $(nRow.childNodes[1]).css('text-align', 'right');
            $(nRow.childNodes[2]).css('text-align', 'right');
            $(nRow.childNodes[3]).css('text-align', 'center');
            $(nRow.childNodes[4]).css('text-align', 'center');
            $(nRow.childNodes[5]).css('text-align', 'right');
            $(nRow.childNodes[6]).css('text-align', 'right');
            $(nRow.childNodes[7]).css('text-align', 'right');
            $(nRow.childNodes[8]).css('text-align', 'right');

            var api = this.api(), data;
            // converting to interger to find total
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // var ProfitTotal = api
            //     .column(8)
            //     .data()
            //     .reduce(function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);

            // $(api.column(0).footer()).html('Total');
            // $(api.column(8).footer()).html(parseFloat(Math.round(ProfitTotal * 100) / 100).toFixed(2));

            // var GRNTotal = api
            //     .column(7)
            //     .data()
            //     .reduce(function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);

            // $(api.column(7).footer()).html(parseFloat(Math.round(GRNTotal * 100) / 100).toFixed(2));

            // var ReturnedTotal = api
            //     .column(6)
            //     .data()
            //     .reduce(function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);

            // $(api.column(6).footer()).html(parseFloat(Math.round(ReturnedTotal * 100) / 100).toFixed(2));

            // var IssuedTotal = api
            //     .column(5)
            //     .data()
            //     .reduce(function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);

            // $(api.column(5).footer()).html(parseFloat(Math.round(IssuedTotal * 100) / 100).toFixed(2));
        }


    });

}
