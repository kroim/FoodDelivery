function customAlert(message, state) {
    var isSuccess = state || false;
    var html = "";
    if (isSuccess) {
        html += '<div class="alert alert-success alert-dismissible">\n' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n'
            // + '<h4><i class="icon fa fa-check"></i> Success!</h4>'
            + message + '</div>';
    } else {
        html += '<div class="alert alert-danger alert-dismissible">\n' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
            // + '<h4><i class="icon fa fa-ban"></i> Failed!</h4>'
            + message + '</div>';
    }
    $('.custom-alert').html(html);
    setTimeout(function () {
        $('.custom-alert').html("");
    }, 3000);
}

function customValid(id, state) {
    var isValid = state || false;
    if (isValid) {
        $('#' + id).addClass('is-valid');
    } else {
        $('#' + id).addClass('is-invalid')
    }
    setTimeout(function () {
        $('#' + id).removeClass('is-invalid').removeClass('is-valid')
    }, 3000);
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}