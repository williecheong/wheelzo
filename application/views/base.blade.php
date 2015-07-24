<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/assets/img/{{ENVIRONMENT}}.ico" type="image/x-icon">
        <link rel="icon" href="/assets/img/{{ENVIRONMENT}}.ico" type="image/x-icon">
        <title>@yield('title')</title>
        <link rel="image_src"  href="/assets/img/logo.png">
        <meta name="description" content="Better rideshare and carpooling for people around the University of Waterloo">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/qtip2/2.2.0/jquery.qtip.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
        <script src="/assets/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="navbar navbar-default" id="compact-bar">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <img class="brand" src="/assets/img/logo.png">
                    </a>
                    @yield('sub_title')
                </div>
                <div class="navbar-collapse collapse">
                    <div class="navbar-form navbar-left">
                        <div id="search-box">
                            <div class="row">
                                <div class="col-md-6" id="search-origin-box">
                                    <div class="right-inner-addon">
                                        <i class="fa fa-search visible-sm"></i>
                                        <input type="text" class="form-control" id="search-origin" placeholder="Leaving from ...">
                                    </div>
                                </div>
                                <div class="col-md-6  hidden-sm" id="search-destination-box">
                                    <div class="right-inner-addon">
                                        <i class="fa fa-search"></i>
                                        <input type="text" class="form-control" id="search-destination" placeholder="Going to ...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        @yield('left_navs')
                        @if ( $session ) 
                            <li>
                                <a class="pull-left" href="#" title="Start New Ride" data-toggle="modal" data-target="#create-ride">
                                    <i class="fa fa-tachometer fa-lg"></i> Drive
                                </a>
                            </li>
                            <li>
                                <a class="pull-left" href="#" title="User Lookup" data-toggle="modal" data-target="#lookup-users">
                                    <i class="fa fa-group fa-lg"></i> Lookup
                                </a>
                            </li>
                            <li class="@yield('my_rides')"> 
                                <a class="pull-left" href="/me" title="My Rides">
                                    <?php $exploded_name = explode(' ', $users[$session]['name'] ); ?>
                                    <i class="fa fa-home fa-lg"></i> {{ $exploded_name[0] }}
                                </a>
                            </li>
                            <li>
                                <a class="pull-left" id="facebook-session" href="{{ $session_url }}" style="color:#B94A48;">
                                    <i class="fa fa-sign-out fa-lg"></i> Sign out
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="pull-left" title="Start New Ride" href="{{ $session_url }}">
                                    <i class="fa fa-tachometer fa-lg"></i> Drive
                                </a>
                            </li>
                            <li>
                                <a class="pull-left" title="User Lookup" href="{{ $session_url }}">
                                    <i class="fa fa-group fa-lg"></i> Lookup
                                </a>
                            </li>
                            <li>
                                <a class="pull-left" title="My Rides" href="{{ $session_url }}">
                                    <i class="fa fa-home fa-lg"></i> My Rides
                                </a>
                            </li>
                            <li>
                                <a class="pull-left" id="facebook-session" href="{{ $session_url }}" style="color:#3B5999;">
                                    <i class="fa fa-facebook-square fa-lg"></i> Sign in
                                </a>
                            </li>
                        @endif
                        @yield('right_navs')
                    </ul>
                </div><!--/.navbar-collapse -->
            </div>
        </div>
        
        @yield('jumbotron')

        <div class="container">
            @yield('table')
            <p class="text-center">
                <small>
                    Can't find the ideal ride? <br>
                    Want notifications when a driver posts it? <br>
                    @if ( $session )  
                        <a class="btn btn-xs" href="#" data-toggle="modal" data-target="#create-request" style="background-color:#512673;padding:2px 20px;color:whitesmoke;">
                            <i class="fa fa-bullhorn"></i> 
                            Request a Ride
                        </a>
                    @else
                        <a class="btn btn-xs" href="{{ $session_url }}" style="background-color:#512673;padding:2px 20px;color:whitesmoke;">
                            <i class="fa fa-bullhorn fa-lg"></i> 
                            Request a Ride
                        </a>
                    @endif
                </small>
            </p>
        </div>

        <div class="container">
            <!-- Footer -->
            <footer>
                <hr>
                <div>
                    <div class="pull-right hidden-xs text-right">
                        <a class="" href="#" data-toggle="modal" data-target="#read-faq">
                            <i class="fa fa-info-circle"></i>
                            Frequently Asked Questions
                        </a>
                        <br>
                        <a class="btn btn-xs" href="https://facebook.com/wheelzo" style="background-color:#354C8C;color:whitesmoke;">
                            <i class="fa fa-facebook-square fa-lg"></i> 
                            Like Us
                        </a>
                        <a class="btn btn-default btn-xs" href="https://twitter.com/gowheelzo" style="color:#333;">
                            <i class="fa fa-twitter fa-lg" style="color:#4099FF;"></i> 
                            Follow
                        </a>
                    </div>
                    &copy; Wheelzo v{{ CURRENT_VERSION }} - 
                    Made by <a href="mailto:info@wheelzo.com" title="<i class='fa fa-coffee fa-lg'></i> Contact us">nerds</a> 
                    from <a href="//uwaterloo.ca" title="University of Waterloo">uWaterloo</a>
                    <br>
                    <small>
                        <i class="fa fa-lock"></i> 
                        Verified secure by 
                        <a href="https://www.comodo.com/" title="SSL Secured By COMODO CA">ComodoCA</a>
                    </small>
                </div>
                <br>
                <br>
            </footer><!-- /.footer -->
        </div><!-- /container -->

        @include('modals/main')
        @yield('custom_modals')
        @include('modals/feedback')

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
        <script src="//cdn.jsdelivr.net/qtip2/2.2.0/jquery.qtip.min.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="//js.stripe.com/v2/" type="text/javascript"></script>
        <script src="//checkout.stripe.com/checkout.js"></script>
        <script src="/assets/vendor/datatables/plugins/fnFilterAll.js"></script>
        <script src="/assets/vendor/typeahead/bootstrap3-typeahead.js"></script>
        <script src="/assets/vendor/timepicker/jquery-ui-timepicker-addon.js"></script>
        <script src="/assets/vendor/timepicker/jquery-ui-sliderAccess.js"></script>
        <script src="/assets/vendor/moment/moment.min.js"></script>
        <script src="/assets/js/constants.js"></script>
        <script src="/assets/js/initializer.js"></script>
        <script src="/assets/js/templates.js"></script>
        <script src="/assets/js/handlers.js"></script>
        <script src="/assets/js/helpers.js"></script>
        <script src="/assets/js/main.js"></script>
        <script>
            var rides = {{ json_encode($rides) }} ;
            var publicUsers = {{ json_encode($users) }} ;
            var session_id = {{ $session ? $session : 'false' }};
            var loadRide = {{ $request_ride_id ? '"'.encode_to_chinese($request_ride_id).'"' : "false" }};
            var loadUser = {{ $request_user_id ? '"'. $request_user_id .'"' : "false" }};
            var myRrequests = {{ json_encode($my_rrequests) }};
            var stripePublicKey = '{{ WHEELZO_STRIPE_PUBLIC_KEY }}';
        </script>
        
        @if ( ENVIRONMENT == 'production' ) <!-- Google Analytics -->
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', 'UA-50068607-1', 'wheelzo.com');
                ga('require', 'displayfeatures');
                ga('send', 'pageview');
            </script>
        @endif
    </body>
</html>