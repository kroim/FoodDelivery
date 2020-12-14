<!-- side nav start -->
<div class="main_nav side_nav hide">
    <div class="main_nav_header">
        @if(Auth::check())
            <div class="user_profile">
                <img src="{{ (Auth::user()->avatar != "")?url(Auth::user()->avatar):url('/uploads/avatar/avatar-default-icon.png') }}" alt="" class="img-fluid">
            </div>
            <div class="user_info">
                <p class="user_name">{{ Auth::user()->name }}</p>
                <p class="user_email">{{ Auth::user()->email }}</p>
            </div>
            <div class="log_in_wrapper">
                <a href="{{ url('/user/my-account') }}" class="btn btn-danger">{{ __('global.side.my_account') }}</a>
            </div>
        @else
            <div class="log_in_wrapper">
                <a href="javascript:" class="btn btn-danger" id="user_login" onclick="user_login()">{{ __('global.side.login') }}</a>
                <a href="javascript:" class="btn btn-default" id="user_register" onclick="user_register()">{{ __('global.common.create_an_account') }}</a>
            </div>
        @endif
    </div>
    <div class="nav-wrapper">
        <ul class="list-unstyled">
            <li><a href="{{ url('/user/orders') }}"><img src="{{ url('/assets/image/shopping-purse-icon.svg') }}" alt="" class="img-fluid nav_icon">{{ __('global.side.orders') }}</a></li>
            <li><a href="{{ url('/search/favorites') }}"><img src="{{ url('/assets/image/like.svg') }}" alt="" class="img-fluid nav_icon">{{ __('global.common.favourites') }}</a></li>
            <li><a href="{{ url('/about-us') }}"><img src="{{ url('/assets/image/store.svg') }}" alt="" class="img-fluid nav_icon">{{ __('global.side.about') }}</a></li>
            <li><a href="{{ url('/terms-conditions') }}"><img src="{{ url('/assets/image/seal.svg') }}" alt="" class="img-fluid nav_icon">{{ __('global.side.terms_conditions') }}</a></li>
            <li><a href="{{ url('/contact-us') }}"><img src="{{ url('/assets/image/info_icon.svg') }}" alt="" class="img-fluid nav_icon">{{ __('global.side.contact_us') }}</a></li>
        </ul>
    </div>
</div>
<!-- side nav End -->
<!-- Register modal -->
<div class="modal fade" id="register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('global.common.register') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="register_account" class="needs-validation" novalidate>
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">{{ __('global.common.name') }}</label>
                        <input type="text" class="form-control" id="register_name" placeholder="Enter Name" autocomplete="false" required/>
                        <div class="invalid-feedback">{{ __('global.errors.enter_name') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('global.common.email') }}</label>
                        <input type="email" class="form-control" id="register_email" placeholder="Enter Email address" autocomplete="false" required/>
                        <div class="invalid-feedback">{{ __('global.errors.enter_email') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('global.common.password') }}</label>
                        <input type="password" class="form-control" id="register_password" placeholder="Password" autocomplete="false" required/>
                        <div class="invalid-feedback">{{ __('global.errors.enter.password') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('global.common.confirm_password') }}</label>
                        <input type="password" class="form-control" id="register_confirm_password" placeholder="Confirm Password" autocomplete="false" required/>
                        <div class="invalid-feedback">{{ __('global.errors.enter.password') }}</div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">{{ __('global.common.submit') }}</button>
                        <p class="text-center">By clicking on submit you accept the terms of use and the <a href="javascript:"> terms of use</a> of the loyalty shop.</p>
                        <p class="mt-2 mb-2 text-center"><a href="javascript:" onclick="goToModal('login')">{{ __('global.errors.have_account') }}</a></p>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<!-- modal end -->

<!-- Login Modal -->
<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('global.side.login') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="login_account" class="needs-validation mt-4" novalidate>
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">{{ __('global.common.email') }}</label>
                        <input type="email" class="form-control" id="login_email" placeholder="Enter Email address" required>
                        <div class="invalid-feedback">{{ __('global.errors.enter_email') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('global.common.password') }}</label>
                        <input type="password" class="form-control" id="login_password" placeholder="Password" autocomplete="false" required>
                        <div class="invalid-feedback">{{ __('global.errors.enter_password') }}</div>
                        <p class="text-right mt-2 fr_gt"><a href="{{ url('/password/reset') }}">{{ __('global.errors.forgot_password') }}</a></p>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">{{ __('global.side.login') }}</button>
                        <p class="text-center">By clicking on submit you accept the terms of use and the <a href="{{ url('/terms-conditions') }}"> terms of use</a> of the loyalty shop.</p>

                        <p class="mt-2 mb-2 text-center"><a href="javascript:" onclick="goToModal('register')">{{ __('global.errors.have_not_account') }}</a></p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- modal end -->

<!-- Forgot Modal -->
<div class="modal fade" id="forgot_password_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="forgot_password_account" class="needs-validation mt-4" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" placeholder="Enter Email address" required>
                        <div class="invalid-feedback">
                            Please enter user email.
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">Send reset password</button>
                        <p class="mt-2 mb-2 text-center"><a href="javascript:" onclick="goToModal('forgot-login')">Go to login</a></p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    var auth_messages = [
        "{{ __('global.errors.name_empty') }}",  // 0
        "{{ __('global.errors.name_exist') }}",  // 1
        "{{ __('global.errors.email_empty') }}",  // 2
        "{{ __('global.errors.email_valid') }}",  // 3
        "{{ __('global.errors.email_exist') }}",  // 4
        "{{ __('global.errors.password_empty') }}",  // 5
        "{{ __('global.errors.password_wrong') }}",  // 6
        "{{ __('global.errors.password_length') }}",  // 7
        "{{ __('global.errors.confirm_password_empty') }}",  // 8
        "{{ __('global.errors.confirm_password_wrong') }}"  // 9
    ]
</script>
