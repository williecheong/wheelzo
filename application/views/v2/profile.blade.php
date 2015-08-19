@layout('v2/base')

@section('title')
    My Rides
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
    @endif 
@endsection

@section('description')
    <meta name="description" content="Better rideshare and carpooling for people around Kitchener, Waterloo and the Greater Toronto Area">
    <link rel="image_src"  href="/assets/img/screenshot-profile_478x250.jpg">  
    <meta property="og:image" content="{{ base_url() }}assets/img/screenshot-profile_478x250.jpg"/>
    <meta property="og:image:width" content="478" />
    <meta property="og:image:height" content="250" />
  
@endsection

@section('custom_css')
    <link rel="stylesheet" href="/assets/css/v2/main.css">
@endsection

@section('main_body')
    <div class="well well-sm mTop10 pBottom10">
        <div class="row">
            <div ng-class="{'visible-xs':showStatistics}" class="col-md-12" ng-init="showStatistics=true">
                <button class="btn btn-block btn-wheelzo hoverable8" ng-click="showStatistics=!showStatistics">
                    <i class="fa fa-briefcase"></i> 
                    <span ng-if="showStatistics==true">Hide rideshare summary</span>
                    <span ng-if="showStatistics==false">Show rideshare summary</span>
                </button>
            </div>
            <div ng-if="showStatistics==true" class="col-md-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <h3 class="mTop10">
                            <i class="fa fa-paw"></i>
                            About <% session.user.name || "you" | shortenString:15 %>
                        </h3>
                        <div style="padding-left:20px;">
                            <li>
                                Taken a ride with 
                                <span ng-bind="statistics.total_carpools_taken || 0"></span>
                                <span ng-bind="pluralize(statistics.total_carpools_taken, 'other carpooler', 'other carpoolers')"></span> 
                            </li>
                            <li>
                                Written 
                                <span ng-bind="statistics.total_reviews_written || 0"></span>
                                <span ng-bind="pluralize(statistics.total_reviews_written, 'user review', 'user reviews')"></span> 
                                and <span ng-bind="statistics.total_comments || 0"></span>
                                <span ng-bind="pluralize(statistics.total_comments, 'comment', 'comments')"></span> 
                            </li>
                            <li>
                                Driven 
                                <span ng-bind="statistics.total_rides || 0"></span>
                                <span ng-bind="pluralize(statistics.total_rides, 'journey', 'journeys')"></span> 
                                and taken on  <span ng-bind="statistics.total_passengers || 0"></span>
                                <span ng-bind="pluralize(statistics.total_passengers, 'passenger', 'passengers')"></span> 
                            </li>
                            <li>
                                Collected 
                                <strong ng-bind="statistics.balance || 0 | currency"></strong>
                                as a driver
                                <span class="clickable" tooltip="Contact the Wheelzo team to withdraw this balance">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </li>
                        </div>
                    </div>
                    <hr class="opaque6 mTop10 mBottom10 mLeft20 mRight20 visible-xs">
                    <div class="col-lg-6 col-md-6 hidden-sm">
                        <h3 class="mTop10">
                            <i class="fa fa-users"></i>
                            Community Feedback
                        </h3>
                        <div class="well well-sm mBottom5">
                            <div ng-if="statistics===false" class="text-center">
                                <span class="lead">
                                    <i class="fa fa-cog fa-spin"></i>
                                    Loading...
                                </span>
                            </div>
                            <div ng-if="statistics">
                                <span ng-if="statistics.points.length==0">Nobody has vouched for you yet.</span>
                                <span ng-if="statistics.points.length>0">
                                    <span ng-repeat="($index, supporter) in supporters">
                                        <span ng-if="supporters.length>1 && $index==supporters.length-1">and</span>
                                        <a ng-bind="supporter.name" ng-click="openReviewModal(supporter.id)" href=""></a><span ng-if="supporters.length!=0 && $index<supporters.length-2">,</span>
                                    </span>
                                    <span ng-bind="pluralize(supporters.length, 'has', 'have')"></span> 
                                    vouched for you.
                                </span>
                                <br class="visible-lg">
                                <span ng-if="statistics.reviews.length==0">You have not received any reviews from the Wheelzo community.</span>
                                <span ng-if="statistics.reviews.length>0">
                                    You have received 
                                    <a ng-click="openReviewModal(session.user_id)" href=""><% statistics.reviews.length || 0 %> <% pluralize(statistics.reviews.length, 'review', 'reviews') %></a>
                                    from the Wheelzo community.
                                </span>
                            </div>
                        </div>
                        <small class="text-muted pull-right">
                            <i class="fa fa-line-chart"></i>
                            Want <a href="mailto:info@wheelzo.com">more statistics</a>?
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <accordion close-others="true">
        <accordion-group>
            <accordion-heading>
                <span class="lead">
                    <i class="fa fa-bullhorn"></i>
                    Posted <% rideRequests.length || 'no' %> 
                    <% pluralize(rideRequests.length, 'ride request', 'ride requests') %>
                </span>
            </accordion-heading>
            <div ng-if="rideRequests===false" class="text-center">
                <span class="lead">
                    <i class="fa fa-cog fa-spin"></i>
                    Loading...
                </span>
            </div>
            <div ng-if="rideRequests.length==0" class="text-center">
                <span class="lead">No ride requests to display</span>
            </div>
            <div ng-if="rideRequests.length>0" class="table-responsive">
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Preferred Departure</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="rrequest in rideRequests" class="clickable" ng-click="openInvitationModal(rrequest)">
                            <td ng-bind="rrequest.origin"></td>
                            <td ng-bind="rrequest.destination"></td>
                            <td ng-bind="rrequest.start | mysqlDateToIso | date:'fullDate'"></td>
                            <td>
                                <% rrequest.invitations.length %>
                                <% pluralize(rrequest.invitations.length, 'invitation', 'invitations') %>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </accordion-group>
        <accordion-group>
            <accordion-heading>
                <span class="lead">
                    <i class="fa fa-car"></i>
                    Passenger of <% passengerRides.length || 'no' %> 
                    <% pluralize(passengerRides.length, 'ride', 'rides') %>
                </span>
            </accordion-heading>
            <div class="row">
                <div ng-if="passengerRides===false" class="text-center">
                    <span class="lead">
                        <i class="fa fa-cog fa-spin"></i>
                        Loading...
                    </span>
                </div>
                <div ng-if="passengerRides.length==0" class="text-center">
                    <span class="lead">No rides to display</span>
                </div>
                <div ng-if="passengerRides.length>0" ng-repeat="ride in passengerRides" class="col-md-4 mBottom10">
                    <div class="panel panel-default" ng-init="menuVisible=false">
                        <div class="panel-heading">
                            <strong ng-bind="ride.start | mysqlDateToIso | date:'h:mm a'" class="pull-right"></strong>
                            
                            <span ng-if="!ride.is_personal" tooltip="Price: <%ride.price|currency:'$':'0'%>" tooltip-placement="right" class="clickable hoverable8">
                                <i class="fa fa-car fa-lg"></i>
                            </span>
                            <a ng-if="ride.is_personal && ride.driver_id==session.user_id" tooltip="You are the driver" tooltip-placement="right" href="" class="">
                                <i class="fa fa-user fa-lg"></i>
                            </a>
                            <a ng-if="ride.is_personal && ride.driver_id!=session.user_id" tooltip="You are a passenger" tooltip-placement="right" href="" class="">
                                <i class="fa fa-users fa-lg"></i>
                            </a>

                            <strong ng-bind="ride.start | mysqlDateToIso | date:'MMM d &mdash; '" class="hidden-md"></strong>
                            <strong ng-bind="ride.start | mysqlDateToIso | date:'EEEE'"></strong>
                        </div>
                        <div ng-mouseover="menuVisible=true" ng-mouseleave="menuVisible=false" class="panel-body" style="position:relative;">
                            <div class="mBottom10">    
                                <a ng-click="openReviewModal(ride.driver_id)" href="">
                                    <img src="<% ride.driver_facebook_id | fbImage %>" class="img-circle mTop5 pull-right hoverable8" width="78">
                                </a>
                                <div class="mBottom10" style="white-space:nowrap;">
                                    <span>Origin</span><br>
                                    <a tooltip="<% ride.origin %>" tooltip-placement="right" href="">
                                        <i class="fa fa-flag fa-border"></i>
                                    </a>
                                    <strong ng-bind="ride.origin | shortenString:28"></strong>
                                </div>
                                <div class="mTop10" style="white-space:nowrap;">
                                    <span>Destination</span><br>
                                    <a tooltip="<% ride.destination %>" tooltip-placement="right" href="">
                                        <i class="fa fa-flag-checkered fa-border"></i>
                                    </a>
                                    <strong ng-bind="ride.destination | shortenString:28"></strong>
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
            </div>
        </accordion-group>
        <accordion-group>
            <accordion-heading>
                <span class="lead">
                    <i class="fa fa-dashboard"></i>
                    Driver of <% driverRides.length || 'no' %> 
                    <% pluralize(driverRides.length, 'ride', 'rides') %>
                </span>
            </accordion-heading>
            <div class="row">
                <div ng-if="driverRides===false" class="text-center">
                    <span class="lead">
                        <i class="fa fa-cog fa-spin"></i>
                        Loading...
                    </span>
                </div>
                <div ng-if="driverRides.length==0" class="text-center">
                    <span class="lead">No rides to display</span>
                </div>
                <div ng-repeat="ride in driverRides" class="col-md-4 mBottom10">
                    <div class="panel panel-default" ng-init="menuVisible=false">
                        <div class="panel-heading">
                            <strong ng-bind="ride.start | mysqlDateToIso | date:'h:mm a'" class="pull-right"></strong>
                            
                            <span ng-if="!ride.is_personal" tooltip="Price: <%ride.price|currency:'$':'0'%>" tooltip-placement="right" class="clickable hoverable8">
                                <i class="fa fa-car fa-lg"></i>
                            </span>
                            <a ng-if="ride.is_personal && ride.driver_id==session.user_id" tooltip="You are the driver" tooltip-placement="right" href="" class="">
                                <i class="fa fa-user fa-lg"></i>
                            </a>
                            <a ng-if="ride.is_personal && ride.driver_id!=session.user_id" tooltip="You are a passenger" tooltip-placement="right" href="" class="">
                                <i class="fa fa-users fa-lg"></i>
                            </a>

                            <strong ng-bind="ride.start | mysqlDateToIso | date:'MMM d &mdash; '" class="hidden-md"></strong>
                            <strong ng-bind="ride.start | mysqlDateToIso | date:'EEEE'"></strong>
                        </div>
                        <div ng-mouseover="menuVisible=true" ng-mouseleave="menuVisible=false" class="panel-body" style="position:relative;">
                            <div class="mBottom10">    
                                <a ng-click="openReviewModal(ride.driver_id)" href="">
                                    <img src="<% ride.driver_facebook_id | fbImage %>" class="img-circle mTop5 pull-right hoverable8" width="78">
                                </a>
                                <div class="mBottom10" style="white-space:nowrap;">
                                    <span>Origin</span><br>
                                    <a tooltip="<% ride.origin %>" tooltip-placement="right" href="">
                                        <i class="fa fa-flag fa-border"></i>
                                    </a>
                                    <strong ng-bind="ride.origin | shortenString:28"></strong>
                                </div>
                                <div class="mTop10" style="white-space:nowrap;">
                                    <span>Destination</span><br>
                                    <a tooltip="<% ride.destination %>" tooltip-placement="right" href="">
                                        <i class="fa fa-flag-checkered fa-border"></i>
                                    </a>
                                    <strong ng-bind="ride.destination | shortenString:28"></strong>
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
            </div>
        </accordion-group>
    </accordion>
@endsection

@section('custom_js')
    <script src="/assets/js/v2/profile.js"></script>
@endsection

@section('custom_modals')
    @include('v2/ng-modals/invitation')
@endsection