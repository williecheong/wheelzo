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
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans&subset=latin,latin-ext,cyrillic,cyrillic-ext">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
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
                        <div class="right-inner-addon">
                            <i class="fa fa-search" title="Search fields include drivers' names and drop-off locations"></i>
                            <input type="text" class="form-control" id="search-box" placeholder=@yield('search_placeholder')>
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
                            <li class="@yield('my_rides')"> 
                                <a class="pull-left" href="/me" title="My Rides">
                                    <?php $exploded_name = explode(' ', $users[$session]['name'] ); ?>
                                    <i class="fa fa-user fa-lg"></i> {{ $exploded_name[0] }}
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
                                <a class="pull-left" title="My Rides" href="{{ $session_url }}">
                                    <i class="fa fa-user fa-lg"></i> My Rides
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
        </div>

        <div class="container">
            <!-- Footer -->
            <footer>
                <hr>
                <div>
                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style pull-right hidden-xs" addthis:url="{{base_url()}}" og:url="{{$_SERVER['SERVER_NAME']}}">
                        <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                        <a class="addthis_button_tweet" tw:counturl="{{$_SERVER['SERVER_NAME']}}"></a>
                    </div>
                    <!-- AddThis Button END -->
                    &copy; Wheelzo v{{ CURRENT_VERSION }} - 
                    Made by <a href="#" title="<i class='fa fa-coffee fa-lg'></i> Contact us" data-toggle="modal" data-target="#write-feedback">nerds</a> from <a href="//uwaterloo.ca" title="University of Waterloo">uWaterloo</a>
                    <br>
                    <small>
                        <i class="fa fa-lock"></i> Verified secure by <a href="http://www.startssl.com/" title="SSL Secured By StartCom">StartSSL</a>
                    </small>
                </div>
                <br>
                <br>
            </footer><!-- /.footer -->
        </div><!-- /container -->
    </body>

    @include('modals/main')
    @include('modals/feedback')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="/assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/qtip2/2.2.0/jquery.qtip.min.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-535c72ab7e2385c1" type="text/javascript"></script>
    <script src="/assets/vendor/typeahead/bootstrap3-typeahead.js"></script>
    <script src="/assets/vendor/timepicker/jquery-ui-timepicker-addon.js"></script>
    <script src="/assets/vendor/timepicker/jquery-ui-sliderAccess.js"></script>
    <script src="/assets/vendor/moment/moment.min.js"></script>
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
    </script>
    
    @if ( ENVIRONMENT == 'production' )
        <!-- Google Analytics -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-50068607-1', 'wheelzo.com');
            ga('send', 'pageview');
        </script>
    @endif

</html>




