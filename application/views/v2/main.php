<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/assets/img/<?= ENVIRONMENT ?>.ico" type="image/x-icon">
        <link rel="icon" href="/assets/img/<?= ENVIRONMENT ?>.ico" type="image/x-icon">
        <link rel="image_src"  href="/assets/img/logo.png">
        <meta name="viewport" content="width=device-width">
        
        <title>
            Wheelzo
            <?= ( ENVIRONMENT != 'production' ) ? " :: " . ucfirst(ENVIRONMENT) : "" ; ?>
        </title>
        <meta name="description" content="Better rideshare and carpooling for people around the University of Waterloo">
        
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/css/v2/main.css">
        
        <script src="/assets/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body ng-app="myApp" ng-controller="myController">
        <div class="container">
            HELLO WORLD
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-animate.min.js"></script>
        <script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-535c72ab7e2385c1"></script>
        <script src="/assets/vendor/ng-quick-date/ng-quick-date.min.js"></script>
        <script src="/assets/vendor/ng-toaster/toaster.js"></script>
        <script src="/assets/js/constants.js"></script>
        <script src="/assets/js/angular/v2/main.js"></script>
        
        <? if ( ENVIRONMENT == 'production' ) { ?>
            <!-- Google Analytics -->
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', 'UA-50068607-1', 'wheelzo.com');
                ga('require', 'displayfeatures');
                ga('send', 'pageview');
            </script>
        <? } ?>
    </body>
</html>