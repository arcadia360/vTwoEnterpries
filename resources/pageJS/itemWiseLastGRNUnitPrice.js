$(document).ready(function () {
    FilterItems();
    $('#cmbItem').on('change', function () {
        FilterItems()
    
    });


});

function FilterItems() {
    var ItemID = $('#cmbItem').val();
    $('#manageTable').DataTable({
        'ajax': base_url + 'GRN/fetchItemWiseGrnPriceData/' + ItemID,
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