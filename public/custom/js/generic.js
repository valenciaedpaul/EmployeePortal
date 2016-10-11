/**
 * Created by evalencia on 9/14/2016.
 */

$(document).ready(function(){
    //hide_preloader();

    //initialize materialize select
    $('select').material_select();

    // Initialize collapse button
    $(".button-collapse").sideNav();

    //Initialize datepickers
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15 // Creates a dropdown of 15 years to control year
    });
});

$(document).bind('ajaxStart', function(){
    show_preloader();
}).bind('ajaxComplete', function(){
    setTimeout(function(){
        hide_preloader();
    }, 500);
}).bind('ajaxSend', function(){
    $('#progress_bar').css('width', '0%');
});

function hide_preloader(){
    $('#progress_bar').addClass('transparent');
    $('#progress_bar').removeClass('white');
    $('#progress_bar').css('width', '0%');
}

function show_preloader(){
    $('#progress_bar').removeClass('transparent');
    $('#progress_bar').addClass('white');
}

function xhrProgressListener(){
    var xhr = new window.XMLHttpRequest();

    //Upload progress
    xhr.upload.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            //Do something with upload progress
            $('#progress_bar').css('width', percentComplete + '%');
        }
    }, false);

    //Download progress
    xhr.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            //Do something with download progress
            $('#progress_bar').css('width', percentComplete + '%');
        }
    }, false);
    return xhr;
}

function showErrorToast(message)
{
    var error_msg = '<i class="fa fa-exclamation" aria-hidden="true" style="margin-right: 5px;"></i>';
    error_msg += message != '' ? message : 'Error';
    Materialize.toast(error_msg, 4000, 'rounded red darken-1 white-text')
}

function showSuccessToast(message)
{
    var error_msg = '<i class="fa fa-check" aria-hidden="true" style="margin-right: 5px;"></i>';
    error_msg += message != '' ? message : 'Success';
    Materialize.toast(error_msg, 4000, 'rounded green darken-1 white-text')
}

function showWarningToast(message)
{
    var error_msg = '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="margin-right: 5px;"></i>';
    error_msg += message != '' ? message : 'Warning';
    Materialize.toast(error_msg, 4000, 'rounded yellow darken-2 white-text')
}

function showInfoToast(message)
{
    var error_msg = '<i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 5px;"></i>';
    error_msg += message != '' ? message : 'Info';
    Materialize.toast(error_msg, 4000, 'rounded blue darken-1 white-text')
}