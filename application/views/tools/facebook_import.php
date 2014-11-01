<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html ng-app="myApp" ng-controller="myController"> <!--<![endif]-->
    <head>
        <title>Facebook Rides Import</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="icon" href="/assets/img/<?=ENVIRONMENT?>.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/assets/img/<?=ENVIRONMENT?>.ico" type="image/x-icon">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <h2>
                Import Facebook Rides
                <a href="/" target="_blank" tooltip="Back to main site" tooltip-placement="right">
                    <i class="fa fa-home"></i>
                </a>
            </h2>
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
                <div class="row">
                    <div class="col-md-5">
                        <div class="well">
                            <div class="media">
                                <a class="pull-left" target="_blank" href="{{  posting.id | facebookPostLink }}">
                                    <img class="img-rounded" src="{{ posting.from.id | facebookImage}}">
                                </a>
                                <div class="media-body">
                                    <div>
                                        <a target="_blank" href="{{  posting.id | facebookPostLink }}">
                                            <strong ng-bind="posting.from.name"></strong>
                                        </a>
                                    </div>
                                    <div class="text-muted">
                                        {{ posting.updated_time | date:'EEEE MMMM d, h:mm a' }}
                                        <a href="" tooltip="{{ posting.to.data[0].name }}">
                                            <i class="fa fa-university"></i>
                                        </a>
                                    </div>
                                    <div ng-bind="posting.message"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <form class="well well-sm">
                            <div class="input-group">
                                <span class="input-group-addon" tooltip="FROM">
                                    <i class="fa fa-sign-out"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Place of origin">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon" tooltip="TO">
                                    <i class="fa fa-sign-in"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Destination place">
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="input-group">
                                        <span class="input-group-addon" tooltip="DEPARTURE">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Departure">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon" tooltip="PRICE">
                                            <i class="fa fa-usd"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Price">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon" tooltip="CAPACITY">
                                            <i class="fa fa-users"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Capacity">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-danger btn-block" ng-click="forgetRide()">
                            <i class="fa fa-times"></i>
                            NOT A RIDE
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success btn-block" ng-click="importRide()">
                            <i class="fa fa-check"></i>
                            IMPORT RIDE
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
        <script src="/assets/js/angular/facebook_import.js"></script>
    </body>
</html>
