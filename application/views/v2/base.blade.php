<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Better rideshare and carpooling for people around the University of Waterloo">
        <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
        <link rel="shortcut icon" href="/assets/img/{{ENVIRONMENT}}.ico" type="image/x-icon">
        <link rel="icon" href="/assets/img/{{ENVIRONMENT}}.ico" type="image/x-icon">    
        <title>@yield('title')</title>
        <link rel="image_src"  href="/assets/img/logo.png">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/vendor/v2/theme-dashgum/css/style.css">
        <link rel="stylesheet" href="/assets/vendor/v2/theme-dashgum/css/style-responsive.css">
        <link rel="stylesheet" href="/assets/vendor/v2/ng-quick-date/ng-quick-date.css">
        <link rel="stylesheet" href="/assets/vendor/v2/ng-toaster/toaster.css">
        <link rel="stylesheet" href="/assets/css/v2/helpers.css">        
        @yield('custom_css')
        <style>
            table[show-meridian] tbody tr:first-of-type,
            table[show-meridian] tbody tr:last-of-type {
                display: none;
            }
            ul[datepicker-popup-wrap] {
                min-width: 250px;
            }
        </style>

        <script src="/assets/vendor/v2/theme-dashgum/js/chart-master/Chart.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <toaster-container toaster-options="{'time-out': 5000}"></toaster-container>
        <section id="container" >
            <header class="header black-bg">
                <a href="/" class="logo">
                    <img class="brand" src="/assets/img/logo-light.png">
                </a>
                @yield('top_search_bar')
            </header>
            <aside>
                <div id="sidebar" class="nav-collapse">
                    <ul class="sidebar-menu" id="nav-accordion">
                  	    <p class="centered">
                            <a href="profile.html">
                                <img src="/assets/img/empty_user.png" class="img-circle" width="110">
                            </a> 
                        </p>
                        <h5 class="centered">
                            Anonymous
                        </h5>
                        @yield('side_search_bar')
                        <li class="">
                            <a href="">
                                <?php //$exploded_name = explode(' ', $users[$session]['name'] ); ?>
                                <i class="fa fa-home fa-lg"></i> 
                                My Profile
                            </a>
                        </li>
                        <li class="">
                            <a href="">
                                <i class="fa fa-group fa-lg"></i> 
                                User Lookup
                            </a>
                        </li>
                        <li class="">
                            <a href="">
                                <i class="fa fa-bullhorn"></i> 
                                Request a Ride
                            </a>
                        </li>
                        <li class="">
                            <a href="">
                                <i class="fa fa-tachometer fa-lg"></i> 
                                Post a Ride
                            </a>
                        </li>
                        <li class="">
                            <a href="">
                                <i class="fa fa-facebook-square fa-lg"></i> 
                                Sign in
                            </a>
                        </li>
                    </ul><!-- sidebar menu end-->
                </div>
            </aside><!--sidebar end-->
      
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper site-min-height">
                  	<h3>
                        <i class="fa fa-angle-right"></i>
                        Discover Our Panels
                    </h3>
                  	<div class="row mt">
                  		<div class="col-lg-12">	
                  		</div>
                  	</div>
        		</section>
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            
            <!--footer start-->
            <footer class="site-footer">
                <div class="text-right mRight15">
                    <div>
                        <i class="fa fa-lock"></i> 
                        Verified secure by
                        <a href="https://www.comodo.com/" title="SSL Secured By COMODO CA">ComodoCA</a>
                    </div>
                    <div>
                        <i class="fa fa-copyright"></i>
                        Wheelzo v{{ CURRENT_VERSION }}
                    </div>
                </div>
            </footer><!--footer end-->
        </section>

        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-animate.min.js"></script>
        <script src="/assets/vendor/v2/ng-quick-date/ng-quick-date.min.js"></script>
        <script src="/assets/vendor/v2/ng-toaster/toaster.js"></script>   
    </body>
</html>
