@extends('layouts.back_layout')
@section('back-style')

@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.commission')}}</h1>
            </header>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('global.side.commission') }} (%)</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="number" class="form-control" id="commission_value" placeholder="5.5" value="{{ ($commission)?$commission->commission:'0' }}">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success" onclick="updateCommission()">{{ __('global.common.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('back-script')
    <script>
        function updateCommission() {
            let commission = $('#commission_value').val();
            commission = parseFloat(commission);
            console.log(commission);
            if (!commission) {
                customAlert('Commission value is required');
                return;
            }
            $.ajax({
                url: '/user/commission',
                method: 'post',
                data: {
                    _token: "<?php echo csrf_token() ?>",
                    commission: commission
                },
                success: function (res) {
                    if (res.status === 'success') customAlert(res.message, true);
                    else customAlert(res.message);
                }
            })
        }
    </script>
@stop
