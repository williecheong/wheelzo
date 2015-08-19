@layout('v2/base')

@section('title')
    Wheelzo
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
    @endif 
@endsection

@section('description')
    @if ($requested_ride) 
        <meta name="description" content="{{$requested_ride->driver_name}} is driving from {{$requested_ride->origin}} to {{$requested_ride->destination}} on {{date('M j, l', strtotime($requested_ride->start))}} @ {{date('g.ia', strtotime($requested_ride->start))}}">
        <link rel="image_src"  href="/assets/img/screenshot-ride_478x250.jpg">
    @elseif ($requested_user) 
        <meta name="description" content="Reviews about {{$requested_user->name}} from the rideshare community @ Wheelzo">
        <link rel="image_src"  href="/assets/img/screenshot-review_478x250.jpg">
    @else
        <meta name="description" content="Better rideshare and carpooling for people around Kitchener, Waterloo and the Greater Toronto Area">
        <link rel="image_src"  href="/assets/img/screenshot-main_478x250.jpg">
    @endif
@endsection

@section('custom_css')
    <link rel="stylesheet" href="/assets/css/v2/main.css">
@endsection

@section('top_search_bar')
    <div style="width:50%;" class="row top-menu pull-right mTop15 hidden-xs">
        <div class="col-xs-6">
            <input ng-model="inputSearchOrigin" ng-change="filterRides()" placeholder="Leaving from ..." class="form-control opaque9">
        </div>
        <div class="col-xs-6">
            <input ng-model="inputSearchDestination" ng-change="filterRides()" placeholder="Going to ..." class="form-control opaque9">
        </div>
    </div>
@endsection

@section('side_search_bar')
    <div class="row mTop15 mBottom5 mLeft5 mRight5 visible-xs">
        <div class="col-xs-12">
            <input ng-model="inputSearchOrigin" ng-change="filterRides()" placeholder="Leaving from ..." class="form-control opaque9">
        </div>
        <div class="col-xs-12">
            <input ng-model="inputSearchDestination" ng-change="filterRides()" placeholder="Going to ..." class="form-control opaque9">
        </div>
    </div>
@endsection

