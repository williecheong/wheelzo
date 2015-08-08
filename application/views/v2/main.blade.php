@layout('v2/base')

@section('title')
    Wheelzo
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
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
        @foreach($day_filters as $key => $day)
            <div ng-click="filterDate('{{ $day }}')" class="col-xs-3">
                <button ng-disabled="activeDateFilter=='{{ $day }}'" class="btn btn-block btn-wheelzo hoverable8">
                    <i class="fa fa-calendar hidden-xs"></i>
                    @if ($key == 0)
                        Today<span class="hidden-xs">, {{ date('M j', strtotime('now')) }}</span>
                    @elseif($key==1)
                        Tomorrow<span class="hidden-xs">, {{ date('M j', strtotime('+1 day')) }}</span>
                    @else
                        {{ $day }}<span class="hidden-xs">, {{ date('M j', strtotime('next '.$day)) }}</span>
                    @endif
                </button>
            </div>  
        @endforeach
    </div>
    <div class="row mTop20">
        <div ng-repeat="ride in displayRides" class="col-md-4">
            <div class="panel panel-default" ng-init="menuVisible=false">
                <div class="panel-heading">
                    <strong ng-bind="ride.price | currency:'$':'0'" class="pull-right"></strong>
                    <a tooltip="<% ride.start | mysqlDateToIso | date : 'fullDate' %>" tooltip-placement="right">
                        <i class="fa fa-calendar"></i>
                    </a>
                    <strong ng-bind="ride.start | mysqlDateToIso | date:'MMM d &mdash; '" class="hidden-md"></strong>
                    <strong ng-bind="ride.start | mysqlDateToIso | date:'EEEE, '"></strong>
                    <strong ng-bind="ride.start | mysqlDateToIso | date:'h:mm a'"></strong>
                </div>
                <div ng-mouseover="menuVisible=true" ng-mouseleave="menuVisible=false" class="panel-body" style="position:relative;">
                    <div class="mBottom10">    
                        <a ng-click="openReviewModal(ride.driver_id)" href="">
                            <img src="<% ride.driver_facebook_id | fbImage %>" class="img-circle mTop5 pull-right hoverable7" width="78">
                        </a>
                        <div class="mBottom10" style="white-space:nowrap;">
                            <strong>Origin</strong><br>
                            <a tooltip="<% ride.origin %>" tooltip-placement="right" href="">
                                <i class="fa fa-flag fa-border"></i>
                            </a>
                            <span ng-bind="ride.origin | shortenString:28"></span>
                        </div>
                        <div class="mTop10" style="white-space:nowrap;">
                            <strong>Destination</strong><br>
                            <a tooltip="<% ride.destination %>" tooltip-placement="right" href="">
                                <i class="fa fa-flag-checkered fa-border"></i>
                            </a>
                            <span ng-bind="ride.destination | shortenString:28"></span>
                        </div>
                    </div>
                    <div ng-show="menuVisible" style="width:50%;position:absolute;bottom:0%;left:0%;">
                        <a href="<% ride.driver_facebook_id | fbProfile %>" target="_blank" class="btn btn-xs btn-block btn-info hoverable8" style="background:#3B5998;border:none;">
                            <i class="fa fa-facebook-square fa-lg"></i> Message
                        </a>
                    </div>
                    <div ng-show="menuVisible" style="width:50%;position:absolute;bottom:0%;right:0%;">
                        <a ng-click="openRideModal(ride.id)" class="btn btn-xs btn-block btn-wheelzo hoverable8">
                            <i class="fa fa-car"></i> More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="/assets/js/v2/main.js"></script>
@endsection