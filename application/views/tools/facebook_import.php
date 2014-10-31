<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html ng-app="myApp" ng-controller="myController"> <!--<![endif]-->
    <head>
        <title>Facebook Rides Import</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <h2>Import Facebook Rides</h2>
            <div class="row">
                <form class="col-lg-7">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Facebook Access Token..." ng-model="accessToken" ng-disabled="loading">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" ng-click="retrievePosts(accessToken)" ng-disabled="loading">
                                <i class="fa fa-facebook-square fa-lg"></i>
                                Get rides
                            </button>
                        </span>
                    </div>
                    <a href="https://developers.facebook.com/tools/explorer" target="_blank">
                        <i class="fa fa-external-link"></i>
                        Get my facebook access token
                    </a>
                </form>
            </div>
            <div class="well well-sm" ng-repeat="posting in postings">
                <pre ng-bind="posting | json"></pre>
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
        <script src="/assets/js/angular/facebook_import.js"></script>
    </body>
</html>
