@extends('layouts.back_layout')
@section('back-style')
    <style>
        .image img {
            height: 40px;
            width: 40px;
        }
        #modal_remove_food .modal-body {
            text-align: center;
        }
        .select2-container--default {
            z-index: 9999;
            width: 100% !important;
        }

        .select2-dropdown {
            background: #3c2f42;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{ __('global.side.foods') }}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $menu->name.' / '.__('global.side.foods')}}</h4>
                    <div class="text-right">
                        <button class="btn btn-link"
                                onclick="$('#modal_add_food').modal('show')">{{ __('global.common.add').' '.__('global.side.food') }}</button>
                        <a class="btn btn-link" href="{{ url('/user/menus/'.$restaurant_id) }}">{{ __('global.common.back') }}</a>
                    </div>
                    <input type="hidden" id="restaurant_id" value="{{ $restaurant_id }}">
                    <input type="hidden" id="menu_id" value="{{ $menu->id }}">
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 10%">{{ __('global.common.image') }}</th>
                                <th style="width: 20%">{{__('global.common.name')}}</th>
                                <th style="width: 30%">{{__('global.common.description')}}</th>
                                <th style="width: 10%">{{__('global.common.price')}}</th>
                                <th style="width: 25%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($foods as $index => $food)
                                <tr id="food_{{ $food->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="image"><img src="{{ $food->image }}"></td>
                                    <td class="name">{{ $food->name }}</td>
                                    <td class="description">{{ $food->description }}</td>
                                    <td class="price">{{ $food->price }}</td>
                                    <td class="td-action">
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="editFood({{ $food->id }})">{{ __('global.common.edit') }}</button>
                                        <button class="btn btn-outline-danger btn-sm" type="button"
                                                onclick="removeFood({{ $food->id }})">{{ __('global.common.remove') }}</button>
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

    <!-- remove food modal -->
    <div class="modal fade" id="modal_remove_food" tabindex="-1">
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
                    <input type="hidden" id="modal_remove_food_id">
                    <button type="button" class="btn btn-link" onclick="removeFoodBtn()">{{ __('global.common.remove') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add food modal -->
    <div class="modal fade" id="modal_add_food" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.side.food') }}</div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="modal_add_img" style="width: 150px; height: 150px" alt="">
                    </div>
                    <div class="text-center">
                        <input type="file" id="modal_add_image" accept="image/*" onchange="previewAddImg(event)" style="display: none">
                        <button class="btn btn-outline-warning" style="margin-top: 1%"
                                onclick="$('#modal_add_image').click();">{{ __('global.common.upload').' '.__('global.common.file') }}</button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_add_food_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.description') }}</label>
                        <textarea class="form-control" id="modal_add_food_description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.price') }}</label>
                        <input type="number" class="form-control" id="modal_add_food_price">
                    </div>

                    <div class="form-group">
                        <label>{{ __('global.side.extras') }}</label>
                        <select class="form-control select2-selection--multiple custom-multiple-select" id="modal_add_food_extras" multiple>
                            @forelse($extras as $extra)
                                <option value="{{ $extra->id }}">{{ $extra->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="addFoodBtn()">{{ __('global.common.add') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit food modal -->
    <div class="modal fade" id="modal_edit_food" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.side.food') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_food_id">
                    <div class="text-center">
                        <img id="modal_edit_img" style="width: 150px; height: 150px" alt="">
                    </div>
                    <div class="text-center">
                        <input type="file" id="modal_edit_image" accept="image/*" onchange="previewEditImg(event)" style="display: none">
                        <button class="btn btn-outline-warning" style="margin-top: 1%"
                                onclick="$('#modal_edit_image').click();">{{ __('global.common.upload').' '.__('global.common.file') }}</button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_edit_food_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.description') }}</label>
                        <textarea class="form-control" id="modal_edit_food_description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.price') }}</label>
                        <input type="number" class="form-control" id="modal_edit_food_price">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.side.extras') }}</label>
                        <select class="form-control select2-selection--multiple custom-multiple-select" id="modal_edit_food_extras" multiple>
                            @forelse($extras as $extra)
                                <option value="{{ $extra->id }}">{{ $extra->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="editFoodBtn()">{{ __('global.common.edit') }}</button>
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
            $(".custom-multiple-select").select2();
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
            "{{ __('global.errors.price_undefined') }}",  // 2
        ];
        function removeFood(id) {
            $('#modal_remove_food_id').val(id);
            $('#modal_remove_food').modal('show');
        }
        function removeFoodBtn() {
            var restaurant_id = $('#restaurant_id').val();
            var menu_id = $('#menu_id').val();
            var food_id = $('#modal_remove_food_id').val();
            $.ajax({
                url: '/user/menus/' + restaurant_id + '/' + menu_id,
                method: 'post',
                data: {
                    action: 'remove',
                    _token: "<?php echo csrf_token() ?>",
                    food_id: food_id,
                },
                success: function (res) {
                    if (res.status === "success") {
                        customAlert(res.message, true);
                        $('tr#food_' + food_id).remove();
                        $('#modal_remove_food').modal('toggle');
                    } else customAlert(res.message);
                }
            })
        }
        function addFoodBtn() {
            var restaurant_id = $('#restaurant_id').val();
            var menu_id = $('#menu_id').val();
            var image = $('#modal_add_img').attr('src');
            if (!image) {
                customAlert(messages[0]);
                return;
            }
            var name = $('#modal_add_food_name').val();
            if (!name) {
                customAlert(messages[1]);
                return;
            }
            var description = $('#modal_add_food_description').val();
            var price = $('#modal_add_food_price').val();
            if (isNaN(parseFloat(price))) {
                customAlert(messages[2]);
                return;
            }
            var extra_ids = $('#modal_add_food_extras').val();
            if (!extra_ids) extra_ids = [];
            var url = '/user/menus/' + restaurant_id + '/' + menu_id;
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                image: image,
                name: name,
                description: description,
                price: price,
                extra_ids: extra_ids
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_food').modal('toggle');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }
        function editFood(id) {
            var restaurant_id = $('#restaurant_id').val();
            var menu_id = $('#menu_id').val();
            $.ajax({
                url: '/user/menus/' + restaurant_id + '/' + menu_id,
                method: 'post',
                data: {
                    action: 'get_food',
                    _token: '<?php echo csrf_token() ?>',
                    food_id: id,
                },
                success: function (res) {
                    if (res.status === 'success') {
                        var food = res.food, extras = res.extras, all_extras = res.all_extras;
                        $('#modal_edit_food_id').val(food.id);
                        $('#modal_edit_img').attr('src', food.image);
                        $('#modal_edit_food_name').val(food.name);
                        $('#modal_edit_food_description').val(food.description);
                        $('#modal_edit_food_price').val(food.price);
                        var extra_html = '';
                        var extra_ids = [];
                        for (var i = 0; i < extras.length; i++) {
                            extra_ids.push(extras[i].extra_id);
                        }
                        for (var j = 0; j < all_extras.length; j++) {
                            if (extra_ids.indexOf(parseInt(all_extras[j].id)) > -1) {
                                extra_html += '<option value="' + all_extras[j].id + '" selected>' + all_extras[j].name + '</option>'
                            } else {
                                extra_html += '<option value="' + all_extras[j].id + '">' + all_extras[j].name + '</option>'
                            }
                        }
                        $('#modal_edit_food_extras').html(extra_html);
                        $('#modal_edit_food').modal('show');
                    } else customAlert(res.message);
                }
            })
        }
        function editFoodBtn() {
            var restaurant_id = $('#restaurant_id').val();
            var menu_id = $('#menu_id').val();
            var food_id = $('#modal_edit_food_id').val();
            var image = $('#modal_edit_img').attr('src');
            if (!image) {
                customAlert(messages[0]);
                return;
            }
            var name = $('#modal_edit_food_name').val();
            if (!name) {
                customAlert(messages[1]);
                return;
            }
            var description = $('#modal_edit_food_description').val();
            var price = $('#modal_edit_food_price').val();
            if (isNaN(parseFloat(price))) {
                customAlert(messages[2]);
                return;
            }
            var extra_ids = $('#modal_edit_food_extras').val();
            if (!extra_ids) extra_ids = [];
            var url = '/user/menus/' + restaurant_id + '/' + menu_id;
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                food_id: food_id,
                image: image,
                name: name,
                description: description,
                price: price,
                extra_ids: extra_ids
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_edit_food').modal('toggle');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
