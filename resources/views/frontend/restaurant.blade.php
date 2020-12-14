@extends('layouts.front_layout')
@section('front-style')
    <style>
        .favt_logo {
            text-align: right;
            padding-right: 5%;
        }
    </style>
@stop
@section('front-content')
    <div class="container-fluid pl-0 pr-0">
        <div class="row">
            <div class="col-md-12">
                <div class="menu">
                    <img src="/assets/image/fruit2.jpg" alt="" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-8 dishes" id="food_list_col">
                    <div class="card restaurant radius_44">
                        <div class="rest_logo">
                            <a href="javascript:void(0);"><img src="{{ $restaurant->image }}" alt="" class="img-fluid"></a>
                        </div>
                        <div class="favt_logo">
                            <a href="javascript:void(0);" class="add_to_fav"><img src="/assets/image/de-like.svg" alt="" class="img-fluid"></a>
                        </div>
                        <div class="card-body text-center">
                            <h4>{{ $restaurant->name }}</h4>
                            <div class="rating_wrapper">
                                <div class="rateit" data-rateit-value="0" data-rateit-ispreset="true" data-rateit-readonly="true">
                                </div>
                                <p class="tag_address text-muted"> (0)</p>
                            </div>
                            <p class="restaurant-info text-center">{{ $restaurant->description }}</p>

                            <div id="food-category">
                                <ul Class="list-inline category_list">
                                    <li class="{{ ($query_menu == 'all')?'active':'' }}"><a href="{{ url('/restaurants/'.$restaurant->id) }}">{{ __('global.common.all_select') }} </a></li>
                                    @forelse($restaurant_menus as $menuItem)
                                        <li class="{{ ($query_menu == $menuItem->id)?'active':'' }}">
                                            <a href="{{ url('/restaurants/'.$restaurant->id.'?menu='.$menuItem->id) }}">{{ $menuItem->name }}</a>
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    @forelse($selected_menus as $index2 => $selected_menu)
                        <h6 class="mt-3">{{ $selected_menu->name }}</h6>
                        <div class="menu-image">
                            <img src="{{ $selected_menu->image }}" alt="Menu Image">
                            <p class="mt-2">{{ $selected_menu->description }}</p>
                        </div>
                        @forelse($foods as $food)
                            @if($food->menu_id == $selected_menu->id)
                                <div class="card mb-4" id="food_item_{{ $food->id }}">
                                    <div class="media menu_dishes">
                                        <div class="media-body">
                                            <a href="javascript:void(0);" class="text-body">
                                                <h5 class="mt-0 mb-1 food-name" data-name="{{ $food->name }}">{{ $food->name }}</h5>
                                                <p>{{ $food->description }}</p>
                                                <div class="text-danger dish-price mt-3" data-price="{{ $food->price }}">$ {{ $food->price }}</div>
                                            </a>
                                        </div>
                                        <div class="dish-img">
                                            <img src="{{ $food->image }}" alt="" class="img-fluid">
                                        </div>
                                        <a href="javascript:void(0);" class="add-order" onclick="clickFoodItem({{ $food->id }})"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    @empty
                    @endforelse
                </div>

                <div class="order_n_step">
                    <a href="javascript:void(0);" id="next_step_one" class="btn btn-success w-100" onclick="goToBasket()">
                        <span class="badge badge-light">2</span> Dishes Proceed <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>

                <div class="col-md-4" id="order_price_wrapper">
                    <div class="card order_price">
                        <div class="shop_logo">
                            <a href="javascript:void(0);"><img src="/assets/image/shopping-basket.svg" alt="" class="img-fluid"></a>
                        </div>
                        <div class="card-body text-center ">
                            <h4>Basket</h4>
                            <div class="add-dishes-wrapper">
                                <a href="javascript:void(0);" onclick="goBackFoods()"><i class="fas fa-plus-circle"></i></a>
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

                            <button type="submit" class="order_button" disabled onclick="order_make('{{ $restaurant->id }}')">ORDER</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- scroll top -->
    <div class="scroll_top">
        <a href="javascript:void(0);"><i class="fas fa-arrow-up"></i></a>
    </div>
    <!-- end scroll top -->

    <!-- share icon -->
    <div class="show_share_icon">
        <ul class="list-unstyled">
            <li><a href="javascript:void(0);" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="javascript:void(0);" target="_blank"><i class="fab fa-twitter"></i></a></li>
            <li><a href="javascript:void(0);" target="_blank"><i class="fab fa-instagram"></i></a></li>
            <li><a href="javascript:void(0);" target="_blank"><i class="fab fa-google-plus"></i></a></li>
        </ul>
    </div>
    <!-- share icon -->
    <input type="hidden" id="__token__" value="{{ csrf_token() }}">
    <input type="hidden" id="restaurant_mini_order" value="{{ $restaurant->mini_order }}">
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
        function order_make(id) {
            location.href = '/order-make/' + id
        }
    </script>
@stop

