
var ProfitTotal = 0;
$(document).ready(function () {
    getIssueWiseCostAndProfitData();
});

$('#cmbIssueNo').on('select2:close', function (e) {
    getIssueWiseCostAndProfitData();
});

// $('#manageTable th.tableHeader').each(function(){
//     $(this).css('background-color','#263238');
//     $(this).css('color','#FFFFFF'); 
// });


// $('#manageTable th.tableFooter').each(function(){
//         $(this).css('background-color','#6B6F70');
//         $(this).css('color','#FFFFFF'); 
    
// });



function getIssueWiseCostAndProfitData() {
    ProfitTotal = 0;
    var IssueHeaderID = $('#cmbIssueNo').val();

    $('#manageTable').DataTable({
        'iDisplayLength': 100,
        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<ip>',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax':  base_url + 'Report/getIssueWiseCostAndProfitData/' + IssueHeaderID,
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
      
            var api = this.api(), data;
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            var ProfitTotal = api
            .column( 7 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 0 ).footer() ).html('Profit Total');
            $( api.column( 7 ).footer() ).html(ProfitTotal);
        }
           
        
    });

}
