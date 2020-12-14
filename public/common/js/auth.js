function goToModal(item) {
    if (item === 'login') {
        $('#register_modal').modal('toggle');
        $('#login_modal').modal('show');
    } else if (item === 'register') {
        $('#login_modal').modal('toggle');
        $('#register_modal').modal('show');
    } else if (item === 'forgot') {
        $('#login_modal').modal('toggle');
        $('#forgot_password_modal').modal('show');
    } else if (item === 'forgot-login') {
        $('#forgot_password_modal').modal('toggle');
        $('#login_modal').modal('show');
    }
}
function user_login() {
    $('#login_modal').modal('show');
    $('.main_nav').addClass('hide').removeClass('show');
}
function user_register() {
    $('#register_modal').modal('show');
    $('.main_nav').addClass('hide').removeClass('show');
}
$(function () {
    $('#register_account').on('submit', function (e) {
        e.preventDefault();
        var url = '/register';
        var register_name = $('#register_name').val();
        if (register_name === '') {
            customAlert(auth_messages[0]);
            return;
        }
        var register_email = $('#register_email').val();
        if (register_email === '') {
            customAlert(auth_messages[2]);
            return;
        }
        if (register_email.indexOf('@') < 0) {
            customAlert(auth_messages[3]);
            return;
        }
        var register_password = $('#register_password').val();
        if (register_password === '') {
            customAlert(auth_messages[5]);
            return;
        }
        if (register_password.length < 6) {
            customAlert(auth_messages[7]);
            return;
        }
        var register_confirm_password = $('#register_confirm_password').val();
        if (register_confirm_password === '') {
            customAlert(auth_messages[8]);
            return;
        }
        if (register_password !== register_confirm_password) {
            customAlert(auth_messages[9]);
            return;
        }
        var _token = $('#register_account input[name=_token]').val();
        var data = {
            _token: _token,
            name: register_name,
            email: register_email,
            password: register_password
        };
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function (res) {
                console.log(res);
                if (res.status === 'success') {
                    customAlert(res.message, true);
                    $('#register_modal').modal('toggle');
                } else customAlert(res.message);
            }
        })
    });
    $('#login_account').on('submit', function (e) {
        e.preventDefault();
        var url = '/login';
        var login_email = $('#login_email').val();
        if (login_email === '') {
            customAlert(auth_messages[2]);
            return;
        }
        if (login_email.indexOf('@') < 0) {
            customAlert(auth_messages[3]);
            return;
        }
        var login_password = $('#login_password').val();
        if (login_password === '') {
            customAlert(auth_messages[5]);
            return;
        }
        var _token = $('#login_account input[name=_token]').val();
        var data = {
            _token: _token,
            email: login_email,
            password: login_password
        };
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function (res) {
                console.log(res);
                if (res.status === 'success') {
                    customAlert(res.message, true);
                    $('#login_modal').modal('toggle');
                    location.href = '/user/my-account'
                } else customAlert(res.message);
            }
        })
    });
    $('#forgot_password_account').on('submit', function (e) {
        e.preventDefault();
        let forgot_email = $('#forgot_password_account input[type="email"]').val();
        if (!validateEmail(forgot_email)) {
            customAlert(auth_messages[3]);
            return;
        }
        let _token = $('#forgot_password_account input[name="_token"]').val();
        console.log(forgot_email, _token);
    })
});

