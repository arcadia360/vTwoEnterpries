$(document).ready(function () {
    ShowNotification();
});

function ShowNotification(){
    $.ajax({
        url: base_url+'Dashboard/showNotification',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('#btnNotification').empty();
            $('#btnNotification').append(response.htmlElement);
        },
        error: function (request, status, error) { 
            arcadiaErrorMessage(error);
        }
    }); 
}