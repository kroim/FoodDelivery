@extends('layouts.back_layout')
@section('back-style')
    <style>
        #modal_remove_category .modal-body {
            text-align: center;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.categories')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.side.categories') }}</h4>
                    <div class="actions">
                        <button class="btn btn-link" onclick="$('#modal_add_category').modal('show')">{{ __('global.common.add').' '.__('global.side.category') }}</button>
                    </div>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('global.common.name') }}</th>
                                <th>{{ __('global.common.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $index => $category)
                                <tr id="category_{{ $category->id }}">
                                    <td class="user-number">{{ $index + 1 }}</td>
                                    <td class="user-name">{{ $category->name }}</td>
                                    <td class="user-action">
                                        <button class="btn btn-warning btn-sm" href="javascript:" onclick="editCategory('{{ $category->id }}')">{{ __('global.common.edit') }}</button>
                                        <button class="btn btn-danger btn-sm" onclick="removeCategory('{{ $category->id }}')">{{ __('global.common.remove') }}</button>
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
    <div class="modal fade" id="modal_add_category" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.side.category') }}</div>
                <div class="modal-body">
                    <input class="form-control" type="text" id="modal_add_category_name" placeholder="{{ __('global.side.category').' '.__('global.common.name') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="addCategoryBtn()">{{ __('global.common.add').' '.__('global.side.category') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit driver modal -->
    <div class="modal fade" id="modal_edit_category" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.side.category') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_category_id">
                    <input class="form-control" type="text" id="modal_edit_category_name" placeholder="{{ __('global.side.category').' '.__('global.common.name') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="editCategoryBtn()">{{ __('global.common.edit').' '.__('global.side.category') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- remove user modal -->
    <div class="modal fade" id="modal_remove_category" tabindex="-1">
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
                    <input type="hidden" id="modal_remove_category_id">
                    <button type="button" class="btn btn-link" onclick="removeCategoryBtn()">{{ __('global.common.remove') }}</button>
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
        var messages = [
            "{{ __('global.errors.category_empty') }}"
        ];
        function removeCategory(id) {
            $('#modal_remove_category_id').val(id);
            $('#modal_remove_category').modal('show');
        }
        function removeCategoryBtn() {
            var category_id = $('#modal_remove_category_id').val();
            var url = '/user/categories';
            var data = {
                action: 'remove',
                _token: '<?php echo csrf_token() ?>',
                category_id: category_id
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_remove_category').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    } else customAlert(res.message);
                }
            })
        }
        function addCategoryBtn() {
            var category_name = $('#modal_add_category_name').val();
            if (category_name === '') {
                customAlert(messages[0]);
                return;
            }
            var url = '/user/categories';
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                category_name: category_name
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_category').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    } else customAlert(res.message);
                }
            })
        }
        function editCategory(id) {
            $('#modal_edit_category_id').val(id);
            $('#modal_edit_category_name').val($('#category_' + id + ' .user-name').text());
            $('#modal_edit_category').modal('show');
        }
        function editCategoryBtn() {
            var category_id = $('#modal_edit_category_id').val();
            var category_name = $('#modal_edit_category_name').val();
            if (category_name === '') {
                customAlert(messages[0]);
                return;
            }
            var url = '/user/categories';
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                category_id: category_id,
                category_name: category_name
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_edit_category').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
