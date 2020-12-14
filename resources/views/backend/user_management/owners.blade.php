@extends('layouts.back_layout')
@section('back-style')
    <style>
        #users-table th, #users-table td {
            text-align: center;
        }

        #modal_remove_user .modal-body {
            text-align: center;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.owners')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.side.owners').' '.__('global.common.management')}}</h4>
                    @if(Auth::user()->role < 3 || ($user_permission && in_array('owner_add', $user_permission)))
                        <div class="actions">
                            <button class="btn btn-success" onclick="$('#modal_add_user').modal('show')">{{ __('global.common.add').' '.__('global.side.owner') }}</button>
                        </div>
                    @endif
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10%">NO</th>
                                <th style="width: 20%">{{__('global.common.name')}}</th>
                                <th style="width: 35%">{{__('global.common.email')}}</th>
                                <th style="width: 15%">{{__('global.common.activation')}}</th>
                                <th style="width: 20%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $index => $user)
                                <tr id="user_{{ $user->id }}">
                                    <td class="user-number">{{ $index + 1 }}</td>
                                    <td class="user-name">{{ $user->name }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    <td class="user-state">{{ ($user->state == 1)?'Activation':'Deactivation' }}</td>
                                    <td class="user-action">
                                        @if(Auth::user()->role < 3 || ($user_permission && (in_array('owner_edit', $user_permission) || in_array('owner_activity', $user_permission))))
                                            <button class="btn btn-warning btn-sm" href="javascript:" onclick="editUser({{ $user->id }})">{{ __('global.common.edit') }}</button>
                                        @endif
                                        @if(Auth::user()->role < 3 || ($user_permission && in_array('owner_remove', $user_permission)))
                                            <button class="btn btn-danger btn-sm" onclick="removeUser({{ $user->id }})">{{ __('global.common.remove') }}</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- add driver modal -->
    <div class="modal fade" id="modal_add_user" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.side.user') }}</div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_add_user_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.email') }}</label>
                        <input class="form-control" type="text" id="modal_add_user_email">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.password') }}</label>
                        <input class="form-control" type="password" id="modal_add_user_password">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.confirm_password') }}</label>
                        <input class="form-control" type="password" id="modal_add_user_confirm_password">
                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-link" onclick="addUserBtn()">{{ __('global.common.add') }}</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- edit driver modal -->
    <div class="modal fade" id="modal_edit_user" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.side.user') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_user_id">
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_edit_user_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.email') }}</label>
                        <input class="form-control" type="text" id="modal_edit_user_email">
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
                            <input class="form-control" type="password" id="modal_edit_user_password">
                        </div>
                        <div class="form-group">
                            <label>{{ __('global.common.confirm_password') }}</label>
                            <input class="form-control" type="password" id="modal_edit_user_confirm_password">
                        </div>
                    </div>
                    <div class="form-group" id="profile_activation">
                        <label>Activation</label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="activation" name="activeRadio" class="custom-control-input" checked>
                            <label class="custom-control-label" for="activation">Activation</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="deactivation" name="activeRadio" class="custom-control-input">
                            <label class="custom-control-label" for="deactivation">Deactivation</label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-link" onclick="editUserBtn()">{{ __('global.common.edit') }}</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- remove user modal -->
    <div class="modal fade" id="modal_remove_user" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <i class="zwicon-info-circle" style="font-size: 7rem"></i>
                    </div>
                    <div class="form-group">
                        <h3>{{ __('global.verify.remove') }}</h3>
                    </div>
                    <input type="hidden" id="modal_remove_user_id">
                    <button type="button" class="btn btn-link" onclick="removeUserBtn()">{{ __('global.common.remove') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('back-script')
    <script>
        $(function () {
            $("#users-table").DataTable({
                aaSorting: [],
                autoWidth: !1,
                responsive: !0,
                lengthMenu: [[15, 40, 100, -1], ["15 Rows", "40 Rows", "100 Rows", "Everything"]],
                language: {searchPlaceholder: "Search for records..."},
                sDom: '<"dataTables__top"flB<"dataTables_actions">>rt<"dataTables__bottom"ip><"clear">',
            });
        });
        var messages = [
            "{{ __('global.errors.name_empty') }}",  // 0
            "{{ __('global.errors.email_empty') }}",  // 1
            "{{ __('global.errors.email_valid') }}",  // 2
            "{{ __('global.errors.password_empty') }}",  // 3
            "{{ __('global.errors.password_length') }}",  // 4
            "{{ __('global.errors.confirm_password_wrong') }}"  // 5
        ];

        function changePasswordCheckbox(ele) {
            if ($(ele).prop('checked')) {
                $('#change_password_fields').css('display', 'inherit')
            } else {
                $('#change_password_fields').css('display', 'none')
            }
        }

        function editUser(id) {
            $('#modal_edit_user_id').val(id);
            $('#modal_edit_user_name').val($('tr#user_' + id + ' .user-name').text());
            $('#modal_edit_user_email').val($('tr#user_' + id + ' .user-email').text());
            $('#change_password_checkbox').prop('checked', false);
            $('#change_password_fields').css('display', 'none');
            var userState = $('tr#user_' + id + ' .user-state').text();
            if (userState === 'Activation') $('#profile_activation #activation').prop('checked', true);
            else $('#profile_activation #deactivation').prop('checked', true);
            $('#modal_edit_user').modal('show');
        }

        function addUserBtn() {
            var name = $('#modal_add_user_name').val();
            if (name === '') {
                customAlert(messages[0]);
                return;
            }
            var email = $('#modal_add_user_email').val();
            if (email === '') {
                customAlert(messages[1]);
                return;
            }
            if (!validateEmail(email)) {
                customAlert(messages[2]);
                return;
            }
            var password = $('#modal_add_user_password').val();
            if (password === '') {
                customAlert(messages[3]);
                return;
            }
            if (password.length < 6) {
                customAlert(messages[4]);
                return;
            }
            var confirm_password = $('#modal_add_user_confirm_password').val();
            if (password !== confirm_password) {
                customAlert(messages[5]);
                return;
            }
            var url = '/user/owners';
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                name: name,
                email: email,
                password: password
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_user').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }

        function editUserBtn() {
            var id = $('#modal_edit_user_id').val();
            var name = $('#modal_edit_user_name').val();
            if (name === '') {
                customAlert(messages[0]);
                return;
            }
            var email = $('#modal_edit_user_email').val();
            if (email === '') {
                customAlert(messages[1]);
                return;
            }
            if (!validateEmail(email)) {
                customAlert(messages[2]);
                return;
            }
            var password = $('#modal_edit_user_password').val();
            var confirm_password = $('#modal_edit_user_confirm_password').val();
            var changePassword = $('#change_password_checkbox').prop('checked');
            if (changePassword) {
                if (password === '') {
                    customAlert(messages[3]);
                    return;
                }
                if (password.length < 6) {
                    customAlert(messages[4]);
                    return;
                }
                if (password !== confirm_password) {
                    customAlert(messages[5]);
                    return;
                }
            }
            var activationState = $('#profile_activation input:checked').attr('id');
            var url = '/user/owners';
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                user_id: id,
                name: name,
                email: email,
                changePassword: changePassword,
                password: password,
                activation: activationState,
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_edit_user').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }

        function removeUser(id) {
            $('#modal_remove_user_id').val(id);
            $('#modal_remove_user').modal('show');
        }

        function removeUserBtn() {
            var id = $('#modal_remove_user_id').val();
            $.ajax({
                url: '/user/admins',
                method: 'post',
                data: {
                    action: 'remove',
                    _token: "<?php echo csrf_token() ?>",
                    user_id: id,
                },
                success: function (res) {
                    if (res.status === "success") {
                        customAlert(res.message, true);
                        $('tr#user_' + id).remove();
                        $('#modal_remove_user').modal('toggle');
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
