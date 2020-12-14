@extends('layouts.front_layout')
@section('front-style')
    <style>
        .panel {
            padding: 1%;
        }
        .panel-default {
            padding: 5%;
        }
    </style>
@stop
@section('front-content')
    <div style="background-image: url('{{ url('/assets/image/food-banner.jpg') }}')">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel-default">
                        <h1 style="text-align: center; color: white; font-weight: bolder;">Terms & Conditions</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="padding: 5%;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <h4 style="font-weight: bolder;">I. Acceptance of terms</h4>
                    <p>
                        Thank you for using Zomato. These Terms of Service (the "Terms") are intended to make you aware of your legal rights and responsibilities with respect to your access to and use of the Zomato website at www.zomato.com (the "Site") and any related mobile or software applications ("Zomato Platform") including but not limited to delivery of information via the website whether existing now or in the future that link to the Terms (collectively, the "Services").
                    </p>
                    <h6 style="font-weight: bolder;">These Terms are effective for all existing and future Zomato users, including but without limitation to users having access to 'restaurant business page' to manage their claimed business listings.</h6>
                    <ul>
                        <li>Clicking to accept or agree to the Terms, where it is made available to you by Zomato in the user interface for any particular Service; or</li>
                        <li>Actually using the Services. In this case, you understand and agree that Zomato will treat your use of the Services as acceptance of the Terms from that point onwards.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <h4 style="font-weight: bolder;">II. Definitions</h4>
                    <h6 style="font-weight: bolder;">User</h6>
                    <p>"User" or "You" or "Your" refers to you, as a user of the Services. A user is someone who accesses or uses the Services for the purpose of sharing, displaying, hosting, publishing, transacting, or uploading information or views or pictures and includes other persons jointly participating in using the Services including without limitation a user having access to 'restaurant business page' to manage claimed business listings or otherwise.</p>
                    <h6 style="font-weight: bolder;">Content</h6>
                    <p>"Content" will include (but is not limited to) reviews, images, photos, audio, video, location data, nearby places, and all other forms of information or data. "Your content" or "User Content" means content that you upload, share or transmit to, through or in connection with the Services, such as likes, ratings, reviews, images, photos, messages, profile information, and any other materials that you publicly display or displayed in your account profile. "Zomato Content" means content that Zomato creates and make available in connection with the Services including, but not limited to, visual interfaces, interactive features, graphics, design, compilation, computer code, products, software, aggregate ratings, reports and other usage-related data in connection with activities associated with your account and all other elements and components of the Services excluding Your Content and Third Party Content. "Third Party Content" means content that comes from parties other than Zomato or its users and is available on the Services.</p>
                    <h6 style="font-weight: bolder;">Restaurant(s)</h6>
                    <p>"Restaurant" means the restaurants listed on Zomato and any related mobile or software applications of Larry Food.</p>

                </div>
            </div>
        </div>
    </div>
@stop
@section('front-script')
    <script>

    </script>
@stop
