@php
    use App\Traits\BaseTrait;
@endphp
@extends('layouts.back_layout')
@section('back-style')
    <style>
        #modal_remove_restaurant .modal-body {
            text-align: center;
        }

        .select2-container--default {
            z-index: 9999;
            width: 100% !important;
        }

        .select2-dropdown {
            background: #3c2f42;
        }
        .image img {
            width: 40px;
            height: 40px;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.restaurants')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.side.restaurant').' '.__('global.common.management')}}</h4>
                    <div class="actions">
                        @if(Auth::user()->role < 3 || BaseTrait::checkPermission('restaurant_add'))
                            <button class="btn btn-link"
                                    onclick="$('#modal_add_restaurant').modal('show')">{{ __('global.common.add').' '.__('global.side.restaurant') }}</button>
                        @endif
                    </div>
                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 10%">{{__('global.common.name')}}</th>
                                <th style="width: 5%">{{ __('global.common.image') }}</th>
                                <th style="width: 20%">{{__('global.side.categories')}}</th>
                                <th style="width: 10%">{{__('global.side.country')}}</th>
                                <th style="width: 20%">{{__('global.common.service_hours')}}</th>
                                @if(Auth::user()->role < 4)
                                    <th style="width: 10%">{{__('global.common.owner')}}</th>
                                @endif
                                <th style="width: 15%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($restaurants as $index => $restaurant)
                                <tr id="restaurant_{{ $restaurant->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="name">{{ $restaurant->name }}</td>
                                    <td class="image"><img src="{{ $restaurant->image }}"></td>
                                    <td class="categories">
                                        <?php $find_first=0;?>
                                        @for($i = 0; $i < count($restaurantCategories); $i++)
                                            @if($restaurantCategories[$i]->restaurant_id == $restaurant->id)
                                                {{$find_first>0?', ':''}}<span class="restaurant-ids">{{ $restaurantCategories[$i]->name }}</span>
                                                <?php $find_first++;?>
                                            @endif
                                        @endfor
                                    </td>
                                    <td class="country">@for($j = 0; $j < count($countries); $j++)
                                            @if($countries[$j]->id == $restaurant->country_id)
                                                {{ $countries[$j]->name }}
                                            @endif
                                        @endfor</td>
                                    <td class="service-hours">
                                        <span class="service-from">{{ $restaurant->service_from }}</span>
                                        <span> ~ </span>
                                        <span class="service-to">{{ $restaurant->service_to }}</span>
                                    </td>
                                    @if(Auth::user()->role < 4)
                                        <td class="owner">@for($k = 0; $k < count($owners); $k++)
                                                @if($owners[$k]->id == $restaurant->owner_id)
                                                    {{ $owners[$k]->name }}
                                                @endif
                                            @endfor</td>
                                    @endif
                                    <td class="td-action">
                                        @if(Auth::user()->role < 3 || BaseTrait::checkPermission('restaurant_edit'))
                                            <a class="btn btn-warning btn-sm"
                                               href="{{ url('/user/restaurant-edit/'.$restaurant->id) }}">{{ __('global.common.edit') }}</a>
                                        @endif
                                        @if(Auth::user()->role < 3 || BaseTrait::checkPermission('restaurant_remove'))
                                            <button class="btn btn-danger btn-sm"
                                                    onclick="removeRestaurant({{ $restaurant->id }})">{{ __('global.common.remove') }}</button>
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

    <!-- add restaurant modal -->
    <div class="modal fade" id="modal_add_restaurant" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.side.restaurant') }}</div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="modal_add_restaurant_img" style="width: 200px; height: 200px" alt="">
                        <input type="file" id="modal_add_restaurant_file" accept="image/*" onchange="previewAddRestaurantImg(event)" style="display: none">
                        <button class="btn btn-outline-warning"
                                onclick="$('#modal_add_restaurant_file').click();">{{ __('global.common.upload').' '.__('global.common.file') }}</button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="modal_add_restaurant_name">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.description') }}</label>
                        <textarea class="form-control" type="text" id="modal_add_restaurant_description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.side.categories') }}</label>
                        <select class="form-control select2-selection--multiple custom-multiple-select" id="modal_add_restaurant_categories" multiple>
                            @forelse($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.side.country') }}</label>
                        <select class="form-control page-select" id="modal_add_restaurant_country">
                            @forelse($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.address') }}</label>
                        <input class="form-control" type="text" id="modal_add_restaurant_address">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.city') }}</label>
                                <input class="form-control" type="text" id="modal_add_restaurant_city">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.state') }}</label>
                                <input class="form-control" type="text" id="modal_add_restaurant_state">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.service_hours') }}</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control input-mask" id="modal_add_service_from" data-mask="00:00:00" placeholder="eg: 23:06:55">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control input-mask" id="modal_add_service_to" data-mask="00:00:00" placeholder="eg: 23:06:55">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="holiday_closed_flag">
                            <label class="custom-control-label" for="holiday_closed_flag">{{ __('global.common.closed_day') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="holiday_closed_content" rows="4" style="display: none;" placeholder="e.g. Now our holiday auto-response is up and running. After January 1st, we'll edit this automation to remove the postscript regarding Xmas and New Year's Day."></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.mini_order') }}($)</label>
                        <input class="form-control" id="modal_add_mini_order" type="number">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.select').' '.__('global.common.owner') }}</label>
                                <select class="form-control page-select" id="modal_add_restaurant_owner">
                                    @forelse($owners as $owner)
                                        <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.special') }}</label>
                                <select class="form-control page-select" id="restaurant_special">
                                    <option value="normal">{{ __('global.common.normal') }}</option>
                                    <option value="new">{{ __('global.common.new') }}</option>
                                    <option value="popular">{{ __('global.common.popular') }}</option>
                                    <option value="featured">{{ __('global.common.featured') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="addRestaurantBtn()">{{ __('global.common.add') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- remove restaurant modal -->
    <div class="modal fade" id="modal_remove_restaurant" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <i class="zwicon-info-circle" style="font-size: 7rem"></i>
                    </div>
                    <div class="form-group">
                        <h5 id="modal_remove_restaurant_name"></h5>
                        <h3>{{ __('global.verify.remove') }}</h3>
                    </div>
                    <input type="hidden" id="modal_remove_restaurant_id">
                    <button type="button" class="btn btn-link" onclick="removeRestaurantBtn()">{{ __('global.common.remove') }}</button>
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
            $(".custom-multiple-select").select2();
        });
        function previewAddRestaurantImg(evt) {
            if (!evt.target.files.length) return;
            let reader = new FileReader();
            reader.onload = function (evt) {
                $('#modal_add_restaurant_img').attr('src', evt.target.result);
            };
            reader.readAsDataURL(evt.target.files[0]);
        }

        var messages = [
            "{{ __('global.errors.image_empty') }}",  // 0
            "{{ __('global.errors.name_empty') }}",  // 1
            "{{ __('global.errors.category_empty') }}",  // 2
            "{{ __('global.errors.country_empty') }}",  // 3
            "{{ __('global.errors.time_invalid') }}",  // 4
            "{{ __('global.errors.owner_empty') }}",  // 5
            "{{ __('global.errors.address_empty') }}",  // 6
            "{{ __('global.errors.city_empty') }}",  // 7
            "{{ __('global.errors.state_empty') }}",  // 8
            "{{ __('global.errors.mini_order_empty') }}",  // 9
        ];
        function removeRestaurant(id) {
            $('#modal_remove_restaurant_id').val(id);
            $('#modal_remove_restaurant_name').text($('#restaurant_' + id + ' td:nth-child(2)').text());
            $('#modal_remove_restaurant').modal('show');
        }
        function removeRestaurantBtn() {
            var id = $('#modal_remove_restaurant_id').val();
            $.ajax({
                url: '/user/restaurants',
                method: 'post',
                data: {
                    action: 'remove',
                    _token: "<?php echo csrf_token() ?>",
                    restaurant_id: id,
                },
                success: function (res) {
                    if (res.status === "success") {
                        customAlert(res.message, true);
                        $('tr#restaurant_' + id).remove();
                        $('#modal_remove_restaurant').modal('toggle');
                    } else customAlert(res.message);
                }
            })
        }
        function addRestaurantBtn() {
            var image = $('#modal_add_restaurant_img').attr('src');
            if (!image) {
                customAlert(messages[0]);
                return;
            }
            var name = $('#modal_add_restaurant_name').val();
            if (!name) {
                customAlert(messages[1]);
                return;
            }
            var description = $('#modal_add_restaurant_description').val();
            var category_ids = $('#modal_add_restaurant_categories').val();
            if (!category_ids || category_ids.length < 1) {
                customAlert(messages[2]);
                return;
            }
            // category_ids = JSON.stringify(category_ids);
            var country_id = $('#modal_add_restaurant_country').val();
            if (!country_id) {
                customAlert(messages[3]);
                return;
            }
            var address = $('#modal_add_restaurant_address').val();
            if (!address) {
                customAlert(messages[6]);
                return;
            }
            var city = $('#modal_add_restaurant_city').val();
            if (!city) {
                customAlert(messages[7]);
                return;
            }
            var state = $('#modal_add_restaurant_state').val();
            if (!state) {
                customAlert(messages[8]);
                return;
            }
            var regexp = /([01][0-9]|[02][0-3]):[0-5][0-9]:[0-5][0-9]/;
            var service_from = $('#modal_add_service_from').val();
            var service_to = $('#modal_add_service_to').val();
            if (service_from.search(regexp) < 0 || service_to.search(regexp) < 0) {
                customAlert(messages[4]);
                return;
            }
            var holiday_closed_flag = 'false';
            var holiday_closed_content = '';
            if ($('#holiday_closed_flag').prop('checked')) {
                holiday_closed_flag = 'true';
                holiday_closed_content = $('#holiday_closed_content').val();
            }
            var mini_order = $('#modal_add_mini_order').val();
            if (!parseFloat(mini_order) || parseFloat(mini_order) < 0) {
                customAlert(messages[9]);
                return;
            }
            mini_order = parseFloat(mini_order);
            var owner_id = $('#modal_add_restaurant_owner').val();
            if (!owner_id) {
                customAlert(messages[5]);
                return;
            }
            var special = $('#restaurant_special').val();
            var url = '/user/restaurants';
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                image: image,
                name: name,
                description: description,
                category_ids: category_ids,
                country_id: country_id,
                address: address,
                city: city,
                state: state,
                service_from: service_from,
                service_to: service_to,
                holiday_closed_flag: holiday_closed_flag,
                holiday_closed_content: holiday_closed_content,
                mini_order: mini_order,
                special: special,
                owner_id: owner_id
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_restaurant').modal('toggle');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else customAlert(res.message);
                }
            })
        }
        $('#holiday_closed_flag').on('change', function () {
            if($(this).prop('checked')) $('#holiday_closed_content').css('display', 'block');
            else $('#holiday_closed_content').css('display', 'none');
        })
    </script>
@stop
