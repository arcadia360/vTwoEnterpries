
// var manageTable;
var Report = function () {
    this.ReportID = 0;
}


$(document).ready(function () {

    // $( ".daterange" ).datepicker({
    //     dateFormat: 'dd-mm-yy'
    //  });

    //  $("#daterange").daterangepicker({
    //     locale: {
    //         format: 'DD/MMM/YYYY'
    //     }
    // });

    // $('#daterange').datepicker({ dateFormat: 'dd-mm-yy' }).val();

    // $('#daterange').daterangepicker({
    //     timePicker: true,
    //     timePickerIncrement: 30,
    //     locale: {
    //         format: 'DD/MM/YYYY'
    //     }
    // })


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