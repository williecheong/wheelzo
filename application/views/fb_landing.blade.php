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
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/basic/jquery.qtip.min.css">
        <script src="/assets/vendor/v1/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="container">
            <div class="row">
                <div class="row text-center">
                    <div class="col-sm-12">
                        <a href="{{ $link }}" target="wheelzo_window">
                            <img src="/assets/img/logo.png" style="width:80%; max-width:400px;">
                        </a>
                    </div>
                    <div class="col-sm-12">
                        <h1>
                            <a href="{{ $link }}" target="wheelzo_window">Enter</a> Wheelzo v{{ CURRENT_VERSION }}                    
                        </h1>
                        <h4>
                            <a href="{{ $link }}">
                                <i class="fa fa-facebook-square"></i> Stay on Facebook
                            </a>                    
                        </h4>
                    </div>
                </div>                   
            </div>
            
            <!-- Footer -->
            <footer>
                <hr>
                <p class="text-center">
                    Made by <a href="#" title="<i class='fa fa-coffee fa-lg'></i> Contact us" data-toggle="modal" data-target="#write-feedback">nerds</a> from <a href="//uwaterloo.ca" title="University of Waterloo" target="uw_window">uWaterloo</a>
                    <br>
                    &copy; Wheelzo v{{ CURRENT_VERSION }}
                </p>
            </footer><!-- /.footer -->
        </div><!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/basic/jquery.qtip.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="/assets/vendor/v1/moment/moment.min.js"></script>
        @if ( ENVIRONMENT == 'production' ) <!-- Google Analytics -->
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