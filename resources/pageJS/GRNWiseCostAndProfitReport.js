
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
        maxDate: new Date()
    }, function (start, end) {
            selectedFromDate = start.format('YYYY-MM-DD');
            selectedToDate = end.format('YYYY-MM-DD');
            FilterItems(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    });

    
     

});

function FilterItems(FromDate,ToDate){

    $('#manageTable').DataTable({

    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": false,

        'ajax': base_url + 'Report/FilterGRNWiseCostAndProfitData/'+FromDate+'/'+ToDate,
        'order': [],
        "bDestroy": true,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $(nRow.childNodes[0]).css('text-align', 'center');
            $(nRow.childNodes[1]).css('text-align', 'center');
            $(nRow.childNodes[2]).css('text-align', 'center');
        },

      


    });

}