<!-- start nav -->
<nav class="navbar navbar-expand-lg navbar-light bg-pink">
    <div class="container">
        <a class="navbar-brand" href="/"><img src="{{ url('/assets/image/site_logo_1.svg') }}" class="img-fluid logo"></a>
        <ul class="navbar-nav mr-4 ml-auto">
            <li class="nav-item dropdown ml-auto mr-0">
                @if(\App::getLocale() == 'de')
                    <a class="nav-link dropdown-toggle selected-language" href="javascript:" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ url('/assets/image/flags/germany.png') }}" alt="" class="img-fluid">
                    </a>
                @elseif(\App::getLocale() == 'tr')
                    <a class="nav-link dropdown-toggle selected-language" href="javascript:" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ url('/assets/image/flags/turkey.svg') }}" alt="" class="img-fluid">
                    </a>
                @else
                    <a class="nav-link dropdown-toggle selected-language" href="javascript:" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ url('/assets/image/flags/english.png') }}" alt="" class="img-fluid">
                    </a>
                @endif
                <div class="dropdown-menu language-list" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item language" href="javascript:" onclick="select_lang('en')">
                        <img src="{{ url('/assets/image/flags/english.png') }}" alt="" class="img-fluid "> English</a>
                    <a class="dropdown-item language" href="javascript:" onclick="select_lang('de')">
                        <img src="{{ url('/assets/image/flags/germany.png') }}" alt="" class="img-fluid"> German</a>
                    <a class="dropdown-item language" href="javascript:" onclick="select_lang('tr')">
                        <img src="{{ url('/assets/image/flags/turkey.svg') }}" alt="" class="img-fluid"> Turkish</a>
                </div>
            </li>
        </ul>
        <button type="button" class="nav-menu-icon">
            <img src="{{ url('/assets/image/bar-ico.png') }}" class="img-fluid" alt="">
        </button>
    </div>
</nav>
<!-- end nav -->
<form id="locale_form" method="post" action="{{ url('/changelocale') }}" accept-charset="UTF-8" style="display: none">
    <input type="text" name="_token" value="{{ csrf_token() }}">
    <input type="text" name="locale" id="locale">
</form>
<script>
    function select_lang(item){
        console.log(item);
        $("#locale").val(item);
        $("#locale")[0].form.submit();
    }
</script>
