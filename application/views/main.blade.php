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
        <title>Wheelzo</title>
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
                </div>
                <div class="navbar-collapse collapse">
                	<div class="navbar-form navbar-left">
	                	<div class="right-inner-addon">
	                        <i class="fa fa-search" title="Search-able fields include drivers' names and drop-off locations"></i>
	                        <input type="text" class="form-control" id="search-box" placeholder="Search through all active rides on Wheelzo...">
	                	</div>
	                </div>
                    <div class="navbar-form navbar-right">
                        <div class="btn-group">
                        	@if ( $session ) 
	                            <a class="btn btn-default" href="/me" title="My Rides">
	                                <i class="fa fa-user fa-lg"></i>
	                            </a>
	                            <button class="btn btn-default" title="Start New Ride" data-toggle="modal" data-target="#create-ride">
	                                <i class="fa fa-plus-circle fa-lg"></i>
	                            </button>  
	                        @else
	                        	<a class="btn btn-default" title="My Rides" href="{{ $session_url }}">
	                                <i class="fa fa-user fa-lg"></i>
	                            </a>
	                            <a class="btn btn-default" title="Start New Ride" href="{{ $session_url }}">
	                                <i class="fa fa-plus-circle fa-lg"></i>
	                            </a>
	                        @endif
                        </div>
                        <div class="btn-group">
                            @if ( $session )
                                <a class="btn btn-danger" id="facebook-session" href="{{ $session_url }}" title="Logout">
                                    <i class="fa fa-sign-out fa-lg"></i> {{ $users[$session]['name'] }}
                                </a>
                            @else
                                <a class="btn btn-primary" id="facebook-session" href="{{ $session_url }}">
                                    <i class="fa fa-facebook-square fa-lg"></i> Login with Facebook
                                </a>
                            @endif  
                            <button class="btn btn-default" data-mytoggler="div#introduction,div#compact-bar">
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                </div><!--/.navbar-collapse -->
            </div>
        </div>
        
        {{--
        <div class="jumbotron" id="introduction">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <a href="/">
                            <img class="brand-logo" src="/assets/img/logo.png">
                        </a>
                    </div>
                    <div class="col-sm-10">
                        <h1>
                            <a id="toggle-getting-started" href="#">Together</a> on the road...
                        </h1>
                        <p>
                            Wheelzo v{{ CURRENT_VERSION }}
                            @if ( $session )
                                <a class="btn btn-danger btn-xs" id="facebook-session" href="{{ $session_url }}" title="Logout">
                                    <i class="fa fa-sign-out fa-lg"></i> {{ $users[$session]['name'] }}
                                </a>
                            @else
                                <a class="btn btn-primary btn-xs" id="facebook-session" href="{{ $session_url }}">
                                    <i class="fa fa-facebook-square fa-lg"></i> Login with Facebook
                                </a>
                            @endif
                            {{--
                            <a class="btn btn-link btn-xs pull-right" href="#" data-mytoggler="div#introduction,div#compact-bar">
                                <i class="fa fa-caret-up fa-lg"></i> Hide
                            </a>
                            --}}
                        </p>
                    </div>
                </div>
            </div>
        </div> 
        --}}

        <div class="container">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="origin">Origin</th>  
                            <th class="destination">Destination</th>  
                            <th class="departure">Departure</th> 
                            <th class="price">Price</th>
                            <th class="ninja-header">Driver</th>
                            <th class="ninja-header">Dropoffs</th>
                            <th class="ninja-header">Encoded ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $rides as $ride )
                            <tr data-ride-id="{{ $ride->id }}">
                                <td>{{ $ride->origin }}</td>
                                <td>
                                    {{ $ride->destination }} 
                                    <?php if ( count($ride->drop_offs) > 0 ) { ?> 
                                        <a href="#">
                                            <i class="fa fa-flag-checkered fa-border" title="{{count($ride->drop_offs)}} drop-off locations"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    {{ date( 'M j, l @ g:ia', strtotime($ride->start) ) }}
                                </td>
                                <td>
                                    @if ( $ride->driver_id == $session )
                                        <i class="fa fa-user"></i> Driver
                                    @elseif ( $ride->is_personal )
                                        <i class="fa fa-users"></i> Passenger
                                    @else                               
                                        ${{ $ride->price }}
                                    @endif
                                </td>
                                <td class="ninja-field">{{ $users[$ride->driver_id]['name'] }}</td>
                                <td class="ninja-field">{{ implode(', ', $ride->drop_offs) }}</td>
                                <td class="ninja-field">{{ encode_to_chinese($ride->id) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- Footer -->
            <footer>
                <hr>
                <p>
                    &copy; Wheelzo v{{ CURRENT_VERSION }} - 
                    Made by <a href="#" title="<i class='fa fa-coffee fa-lg'></i> Contact us" data-toggle="modal" data-target="#write-feedback">nerds</a> from <a href="//uwaterloo.ca" title="University of Waterloo">uWaterloo</a>
                    <br>
                    <small>
                        <i class="fa fa-lock"></i> Verified secure by <a href="http://www.startssl.com/" title="SSL Secured By StartCom">StartSSL</a>
                    </small>
                </p>
                <br>
                <br>
            </footer><!-- /.footer -->
        </div><!-- /container -->
        
        <?php 
            $this->load->view('modals/main',
                array(
                    'session' => $session,
                    'users' => $users
                )
            );

            $this->load->view('modals/feedback');
        ?>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="/assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.jsdelivr.net/qtip2/2.2.0/jquery.qtip.min.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
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
            <!-- AddThis Smart Layers BEGIN -->
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-5350e5067c994429"></script>
            <script type="text/javascript">
              addthis.layers({
                'theme' : 'transparent',
                'share' : {
                  'position' : 'right',
                  'services' : 'facebook,twitter,pinterest,reddit'
                }   
              });
            </script>
        @endif

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
    </body>
</html>




