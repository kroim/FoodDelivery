@php
    use App\Traits\BaseTrait;
@endphp
<aside class="sidebar ">
    <div class="scrollbar">
        <div class="user">
            <div class="user__info">
                <img class="user__img" src="{{ (Auth::user()->avatar != "")?url(Auth::user()->avatar):url('/uploads/avatar/avatar-default-icon.png') }}" alt="">
                <div style="word-break: break-all">
                    <div class="user__name">{{ Auth::user()->name }}</div>
                    <div class="user__email">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>
        <ul class="navigation">

            <li class="{{ ($sidebar['menu'] == 'my_account')?'navigation__active':'' }}">
                <a href="{{ url('/user/my-account') }}"><i class="zwicon-user-circle"></i> {{__('global.side.my_account')}}</a>
            </li>
            @if(Auth::user()->role != 5 && Auth::user()->role != 6)
                <li class="navigation__sub {{ ($sidebar['menu'] == 'user_management')?'navigation__sub--active':'' }}">
                    <a href="javascript:"><i class="zwicon-users"></i> {{__('global.side.user_management')}} <i class="zwicon-arrow-down"></i></a>
                    <ul>
                        @if(Auth::user()->role == 1)
                            <li class="{{ ($sidebar['sub_menu'] == 'change_role')?'navigation__active':'' }}">
                                <a href="{{ url('/user/change-role') }}"> {{__('global.common.change').' '.__('global.common.role')}}</a>
                            </li>
                            <li class="{{ ($sidebar['sub_menu'] == 'admin')?'navigation__active':'' }}">
                                <a href="{{ url('/user/admins') }}"> {{__('global.side.admins')}}</a>
                            </li>
                        @endif

                        @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                            <li class="{{ ($sidebar['sub_menu'] == 'co_admin')?'navigation__active':'' }}">
                                <a href="{{ url('/user/co-admins') }}"> {{__('global.side.editors')}}</a>
                            </li>
                        @endif

                        @if(Auth::user()->role < 3 || BaseTrait::checkPermission('owner_add') || BaseTrait::checkPermission('owner_edit')
                         || BaseTrait::checkPermission('owner_remove') || BaseTrait::checkPermission('owner_activity'))
                            <li class="{{ ($sidebar['sub_menu'] == 'owner')?'navigation__active':'' }}">
                                <a href="{{ url('/user/owners') }}"> {{__('global.side.owners')}}</a>
                            </li>
                        @endif
                        @if(Auth::user()->role < 3 || BaseTrait::checkPermission('driver_add') || BaseTrait::checkPermission('driver_edit') || BaseTrait::checkPermission('driver_remove'))
                            <li class="{{ ($sidebar['sub_menu'] == 'driver')?'navigation__active':'' }}">
                                <a href="{{ url('/user/drivers') }}"> {{__('global.side.drivers')}}</a>
                            </li>
                        @endif
                        @if(Auth::user()->role < 3 || BaseTrait::checkPermission('user_add') || BaseTrait::checkPermission('user_edit') || BaseTrait::checkPermission('user_remove'))
                            <li class="{{ ($sidebar['sub_menu'] == 'user')?'navigation__active':'' }}">
                                <a href="{{ url('/user/users') }}"> {{ __('global.side.users') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(Auth::user()->role < 3 || BaseTrait::checkPermission('category'))
                <li class="{{ ($sidebar['menu'] == 'categories')?'navigation__active':'' }}">
                    <a href="{{ url('/user/categories') }}"><i class="zwicon-layout-5"></i> {{__('global.side.categories')}}</a>
                </li>
            @endif

            @if(Auth::user()->role < 3 || BaseTrait::checkPermission('restaurant_add') || BaseTrait::checkPermission('restaurant_edit') || BaseTrait::checkPermission('restaurant_remove'))
                <li class="{{ ($sidebar['menu'] == 'restaurants')?'navigation__active':'' }}">
                    <a href="{{ url('/user/restaurants') }}"><i class="zwicon-command"></i> {{__('global.side.restaurants')}}</a>
                </li>
            @endif

            @if(Auth::user()->role < 3 || BaseTrait::checkPermission('menu_add') || BaseTrait::checkPermission('menu_edit') || BaseTrait::checkPermission('menu_remove'))
                <li class="{{ ($sidebar['menu'] == 'menus')?'navigation__active':'' }}">
                    <a href="{{ url('/user/menus') }}"><i class="zwicon-lan"></i> {{__('global.side.menus')}}</a>
                </li>
            @endif

            @if(Auth::user()->role < 3 || BaseTrait::checkPermission('u_message'))
                <li class="{{ ($sidebar['menu'] == 'message_users')?'navigation__active':'' }}">
                    <a href="{{ url('/user/message-to-users') }}"><i class="zwicon-chat"></i> {{__('global.side.user').' '.__('global.side.messages')}}</a>
                </li>
            @endif

            @if(Auth::user()->role < 3 || BaseTrait::checkPermission('o_message'))
                <li class="{{ ($sidebar['menu'] == 'message_owners')?'navigation__active':'' }}">
                    <a href="{{ url('/user/message-to-owners') }}"><i class="zwicon-comment"></i> {{__('global.side.owner').' '.__('global.side.messages')}}</a>
                </li>
            @endif

            {{--            <li class="{{ ($sidebar['menu'] == 'messages')?'navigation__active':'' }}">--}}
            {{--                <a href="{{ url('/user/messages') }}"><i class="zwicon-mail"></i> {{__('global.side.messages')}}</a>--}}
            {{--            </li>--}}

            @if(Auth::user()->role < 3 || Auth::user()->role == 2)
                <li class="{{ ($sidebar['menu'] == 'extras')?'navigation__active':'' }}">
                    <a href="{{ url('/user/extras') }}"><i class="zwicon-bell"></i> {{__('global.side.extras')}}</a>
                </li>
            @endif

            <li class="{{ ($sidebar['menu'] == 'orders')?'navigation__active':'' }}">
                <a href="{{ url('/user/orders') }}"><i class="zwicon-bill"></i> {{__('global.side.orders')}}</a>
            </li>

            @if(Auth::user()->role < 2)
                <li class="{{ ($sidebar['menu'] == 'commission')?'navigation__active':'' }}">
                    <a href="{{ url('/user/commission') }}"><i class="zwicon-receipt"></i> {{__('global.side.commission')}}</a>
                </li>
            @endif

            @if(Auth::user()->role <= 2)
                <li class="{{ ($sidebar['menu'] == 'countries')?'navigation__active':'' }}">
                    <a href="{{ url('/user/countries') }}"><i class="zwicon-flag"></i> {{__('global.side.countries')}}</a>
                </li>
            @endif

            @if(Auth::user()->role  <= 2 || Auth::user()->role == 4)
                <li class="navigation__sub {{ ($sidebar['menu'] == 'reports')?'navigation__sub--active':'' }}">
                    <a href="javascript:"><i class="zwicon-book"></i> {{__('global.side.reports')}} <i class="zwicon-arrow-down"></i></a>
                    <ul>
                        <li class="{{ ($sidebar['sub_menu'] == 'order_reports')?'navigation__active':'' }}">
                            <a href="{{ url('/user/reports/orders') }}"> {{ __('global.side.order_reports') }}</a>
                        </li>
                        <li class="{{ ($sidebar['sub_menu'] == 'restaurant_reports')?'navigation__active':'' }}">
                            <a href="{{ url('/user/reports/restaurants') }}"> {{ __('global.side.restaurant_reports') }}</a>
                        </li>
                        <li class="{{ ($sidebar['sub_menu'] == 'customer_reports')?'navigation__active':'' }}">
                            <a href="{{ url('/user/reports/customers') }}"> {{ __('global.side.customer_reports') }}</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li>
                <a href="javascript:" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="zwicon-arrow-circle-right"></i> {{ __('global.side.logout') }}</a>
            </li>
        </ul>
    </div>
</aside>
