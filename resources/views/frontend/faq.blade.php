@extends('layouts.front_layout')
@section('front-style')
    <style>
        .panel-default {
            padding: 5% 0;
        }
        .panel {
            padding: 5% 0;
        }
    </style>
@stop
@section('front-content')
    <div style="background-image: url('{{ url('/assets/image/food-banner.jpg') }}')">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel-default">
                        <h1 style="text-align: center; color: white; font-weight: bolder;">FAQ</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="panel">
            <ul>
                <li><b>I am new to all this. How do I get myself added as a blogger on Larry Food?</b></li>
                <p>It's really simple – please make sure you are logged in and fill out the form below. You will immediately be granted access to SpoonBack and you can start right away. We look forward to reading all about your foodie adventures!</p>
                <li><b>What exactly is a Larry Food?</b></li>
                <p>A Spoonback is an automatic link from a Larry Food restaurant page to your blog post. You review the restaurant on your blog, and we'll link to your post. The link appears in a special Blogs section on each Larry Food restaurant page, city feed, your Food Journey, your profile page, and your followers' feeds.</p>
                <li><b>Why would I want to use it?</b></li>
                <p>Here at Larry Food, we prominently display blogger reviews. If you use Spoonbacks, Larry Food users will see an excerpt from your review, and a link to click through to your blog. In short, you get exposure and traffic to your blog for free.</p>
                <li><b>How does it work?</b></li>
                <p>Once registered, all linked posts from your blog will show up automatically in the Blog Posts section of your profile on Larry Food as pending blog posts. Here, you can edit your post snippet, add a rating and photo(s) for the restaurant, and tag friends before publishing it to Larry Food. It is advisable to edit your post snippet on Larry Food to reflect the content related to the restaurant, as that would encourage viewers to be directed to your blog page.</p>
                <ul>
                    <li>Once you have registered, you can link blog posts to your Larry Food profile using the unique HTML widget codes we have for all restaurants. To locate this, you need to scroll to the end of a restaurant page and click on the option 'Are you a Food Blogger?'</li>
                    <li>From here, you can choose any one of the four available widget codes and paste it anywhere on your blogpost.</li>
                    <li>Once this is done, please refresh your blog post as this would initiate the process that would lead to your blogpost starting to appear under the 'Pending Blog Post' section (under the Blog Post section itself).</li>
                    <li>Now, you can edit your post snippet, add a rating, add photo(s) of the restaurant, and tag friends before publishing it to Larry Food. It is advisable to edit your post snippet on Larry Food to reflect the content related to the restaurant, as that would encourage viewers to be directed to your blog page. <b>Also, you'd be able to see the blog post section on your profile only after you have published your first blog post.</b></li>
                </ul>
                <li><b>How long will it take for my blog link to appear on Larry Food?</b></li>
                <p>Once you add a spoonback to your blog post, it will reflect on your Larry Food profile page under 'Pending Posts' within 24 hours. From here you can easily add a rating, photos, tag friends and publish your blog post on Larry Food. If it has been significantly longer than that, please write to us at domain@domain.com and we'll investigate. It's helpful if you can provide us with links to the blog posts in question.</p>
            </ul>
        </div>
    </div>
@stop
@section('front-script')
    <script>

    </script>
@stop
