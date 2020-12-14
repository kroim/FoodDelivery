@extends('layouts.back_layout')

@section('back-style')
    <style>
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.users')}}</h1>
                <div class="actions">
                    <a class="btn btn-outline-secondary" href="javascript:" onclick="location.href='/user/users';"><i class="zwicon-arrow-left"></i> {{ __('global.common.back') }}</a>
                </div>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.common.edit_user')}}</h4>
                    <input type="hidden" id="edit_user_id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('global.common.name') }}</label>
                                <input type="text" class="form-control" id="profile_name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('global.common.email') }}</label>
                                <input type="email" class="form-control" id="profile_email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('global.common.password') }}</label>
                                <input type="password" class="form-control" id="profile_password">
                            </div>
                            <div class="form-group">
                                <label>{{ __('global.common.confirm_password') }}</label>
                                <input type="password" class="form-control" id="profile_confirm_password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="profile_activation">
                                <label>Activation</label>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="activation" name="activeRadio" class="custom-control-input" {{ ($user->state == 1)?'checked':'' }}>
                                    <label class="custom-control-label" for="activation">Activation</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="deactivation" name="activeRadio" class="custom-control-input" {{ ($user->state == 2)?'checked':'' }}>
                                    <label class="custom-control-label" for="deactivation">Deactivation</label>
                                </div>
                            </div>
                            <div class="form-group" id="profile_roles">
                                <label>Roles</label>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_host_admin" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 1)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_host_admin">Host Admin</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_admin" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 2)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_admin">Admin</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_co_admin" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 3)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_co_admin">Co Admin</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_editor" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 4)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_editor">Editor</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_owner" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 5)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_owner">Owner</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_driver" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 6)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_driver">Driver</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="role_user" name="roleRadio"
                                           class="custom-control-input" {{ ($user->role == 7)?'checked':'' }}>
                                    <label class="custom-control-label" for="role_user">User</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="editUser()">{{ __('global.common.edit_user') }}</button>
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
            "{{ __('global.errors.confirm_password_wrong') }}"  // 6
        ];
        function editUser() {
            var user_id = $('#edit_user_id').val();
            var activationState = $('#profile_activation input:checked').attr('id');
            var roleState = $('#profile_roles input:checked').attr('id');

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
            if (email.indexOf('@') < 0) {
                customAlert(auth_messages[2]);
                return;
            }
            var password = $('#profile_password').val();
            if (password === '') {
                customAlert(auth_messages[3]);
                return;
            }
            if (password.length < 6) {
                customAlert(auth_messages[4]);
                return;
            }
            var confirm_password = $('#profile_confirm_password').val();
            if (confirm_password === '') {
                customAlert(auth_messages[5]);
                return;
            }
            if (password !== confirm_password) {
                customAlert(auth_messages[6]);
                return;
            }
            var data = {
                edit_user: 'Y',
                _token: '<?php echo csrf_token() ?>',
                name: name,
                email: email,
                password: password,
                activation: activationState,
                role: roleState,
            };
            $.ajax({
                url: '/user/edit-user/' + user_id,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                    } else customAlert(res.message);
                }
            });
        }
    </script>
@endsection
