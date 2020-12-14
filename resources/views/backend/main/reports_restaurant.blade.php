@extends('layouts.back_layout')
@section('back-style')

@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{ __('global.side.restaurant_reports') }}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="report-table">
                            <thead style="background: #4b2436;">
                            <tr>
                                <th>No</th>
                                <th>Restaurants</th>
                                <th>Email</th>
                                <th>Order#</th>
                                <th>Total Purchased($)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $index => $order)
                                <tr id="order_{{ $order->id }}}">
                                    <td>{{ $index + 1 }}</td>
                                    <td data-class="restaurant">{{ $order->name }}</td>
                                    <td data-class="email">{{ $order->email }}</td>
                                    <td data-class="order">{{ $order->order }}</td>
                                    <td data-class="orders_sum">{{ $order->orders_sum }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot style="background: #55425e">
                            <tr>
                                <td colspan="4">Total</td>
                                <td>{{ $total_price }}</td>
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

    </script>
@stop