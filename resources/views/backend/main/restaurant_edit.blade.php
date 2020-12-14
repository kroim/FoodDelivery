@extends('layouts.back_layout')
@section('back-style')
    <style>
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
                <h1>{{__('global.side.restaurants').' '.__('global.common.management')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.common.edit').' '.__('global.side.restaurant')}}</h4>
                    <input type="hidden" id="edit_restaurant_id" value="{{ $restaurant->id }}">
                    <div class="text-center">
                        <img id="edit_restaurant_img" style="width: 200px; height: 200px" alt="" src="{{ $restaurant->image }}">
                        <input type="file" id="edit_restaurant_file" accept="image/*" onchange="previewEditRestaurantImg(event)" style="display: none">
                        <button class="btn btn-outline-warning"
                                onclick="$('#edit_restaurant_file').click();">{{ __('global.common.upload').' '.__('global.common.file') }}</button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.name') }}</label>
                        <input class="form-control" type="text" id="edit_restaurant_name" value="{{ $restaurant->name }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.description') }}</label>
                        <textarea class="form-control" type="text" id="edit_restaurant_description" rows="4">{{ $restaurant->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.side.categories') }}</label>
                        <select class="form-control select2-selection--multiple custom-multiple-select" id="edit_restaurant_categories" multiple>
                            @forelse($categories as $category)
                                <option value="{{ $category->id }}" {{ (in_array($category->id, $restaurantCategoryIds))?'selected':'' }}>{{ $category->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.side.country') }}</label>
                        <select class="form-control page-select" id="edit_restaurant_country">
                            @forelse($countries as $country)
                                <option value="{{ $country->id }}" {{ ($country->id == $restaurant->country_id)?'selected':'' }}>{{ $country->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.address') }}</label>
                        <input class="form-control" type="text" id="edit_restaurant_address" value="{{ $restaurant->address }}">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.city') }}</label>
                                <input class="form-control" type="text" id="edit_restaurant_city" value="{{ $restaurant->city }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.state') }}</label>
                                <input class="form-control" type="text" id="edit_restaurant_state" value="{{ $restaurant->state }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.service_hours') }}</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control input-mask" id="edit_service_from" data-mask="00:00:00" value="{{ $restaurant->service_from }}" placeholder="eg: 23:06:55">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control input-mask" id="edit_service_to" data-mask="00:00:00" value="{{ $restaurant->service_to }}" placeholder="eg: 23:06:55">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="holiday_closed_flag" {{ ($restaurant->holiday_closed_flag)?'checked':'' }}>
                            <label class="custom-control-label" for="holiday_closed_flag">{{ __('global.common.closed_day') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="holiday_closed_content" rows="4" style="display: {{ ($restaurant->holiday_closed_flag)?'block':'none' }};"
                                  placeholder="e.g. Now our holiday auto-response is up and running. After January 1st, we'll edit this automation to remove the postscript regarding Xmas and New Year's Day.">{{ $restaurant->holiday_closed_content }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('global.common.mini_order') }}($)</label>
                        <input class="form-control" type="number" id="edit_mini_order" value="{{ $restaurant->mini_order }}">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.select').' '.__('global.common.owner') }}</label>
                                <select class="form-control page-select" id="edit_restaurant_owner">
                                    @if(Auth::user()->role < 4)
                                        @forelse($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ ($owner->id == $restaurant->owner_id)?'selected':'' }}>{{ $owner->name }}</option>
                                        @empty
                                        @endforelse
                                    @else
                                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('global.common.special') }}</label>
                                <select class="form-control page-select" id="restaurant_special">
                                    <option value="normal" {{ ($restaurant->special == 'normal')?'selected':'' }}>{{ __('global.common.normal') }}</option>
                                    <option value="new" {{ ($restaurant->special == 'new')?'selected':'' }}>{{ __('global.common.new') }}</option>
                                    <option value="popular" {{ ($restaurant->special == 'popular')?'selected':'' }}>{{ __('global.common.popular') }}</option>
                                    <option value="featured" {{ ($restaurant->special == 'featured')?'selected':'' }}>{{ __('global.common.featured') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-link" onclick="editRestaurantBtn()">{{ __('global.common.edit') }}</button>
                        <a class="btn btn-link" href="{{ url('/user/restaurants') }}">{{ __('global.common.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('back-script')
    <script>
        $(function () {
            $('.custom-multiple-select').select2();
        });
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
        function previewEditRestaurantImg(evt) {
            if (!evt.target.files.length) return;
            let reader = new FileReader();
            reader.onload = function (evt) {
                $('#edit_restaurant_img').attr('src', evt.target.result);
            };
            reader.readAsDataURL(evt.target.files[0]);
        }
        function editRestaurantBtn() {
            var restaurant_id = $('#edit_restaurant_id').val();
            var image = $('#edit_restaurant_img').attr('src');
            if (!image) {
                customAlert(messages[0]);
                return;
            }
            var name = $('#edit_restaurant_name').val();
            if (!name) {
                customAlert(messages[1]);
                return;
            }
            var description = $('#edit_restaurant_description').val();
            var category_ids = $('#edit_restaurant_categories').val();
            if (!category_ids || category_ids.length < 1) {
                customAlert(messages[2]);
                return;
            }
            var country_id = $('#edit_restaurant_country').val();
            if (!country_id) {
                customAlert(messages[3]);
                return;
            }
            var address = $('#edit_restaurant_address').val();
            if (!address) {
                customAlert(messages[6]);
                return;
            }
            var city = $('#edit_restaurant_city').val();
            if (!city) {
                customAlert(messages[7]);
                return;
            }
            var state = $('#edit_restaurant_state').val();
            if (!state) {
                customAlert(messages[8]);
                return;
            }
            var regexp = /([01][0-9]|[02][0-3]):[0-5][0-9]:[0-5][0-9]/;
            var service_from = $('#edit_service_from').val();
            var service_to = $('#edit_service_to').val();
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
            var mini_order = $('#edit_mini_order').val();
            if (!parseFloat(mini_order) || parseFloat(mini_order) < 0) {
                customAlert(messages[9]);
                return;
            }
            mini_order = parseFloat(mini_order);
            var owner_id = $('#edit_restaurant_owner').val();
            if (!owner_id) {
                customAlert(messages[5]);
                return;
            }
            var special = $('#restaurant_special').val();
            var url = '/user/restaurant-edit/' + restaurant_id;
            var data = {
                action: 'edit',
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
                        setTimeout(function () {
                            location.href = '/user/restaurants';
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
