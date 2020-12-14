<!DOCTYPE html>
<html lang="en">
@include('backend.partials.head')
@yield('back-style')
<body data-sa-theme="10">
@include('backend.partials.header')
@include('backend.partials.sidebar')

@yield('back-content')

@include('backend.partials.footer')
@include('backend.partials.foot')

@yield('back-script')

</body>
</html>
