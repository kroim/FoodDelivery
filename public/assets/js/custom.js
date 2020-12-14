$(function () {
    "use strict";

    $('.language-list .language').on('click', function () {
        var imgUrl = $(this).find('img').attr('src');
        $('.selected-language').find('img').attr('src', imgUrl);
    });

    $('#search_location').on('click', function () {
        $(this).parent().find('img').addClass('show');
    });


    var winHeight = $(window).height();
    var totalHeight = winHeight + 250;
    var winWidth = $(window).width();

    if (winWidth >= 576) {
        if (totalHeight) {
            $(document).scroll(function () {
                var windowSr = $(window).scroll();
                if ($(windowSr).scrollTop() >= 250) {
                    $('nav.navbar').addClass('fixed_nav nav_in');
                    $('.scroll_top, .show_share_icon').fadeIn(500);
                } else {
                    $('nav.navbar').removeClass('fixed_nav nav_in');
                    $('.scroll_top, .show_share_icon').fadeOut(500);
                }
            });
        }
    }

    $('.scroll_top').on('click', function () {
        $('html, body').animate({scrollTop: 0}, 800);
    });

    $('.nav-menu-icon').on('click', function (e) {
        $('.main_nav').addClass('show').removeClass('hide');
        e.stopPropagation();
    });

    $('.main_nav.side_nav ').on('click', function (event) {
        event.stopPropagation();
    });

    $(document).click(function () {
        $('.main_nav').addClass('hide').removeClass('show');
    });

    window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);


    $('.add_to_fav').on('click', function () {
        var imgIcon = $(this).find('img');
        if ($(this).hasClass('fav_like')) {
            imgIcon.removeAttr('src');
            imgIcon.attr('src', '/assets/image/de-like.svg');
            $(this).removeClass('fav_like');
        } else {

            imgIcon.attr('src', '/assets/image/fav_like.svg');
            $(this).addClass('fav_like');
        }
    });

    $('.restaurant_info').on('click', function () {
        $('#restaurants_info').modal('show');
    });


    $('#voucher').on('change', function () {
        if ($(this).is(':checked')) {
            $('.voucher_wrapper').show(800);
        } else {
            $('.voucher_wrapper').hide(500);
        }
    });

    new WOW().init();

    var siteYear = new Date().getFullYear();
    $(".year_st").text(siteYear);


    $('#showFilter').on('click', function () {

        $('.m_filter_wrapper').addClass('show');

    });

    $('.back_ft').on('click', function () {
        $('.m_filter_wrapper').removeClass('show');
    });


    $('.add-order').on('click', function () {
        $('.order_n_step').addClass('show');
    });

});
