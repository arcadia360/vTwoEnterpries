$(document).ready(function () {


    $('#cmbDispatchNo').on('select2:select', function (e) {
        getDispatchedDetails();
    });

    // $('#txtReceiveQty').on('keyup', function (e) {
    //     debugger;
    //     alert("dd");
    //     if ($('#txtBalanceQty').val() == 0) {
    //         $('#txtReceiveQty').val(null);
    //         toastr["error"]("You can't collect this. Because this item already fully received !");
    //     } else if ($('#txtBalanceQty').val() > 0) {
    //         if (parseFloat($('#txtReceiveQty').val()) > parseFloat($('#txtBalanceQty').val())) {
    //             toastr["error"]("You can't exceed stock quantity  !");
    //         }
    //     }

    // });

});

var Dispatch = function () {
    this.intDispatchHeaderID = 0;
}

function getDispatchedDetails(){
    var DispatchHeaderID = $("#cmbDispatchNo").val();
    if (DispatchHeaderID > 0) {

        $('#dispatchItemTable tbody').empty();
     

        var model = new Dispatch(); 
        model.intDispatchHeaderID = DispatchHeaderID;


        $("#itemCount").text("Item Count : 0");

        ajaxCall('Dispatch/getDispatchedItemDetails', model, function (response) {

            $("#itemCount").text("Item Count : " + response.length);

            for (let index = 0; index < response.length; index++) {

                var htmlElement = '';
                var badge = '<span class="badge badge-success" style="padding: 4px 10px; float:right; margin-right:10px;">Fully Received</span>';


              
                if(parseFloat(response[index].ExpectedQty) > parseFloat(response[index].decReceivedQty) || parseFloat(response[index].ExpectedQty) == parseFloat(response[index].decReceivedQty)){
                    htmlElement = '<input type="text" class="form-control only-decimal" name="txtReceiveQty[]" id="txtReceiveQty" style="text-align:center;" placeholder="_ _ _ _ _" onkeyup="validateReceveQty(this)" onkeypress="return isNumber(event,this)" >'
                   
                }

                if(parseFloat(response[index].ExpectedQty) > parseFloat(response[index].decReceivedQty)) 
                {
                    badge = '<span class="badge badge-secondary" style="padding: 4px 10px; float:right; margin-right:10px;">Partially Received</span>';
                }
            

                if(response[index].decReceivedQty == 0){
                    badge = '<span class="badge badge-warning" style="padding: 4px 10px; float:right; margin-right:10px;">TOTAL PENDING</span>';
                }  
              
                debugger;

                $("#dispatchItemTable tbody").append('<tr>'+
                '<td hidden><input type="number" class="form-control" name="txtDispatchDetailID[]" value="'+response[index].intDispatchDetailID+'"></td>'+
                '<td hidden><input type="number" class="form-control" name="txtItemID[]" value="'+response[index].intItemID+'"></td>'+
                '<td><div style="padding: 10px 0px; text-align:center;">'+response[index].OutputFinishItemName+badge+'</div></td>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcMeasureUnit+'" disabled></td>'+
                '<td hidden><input type="number" class="form-control" name="txtCuttingOrderDetailID[]" value="'+response[index].intCuttingOrderDetailID+'"></td>'+
                    '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:center;" value="' + parseInt(response[index].ExpectedQty) +'" disabled></td>'+
                    '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:center;" value="' + parseInt(response[index].decReceivedQty)+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtBalanceQty[]" id="txtBalanceQty" style="text-align:center;" value="'+(response[index].ExpectedQty - response[index].decReceivedQty)+'" readonly></td>'+
                '<td>'+htmlElement+'</td>'+
                '<td hidden><input type="text" class="form-control" name="Rv[]" id="Rv" value=' + response[index].rv +   '></td>'+
            '</tr>');
            }
      
        });
    }


} 


 
$('#btnSubmit').click(function () {

    // if ($('#itemTable tr').length == 2) {
    //     toastr["error"]("Please add the dispatch items !");
    //     $("#cmbItem").focus();
    // } else {
        arcadiaConfirmAlert("You want to be able to collect this items !", function (button) {

            var form = $("#collectDispatchItem");
            debugger;
            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(), 
                dataType: 'json',
                success: function (response) {
                    debugger;
                    if (response.success == true) {
                        arcadiaSuccessMessage("Saved !");
                    } else {
                        toastr["error"](response.messages);
                    }
                }
            });
        }, this);
    // }

});


function validateReceveQty(evnt){
    var BalanceQty = $(evnt).closest("tr").find('#txtBalanceQty').val();

    if (parseFloat(BalanceQty) == 0) {
        $(evnt).closest("tr").find('#txtReceiveQty').val(null);
        toastr["error"]("You can't collect this. Because this item already fully received !");
    } else if (parseFloat(BalanceQty) > 0) {
        if (parseFloat($(evnt).closest("tr").find('#txtReceiveQty').val()) > parseFloat(BalanceQty)) {
            toastr["error"]("You can't exceed balance quantity  !");
            $(evnt).closest("tr").find('#txtReceiveQty').val(null);
        }
    }
}

