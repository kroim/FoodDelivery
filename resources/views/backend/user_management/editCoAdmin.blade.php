@extends('layouts.back_layout')

@section('back-style')
    <style>
        #change_password_fields {
            display: none;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.editors')}}</h1>
                <div class="actions">
                    <a class="btn btn-outline-secondary" href="{{ url('/user/co-admins') }}"><i class="zwicon-arrow-left"></i> {{ __('global.common.back') }}</a>
                </div>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.common.edit').' '.__('global.side.editors')}} </h4>
                    <input type="hidden" id="edit_user_id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>{{ __('global.common.name') }}</label>
                                <input type="text" class="form-control" id="profile_name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('global.common.email') }}</label>
                                <input type="email" class="form-control" id="profile_email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" id="change_password_checkbox" class="custom-control-input" onchange="changePasswordCheckbox(this)">
                                    <label class="custom-control-label" for="change_password_checkbox">{{ __('global.common.change').' '.__('global.common.password') }}</label>
                                </div>
                            </div>
                            <div id="change_password_fields">
                                <div class="form-group">
                                    <label>{{ __('global.common.password') }}</label>
                                    <input class="form-control" type="password" id="profile_password">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('global.common.confirm_password') }}</label>
                                    <input class="form-control" type="password" id="profile_confirm_password">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group" id="profile_permissions">
                                <label>{{ __('global.side.permissions') }}</label>
                                @forelse($permissions as $index => $permission)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" id="{{ $permission->name }}"
                                               class="custom-control-input" {{ in_array($permission->name, json_decode($user->permissions))?'checked':'' }}>
                                        <label class="custom-control-label" for="{{ $permission->name }}">{{ $permission->description }}</label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="editUser()">{{ __('global.common.save').' '.__('global.common.changes') }}</button>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('back-script')
    <script>
        var auth_messages = [
            "{{ __('global.errors.name_empty') }}",  // 0
            "{{ __('global.errors.email_empty') }}",  // 1
            "{{ __('global.errors.email_valid') }}",  // 2
            "{{ __('global.errors.password_empty') }}",  // 3
            "{{ __('global.errors.password_length') }}",  // 4
            "{{ __('global.errors.confirm_password_empty') }}",  // 5
            "{{ __('global.errors.confirm_password_wrong') }}",  // 6
            "{{ __('global.errors.unchecked_permissions') }}"  // 7
        ];
        function changePasswordCheckbox(ele) {
            if ($(ele).prop('checked')) {
                $('#change_password_fields').css('display', 'inherit')
            } else {
                $('#change_password_fields').css('display', 'none')
            }
        }
        function editUser() {
            var user_id = $('#edit_user_id').val();
            var selected_permissions = [];
            $('#profile_permissions input[type="checkbox"]:checked').each(function() {
                selected_permissions.push($(this).attr('id'));
            });

            var name = $('#profile_name').val();
            if (!name || name === '') {
                customAlert(auth_messages[0]);
                return;
            }
            var email = $('#profile_email').val();
            if (email === '') {
                customAlert(auth_messages[1]);
                return;
            }
            if (!validateEmail(email)) {
                customAlert(auth_messages[2]);
                return;
            }
            var password = $('#profile_password').val();
            var confirm_password = $('#profile_confirm_password').val();
            var changePassword = $('#change_password_checkbox').prop('checked');
            if (changePassword) {
                if (password === '') {
                    customAlert(auth_messages[3]);
                    return;
                }
                if (password.length < 6) {
                    customAlert(auth_messages[4]);
                    return;
                }
                if (password !== confirm_password) {
                    customAlert(auth_messages[6]);
                    return;
                }
            }
            if (selected_permissions.length < 1) {
                customAlert(auth_messages[7]);
                return;
            }
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                user_id: user_id,
                name: name,
                email: email,
                changePassword: changePassword,
                password: password,
                permissions: selected_permissions
            };
            $.ajax({
                url: '/user/co-admins',
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        setTimeout(function () {
                            location.href = '/user/co-admins'
                        }, 3000);
                    } else customAlert(res.message);
                }
            });
        }
    </script>
@endsection