@section('main_body')
    <div class="row mTop20">
        <div ng-if="ridesByDate.length==0" class="col-md-12">
            <div class="well well-sm text-center">
                <div ng-if="session.user_id==0">
                    <h3 class="mBottom20">
                        No rides to display
                        <br> ¯\_(ツ)_/¯
                    </h3>
                </div>
                <div ng-if="session.user_id>0" class="row">
                    <div class="col-md-offset-4 col-md-4">
                       <div ng-click="openModal('drive', 'lg')" class="well well-lg mTop20 text-center clickable hoverable7" style="color:#333;border:dashed 8px;height:150px;">
                            <div class="mTop15">
                                <i class="fa fa-car fa-2x"></i>
                                <p class="lead">
                                    POST A RIDE
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div ng-if="ridesByDate.length>0" class="col-md-12">
            <h3 class="text-center mTop10 mBottom10">
                <span ng-if="ridesToDisplay.length==rides.length">
                    <span class="hidden-xs">
                        <i class="fa fa-star"></i>
                        Showing all active rides
                    </span>
                    <span class="visible-xs">
                        <i class="fa fa-star"></i>
                        All active rides
                    </span>
                </span> 
                <span ng-if="ridesToDisplay.length<rides.length">
                    Rides
                    <span ng-if="inputSearchOrigin.length>0">
                        from <% inputSearchOrigin | ucfirst %>
                    </span>
                    <span ng-if="inputSearchDestination.length>0">
                        to <% inputSearchDestination | ucfirst %>
                    </span>
                </span>
            </h3>
            <accordion>
                <accordion-group ng-repeat="(key, group) in ridesByDate" ng-init="isOpen=(key==0)" is-open="isOpen">
                    <accordion-heading ng-click="isOpen=!isOpen">
                        <div>
                            <a class="pull-right" href="">
                                <% group.rides.length %>
                                <% pluralize(group.rides.length, 'ride', 'rides') %>
                                <span class="hidden-xs">found</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <span class="hidden-xs">
                                <i class="fa fa-calendar"></i>
                                <% group.start | mysqlDateToIso | date:'MMMM-d,' %>                       
                            </span>
                            <span class="visible-xs">
                                <i class="fa fa-calendar"></i>
                                <% group.start | mysqlDateToIso | date:'MMM-d,' %>
                            </span>
                            <span ng-if="isToday(group.start)" ng-bind="'Today'"></span>
                            <span ng-if="isTomorrow(group.start)" ng-bind="'Tomorrow'"></span>
                            <span ng-if="!isToday(group.start) && !isTomorrow(group.start)" ng-bind="group.start|mysqlDateToIso|date:'EEEE'"></span>
                        </div>
                    </accordion-heading>
                    <div class="row">
                        <div ng-repeat="ride in group.rides" class="col-md-4 mBottom10">
                            <div class="panel panel-default" ng-init="menuVisible=false">
                                <div class="panel-heading">
                                    <strong class="pull-right">
                                        <% ride.start | mysqlDateToIso | date:'h:mm a' %>
                                    </strong>
                                    <span ng-if="!ride.is_personal">
                                        <strong>
                                            Asking: <% ride.price | currency:'$':0 %> 
                                        </strong>
                                        <a ng-if="ride.allow_payments==1" tooltip="Online transactions enabled" tooltip-placement="right" href="">
                                            <i class="fa fa-credit-card"></i>
                                        </a>
                                        <a ng-if="ride.allow_payments==0" tooltip="Bring cash for the ride" tooltip-placement="right" href="">
                                            <i class="fa fa-money"></i>
                                        </a>
                                    </span>
                                    <strong ng-if="ride.is_personal && ride.driver_id==session.user_id">
                                        <i class="fa fa-dashboard"></i>
                                        <span class="hidden-xs">You are the</span>
                                        Driver
                                    </strong>
                                    <strong ng-if="ride.is_personal && ride.driver_id!=session.user_id">
                                        <i class="fa fa-user"></i>
                                        <span class="hidden-xs">You are a</span>
                                        Passenger
                                    </strong>
                                </div>
                                <div ng-mouseover="menuVisible=true" ng-mouseleave="menuVisible=false" class="panel-body" style="position:relative;">
                                    <div class="mBottom10">    
                                        <a ng-click="openReviewModal(ride.driver_id)" href="">
                                            <img src="<% ride.driver_facebook_id | fbImage %>" class="img-circle mTop5 pull-right hoverable8" width="78">
                                        </a>
                                        <div class="mBottom10 text-ellipsis">
                                            <span>Origin</span><br>
                                            <a tooltip="<% ride.origin %>" tooltip-placement="right" href="">
                                                <i class="fa fa-flag fa-border"></i>
                                            </a>
                                            <strong ng-bind="ride.origin"></strong>
                                        </div>
                                        <div class="mTop10 text-ellipsis">
                                            <span>Destination</span><br>
                                            <a tooltip="<% ride.destination %>" tooltip-placement="right" href="">
                                                <i class="fa fa-flag-checkered fa-border"></i>
                                            </a>
                                            <strong ng-bind="ride.destination"></strong>
                                        </div>
                                    </div>
                                    <div ng-show="menuVisible" style="width:50%;position:absolute;bottom:0%;left:0%;">
                                        <a href="<% ride.driver_facebook_id | fbProfile %>" target="_blank" class="btn btn-xs btn-block btn-info hoverable9" style="background:#3B5998;border:none;">
                                            <i class="fa fa-facebook-square fa-lg"></i> Message
                                        </a>
                                    </div>
                                    <div ng-show="menuVisible" style="width:50%;position:absolute;bottom:0%;right:0%;">
                                        <a ng-click="openRideModal(ride.id)" class="btn btn-xs btn-block btn-wheelzo hoverable9">
                                            <i class="fa fa-car"></i> More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="session.user_id>0" class="col-md-4">
                           <div ng-click="openModal('drive', 'lg')" class="well well-lg text-center clickable hoverable7" style="color:#333;border:dashed 8px;height:150px;">
                                <div class="mTop15">
                                    <i class="fa fa-car fa-2x"></i>
                                    <p class="lead">
                                        POST A RIDE
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordion-group>
            </accordion>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $wheelzo['autoQueryRide'] = {{ $requested_ride ? '"'. $requested_ride->id .'"' : "false" }};
        $wheelzo['autoQueryUser'] = {{ $requested_user ? '"'. $requested_user->id .'"' : "false" }};
    </script>
    <script src="/assets/js/v2/main.js"></script>
@endsection