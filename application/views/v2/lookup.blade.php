@layout('v2/base')

@section('title')
    Community Lookup
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
    @endif 
@endsection

@section('description')
    <meta name="description" content="Better rideshare and carpooling for people around Kitchener, Waterloo and the Greater Toronto Area">
    <link rel="image_src"  href="/assets/img/screenshot-lookup_478x250.jpg">
@endsection

@section('custom_css')
    <link rel="stylesheet" href="/assets/css/v2/main.css">
@endsection

@section('main_body')
    <div class="row mTop20">
        <div class="col-sm-12">
            <span class="lead">
                <i class="fa fa-users"></i>
                Community Lookup
            </span>
        </div>
        <form class="col-sm-7">
            <div class="input-group">
                <input ng-model="name" ng-disabled="loading" type="text" class="form-control" placeholder="e.g. Maksym Pikhteryev">
                <span class="input-group-btn">
                    <button class="btn btn-success" ng-click="searchUsers(name)" ng-disabled="loading">
                        <i class="fa fa-search fa-lg"></i>
                        <span class="hidden-xs">Search</span>
                    </button>
                </span>
            </div>
            <p class="text-muted mLeft5">
                <i class="fa fa-shield"></i>
                Reviewing helps keep everyone in the community accountable
            </p>
        </form>
    </div>
    <div class="row">
        <div ng-repeat="user in users" class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="well well-sm">
                <div>
                    <a ng-click="openReviewModal(user.id)" href="">
                        <img class="img-responsive img-circle hoverable8" src="<% user.facebook_id | fbImage %>" style="min-width:100%;">
                    </a>
                </div>
                <div class="text-center mTop10">
                    <a tooltip="<% user.name %>" tooltip-placement="right" href="">
                        <i class="fa fa-caret-square-o-right"></i>
                    </a>
                    <strong ng-bind="user.name | shortenString:12"></strong>
                </div>
                <div>
                    <span>Reputation:</span>
                    <strong ng-bind="user.score" class="pull-right"></strong>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="/assets/js/v2/lookup.js"></script>
@endsection