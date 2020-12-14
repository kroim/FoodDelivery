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
                <h1>{{__('global.side.users')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.side.user_management')}}</h4>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 20%">{{__('global.common.name')}}</th>
                                <th style="width: 35%">{{__('global.common.email')}}</th>
                                <th style="width: 10%">{{__('global.common.role')}}</th>
                                <th style="width: 10%">{{__('global.common.state')}}</th>
                                <th style="width: 20%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $index => $user)
                                <tr id="user_{{ $user->id }}">
                                    <td class="user-number">{{ $index + 1 }}</td>
                                    <td class="user-name">{{ $user->name }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    <td class="user-role">
                                        @switch($user->role)
                                            @case(2)
                                            Admin
                                            @break
                                            @case(3)
                                            Editor
                                            @break
                                            @case(4)
                                            Owner
                                            @break
                                            @case(5)
                                            Driver
                                            @break
                                            @default
                                            User
                                            @break
                                        @endswitch</td>
                                    <td class="user-state">{{ ($user->state==1)?__('global.common.activation'):__('global.common.deactivation') }}</td>
                                    <td class="user-action">
                                        <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})">{{ __('global.common.edit') }}</button>
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
    <!-- edit role modal -->
    <div class="modal fade" id="modal_edit_user" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.side.user') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_user_id">
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_edit_user_name" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.email') }}</label>
                        <input class="form-control" type="text" id="modal_edit_user_email" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.role') }}</label>
                        <select class="form-control page-select" id="modal_edit_user_role">
                            <option value="Admin">Admin</option>
                            <option value="Editor">Editor</option>
                            <option value="Owner">Owner</option>
                            <option value="Driver">Driver</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-link" onclick="editUserBtn()">{{ __('global.common.edit') }}</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
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
        function editUser(id) {
            $('#modal_edit_user_id').val(id);
            $('#modal_edit_user_name').val($('tr#user_' + id + ' .user-name').text());
            $('#modal_edit_user_email').val($('tr#user_' + id + ' .user-email').text());
            var role = $('tr#user_' + id + ' .user-role').text().replace(/\s/g, '');
            console.log(role);
            $('#modal_edit_user_role').val(role);
            $('#modal_edit_user').modal('show');
        }
        function editUserBtn() {
            var user_id = $('#modal_edit_user_id').val();
            var user_role = $('#modal_edit_user_role').val();
            var url = '/user/change-role';
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                user_id: user_id,
                user_role: user_role
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
    </script>
@endsection
