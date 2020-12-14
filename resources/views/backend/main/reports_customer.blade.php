@extends('layouts.back_layout')
@section('back-style')

@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                @if($report_type == 'owners')
                    <h1>{{ __('global.side.owner_reports') }}</h1>
                @elseif($report_type == 'restaurants')
                    <h1>{{ __('global.side.restaurant_reports') }}</h1>
                @else
                    <h1>{{ __('global.side.customer_reports') }}</h1>
                @endif
            </header>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="report-table">
                            <thead style="background: #4b2436;">
                            <tr>
                                <th>No</th>
                                <th>Customers</th>
                                <th>Phone</th>
                                <th>Order Count</th>
                                <th>Purchased($)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                    $total_count = 0;
                                    $total_purchased = 0;
                            ?>
                            @forelse($customers as $index => $customer)
                                <tr>
                                    <td data-class="no">{{ $index + 1 }}</td>
                                    <td data-class="customer">{{ $customer->email }}</td>
                                    <td data-class="phone">{{ $customer->phone }}</td>
                                    <td data-class="count">{{ $customer->count }}</td>
                                    <td data-class="purchased">{{ $customer->purchased }}</td>
                                </tr>
                                <?php
                                        $total_count += $customer->count;
                                        $total_purchased += $customer->purchased;
                                ?>
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot style="background: #55425e;">
                            <tr>
                                <td colspan="3">Total</td>
                                <td>{{ $total_count }}</td>
                                <td>{{ $total_purchased }}</td>
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