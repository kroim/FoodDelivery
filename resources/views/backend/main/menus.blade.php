@extends('layouts.back_layout')
@section('back-style')
    <style>
        .image img {
            height: 30px;
            max-width: 100px;
        }
        #modal_remove_menu .modal-body {
            text-align: center;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.menus').' '.__('global.common.management')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $restaurant->name.' / '.__('global.side.menus')}}</h4>
                    <div class="text-right">
                        <button class="btn btn-link"
                                onclick="$('#modal_add_menu').modal('show')">{{ __('global.common.add').' '.__('global.side.menu') }}</button>
                    </div>
                    <input type="hidden" id="restaurant_id" value="{{ $restaurant->id }}">
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 15%">{{ __('global.common.image') }}</th>
                                <th style="width: 20%">{{__('global.common.name')}}</th>
                                <th style="width: 35%">{{__('global.common.description')}}</th>
                                <th style="width: 25%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($menus as $index => $menu)
                                <tr id="menu_{{ $menu->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="image"><img src="{{ $menu->image }}"></td>
                                    <td class="name">{{ $menu->name }}</td>
                                    <td class="description">{{ $menu->description }}</td>
                                    <td class="td-action">
                                        <a class="btn btn-outline-warning btn-sm"
                                           href="{{ url('/user/menus/'.$restaurant->id.'/'.$menu->id) }}">{{ __('global.common.go_to_food') }}</a>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="editMenu({{ $menu->id }})">{{ __('global.common.edit') }}</button>
                                        <button class="btn btn-outline-danger btn-sm" type="button"
                                                onclick="removeMenu({{ $menu->id }})">{{ __('global.common.remove') }}</button>
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

    <!-- remove menu modal -->
    <div class="modal fade" id="modal_remove_menu" tabindex="-1">
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
                    <input type="hidden" id="modal_remove_menu_id">
                    <button type="button" class="btn btn-link" onclick="removeMenuBtn()">{{ __('global.common.remove') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add restaurant modal -->
    <div class="modal fade" id="modal_add_menu" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.side.menu') }}</div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="modal_add_img" style="width: 100%; height: 100px" alt="">
                    </div>
                    <div class="text-center">
                        <input type="file" id="modal_add_image" accept="image/*" onchange="previewAddImg(event)" style="display: none">
                        <button class="btn btn-outline-warning" style="margin-top: 1%"
                                onclick="$('#modal_add_image').click();">{{ __('global.common.upload').' '.__('global.common.file') }}</button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_add_menu_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.description') }}</label>
                        <textarea class="form-control" id="modal_add_menu_description" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="addMenuBtn()">{{ __('global.common.add') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add restaurant modal -->
    <div class="modal fade" id="modal_edit_menu" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.side.menu') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_menu_id">
                    <div class="text-center">
                        <img id="modal_edit_img" style="width: 100%; height: 100px" alt="">
                    </div>
                    <div class="text-center">
                        <input type="file" id="modal_edit_image" accept="image/*" onchange="previewEditImg(event)" style="display: none">
                        <button class="btn btn-outline-warning" style="margin-top: 1%"
                                onclick="$('#modal_edit_image').click();">{{ __('global.common.upload').' '.__('global.common.file') }}</button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_edit_menu_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.description') }}</label>
                        <textarea class="form-control" id="modal_edit_menu_description" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="editMenuBtn()">{{ __('global.common.edit') }}</button>
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
        function previewAddImg(evt) {
            if (!evt.target.files.length) return;
            let reader = new FileReader();
            reader.onload = function (evt) {
                $('#modal_add_img').attr('src', evt.target.result);
            };
            reader.readAsDataURL(evt.target.files[0]);
        }
        function previewEditImg(evt) {
            if (!evt.target.files.length) return;
            let reader = new FileReader();
            reader.onload = function (evt) {
                $('#modal_edit_img').attr('src', evt.target.result);
            };
            reader.readAsDataURL(evt.target.files[0]);
        }
        var messages = [
            "{{ __('global.errors.image_empty') }}",  // 0
            "{{ __('global.errors.name_empty') }}",  // 1
            "{{ __('global.errors.restaurant_undefined') }}",  // 2
        ];
        function removeMenu(id) {
            $('#modal_remove_menu_id').val(id);
            $('#modal_remove_menu').modal('show');
        }
        function removeMenuBtn() {
            var restaurant_id = $('#restaurant_id').val();
            var menu_id = $('#modal_remove_menu_id').val();
            $.ajax({
                url: '/user/menus/' + restaurant_id,
                method: 'post',
                data: {
                    action: 'remove',
                    _token: "<?php echo csrf_token() ?>",
                    menu_id: menu_id,
                },
                success: function (res) {
                    if (res.status === "success") {
                        customAlert(res.message, true);
                        $('tr#menu_' + id).remove();
                        $('#modal_remove_menu').modal('toggle');
                    } else customAlert(res.message);
                }
            })
        }
        function addMenuBtn() {
            var restaurant_id = $('#restaurant_id').val();
            var image = $('#modal_add_img').attr('src');
            if (!image) {
                customAlert(messages[0]);
                return;
            }
            var name = $('#modal_add_menu_name').val();
            if (!name) {
                customAlert(messages[1]);
                return;
            }
            var description = $('#modal_add_menu_description').val();
            var url = '/user/menus/' + restaurant_id;
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                image: image,
                name: name,
                description: description
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_menu').modal('toggle');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }
        function editMenu(id) {
            var image = $('#menu_' + id + ' .image img').attr('src');
            console.log(image);
            var name = $('#menu_' + id + ' .name').text();
            var description = $('#menu_' + id + ' .description').text();
            $('#modal_edit_menu_id').val(id);
            $('#modal_edit_menu_name').val(name);
            $('#modal_edit_img').attr("src", image);
            $('#modal_edit_menu_description').val(description);
            $('#modal_edit_menu').modal('show');
        }
        function editMenuBtn() {
            var restaurant_id = $('#restaurant_id').val();
            var menu_id = $('#modal_edit_menu_id').val();
            var image = $('#modal_edit_img').attr('src');
            if (!image) {
                customAlert(messages[0]);
                return;
            }
            var name = $('#modal_edit_menu_name').val();
            if (!name) {
                customAlert(messages[1]);
                return;
            }
            var description = $('#modal_edit_menu_description').val();
            var url = '/user/menus/' + restaurant_id;
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                menu_id: menu_id,
                image: image,
                name: name,
                description: description
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_edit_menu').modal('toggle');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
