<!DOCTYPE html>
<html lang="en">
@include('frontend.partials.head')
@yield('front-style')
<body>
@include('frontend.partials.header')
@include('frontend.partials.sidebar')

@yield('front-content')

@include('frontend.partials.footer')
@include('frontend.partials.foot')

@yield('front-script')

</body>
</html>
