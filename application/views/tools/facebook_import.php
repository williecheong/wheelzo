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
        <link rel="stylesheet" href="/assets/vendor/ng-quick-date/ng-quick-date.css">
        <link rel="stylesheet" href="/assets/vendor/ng-toaster/toaster.css">
    </head>
    <body>
        <toaster-container toaster-options="{'time-out': 5000}"></toaster-container>
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
                        <input type="text" class="form-control" placeholder="Facebook Access Token..." ng-model="accessToken" ng-init="accessToken='<?=$accessToken?>'" ng-disabled="loading">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" ng-click="retrievePosts(accessToken)" ng-disabled="loading">
                                <i class="fa fa-facebook-square fa-lg"></i>
                                Get rides
                            </button>
                        </span>
                    </div>
                    <p class="text-muted">
                        <i class="fa fa-clock-o"></i>
                        Token expires on <?=date('F d, Y', WHEELZO_FACEBOOK_ACCESS_TOKEN_EXPIRY)?>. Remind Willie to change it when this happens.
                    </p>
                    <!--
                    <a href="https://developers.facebook.com/tools/explorer" target="_blank">
                        <i class="fa fa-external-link"></i>
                        Get my facebook access token
                    </a>
                    -->
                </form>
            </div>
            <div class="well well-sm" ng-repeat="(key, posting) in postings" ng-init="  showActiveRides[key]                =   false;
                                                                                        posting.processedRide.origin        =   (posting.processedRide.origin)          ?   posting.processedRide.origin            :   ''      ;
                                                                                        posting.processedRide.destination   =   (posting.processedRide.destination)     ?   posting.processedRide.destination       :   ''      ;
                                                                                        posting.processedRide.price         =   (posting.processedRide.price)           ?   posting.processedRide.price             :   '10'    ;
                                                                                        posting.processedRide.capacity      =   (posting.processedRide.capacity)        ?   posting.processedRide.capacity          :   '2'     ;
                                                                                        posting.processedRide.departure     =   (posting.processedRide.departure)       ?   Date(posting.processedRide.departure)   :   ''      ;" >
                <div class="row">
                    <div class="col-md-5">
                        <div class="well">
                            <div class="media">
                                <a class="pull-left" target="_blank" href="{{ posting.id | facebookPostLink }}">
                                    <img class="img-rounded" ng-src="{{ posting.from.id | facebookImage}}">
                                </a>
                                <div class="media-body">
                                    <div>
                                        <a target="_blank" href="{{ posting.id | facebookPostLink }}">
                                            <strong ng-bind="posting.from.name"></strong>
                                        </a>
                                        <a href="" class="label label-warning" tooltip-html-unsafe="<i class='fa fa-exclamation-triangle'></i> Potential Duplicate" ng-click="showActiveRides[key]=!showActiveRides[key]" ng-if="posting.activeRides">
                                            {{ posting.activeRides.length }} active ride found
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
                                <input type="text" class="form-control" placeholder="Place of origin" ng-model="posting.processedRide.origin" typeahead="place for place in defaultSuggestedPlaces | filter:$viewValue | limitTo:8" ng-disabled="loading">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon" tooltip="TO">
                                    <i class="fa fa-sign-in"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Destination place" ng-model="posting.processedRide.destination" typeahead="place for place in defaultSuggestedPlaces | filter:$viewValue | limitTo:8" ng-disabled="loading">
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon" tooltip="PRICE">
                                            <i class="fa fa-usd"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Price" ng-model="posting.processedRide.price" ng-disabled="loading" valid-number>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon" tooltip="CAPACITY">
                                            <i class="fa fa-users"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Capacity" ng-model="posting.processedRide.capacity" ng-disabled="loading" valid-number>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-control-static">
                                        <quick-datepicker ng-model="posting.processedRide.departure" label-format="MMM-d, EEEE @ h:mma" date-filter="onlyDatesInFutureDateFilter" disable-clear-button="true"></quick-datepicker>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div ng-if="posting.activeRides">
                    <table class="table table-bordered table-condensed" ng-hide="!showActiveRides[key]">
                        <thead>
                            <tr>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Departure</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="ride in posting.activeRides">
                                <td ng-bind="ride.origin"></td>
                                <td ng-bind="ride.destination"></td>
                                <td ng-bind="ride.start | mysqlToISO | date:'MMM-dd, EEEE @ h:mma'"></td>
                                <td>
                                    ${{ ride.price }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-6" ng-hide="showForget">
                        <button class="btn btn-danger btn-block" ng-click="showForget=true">
                            <i class="fa fa-times"></i>
                            FORGET POSTING
                        </button>
                    </div>
                    <div class="col-md-6" ng-hide="!showForget">
                        <button class="btn btn-danger btn-block" ng-click="forgetRide(posting, key)">
                            <i class="fa fa-exclamation-triangle"></i>
                            Really forget posting?
                        </button>
                    </div>
                    <div class="col-md-6" ng-hide="showImport">
                        <button class="btn btn-success btn-block" ng-click="showImport=true">
                            <i class="fa fa-check"></i>
                            IMPORT RIDE
                        </button>
                    </div>
                    <div class="col-md-6" ng-hide="!showImport">
                        <button class="btn btn-success btn-block" ng-click="importRide(posting, key)">
                            <i class="fa fa-exclamation-triangle"></i>
                            Really import ride?
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-animate.min.js"></script>
        <script src="/assets/vendor/ng-quick-date/ng-quick-date.min.js"></script>
        <script src="/assets/vendor/ng-toaster/toaster.js"></script>
        <script src="/assets/js/constants.js"></script>
        <script src="/assets/js/angular/facebook-import.js"></script>
    </body>
</html>
