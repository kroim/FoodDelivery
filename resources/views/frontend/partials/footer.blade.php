<!-- footer start -->
<section id="footer">
    <div class="container">
        <div class="row text-center text-xs-center text-sm-left text-md-left">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <h5>Secure Payments with</h5>
                <p><a href="javascript:"><img src="{{ url('/assets/image/credit-card.png') }}" alt="" class="img-fluid credit_cards"></a></p>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <h5>About</h5>
                <ul class="list-unstyled quick-links">
                    <li><a href="{{ url('/about-us') }}">{{ __('global.side.about') }}</a></li>
                    <li><a href="{{ url('/faq') }}">{{ __('global.side.faq') }}</a></li>
                    <li><a href="{{ url('/contact-us') }}">{{ __('global.side.contact_us') }}</a></li>
                    @if(Auth::check())
                        <li><a href="{{ url('/user/my-account') }}">{{ __('global.side.my_account') }}</a></li>
                    @else
                        <li><a href="javascript:" onclick="user_login()">{{ __('global.side.login') }}</a></li>
                        <li><a href="javascript:" onclick="user_register()">{{ __('global.common.register') }}</a></li>
                    @endif
                    <li><a href="{{ url('/terms-conditions') }}">{{ __('global.side.terms_conditions') }}</a></li>
                </ul>

            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <h5>Newsletter</h5>
                <p>Join our newsletter to keep be informed News.</p>
                <form action="javascript:" method="post" id="subscribe-from">
                    <input type="email" name="" class="form-control" placeholder="Enter the Mail" required>
                    <button type="submit" class="btn btn-danger">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
                <ul class="list-unstyled list-inline social text-center">
                    <li class="list-inline-item"><a href="javascript:"><i class="fab fa-facebook-f"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:"><i class="fab fa-twitter"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:"><i class="fab fa-instagram"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:"><i class="fab fa-google-plus"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:" target="_blank"><i class="fa fa-envelope"></i></a></li>
                </ul>
            </div>
            </hr>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                <p class="_font_09">Larry Food &copy <span class="year_st"></span> All right Reversed.</p>
            </div>
            </hr>
        </div>
    </div>
    <div class="custom-alert"></div>
</section>
