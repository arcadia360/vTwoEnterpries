
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

function FilterItems(FromDate,ToDate) {
    ProfitTotal = 0;
    var IssueHeaderID = $('#cmbIssueNo').val();

    $('#manageTable').DataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
    
            'ajax': base_url + 'Report/FilterIssueSummaryReport/'+FromDate+'/'+ToDate,
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
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            var ProfitTotal = api
            .column( 8 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 0 ).footer() ).html('Profit Total');
            $(api.column(8).footer()).html(parseFloat(Math.round(ProfitTotal * 100) / 100).toFixed(2));
        }
           
        
    });

}
