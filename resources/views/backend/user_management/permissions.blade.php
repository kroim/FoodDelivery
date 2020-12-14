@extends('layouts.back_layout')

@section('back-style')
    <style>
        #users-table th, #users-table td {
            text-align: center;
        }

        .description {
            color: orange;
            font-size: 12px;
        }

        #modal_remove_user .modal-body {
            text-align: center;
        }

        .custom-checkbox {
            padding-top: 2%;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.permissions')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <div class="form-group description">
                        <h4>Description</h4>
                        @for ($pi = 0; $pi < count($permissions); $pi++)
                            <p>{{ $permissions[$pi]->name.': '.$permissions[$pi]->description }}</p>
                        @endfor
                    </div>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 10%">{{__('global.common.name')}}</th>
                                <th style="width: 25%">{{__('global.common.email')}}</th>
                                @for ($pi = 0; $pi < count($permissions); $pi++)
                                    <th style="width: 5%">{{ $permissions[$pi]->name }}</th>
                                @endfor
                                <th style="width: 15%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($co_admins as $index => $user)
                                <tr id="user_{{ $user->id }}">
                                    <td class="user-number">{{ $index + 1 }}</td>
                                    <td class="user-name">{{ $user->name }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    @for ($pi = 0; $pi < count($permissions); $pi++)
                                        <td>@if($user->permissions && in_array($permissions[$pi]->name, json_decode($user->permissions)))
                                                <i class="zwicon-checkmark" style="font-size: 2rem"></i>@endif</td>
                                    @endfor
                                    <td class="user-action">
                                        <a class="btn btn-warning btn-sm" href="javascript:" onclick="editPermissions({{$user->id}})">{{ __('global.common.edit') }}</a>
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
    <!-- edit co admin permissions modal -->
    <div class="modal fade" id="modal-edit-permissions" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('global.common.edit').' '.__('global.side.permissions') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input id="modal_co_admin_id" type="hidden">
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control modal-co-admin-name" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.email') }}</label>
                        <input class="form-control modal-co-admin-email" readonly>
                    </div>
                    <div class="modal-permissions"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="editPermissionsBtn()">{{ __('global.common.edit').' '.__('global.side.permissions') }}</button>
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

        function editPermissions(id) {
            $('#modal_co_admin_id').val(id);
            $.ajax({
                url: '/user/change-user',
                method: 'post',
                data: {
                    get_user: 'Y',
                    _token: '<?php echo csrf_token() ?>',
                    user_id: id
                },
                success: function (res) {
                    if (res.status === 'success') {
                        console.log(JSON.parse(res.user.permissions));
                        console.log(res.permissions);
                        var permissionHTML = '';
                        for (var i = 0; i < res.permissions.length; i++) {
                            if (res.user.permissions && JSON.parse(res.user.permissions).indexOf(res.permissions[i].name) > -1) {
                                permissionHTML += '<div class="custom-control custom-checkbox">' +
                                    '<input type="checkbox" class="custom-control-input permission-checkbox" id="' + res.permissions[i].name + '" checked>' +
                                    '<label class="custom-control-label" for="' + res.permissions[i].name + '">' + res.permissions[i].description + '</label></div>';
                            } else {
                                permissionHTML += '<div class="custom-control custom-checkbox">' +
                                    '<input type="checkbox" class="custom-control-input permission-checkbox" id="' + res.permissions[i].name + '">' +
                                    '<label class="custom-control-label" for="' + res.permissions[i].name + '">' + res.permissions[i].description + '</label></div>';
                            }
                        }
                        $('#modal_co_admin_id').val(res.user.id);
                        $('.modal-co-admin-name').val(res.user.name);
                        $('.modal-co-admin-email').val(res.user.email);
                        $('.modal-permissions').html(permissionHTML);
                        $('#modal-edit-permissions').modal('show');
                    } else customAlert(res.message);
                }
            });
        }

        function editPermissionsBtn() {
            var user_id = $('#modal_co_admin_id').val();
            var permissions = [];
            var checkboxes = $('input.permission-checkbox');
            for (let i = 0; i < checkboxes.length; i++) {
                if ($(checkboxes[i]).prop('checked')) permissions.push($(checkboxes[i]).attr('id'));
            }
            $.ajax({
                url: '/user/change-user',
                method: 'post',
                data: {
                    'change_permissions': 'Y',
                    _token: '<?php echo csrf_token() ?>',
                    user_id: user_id,
                    permissions: JSON.stringify(permissions)
                },
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        setTimeout(function () {
                            location.reload()
                        }, 1000);
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
