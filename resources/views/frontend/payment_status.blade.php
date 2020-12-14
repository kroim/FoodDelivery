@extends('layouts.front_layout')
@section('front-style')
    <style>
        .panel {
            padding: 5%;
        }
    </style>
@stop
@section('front-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    @if ($message = Session::get('success'))
                        <h1 style="text-align: center; color: green">{!! $message !!}</h1>
                    <input type="hidden" id="payment_success_input" value="{{ $message }}">
                        <?php Session::forget('success');?>
                    @elseif ($message = Session::get('error'))
                        <h1 style="text-align: center; color: orangered">{!! $message !!}</h1>
                        <?php Session::forget('error');?>
                    @else
                        <h1 style="text-align: center; color: orange">Expired this page</h1>
                    @endif
                    <a href="/" style="float: right">Go to home page -></a>
                </div>
            </div>
        </div>
    </div>
@stop
@section('front-script')
    <script>
        $(function () {
            if ($('#payment_success_input').val() !== '') {
                localStorage.removeItem('basket_items');
            }
        })
    </script>
@stop
