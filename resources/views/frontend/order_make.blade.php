@extends('layouts.front_layout')
@section('front-style')

@stop
@section('front-content')
    <!-- start top banner-->
    <div class="container-fluid pl-0 pr-0">
        <div class="row">
            <div class="col-md-12">
                <div class="cart-banner">
                    <img src="{{ url('/assets/image/cart-banner.jpg') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        <input type="hidden" id="restaurant_mini_order" value="{{ $restaurant->mini_order }}">
        <!-- start the payment form -->
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card payment_form">
                        <div class="card-body pay_detail">
                            <h3 class="card_heading mb-3">Ready to eat?</h3>
                            @if($session_message = Session::get('order_make_errors'))
                                <h5 class="mb-4 restaurant_name" style="color: orangered">{!! $session_message !!}</h5>
                                <?php Session::forget('order_make_errors'); ?>
                            @endif
                            <form id="order_form" method="post" action="{{ url('/order-payment') }}">
                                {{ csrf_field() }}
                                <h4 class="ques">Where do you want your order to be delivered?</h4>
                                <input type="hidden" name="order_restaurant_id" value="{{ $restaurant->id }}">
                                <div class="form-group">
                                    <input type="text" name="order_address" class="form-control" placeholder="Enter address*" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="order_postcode" class="form-control" placeholder="Postcode*" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="order_city" class="form-control" placeholder="City*" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="order_state" class="form-control" placeholder="State*" required>
                                </div>
                                <p class="mt-4 ques">How can we reach you?</p>
                                <div class="form-group">
                                    <input type="email" name="order_email" class="form-control" placeholder="Enter email*" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" name="order_phone" class="form-control" placeholder="Phone number*" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="order_company" class="form-control" placeholder="Enter the company name">
                                </div>
                                <p class="mt-4 ques">When would you like your food?</p>
                                <div class="form-group">
                                    <select name="deliverytime" class="pulldown_form form-control">
                                        <option value="0" class="pulldown">As soon as possible</option>
                                        <option value="15" class="pulldown">Current time + 15 minutes</option>
                                        <option value="30" class="pulldown">Current time + 30 minutes</option>
                                        <option value="45" class="pulldown">Current time + 45 minutes</option>
                                        <option value="60" class="pulldown">Current time + 60 minutes</option>
                                        <option value="90" class="pulldown">Current time + 90 minutes</option>
                                        <option value="120" class="pulldown">Current time + 120 minutes</option>
                                        <option value="240" class="pulldown">Current time + 240 minutes</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea class="form-control" name="message" style="resize:none; height:100px;"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" name="order_agree" type="checkbox" value="" id="invalidCheck2" required>
                                        <label class="form-check-label" for="invalidCheck2">
                                            Agree to terms and conditions
                                        </label>
                                        <input type="hidden" name="order_baskets" id="order_baskets">
                                    </div>
                                    <p class="mt-4 ques">How would like to payment?</p>
                                    <div class="payment-method-options" style="display: none">
                                        <input type="radio" name="payment_method" value="paypal" checked required>
                                        <input type="radio" name="payment_method" value="visa" required>
                                        <input type="radio" name="payment_method" value="master" required>
                                    </div>
                                    <ul class="payment-method-cards list-unstyled">
                                        <li class="card-icon lightgray" data-method="paypal">
                                            <a href="javascript:"><img src="{{ url('/assets/image/payment_18.png') }}" class="img-fluid" alt="">paypal</a>
                                        </li>
                                        <li class="card-icon" data-method="visa">
                                            <a href="javascript:"><img src="{{ url('/assets/image/payment_61.png') }}" class="img-fluid" alt="">visa</a>
                                        </li>
                                        <li class="card-icon" data-method="master">
                                            <a href="javascript:"><img src="{{ url('/assets/image/payment_62.png') }}" class="img-fluid" alt="">master card</a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="card_payment_info" style="display: none">
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Card Number</label>
                                                <input type="text" class="form-control" name="card_no">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>CVV</label>
                                                        <input type="number" class="form-control" name="cvv" placeholder="ex. 311">
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label>Expiration</label>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <input type="number" class="form-control" name="expiry_month" placeholder="MM">
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="number" class="form-control" name="expiry_year" placeholder="YYYY">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($card_err_msg = Session::get('card_err_msg'))
                                                <p id="card_err_msg" style="color: orangered">{{ $card_err_msg }}</p>
                                                <?php Session::forget('card_err_msg'); ?>
                                            @endif
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger" id="order_and_pay">ORDER AND PAY</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end the payment form -->

                <div class="col-md-4">
                    <div class="card order_price">
                        <div class="shop_logo">
                            <a href="javascript:void(0);"><img src="/assets/image/shopping-basket.svg" alt="" class="img-fluid"></a>
                        </div>
                        <div class="card-body text-center ">
                            <h4>Basket</h4>
                            <div class="add-dishes-wrapper">
                                <div id="basket_items_panel">
                                </div>
                            </div>
                            <hr>
                            <div class="rate">
                                <div class="price">
                                    <span>Sub-total</span>
                                    <span class="sub-total-span">$ 0.00</span>
                                </div>
                                <div class="price">
                                    <span>Delivery costs</span>
                                    <span>Free</span>
                                </div>
                                <div class="price total">
                                    <span>Total</span>
                                    <span class="total-span">$ 0.00</span>
                                </div>
                            </div>
                            <hr>
                            <p class="text-center response" id="order_button_status"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End top banner -->
@stop
@section('front-script')
    <script>
        let mini_order = 9.9;
        let mini_order_string = "9,9";
        $(function () {
            mini_order = parseFloat($('#restaurant_mini_order').val());
            mini_order_string = mini_order.toString().replace('.', ',');
        })
    </script>
    <script src="{{ url('/assets/js/custom-baskets.js') }}"></script>
    <script>
        $(function () {
            if ($('#card_err_msg').text() !== '') {
                $('.payment-method-cards li')[1].click();
            }
        });
        $('.payment-method-cards li').on('click', function () {
            var paymentMethod = $(this).attr('data-method');
            $('input[name="payment_method"][value="' + paymentMethod + '"]').click();
            $('.payment-method-cards li').removeClass('lightgray');
            $(this).addClass('lightgray');
            if (paymentMethod === 'paypal') {
                $('#card_payment_info').css('display', 'none');
                $('input[name="card_no"]').prop('required', false);
                $('input[name="cvv"]').prop('required', false);
                $('input[name="expiry_month"]').prop('required', false);
                $('input[name="expiry_year"]').prop('required', false);
            } else {
                $('#card_payment_info').css('display', 'block');
                $('input[name="card_no"]').prop('required', true);
                $('input[name="cvv"]').prop('required', true);
                $('input[name="expiry_month"]').prop('required', true);
                $('input[name="expiry_year"]').prop('required', true);
            }
        })
    </script>
@stop
