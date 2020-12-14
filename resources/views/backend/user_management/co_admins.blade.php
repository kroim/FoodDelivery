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
                <h1>{{__('global.side.editors')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.side.editors').' '.__('global.common.management')}}</h4>
                    <div class="actions">
                        <a class="btn btn-success" href="{{ url('/user/create-co-admin') }}">{{ __('global.common.add').' '.__('global.side.editor') }}</a>
                    </div>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10%">NO</th>
                                <th style="width: 20%">{{__('global.common.name')}}</th>
                                <th style="width: 40%">{{__('global.common.email')}}</th>
                                <th style="width: 20%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $index => $user)
                                <tr id="user_{{ $user->id }}">
                                    <td class="user-number">{{ $index + 1 }}</td>
                                    <td class="user-name">{{ $user->name }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    <td class="user-action">
                                        <a class="btn btn-warning btn-sm" href="{{ url('/user/edit-co-admin/'.$user->id) }}">{{ __('global.common.edit') }}</a>
                                        <button class="btn btn-danger btn-sm" onclick="removeUser({{ $user->id }})">{{ __('global.common.remove') }}</button>
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
        function removeUser(id) {
            $('#modal_remove_user_id').val(id);
            $('#modal_remove_user').modal('show');
        }

        function removeUserBtn() {
            var id = $('#modal_remove_user_id').val();
            $.ajax({
                url: '/user/co-admins',
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
