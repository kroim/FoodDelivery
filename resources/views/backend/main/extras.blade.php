@extends('layouts.back_layout')
@section('back-style')
    <style>
        #modal_remove_extra .modal-body {
            text-align: center;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.extras')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.side.extras').' '.__('global.common.management')}}</h4>
                    <div class="text-right">
                        <button class="btn btn-link"
                                onclick="$('#modal_add_extra').modal('show')">{{ __('global.common.add').' '.__('global.side.extra') }}</button>
                    </div>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 30%">{{__('global.common.name')}}</th>
                                <th style="width: 30%">{{__('global.common.price')}}</th>
                                <th style="width: 20%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($extras as $index => $extra)
                                <tr id="extra_{{ $extra->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="name">{{ $extra->name }}</td>
                                    <td class="price">{{ $extra->price }}</td>
                                    <td class="td-action">
                                        <button type="button" class="btn btn-warning btn-sm"
                                                onclick="editExtra({{ $extra->id }})">{{ __('global.common.edit') }}</button>
                                        <button class="btn btn-danger btn-sm" type="button"
                                                onclick="removeExtra({{ $extra->id }})">{{ __('global.common.remove') }}</button>
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

    <!-- remove extra modal -->
    <div class="modal fade" id="modal_remove_extra" tabindex="-1">
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
                    <input type="hidden" id="modal_remove_extra_id">
                    <button type="button" class="btn btn-link" onclick="removeExtraBtn()">{{ __('global.common.remove') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add extra modal -->
    <div class="modal fade" id="modal_add_extra" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.side.extra') }}</div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_add_extra_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.price') }}</label>
                        <input class="form-control" type="number" id="modal_add_extra_price">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="addExtraBtn()">{{ __('global.common.add') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit extra modal -->
    <div class="modal fade" id="modal_edit_extra" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.side.extra') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_extra_id">
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_edit_extra_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.side.restaurant') }}</label>
                        <input class="form-control" type="number" id="modal_edit_extra_price">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="editExtraBtn()">{{ __('global.common.edit') }}</button>
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
            "{{ __('global.errors.name_empty') }}",  // 0
            "{{ __('global.errors.price_undefined') }}",  // 1
        ];
        function removeExtra(id) {
            $('#modal_remove_extra_id').val(id);
            $('#modal_remove_extra').modal('show');
        }
        function removeExtraBtn() {
            var id = $('#modal_remove_extra_id').val();
            $.ajax({
                url: '/user/extras',
                method: 'post',
                data: {
                    action: 'remove',
                    _token: "<?php echo csrf_token() ?>",
                    extra_id: id,
                },
                success: function (res) {
                    if (res.status === "success") {
                        customAlert(res.message, true);
                        $('tr#extra_' + id).remove();
                        $('#modal_remove_extra').modal('toggle');
                    } else customAlert(res.message);
                }
            })
        }
        function addExtraBtn() {
            var name = $('#modal_add_extra_name').val();
            if (!name) {
                customAlert(messages[0]);
                return;
            }
            var price = $('#modal_add_extra_price').val();
            if (isNaN(parseFloat(price))) {
                customAlert(messages[1]);
                return;
            }
            var url = '/user/extras';
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                name: name,
                price: price
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_extra').modal('toggle');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }
        function editExtra(id) {
            var name = $('#extra_' + id + ' .name').text();
            var price = $('#extra_' + id + ' .price').text();
            $('#modal_edit_extra_id').val(id);
            $('#modal_edit_extra_name').val(name);
            $('#modal_edit_extra_price').val(price);
            $('#modal_edit_extra').modal('show');
        }
        function editExtraBtn() {
            var id = $('#modal_edit_extra_id').val();
            var name = $('#modal_edit_extra_name').val();
            if (!name) {
                customAlert(messages[0]);
                return;
            }
            var price = $('#modal_edit_extra_price').val();
            if (isNaN(parseFloat(price))) {
                customAlert(messages[1]);
                return;
            }
            $.ajax({
                url: '/user/extras',
                method: 'post',
                data: {
                    action: 'edit',
                    _token: '<?php echo csrf_token() ?>',
                    extra_id: id,
                    name: name,
                    price: price
                },
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_edit_extra').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 1500)
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
