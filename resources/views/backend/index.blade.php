@extends('layouts.back_layout')

@section('back-style')
    <link rel="stylesheet" href="{{ url('/common/croppie/croppie.css') }}" type="text/css">
    <style>
        #other_food_time::-webkit-outer-spin-button,
        #other_food_time::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        #other_food_time {
            -moz-appearance:textfield; /* Firefox */
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.my_account')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card profile">
                                <div class="profile__img">
                                    <img src="{{ Auth::user()->avatar }}" alt="Profile Avatar" id="profile_avatar">
                                    <a href="javascript:" class="zwicon-camera profile__img__edit" onclick="$('#crop-image-modal').modal()"></a>
                                </div>

                                <div class="profile__info">
                                    <ul class="icon-list">
                                        <li><i class="zwicon-user-circle"></i> {{ Auth::user()->name }}</li>
                                        <li><i class="zwicon-mail"></i> {{ Auth::user()->email }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>{{ __('global.common.address') }}*</label>
                                        <input type="text" id="profile_address" class="form-control" value="{{ Auth::user()->address }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.postcode') }}*</label>
                                        <input type="text" id="profile_postcode" class="form-control" value="{{ Auth::user()->postcode }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.city') }}*</label>
                                        <input type="text" id="profile_city" class="form-control" value="{{ Auth::user()->city }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.floor') }}*</label>
                                        <input type="text" id="profile_floor" class="form-control" value="{{ Auth::user()->floor }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.phone') }}*</label>
                                        <input type="text" id="profile_phone" class="form-control" value="{{ Auth::user()->phone }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.company') }}</label>
                                        <input type="text" id="profile_company" class="form-control" value="{{ Auth::user()->company }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.description') }}</label>
                                        <textarea class="form-control" id="profile_description" rows="4">{{ Auth::user()->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.food_time') }}</label>
                                        <select class="select form-control page-select" id="profile_food_time">
                                            <option value="0" {{ (Auth::user()->food_time == 0)?'selected':'' }}>As soon as possible</option>
                                            <option value="15" {{ (Auth::user()->food_time == 15)?'selected':'' }}>Current time + 15 minutes</option>
                                            <option value="30" {{ (Auth::user()->food_time == 30)?'selected':'' }}>Current time + 30 minutes</option>
                                            <option value="45"  {{ (Auth::user()->food_time == 45)?'selected':'' }}>Current time + 45 minutes</option>
                                            <option value="60" {{ (Auth::user()->food_time == 60)?'selected':'' }}>Current time + 60 minutes</option>
{{--                                            <option value="other">Other</option>--}}
                                        </select>
{{--                                        <label></label>--}}
{{--                                        <input type="number" class="form-control" id="other_food_time" placeholder="Enter only number as minutes" style="display: none">--}}
                                    </div>
                                    <div class="form-group" style="text-align: center">
                                        <button class="btn btn-outline-warning" onclick="changeProfile()">{{ __('global.common.change').' '.__('global.common.profile') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>{{ __('global.common.current').' '.__('global.common.password') }}*</label>
                                        <input type="password" id="profile_old_password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.new').' '.__('global.common.password') }}*</label>
                                        <input type="password" class="form-control" id="profile_new_password">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('global.common.confirm_password') }}*</label>
                                        <input type="password" class="form-control" id="profile_confirm_password">
                                    </div>
                                    <div class="form-group" style="text-align: center">
                                        <button class="btn btn-outline-danger" onclick="changePassword()">{{ __('global.common.change').' '.__('global.common.password') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <h5>Required fields (*)</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Crop Image Modal -->
    <div id="crop-image-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 style="text-align: center">{{ __('global.common.profile').' '.__('global.common.photo') }}</h3>
                    <div class="form-group" style="overflow: auto;">
                        <div id="upload-origin" style="width:100%;"></div>
                    </div>
                    <input type="file" id="upload-crop" style="display:none;" accept="image/*"/>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary form-control" onclick="$('#upload-crop').click();">
                                {{ __('global.common.select').' '.__('global.common.image') }}</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary form-control" data-dismiss="modal" onclick="uploadCropImg()">{{ __('global.common.crop') }}</button>
                        </div>
                    </div>
                </div><!-- end modal-bpdy -->
            </div><!-- end modal-content -->
        </div><!-- end modal-dialog -->
    </div>
@stop
@section('back-script')
    <script type="text/javascript" src="{{ url('/common/croppie/croppie.js') }}"></script>
    <script type="text/javascript" src="{{ url('/common/croppie/upload_img.js') }}"></script>
    <script>
        var profile_messages = [
            "{{ __('global.errors.address_empty') }}",  // 0
            "{{ __('global.errors.postcode_empty') }}", // 1
            "{{ __('global.errors.city_empty') }}",     // 2
            "{{ __('global.errors.floor_empty') }}",    // 3
            "{{ __('global.errors.phone_empty') }}",    // 4
            "{{ __('global.errors.password_empty') }}",    // 5
            "{{ __('global.errors.password_length') }}",    // 6
            "{{ __('global.errors.confirm_password_empty') }}",    // 7
            "{{ __('global.errors.confirm_password_wrong') }}",    // 8
        ];
        function changeProfile() {
            var profile_avatar = $('#profile_avatar').attr('src');
            var profile_address = $('#profile_address').val();
            if (profile_address === "") {
                customAlert(profile_messages[0]);
                return;
            }
            var profile_postcode = $('#profile_postcode').val();
            if (profile_postcode === "") {
                customAlert(profile_messages[1]);
                return;
            }
            var profile_city = $('#profile_city').val();
            if (profile_city === "") {
                customAlert(profile_messages[2]);
                return;
            }
            var profile_floor = $('#profile_floor').val();
            if (profile_floor === "") {
                customAlert(profile_messages[3]);
                return;
            }
            var profile_phone = $('#profile_phone').val();
            if (profile_phone === "") {
                customAlert(profile_messages[4]);
                return;
            }
            var profile_company = $('#profile_company').val();
            var profile_description = $('#profile_description').val();
            var profile_food_time = $('#profile_food_time').val();

            var url = '/user/my-account';
            var data = {
                _token: '<?php echo csrf_token() ?>',
                avatar: profile_avatar,
                address: profile_address,
                postcode: profile_postcode,
                city: profile_city,
                floor: profile_floor,
                phone: profile_phone,
                company: profile_company,
                description: profile_description,
                food_time: profile_food_time
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') customAlert(res.message, true);
                    else customAlert(res.message);
                }
            })
        }
        function changePassword() {
            var current_password = $('#profile_old_password').val();
            var new_password = $('#profile_new_password').val();
            if (current_password === '' || new_password === '') {
                customAlert(profile_messages[5]);
                return;
            }
            if (new_password.length < 6) {
                customAlert(profile_messages[6]);
                return;
            }
            var confirm_password = $('#profile_confirm_password').val();
            if (new_password !== confirm_password) {
                customAlert(profile_messages[8]);
                return;
            }
            var url = '/user/my-account';
            var data = {
                _token: '<?php echo csrf_token() ?>',
                action: 'change_password',
                current_password: current_password,
                new_password: new_password
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') customAlert(res.message, true);
                    else customAlert(res.message);
                }
            })
        }
    </script>
@stop
