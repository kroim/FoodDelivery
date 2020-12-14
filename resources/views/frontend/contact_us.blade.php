@extends('layouts.front_layout')
@section('front-style')
    <style>
        .panel-default {
            padding: 5% 0;
        }
        .panel {
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
                        <h1 style="text-align: center; color: white; font-weight: bolder;">CONTACT US</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="panel">
            <form class="form" method="post" id="contact_form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Name*</label>
                    <input class="form-control" type="text" name="contact_name" required>
                </div>

                <div class="form-group">
                    <label>Email*</label>
                    <input class="form-control" type="email" name="contact_email" required>
                </div>

                <div class="form-group">
                    <label>Phone number (optional)</label>
                    <input class="form-control" type="text" name="contact_phone">
                </div>

                <div class="form-group">
                    <label>How can we help you?*</label>
                    <input class="form-control" type="text" name="contact_title" required>
                </div>

                <div class="form-group">
                    <label>Message*</label>
                    <textarea class="form-control" rows="5" name="contact_message" required></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger" name="contact_button">Send Message</button>
                </div>
            </form>
        </div>
    </div>
@stop
@section('front-script')
    <script>
        $('#contact_form').on('submit', function (e) {
            e.preventDefault();
            let name = $('input[name="contact_name"]').val();
            let email = $('input[name="contact_email"]').val();
            let phone = $('input[name="contact_phone"]').val();
            let subject = $('input[name="contact_title"]').val();
            let content = $('textarea[name="contact_message"]').val();
            let data = {
                _token: '<?php echo csrf_token(); ?>',
                name: name,
                email: email,
                phone: phone,
                subject: subject,
                content: content,
            };
            console.log(data);
            $.ajax({
                url: '/contact-us',
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                        customAlert(res.message, true);
                        $('input[name="contact_name"]').val('');
                        $('input[name="contact_email"]').val('');
                        $('input[name="contact_phone"]').val('');
                        $('input[name="contact_title"]').val('');
                        $('input[name="contact_message"]').val('');
                    } else customAlert(res.message);
                }
            })
        })
    </script>
@stop
