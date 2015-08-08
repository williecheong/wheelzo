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
        <link rel="stylesheet" href="/assets/vendor/v2/ng-sweet-alert/sweet-alert.css">
        <link rel="stylesheet" href="/assets/vendor/v2/ng-toaster/toaster.css">
        <link rel="stylesheet" href="/assets/css/v2/helpers.css">        
        @yield('custom_css')

        <script src="/assets/vendor/v2/theme-dashgum/js/chart-master/Chart.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body ng-app="myApp" ng-controller="myController">
        <toaster-container toaster-options="{'time-out': 5000}"></toaster-container>
        <section id="container" >
            <header class="header black-bg">
                <a href="/" class="logo">
                    <img class="brand" src="/assets/img/logo-light.png">
                </a>
                @yield('top_search_bar')
                <div class="top-menu pull-right mTop15 visible-xs">
                    <button ng-init="isCollapsed=true" ng-click="isCollapsed=!isCollapsed" style="background:#D3D6D4;" class="btn">
                        <i class="fa fa-bars"></i>   
                    </button>
                </div>
            </header>
            <aside>
                <div ng-class="{ 'hidden-xs' : isCollapsed }" id="sidebar" class="nav-collapse">
                    <ul class="sidebar-menu" id="nav-accordion">
                  	    <p class="centered hidden-xs">
                            <a ng-click="openReviewModal(session.user_id)" ng-if="isActive()" href="">
                                <img src="<% session.user.facebook_id | fbImage %>" class="img-circle hoverable8" width="80">
                            </a>
                            <img ng-if="!isActive()" src="/assets/img/empty_user.png" class="img-circle opaque8" width="80">
                        </p>
                        <h5 class="centered hidden-xs">
                            <span ng-if="isActive() && session.user.name" ng-bind="session.user.name"></span>
                            <span ng-if="isActive() && !session.user.name">
                                <i class="fa fa-cog fa-spin"></i> Loading...
                            </span>
                            <span ng-if="!isActive()" ng-bind="'Anonymous'"></span>
                        </h5>
                        @yield('side_search_bar')
                        
                        <hr class="opaque5 mTop5 mBottom5 mLeft20 mRight20 hidden-xs">
                        
                        <li class="">
                            <a ng-if="isActive()" ng-click="openModal('drive', 'lg')" href="">
                                <i class="fa fa-tachometer fa-lg"></i> Post a Ride
                            </a>
                            <a ng-if="!isActive()" href="<% session.facebook_url %>">
                                <i class="fa fa-tachometer fa-lg"></i> Post a Ride
                            </a>
                        </li>
                        <li class="">
                            <a href="<% isActive() ? '/lookup' : session.facebook_url %>">
                                <i class="fa fa-group fa-lg"></i> User Lookup
                            </a>
                        </li>
                        <li class="">
                            <a ng-if="isActive()" ng-click="openModal('rrequest')" href="">
                                <i class="fa fa-bullhorn"></i> Request a Ride
                            </a>
                            <a ng-if="!isActive()" href="<% session.facebook_url %>">
                                <i class="fa fa-bullhorn"></i> Request a Ride
                            </a>
                        </li>

                        <hr class="opaque5 mTop5 mBottom5 mLeft20 mRight20">

                        <li class="">
                            <a ng-click="openModal('about', 'lg')" href="">
                                <i class="fa fa-newspaper-o fa-lg"></i> About Wheelzo
                            </a>
                        </li>                        
                        <li  ng-if="isActive()" class="">
                            <a ng-click="openReviewModal(session.user_id)" class="visible-xs" href="">
                                <i class="fa fa-get-pocket fa-lg"></i> My Reviews
                            </a>
                        </li>                    
                        <li class="">
                            <a href="<% isActive() ? '/me' : session.facebook_url %>">
                                <i class="fa fa-car fa-lg"></i> My Rides
                            </a>
                        </li>
                        <li class="">
                            <a href="<% session.facebook_url %>">
                                <span ng-if="isActive()">
                                    <i class="fa fa-sign-out fa-lg"></i> 
                                    Sign out
                                </span>
                                <span ng-if="!isActive()">
                                    <i class="fa fa-facebook-square fa-lg"></i> 
                                    Sign in
                                </span>
                            </a>
                        </li>
                    </ul><!-- sidebar menu end-->
                </div>
            </aside><!--sidebar end-->
      
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper site-min-height">
                    @yield('main_body')
                </section>
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            
            <!--footer start-->
            <footer class="site-footer">
                <div class="text-right mRight15">
                    <small>
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:info@wheelzo.com">Contact Us</a>
                        &middot;
                        <i class="fa fa-copyright"></i>
                        Wheelzo v{{ CURRENT_VERSION }}
                    </small>
                    <br>
                    <small class="">
                        <i class="fa fa-check-square"></i> 
                        Verified secure by
                        <a href="https://www.comodo.com/" title="SSL Secured By COMODO CA">ComodoCA</a>
                    </small>
                </div>
            </footer><!--footer end-->
        </section>

        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.2/ui-bootstrap-tpls.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.3/angular-animate.min.js"></script>
        <script src="//js.stripe.com/v2/" type="text/javascript"></script>
        <script src="//checkout.stripe.com/checkout.js"></script>
        <script src="/assets/vendor/v2/ng-quick-date/ng-quick-date.min.js"></script>
        <script src="/assets/vendor/v2/ng-linkify/angular-linkify.min.js"></script>
        <script src="/assets/vendor/v2/ng-sweet-alert/sweet-alert.js"></script>
        <script src="/assets/vendor/v2/ng-timeago/ng-timeago.js"></script>
        <script src="/assets/vendor/v2/ng-toaster/toaster.js"></script>
        <script src="/assets/js/v2/constants.js"></script>
        <script src="/assets/js/v2/base.js"></script>
        <script>
            var $wheelzo = {
                'autoQueryRide' : {{ $request_ride_id ? '"'. $request_ride_id .'"' : "false" }},
                'autoQueryUser' : {{ $request_user_id ? '"'. $request_user_id .'"' : "false" }},
                'stripePublicKey' : '{{ WHEELZO_STRIPE_PUBLIC_KEY }}'
            };
        </script>
        @yield('custom_js')
        
        @include('v2/ng-modals/about')
        @include('v2/ng-modals/ride')
        @include('v2/ng-modals/drive')
        @include('v2/ng-modals/review')
        @include('v2/ng-modals/rrequest')

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
