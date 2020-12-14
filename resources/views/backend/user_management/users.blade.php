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
                    <div class="actions">
                        <a class="btn btn-success" href="{{ url('/user/create-user') }}">{{ __('global.common.add_user') }}</a>
                    </div>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 20%">{{__('global.common.name')}}</th>
                                <th style="width: 35%">{{__('global.common.email')}}</th>
                                <th style="width: 15%">{{__('global.common.role')}}</th>
                                <th style="width: 10%">{{__('global.common.activation')}}</th>
                                <th style="width: 15%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $index => $user)
                                <tr id="user_{{ $user->id }}">
                                    <td class="user-number">{{ $index + 1 }}</td>
                                    <td class="user-name">{{ $user->name }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    <td class="user-role">@switch($user->role)
                                            @case(1)
                                            Host-Admin
                                            @break
                                            @case(2)
                                            Admin
                                            @break
                                            @case(3)
                                            Co-Admin
                                            @break
                                            @case(4)
                                            Editor
                                            @break
                                            @case(5)
                                            Owner
                                            @break
                                            @case(6)
                                            Driver
                                            @break
                                            @default
                                            User
                                            @break
                                        @endswitch</td>
                                    <td class="user-state">{{ ($user->state==1)?__('global.common.yes'):__('global.common.no') }}</td>
                                    <td class="user-action">
                                        <a class="btn btn-warning btn-sm" href="{{ url('/user/edit-user').'/'.$user->id }}">{{ __('global.common.edit') }}</a>
                                        <button class="btn btn-danger btn-sm"
                                                onclick="removeUser({{ $user->id }})">{{ __('global.common.remove') }}</button>
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
                buttons: [{extend: "excelHtml5", title: "Export Data"}, {extend: "csvHtml5", title: "Export Data"}, {extend: "print", title: "Material Admin"}],
                initComplete: function () {
                    let html = '<i class="zwicon-more-h" data-toggle="dropdown"></i>' +
                        '<div class="dropdown-menu dropdown-menu-right">' +
                        '<a data-table-action="fullscreen" class="dropdown-item">Fullscreen</a>' +
                        '<a data-table-action="excel" class="dropdown-item">Excel (.xlsx)</a>' +
                        '<a data-table-action="csv" class="dropdown-item">CSV (.csv)</a>' +
                        '</div>';
                    $(".dataTables_actions").html(html)
                }
            });
            $("body").on("click", "[data-table-action]", function (e) {
                e.preventDefault();
                let t = $(this).data("table-action");
                if ("excel" === t && $("#users-table_wrapper").find(".buttons-excel").click(), "csv" === t && $("#users-table_wrapper").find(".buttons-csv").click(), "print" === t && $("#users-table_wrapper").find(".buttons-print").click(), "fullscreen" === t) {
                    let a = $(this).closest(".card");
                    a.hasClass("card--fullscreen") ? (a.removeClass("card--fullscreen"), $('body').removeClass("data-table-toggled")) : (a.addClass("card--fullscreen"), $('body').addClass("data-table-toggled"))
                }
            });
        });

        function removeUser(id) {
            $('#modal_remove_user_id').val(id);
            $('#modal_remove_user').modal('show');
        }

        function removeUserBtn() {
            var id = $('#modal_remove_user_id').val();
            $.ajax({
                url: '/user/remove-user',
                method: 'post',
                data: {
                    remove_user: 'Y',
                    _token: "<?php echo csrf_token() ?>",
                    id: id,
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
@endsection
