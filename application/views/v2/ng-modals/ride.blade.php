<script type="text/ng-template" id="ride.html">
    <div class="modal-header visible-xs" style="background:none;border-bottom:0px;">
        <button ng-click="cancel()" type="button" style="color:#333;" class="close">&times;</button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4 text-center">
                <p>
                    <a ng-click="openReviewModal(ride.driver_id)" id="driver-picture" target="_blank" href="">
                        <img src="<% ride.driver_facebook_id | fbImage %>" class="img-circle hoverable8" id="driver-picture">
                    </a>
                </p>
                <a href="<% ride.driver_facebook_id | fbProfile %>" class="lead" target="_blank"><i class="fa fa-facebook-square"></i></a>
                <a ng-bind="ride.driver_name" ng-click="openReviewModal(ride.driver_id)" class="lead" href=""></a>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <h3 class="col-lg-12 text-center">
                        <strong ng-bind="ride.price | currency:'$':0"></strong> 
                        -
                        <span ng-bind="ride.start | mysqlDateToIso | date:'EEEE MMM d, h:mm a'" class="lead"></span>
                        <span class="lead">
                            <a tooltip="Get a direct link for sharing this ride" tooltip-placement="left" clip-copy="'{{base_url()}}?ride='+ride.id" clip-click="copyShowMessage()" clip-click-fallback="copyFallback('{{base_url()}}?ride='+ride.id)" href="">
                                <i class="fa fa-share-alt-square"></i>
                            </a>
                        </span>
                    </h3>
                    <div class="col-lg-12 text-center">
                        <div class="row" id="ride-passengers">
                            <div ng-repeat="colSize in colSizes track by $index" class="col-xs-<%colSize%>" id="passenger-box">
                                <a ng-if="$index<ride.passengers.length" ng-click="openReviewModal(ride.passengers[$index].user_id)" href="">
                                    <img src="<% ride.passengers[$index].userObject.facebook_id | fbImage %>" class="img-circle hoverable5" id="passenger-picture">
                                </a>
                                <span ng-if="$index>=ride.passengers.length">
                                    <img ng-if="ride.driver_id==session.user_id || ride.allow_payments==0" class="img-circle opaque5" id="passenger-picture" src="/assets/img/empty_user.png">
                                    
                                    <a ng-if="session.user_id==0 && ride.driver_id!=session.user_id && ride.allow_payments==1" href="/sign">
                                        <img class="img-circle hoverable5" id="passenger-picture" src="/assets/img/payment.png">
                                    </a>

                                    <a ng-if="session.user_id!=0 && ride.driver_id!=session.user_id && ride.allow_payments==1" ng-click="handlePayment($event)" href="">
                                        <img class="img-circle hoverable5" id="passenger-picture" src="/assets/img/payment.png">
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div ng-if="session.user_id==0" class="well well-sm payment-message">
            <i class="fa fa-facebook-square fa-lg"></i> 
            Sign in to 
            <a href="/sign">reserve a seat</a> 
            on this ride.
        </div>
        <div ng-if="session.user_id!=0 && session.user_id!=ride.driver_id && ride.allow_payments==1" class="well well-sm payment-message">
            <i class="fa fa-credit-card"></i> 
            Passengers are encouraged to 
            <a target="_blank" href="<% ride.driver_facebook_id | fbProfile %>">contact drivers</a> before making a payment.
            In the event of a refund or dispute, please reach out directly to the Wheelzo team.
        </div>
        <div ng-if="session.user_id!=0 && session.user_id!=ride.driver_id && ride.allow_payments==0" class="well well-sm payment-message">
            <i class="fa fa-exclamation-triangle"></i> 
            Online payments have been disabled for this ride. <br class="hidden-xs">
            Passengers should contact the driver directly to 
            <a target="_blank" href="<% ride.driver_facebook_id | fbProfile %>">make arrangements</a>.
        </div>
        <div ng-if="session.user_id!=0 && session.user_id==ride.driver_id && ride.allow_payments==1" class="well well-sm payment-message">
            <i class="fa fa-car"></i>
            Drivers can check for <a href="/me">account balances</a> on their profile. 
            Wheelzo collects a {{ WHEELZO_PAYMENT_COMMISSION*100 }}% commission 
            to cover maintenance fees such as processing charges from  
            <a target="_blank" href="https://stripe.com">Stripe</a>.
        </div>
        <div ng-if="session.user_id!=0 && session.user_id==ride.driver_id && ride.allow_payments==0" class="well well-sm payment-message">
            <i class="fa fa-car"></i>
            This ride will not receive online payments through Wheelzo. <br class="hidden-xs">
            Instead, passengers will be prompted to reach out directly through Facebook.
        </div>
        <div class="row text-center">
            <div class="col-xs-5">
                <span ng-bind="ride.origin" class="lead"></span>
            </div>
            <div class="col-xs-2">
                <i class="fa fa-arrow-circle-right fa-2x"></i>
                <br>
                <button ng-if="session.user_id==ride.driver_id" ng-click="deleteRide()" class="btn btn-danger btn-xs">
                    Delete
                </button>
            </div>
            <div class="col-xs-5">
                <span ng-bind="ride.destination" class="lead"></span> 
                <a ng-if="ride.drop_offs.length>0" popover-html="ride.drop_offs_html" popover-title="Drop Off Locations:" popover-trigger="mouseenter" popover-animation="false" popover-placement="left" href="">
                    <i class="fa fa-flag-checkered fa-lg fa-border"></i>
                </a>
            </div>
        </div>
        <hr style="border-color:#EEE;">
        <div class="row">
            <div ng-repeat="comment in ride.comments" class="media col-lg-12">
                <a ng-click="openReviewModal(comment.user_id)" class="pull-left" target="_blank" href="">
                    <img src="<% comment.userObject.facebook_id | fbImageSquare %>" class="img-rounded media-object">
                </a>
                <div class="media-body">
                    <div ng-bind-html="comment.comment"></div>
                    <small class="single-comment-meta">
                        <a ng-click="openReviewModal(comment.user_id)" ng-bind="comment.userObject.name" target="_blank" href=""></a> 
                        @ <span ng-bind="comment.last_updated | mysqlDateToIso | date:'EEEE MMMM d, h:mm a'"></span>
                    </small>
                </div>
            </div>
            <div ng-if="ride.comments.length==0">
                <div class="media dummy-comment text-center">
                    <em>No comments to display ...</em>
                </div>
            </div>
            <form ng-if="session.user_id" class="col-lg-12 media">
                <div class="input-group">
                    <input ng-model="inputComment" ng-disabled="loading" type="text" class="form-control" placeholder="<% (session.user_id==ride.driver_id) ? 'Write about your ride or respond to potential passengers' : 'Write a request to join or ask questions to the driver' %>" autocomplete="off">
                    <span class="input-group-btn">
                        <button ng-click="submitComment(inputComment)" ng-disabled="loading" class="btn btn-default">
                            <i class="fa fa-comment fa-lg"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/ride.js"></script>