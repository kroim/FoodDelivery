<div class="page-loader">
    <div class="page-loader__spinner">
        <svg viewBox="25 25 50 50">
            <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
</div>
<style>
    .top-nav>li>a:not(.header__nav__text) {
        padding: .6rem 1rem;
    }
    .top-nav label {
        padding-left: 5px;
        text-transform: uppercase;
    }
</style>

<header class="header">
    <div class="navigation-trigger d-xl-none" data-sa-action="aside-open" data-sa-target=".sidebar">
        <i class="zwicon-hamburger-menu"></i>
    </div>

    <div class="logo d-sm-inline-flex">
        <a href="/"><img src="{{ url('/assets/image/logo2.png') }}" width="170"></a>
    </div>

    <ul class="top-nav">
        <li class="d-none d-sm-inline-block">
            <a href="javascript:" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="zwicon-sign-out"></i><label>{{__('global.side.logout')}}</label>
            </a>
        </li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</header>
