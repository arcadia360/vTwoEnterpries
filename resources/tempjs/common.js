$(document).ready(function () {

    $('#manageTable').DataTable();

    $('.only-decimal').keypress(function (event) {
        return isNumber(event, this)
    });


});

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (
        (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57) &&
        (charCode != 13)) {
        return false;
    }
    return true;
}

// Preloader
var preLoader = document.getElementById('Preloader');

function Preloader() {
    preLoader.style.display = 'none';
}

// Popup Notification Message
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}


//Initialize Select2 Elements
$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})


//Date picker
$('#dtReceivedDate').datetimepicker({
    format: 'L',
    defaultDate: new Date(),
    maxDate: new Date()
});


$('#dtDispatchDate').datetimepicker({
    format: 'L',
    defaultDate: new Date(),
    maxDate: new Date()
});

$('#dtReceiptDate').datetimepicker({
    format: 'L',
    defaultDate: new Date(),
    maxDate: new Date()
});

$('#dtSettlementDate').datetimepicker({
    format: 'L',
    defaultDate: new Date(),
    maxDate: new Date()
});

$('#dtPDDate').datetimepicker({
    format: 'L',
    defaultDate: new Date()
});



// Alerts
function arcadiaConfirmAlert(message, event, button) {
    debugger;

    $(button).prop('disabled', true);

    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var result = event(button);

            // if (result == true) {
            //     Swal.fire(
            //         'Succeeded !',
            //         'Your request has been successfully processed.',
            //         'success'
            //     ).then((res) => {
            //         if (res.isConfirmed || res.dismiss) {
            //             location.reload();
            //         }
            //     })
            // }else{
            //     toastr["error"]("GRN Saving Error !");
            // }
          
        }else{
            $(button).prop('disabled', false);
        }
    })
}

function arcadiaSuccessMessage(Title = "Succeeded !",ReDirectPage = null){
    Swal.fire(
        Title,
        'Your request has been successfully processed.',
        'success'
    ).then((res) => {
        if (res.isConfirmed || res.dismiss) {
            if (ReDirectPage == null) {
                location.reload();
            }else{                
                window.location.replace(base_url + ReDirectPage);
            }
        }
    })
}

// function arcadiaSuccessAfterIssuePrint(Title = "Succeeded !",intIssueHeaderID = null){
//     Swal.fire(
//         Title,
//         'Your request has been successfully processed.',
//         'success'
//     ).then((res) => {
//         if (res.isConfirmed || res.dismiss) {
//             swal.close();
//         $.ajax({
//             async: false,
//             url: base_url + 'Issue/PrintIssueDiv/' + intIssueHeaderID,
//             success: function (response) {
//             //    var headstr = "<html><head><title>Booking Details</title></head><body>";
//             //    var footstr = "</body>";
//                var newstr = response;
//                var oldstr = document.body.innerHTML;
//             //    document.body.innerHTML = headstr+newstr+footstr;
//               document.body.innerHTML = newstr;
//                window.print();
//             //    document.body.innerHTML = oldstr;
//             //    location.reload();
//                return false;
//             },
//             error: function(XMLHttpRequest, textStatus, errorThrown) { 
//                 alert("Status: " + textStatus); alert("Error: " + errorThrown); 
//             }
//         });

//     }
       
//     })
// }

function arcadiaSuccessAfterDispatchPrint(Title = "Succeeded !",intDispatchHeaderID = null){
    Swal.fire(
        Title,
        'Your request has been successfully processed.',
        'success'
    ).then((res) => {
        if (res.isConfirmed || res.dismiss) {
            swal.close();
        $.ajax({
            async: false,
            url: base_url + 'Dispatch/PrintDispatchDiv/' + intDispatchHeaderID,
            success: function (response) {
            //    var headstr = "<html><head><title>Booking Details</title></head><body>";
            //    var footstr = "</body>";
               var newstr = response;
               var oldstr = document.body.innerHTML;
            //    document.body.innerHTML = headstr+newstr+footstr;
              document.body.innerHTML = newstr;
               window.print();
               document.body.innerHTML = oldstr;
               location.reload();
               return false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            }
        });

    }
       
    })
}


function arcadiaErrorMessage(Msg, ReDirectPage = null){
    Swal.fire(
        'Something went wrong !',
        Msg,
        'error'
    ).then((res) => {
        if (res.isConfirmed || res.dismiss) {
            if (ReDirectPage == null) {
                location.reload();
            }else{
                window.location.replace(base_url + ReDirectPage);
            }
        }
    })
}

// Arcadia Currency Format
function currencyFormat(num){
    return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

// Datetimepicker

function convertToShortDate(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-");
}


// Ajax Common Functions

function ajaxCall(url, parameters, successCallback) {
    debugger;
    $.ajax({
        async: true,
        type: 'POST',
        url: base_url + url,
        // timeout: 0,
        // data: JSON.stringify(parameters),
        data: parameters,
        // contentType: 'application/json;',
        dataType: 'json',
        success: successCallback,
        error: function (request, status, error) {
            debugger;
            console.log(request.responseText);
            arcadiaErrorMessage(error);
        }
    });
}

$(function () {
    // //Initialize Select2 Elements
    // $('.select2').select2()

    // //Initialize Select2 Elements
    // $('.select2bs4').select2({
    //     theme: 'bootstrap4'
    // })

    // //Datemask dd/mm/yyyy
    // $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    // //Datemask2 mm/dd/yyyy
    // $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    // //Money Euro
    // $('[data-mask]').inputmask()

    // //Date range picker
    // $('#reservationdate').datetimepicker({
    //     format: 'L'
    // });
    // //Date range picker
    // $('#reservation').daterangepicker()
    // //Date range picker with time picker
    // $('#reservationtime').daterangepicker({
    //     timePicker: true,
    //     timePickerIncrement: 30,
    //     locale: {
    //         format: 'MM/DD/YYYY hh:mm A'
    //     }
    // })
    // //Date range as a button
    // $('#daterange-btn').daterangepicker(
    //     {
    //         ranges: {
    //             'Today': [moment(), moment()],
    //             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //             'This Month': [moment().startOf('month'), moment().endOf('month')],
    //             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //         },
    //         startDate: moment().subtract(29, 'days'),
    //         endDate: moment()
    //     },
    //     function (start, end) {
    //         $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    //     }
    // )

    // //Timepicker
    // $('#timepicker').datetimepicker({
    //     format: 'LT'
    // })

    // //Bootstrap Duallistbox
    // $('.duallistbox').bootstrapDualListbox()

    // //Colorpicker
    // $('.my-colorpicker1').colorpicker()
    // //color picker with addon
    // $('.my-colorpicker2').colorpicker()

    // $('.my-colorpicker2').on('colorpickerChange', function (event) {
    //     $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    // });

    // $("input[data-bootstrap-switch]").each(function () {
    //     $(this).bootstrapSwitch('state', $(this).prop('checked'));
    // });

})

