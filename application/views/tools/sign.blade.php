<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html> <!--<![endif]-->
    <head>
        <title>Sign in</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Sign in to join the best rideshare community ever">
        <link rel="image_src"  href="/assets/img/logo.jpg">
        <link rel="icon" href="/assets/img/{{ENVIRONMENT}}.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/assets/img/{{ENVIRONMENT}}.ico" type="image/x-icon">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/css/v2/helpers.css">
        <link rel="stylesheet" href="/assets/css/sign/main.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <!-- Intro Header -->
        <header class="intro">
            <div class="intro-body">
                <div class="row text-center" id="main-content">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="row">
                        	<div class="col-sm-offset-4 col-sm-4">
		                        <a href="/">
			                        <img src="/assets/img/logo.png" class="img-responsive" style="width:100%;max-width:150px;margin:0 auto;">
    							</a>
        					</div>
    					</div>
    					<div>
	                        <strong class="intro-text">
	                        	<em>Keeping the rideshare community accountable</em>
	                        </strong>
                        </div>
                        <div class="" style="color:#D3D6D4;">
                            <i class="fa fa-hand-spock-o fa-lg"></i>
                            Wheelzo will never post anything to Facebook without your permission
                        </div>
                        <a class="btn btn-facebook btn-lg btn-wide hoverable9" href="{{ $this->facebook_url }}">
                            <i class="fa fa-facebook-square fa-lg"></i>
							Sign in with Facebook
                        </a>

                    </div>
                </div>
            </div>
        </header>

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



