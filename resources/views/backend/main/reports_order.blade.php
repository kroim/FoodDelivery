@extends('layouts.back_layout')
@section('back-style')

@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{ __('global.side.order_reports') }}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-5"><input type="date" class="form-control" id="start_order_date" value="{{ $start_date }}"></div>
                            <div class="col-5"><input type="date" class="form-control" id="end_order_date" value="{{ $end_date }}"></div>
                            <div class="col-2"><button class="btn btn-warning" onclick="order_filter()">Filter</button></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="report-table">
                            <thead style="background: #4b2436">
                            <tr>
                                <th>#</th>
                                <th>Order Number</th>
                                <th>Restaurant Name</th>
                                <th>Customer</th>
                                <th>Foods</th>
                                <th>Date</th>
                                <th>Price($)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_price = 0; ?>
                            @forelse($orders as $index => $order)
                                <tr id="order_{{ $order->id }}}">
                                    <td>{{ $index + 1 }}</td>
                                    <td data-class="order_id">{{ $order->id }}</td>
                                    <td data-class="restaurant_name">{{ $order->restaurant_name }}</td>
                                    <td data-class="customer">{{ $order->email }}</td>
                                    <td data-class="foods">
                                        <?php
                                            $foods = json_decode($order->order_data);
                                            foreach ($foods as $food) { ?>
                                            <div class="food_item">{{ $food->food_amount }} x {{ $food->food_name }} (${{ $food->price }})</div>
                                            <?php }
                                        ?>
                                    </td>
                                    <td data-class="date">{{ $order->created_at }}</td>
                                    <td data-class="price">{{ $order->order_price }}</td>
                                </tr>
                                <?php $total_price += (float)$order->order_price; ?>
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot style="background: #55425e">
                            <tr>
                                <td colspan="5" data-class="total">Total</td>
                                <td colspan="5" data-class="total_price">${{ $total_price }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('back-script')
    <script>
        function order_filter() {
            let start_date = $('#start_order_date').val();
            let end_date = $('#end_order_date').val();
            console.log(start_date, end_date);
            location.href = '/user/reports/orders?start_date=' + start_date + '&end_date=' + end_date;
        }
    </script>
@stop