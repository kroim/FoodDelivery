@extends('layouts.back_layout')
@section('back-style')
    <style>
        #modal_remove_country .modal-body {
            text-align: center;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.countries')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.side.countries') }}</h4>
                    <div class="actions">
                        <button class="btn btn-link" onclick="$('#modal_add_country').modal('show')">{{ __('global.common.add') }}</button>
                    </div>
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('global.common.name') }}</th>
                            <th>{{ __('global.common.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($countries as $index => $country)
                            <tr id="{{ $country->name }}">
                                <td class="user-number">{{ $index + 1 }}</td>
                                <td class="user-name">{{ $country->name }}</td>
                                <td class="user-action">
                                    <button class="btn btn-warning btn-sm" href="javascript:" onclick="editCountry('{{ $country->name }}')">{{ __('global.common.edit') }}</button>
                                    <button class="btn btn-danger btn-sm" onclick="removeCountry('{{ $country->name }}')">{{ __('global.common.remove') }}</button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- add driver modal -->
    <div class="modal fade" id="modal_add_country" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.add').' '.__('global.common.country') }}</div>
                <div class="modal-body">
                    <input class="form-control" type="text" id="modal_add_country_name" placeholder="{{ __('global.common.country').' '.__('global.common.name') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="addCountryBtn()">{{ __('global.common.add').' '.__('global.common.country') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit driver modal -->
    <div class="modal fade" id="modal_edit_country" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">{{ __('global.common.edit').' '.__('global.common.country') }}</div>
                <div class="modal-body">
                    <input type="hidden" id="original_country_name">
                    <input class="form-control" type="text" id="modal_edit_country_name" placeholder="{{ __('global.common.country').' '.__('global.common.name') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" onclick="editCountryBtn()">{{ __('global.common.edit').' '.__('global.common.country') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- remove user modal -->
    <div class="modal fade" id="modal_remove_country" tabindex="-1">
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
                    <input type="hidden" id="modal_remove_country_name">
                    <button type="button" class="btn btn-link" onclick="removeCountryBtn()">{{ __('global.common.remove') }}</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('back-script')
    <script>
        var messages = [
            "{{ __('global.errors.country_empty') }}"
        ];
        function editCountry(name) {
            $('#original_country_name').val(name);
            $('#modal_edit_country_name').val(name);
            $('#modal_edit_country').modal('show');
        }
        function removeCountry(name) {
            $('#modal_remove_country_name').val(name);
            $('#modal_remove_country').modal('show');
        }
        function removeCountryBtn() {
            var country_name = $('#modal_remove_country_name').val();
            var url = '/user/countries';
            var data = {
                action: 'remove',
                _token: '<?php echo csrf_token() ?>',
                country_name: country_name
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_remove_country').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    } else customAlert(res.message);
                }
            })
        }
        function addCountryBtn() {
            var country_name = $('#modal_add_country_name').val();
            if (country_name === '') {
                customAlert(messages[0]);
                return;
            }
            var url = '/user/countries';
            var data = {
                action: 'add',
                _token: '<?php echo csrf_token() ?>',
                country_name: country_name
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_add_country').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    } else customAlert(res.message);
                }
            })
        }
        function editCountryBtn() {
            var origin_name = $('#original_country_name').val();
            var country_name = $('#modal_edit_country_name').val();
            if (country_name === '') {
                customAlert(messages[0]);
                return;
            }
            var url = '/user/countries';
            var data = {
                action: 'edit',
                _token: '<?php echo csrf_token() ?>',
                origin_name: origin_name,
                country_name: country_name
            };
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('#modal_edit_country').modal('toggle');
                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
