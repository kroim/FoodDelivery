@extends('layouts.front_layout')
@section('front-style')

@stop
@section('front-content')

    <!-- start top banner-->
    <div class="container-fluid pl-0 pr-0">
        <div class="row">
            <div class="col-md-12">
                <div class="home-banner">
                    <img src="{{ url('/assets/image/home_banner.jpg') }}" alt="" class="img-fluid">
                    <div class="text-wrapper wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
                        <h1>Order Takeaway or Delivery Food</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End top banner -->

    <!-- start search div -->
    <div class="container">
        <div class="row">
            <div class="col-sm-10 ml-auto mr-auto search wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="1s">
                <div class="card search_wrapper">
                    <div class="card-body">
                        <h4>search your location</h4>
                        <div class="input-group">
                            <img src="{{ url('/assets/image/placeholder.svg') }}">
                            <input class="form-control" id="search_location" type="text" name="" placeholder="Search Location" onfocus="geolocate()">
                            <div class="input-group-append">
                                <button class="input-group-text bg-pink" id="button" onClick="onSearch()">Show</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search div -->

    <!--start text-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h4 class="fn_2rem">How it works</h4>
                <h3 class="p_heading mb-4 wow rubberBand" data-wow-duration="0.8s" data-wow-delay="0.5s">Easy as that!</h3>
            </div>
        </div>

        <!--end of text-->
        <div class="row" id="step-process">
            <div class="col-sm-3 wow fadeInUp" data-wow-duration="0.9s" data-wow-delay="0.7s">
                <div class="card">
                    <div class="step-icon">
                        1
                    </div>
                    <div class="card-body">
                        <img src="{{ url('/assets/image/map.png') }}" class="card-title">
                        <h4 class="card-text">Search by Address</h4>
                        <p class="card-text text-muted">Find all restaurants available in your zone.</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay="0.9s">
                <div class="card">
                    <div class="step-icon">
                        2
                    </div>
                    <div class="card-body">
                        <img src="{{ url('/assets/image/icon_2.png') }}" class="card-title">
                        <h4 class="card-text">Choose A Restaurant</h4>
                        <p class="card-text text-muted">We have more than 1000s of menus online</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay="1s">
                <div class="card">
                    <div class="step-icon">
                        3
                    </div>
                    <div class="card-body">
                        <img src="{{ url('/assets/image/lock.png') }}" class="card-title size_two">
                        <h4 class="card-text">Pay by card or cash</h4>
                        <p class="card-text text-muted">It quick, easy and totally secure.</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay="1.5s">
                <div class="card">
                    <div class="step-icon">
                        4
                    </div>
                    <div class="card-body">
                        <img src="{{ url('/assets/image/car.png') }}" class="card-title size_three">
                        <h4 class="card-text">Delivery of takeaway</h4>
                        <p class="card-text text-muted">You are lazy? Are you backing home?</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 wow zoomIn" data-wow-duration="0.8s" data-wow-delay="0.8s" id="min_wrapper">
                <div class="card">
                    <div class="card-body">
                        3
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        0
                    </div>
                </div>
            </div>

            <div class="col-sm-12 wow bounceIn" data-wow-duration="0.8s" data-wow-delay="0.8s">
                <h5 class="text-center min_heading">The minutes that usually take to deliver!</h5>
            </div>
        </div>
    </div>

    <div class="container" id="most_popular">
        <div class="row mt-5">
            <div class="col-sm-12 text-center">
                <h3 class="p_heading mb-4">Choose From Most Popular</h3>
                <p class="text-muted">Lorem Ipsum is simply dummy text of the printing</p>
            </div>
        </div>
        <div class="row mt-5">
            @forelse ($popular_restaurants as $popular_index => $popular_restaurant)
                <div class="col-md-6 wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="0.8s">
                    <div class="card popular-item">
                        <img src="{{ url('/assets/image/popular.png') }}" alt="" class="img-fluid p_img">
                        <div class="card-body">
                            <a href="{{ url('/restaurants/'.$popular_restaurant->id) }}">
                                <div class="img_wrapper">
                                    <img src="{{ $popular_restaurant->image }}" alt="" class="img-fluid">
                                </div>
                            </a>
                            <div class="rating_wrapper">
                                <div class="rateit" data-rateit-value="0" data-rateit-ispreset="true" data-rateit-readonly="true">
                                </div>
                                <div class="open_at">Open at {{ $popular_restaurant->service_from }}</div>
                            </div>
                            <h4>{{ $popular_restaurant->name }}</h4>
                            <p class="tag_address text-muted">
                                <?php $find_first=0; ?>
                                    @for($c = 0; $c < count($popular_categories[$popular_index]); $c++)
                                        {{$find_first>0?', ':''}}{{ $popular_categories[$popular_index][$c]->name }}
                                        <?php $find_first++;?>
                                    @endfor
                            </p>
                            <p class="address text-muted">{{ $popular_restaurant->address.', '.$popular_restaurant->city.', '.$popular_restaurant->state }}</p>
                            <ul class="list-unstyled d-flex">
                                <li class="active"><i class="far fa-check-circle"></i> Take away</li>
                                <li class="active ml-2"><i class="far fa-check-circle"></i> Delivery</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <p>No popular restaurants</p>
            @endforelse
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="row bg-primary full-wrapper">
            <div class="col-md-8">
                <h1 class="text-center text-light">Choose from over 2,000 Restaurants</h1>
            </div>
            <div class="col-md-4">
                <p class="text-center">
                    <a href="{{ url('/search/all') }}" class="btn btn-warning">View all Restaurants</a>
                </p>
            </div>
        </div>
    </div>

    <div class="container" id="client-benefits">
        <div class="row mt-5">
            <div class="col-sm-12 text-center">
                <h3 class="p_heading mb-4 wow rubberBand" data-wow-duration="0.8s" data-wow-delay="0.5s">Your time.</h3>
                <p class="text-muted">Lorem Ipsum is simply dummy text of the printing</p>
            </div>
        </div>

        <div class="row" id="step-process">
            <div class="col-sm-4 wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="0.7s">
                <div class="card">
                    <div class="step-icon">
                        <img src="{{ url('/assets/image/bonus.svg') }}" alt="" class="img-fluid icon">
                    </div>
                    <div class="card-body">
                        <h4 class="card-text">Your Bonus</h4>
                        <ul class="list-unstyled benefits-list">
                            <li> Our loyalty shop programme: discover many discounts and prizes</li>
                            <li> Receive stamps, promotions, discounts, news and more, via our newsletters and social channels</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="1s">
                <div class="card">
                    <div class="step-icon">
                        <img src="{{ url('/assets/image/guarantee.svg') }}" alt="" class="img-fluid icon">
                    </div>
                    <div class="card-body">
                        <h4 class="card-text">Your Guarantee</h4>
                        <ul class="list-unstyled benefits-list">
                            <li>Excellent service for free</li>
                            <li>Authentic user reviews</li>
                            <li>Price guarantee: you pay just as much for your delivered meal, as you would pay when ordering directly from the restaurant</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="1.3s">
                <div class="card">
                    <div class="step-icon">
                        <img src="{{ url('/assets/image/growth.svg') }}" alt="" class="img-fluid icon">
                    </div>
                    <div class="card-body">
                        <h4 class="card-text">Your Benefits</h4>
                        <ul class="list-unstyled benefits-list">
                            <li>15.000+ partner restaurants to choose from</li>
                            <li>Pay cash or online</li>
                            <li>Order anytime, anywhere, on any device</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0" id="download_wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="blur-wrapper wow flipInX" data-wow-duration="1s" data-wow-delay="0.9s">
                    <h1 class="text-center">Download the app <br/> Click, sit back and enjoy.</h1>
                    <div class="app-btn-group">
                        <a href="javascript:"><img src="{{ url('/assets/image/google_play.png') }}" alt="" class="img-fluid"></a>
                        <a href="javascript:"><img src="{{ url('/assets/image/apple_stor.png') }}" alt="" class="img-fluid"></a>
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
            <li><a href="javascript:" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="javascript:" target="_blank"><i class="fab fa-twitter"></i></a></li>
            <li><a href="javascript:" target="_blank"><i class="fab fa-instagram"></i></a></li>
            <li><a href="javascript:" target="_blank"><i class="fab fa-google-plus"></i></a></li>
        </ul>
    </div>
    <!-- share icon -->

@stop

@section('front-script')
    <script>
        localStorage.removeItem('search_location');
    </script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBmofwXO4eBxqJY_GxcWJqoVtUnb4GtQAs&libraries=places&sensor=false"></script>
    <script src="{{ url('/common/map/custom-map.js') }}"></script>
    <script>
        $(function () {
            navigator.geolocation.getCurrentPosition(function (position) {
                    getUserAddressBy(position.coords.latitude, position.coords.longitude)
                },
                function (error) {
                    alert("The Locator was denied :( Please add your address manually")
                });
            function getUserAddressBy(lat, long) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var address = JSON.parse(this.responseText);
                        setAddressToInputField(address.results[0].formatted_address);
                        place = address.results[0];
                    }
                };
                xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long + "&key=AIzaSyBmofwXO4eBxqJY_GxcWJqoVtUnb4GtQAs", true);
                xhttp.send();
            }
            function setAddressToInputField(address) {
                var input = document.getElementById("search_location");
                input.value = address;
            }
        });
    </script>
@stop

