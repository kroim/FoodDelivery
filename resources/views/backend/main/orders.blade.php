@extends('layouts.back_layout')
@section('back-style')
    <style>
        #modal_complete_order .modal-body {
            text-align: center;
        }
    </style>
@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{__('global.side.orders')}}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('global.side.orders').' '.__('global.common.management')}}</h4>

                    <div class="table-responsive data-table">
                        <table id="users-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 10%">{{__('global.common.email')}}</th>
                                <th style="width: 20%">{{__('global.common.service_hours')}}</th>
                                <th style="width: 10%">{{__('global.common.state')}}</th>
                                <th style="width: 15%">{{__('global.common.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $index => $order)
                                <tr id="order_{{ $order->id }}}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="email">{{ $order->email }}</td>
                                    <td class="service-hours">{{ $order->created_at }} + {{ $order->service_hours }}min</td>
                                    <td class="state">{{ $order->payment_status }}</td>
                                    <td class="td-action">
                                        <button class="btn btn-primary btn-sm" onclick="viewOrder('{{ $order->id }}')">{{ __('global.common.view') }}</button>
                                        @if(Auth::user()->role <= 1)
                                            <button class="btn btn-warning btn-sm" onclick="completeOrder('{{ $order->id }}')">{{ __('global.common.complete') }}</button>
                                            <button class="btn btn-danger btn-sm">{{ __('global.common.refund') }}</button>
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

    {{--  View modal  --}}
    <div class="modal fade" id="modal_view_order" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Shipping Address</h4>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="modal_view_order_address" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Postcode</label>
                        <input type="text" id="modal_view_order_postcode" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" id="modal_view_order_city" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" id="modal_view_order_state" class="form-control" readonly>
                    </div>
                    <h4>Contacts</h4>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="modal_view_order_email" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Phone number</label>
                        <input type="text" id="modal_view_order_phone" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" id="modal_view_order_company" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea id="modal_view_order_remark" class="form-control" rows="4" readonly></textarea>
                    </div>
                    <div class="modal_view_order_details">
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th style="width: 40%">Food Name</th>
                                    <th style="width: 30%">Extras</th>
                                    <th style="width: 10%">Amount</th>
                                    <th style="width: 20%">Cost($)</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('global.common.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- remove restaurant modal -->
    <div class="modal fade" id="modal_complete_order" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <i class="zwicon-info-circle" style="font-size: 7rem"></i>
                    </div>
                    <div class="form-group">
                        <h3>{{ __('global.verify.complete') }}</h3>
                    </div>
                    <input type="hidden" id="modal_complete_order_id">
                    <button type="button" class="btn btn-link" onclick="completeOrderBtn()">{{ __('global.common.complete') }}</button>
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
        });
        function viewOrder(id) {
            console.log(id);
            $.ajax({
                url: '/user/get-order-details',
                method: 'post',
                data: {
                    _token: '<?php echo csrf_token(); ?>',
                    action: 'get_order_details',
                    order_id: id,
                },
                success: function (res) {
                    if (res.status === 'success') {
                        var foods = JSON.parse(res.order.order_data);
                        var food_html = '';
                        for (var i = 0; i < foods.length; i++) {
                            food_html += '<tr><td>' + foods[i].food_name + '</td><td>';
                            for (var j = 0; j < foods[i].extras.length; j++) {
                                food_html += '<p>' + foods[i].extras[j][1] + '</p>'
                            }
                            food_html += '</td><td>' + foods[i].food_amount + '</td><td>' + foods[i].price + '</td>'
                        }
                        food_html += '<tr><td colspan="3">Total Price</td><td>$ ' + res.order.order_price + '</td></tr>'
                        $('.modal_view_order_details tbody').html(food_html);
                        $('#modal_view_order_address').val(res.order.address);
                        $('#modal_view_order_postcode').val(res.order.postcode);
                        $('#modal_view_order_city').val(res.order.city);
                        $('#modal_view_order_state').val(res.order.state);
                        $('#modal_view_order_email').val(res.order.email);
                        $('#modal_view_order_phone').val(res.order.phone);
                        $('#modal_view_order_company').val(res.order.company);
                        $('#modal_view_order_remark').val(res.order.remark);
                        $('#modal_view_order').modal('show');
                    } else customAlert(res.message);
                }
            });
        }
        function completeOrder(id) {
            $('#modal_complete_order_id').val(id);
            $('#modal_complete_order').modal('show');
        }
        function completeOrderBtn() {
            var order_id = $('#modal_complete_order_id').val();
            $.ajax({
                url: '/user/complete-order',
                method: 'post',
                data: {
                    action: 'complete_order',
                    _token: '<?php echo csrf_token(); ?>',
                    order_id: order_id
                },
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        setTimeout(function () {
                            location.reload();
                        }, 2000)
                    } else customAlert(res.message);
                }
            })
        }
    </script>
@stop
