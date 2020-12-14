@extends('layouts.front_layout')
@section('front-style')
    <link rel="stylesheet" href="{{ url('/common/map/custom-map.css') }}">
    <style>
        .search-body {
            padding-left: 1%;
            padding-right: 1%;
        }

        #map-container, #map {
            height: calc(60vh);
        }
    </style>
@stop
@section('front-content')
    <!-- start top banner-->
    <div class="container-fluid pl-0 pr-0">
        <div class="row">
            <div class="col-md-12">
                <div class="page_banner">
                    <img src="/assets/image/restaturant_banner.jpg" alt="" class="img-fluid">
                    <div id="search_restaurants">
                        <form action="javascript:void(0);" method="post">
                            <div class="input-group">
                                <img src="/assets/image/search-magnifier-interface-symbol.svg">
                                <input class="form-control" id="search_location" type="text" placeholder="Search Restaurants" onfocus="geolocate()">
                                <div class="input-group-append">
                                    <button class="btn bg-pink cu-btn" onclick="onSearch()">Show</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="food-category">
            <ul Class="list-inline category_list">
                <li class="{{ ($query_category == 'all')?'active':'' }}"><a href="{{ url('/search/'.$location.'?category=all') }}">All select </a></li>
                @forelse($all_categories as $caIndex => $categoryItem)
                    <li class="{{ ($query_category == $categoryItem->id)?'active':'' }}">
                        <a href="{{ url('/search/'.$location.'?category='.$categoryItem->id) }}">{{ $categoryItem->name }}</a></li>
                @empty
                @endforelse
            </ul>
        </div>

        <div class="search-body">
            <div class="row" style="margin: 0">
                <div class="col-md-4 m_filter_wrapper">
                    <div class="side-bar">
                        <h5><span>{{ count($restaurants) }}</span> restaurants <a href="javascript:void(0);" class="back_ft">Back</a></h5>
                        <h5 class="mt-4">Select</h5>
                        <div class="cbtn-group">
                            <a href="javascript:void(0);" class="btn btn-default active">Delivery</a>
                            <a href="javascript:void(0);" class="btn btn-default">Pickup</a>
                        </div>

                        <div class="side-card">
                            <h5>Minimum order amount</h5>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio1" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio1">No Preference</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio2" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio2">Less than $100</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio3" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio3">Less than $1500</label>
                            </div>
                        </div>

                        <div class="side-card">
                            <h5>Delivery Cost</h5>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio4" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio4">No Preference</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio5" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio5">Free</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio6" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio6">Less than $10.00</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio7" name="mini_amount" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio7">Less than $20.00</label>
                            </div>
                        </div>

                        <div class="side-card">
                            <h5>Ratting</h5>
                            <div class="rateit" data-rateit-value="3" data-rateit-ispreset="true" data-rateit-readonly="false">
                            </div>
                        </div>

                        <div class="side-card pb-4">
                            <h5> Discount and rebats</h5>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Check this custom checkbox</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    @if(count($restaurants) > 0)
                        <div class="card restaurant_wrapper">
                            {{-- Map Here --}}
                            <div id="map-container">
                                <div id="businessdata" style="display: none">
                                    <?php
                                    $mapArray = array();
                                    foreach ($restaurants as $item){
                                        array_push($mapArray, array(
                                            'id' => $item->id,
                                            'title' => $item->name,
                                            'postcode' => '',
                                            'image' => $item->image,
                                            'headerimage' => $item->image,
                                            'lat' => $item->lat,
                                            'long' => $item->long,
                                            'address' => $item->address,
                                            'city' => $item->city,
                                            'rating' => 5,
                                        ));
                                    }
                                    echo json_encode($mapArray);
                                    ?></div>
                                <div id="map" data-map-zoom="9" data-map-scroll="true">
                                    <!-- map goes here -->
                                </div>
                            </div>
                        </div>
                        @foreach($restaurants as $restaurant_index => $restaurant)
                            <div class="card restaurant_wrapper wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="0.4s">
                                <a href="{{ url('/restaurants/'.$restaurant->id) }}" class="text-body">
                                    <div class="media">
                                        @if($restaurant->special == 'new')
                                            <div class="featured_label_new">New</div>
                                        @elseif($restaurant->special == 'featured')
                                            <div class="featured_label">Featured</div>
                                        @elseif($restaurant->special == 'popular')
                                            <div class="featured_label_popular">Popular</div>
                                        @endif
                                        <div class="restaurant_logo">
                                            <img src="{{ $restaurant->image }}" class="mr-3 img-fluid" alt="">
                                            <div class="rateit" data-rateit-value="3" data-rateit-ispreset="true" data-rateit-readonly="true">
                                                <div class="ratting-count">(0)</div>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $restaurant->name }}</h5>
                                            <p class="text-muted food-category">
                                                <?php $find_first=0;?>
                                                    @for($index1 = 0; $index1 < count($restaurant_category_ids[$restaurant_index]); $index1++)
                                                        {{$find_first>0?', ':''}}{{ $restaurant_category_ids[$restaurant_index][$index1]->name }}
                                                        <?php $find_first++;?>
                                                    @endfor
                                            </p>
                                            <div class="service-features">
                                                <ul class="list-unstyled d-flex">
                                                    <li><img src="/assets/image/time.svg"> <span>55min</span></li>
                                                    <li><img src="/assets/image/scooter.svg"> <span>Free</span></li>
                                                    <li><img src="/assets/image/shopping-bag.svg"> <span>Min. $144.00</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <h3>No results</h3>
                    @endif
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
                <li><a href="javascript:" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="javascript:" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="javascript:" target="_blank"><i class="fab fa-instagram"></i></a></li>
                <li><a href="javascript:" target="_blank"><i class="fab fa-google-plus"></i></a></li>
            </ul>
        </div>
        <!-- share icon -->

        <!-- show filter -->
        <a href="javascript:void(0);" id="showFilter"><i class="fas fa-filter"></i></a>
        <!-- end filter -->
    </div>
    <!-- End top banner -->
@stop
@section('front-script')
   <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBmofwXO4eBxqJY_GxcWJqoVtUnb4GtQAs&libraries=places&sensor=false"></script>
    <script type="text/javascript" src="{{asset('/common/map/ratings.js')}}"></script>
    <script type="text/javascript" src="{{asset('/common/map/infobox.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/common/map/markerclusterer.js')}}"></script>
    <script type="text/javascript" src="{{asset('/common/map/maps.js')}}"></script>

    <script src="{{ url('/common/map/custom-map.js') }}"></script>
    <script>
        $(function () {
            let searched_location = localStorage.getItem('search_location');
            console.log(searched_location);
            $('#search_location').val(searched_location);
        });
    </script>
@stop
