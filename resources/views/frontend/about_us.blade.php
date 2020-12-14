@extends('layouts.front_layout')
@section('front-style')
    <style>
        .panel {
            padding: 5%;
        }
        .about-image {
            float: left;
        }
        .about-image img {
            max-width: 200px;
            max-height: 200px;
        }
        .about-text {
            float: left;
            padding: 5%;
        }
    </style>
@stop
@section('front-content')
    <div style="background-image: url('{{ url('/assets/image/food-banner.jpg') }}')">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <h1 style="text-align: center; color: white; font-weight: bolder;">ABOUT US</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="padding-bottom: 5%;">
        <div class="row">
            <div class="col-md12 col-md-offset-2">
                <div class="panel panel-default">
                    <h5>Larry Food</h5>
                    <p>Launched in Delhi 11 years ago, Zomato has grown from a home project to one of the largest food aggregators in the world. We are present in 24 countries and 10000+ cities globally, enabling our vision of better food for more people. We not only connect people to food in every context but work closely with restaurants to enable a sustainable ecosystem.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="about-image">
                        <img src="{{ url('/assets/image/food1.png') }}">
                    </div>
                    <div class="about-text">
                        <h4>Larry is his name</h4>
                        <h5>CEO</h5>
                        <h5>2019-12-27</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="about-image">
                        <img src="{{ url('/assets/image/food1.png') }}">
                    </div>
                    <div class="about-text">
                        <h4>Larry is his name</h4>
                        <h5>CEO</h5>
                        <h5>2019-12-27</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="about-image">
                        <img src="{{ url('/assets/image/food1.png') }}">
                    </div>
                    <div class="about-text">
                        <h4>Larry is his name</h4>
                        <h5>CEO</h5>
                        <h5>2019-12-27</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="about-image">
                        <img src="{{ url('/assets/image/food1.png') }}">
                    </div>
                    <div class="about-text">
                        <h4>Larry is his name</h4>
                        <h5>CEO</h5>
                        <h5>2019-12-27</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="about-image">
                        <img src="{{ url('/assets/image/food1.png') }}">
                    </div>
                    <div class="about-text">
                        <h4>Larry is his name</h4>
                        <h5>CEO</h5>
                        <h5>2019-12-27</h5>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
@section('front-script')
    <script>

    </script>
@stop
