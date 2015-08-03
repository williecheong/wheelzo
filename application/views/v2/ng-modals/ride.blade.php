<script type="text/ng-template" id="ride.html">
	<div class="modal-header visible-xs" style="border-bottom:0px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body"> 
        <div class="row">
            <div class="col-sm-4 text-center">
                <p>
                    <a id="driver-picture" target="_blank" href="#">
                        <img class="img-circle" id="driver-picture" src="">
                    </a>
                </p>
                <a class="lead" id="driver-facebook" target="_blank" href="#"><i class="fa fa-facebook-square"></i></a>
                <a class="lead" id="driver-name" target="_blank" href="#"></a>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <h3 class="col-lg-12 text-center">
                        <strong id="ride-price"></strong> 
                        -
                        <span class="lead" id="ride-departure"></span>
                        {{--
                        <span class="lead">
                            <a id="go-to-ride" href="?ride=0">
                                <i class="fa fa-share-square"></i>
                            </a>
                        </span>
                        --}}
                    </h3>
                    <div class="col-lg-12 text-center">
                        <div class="row" id="ride-passengers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="well well-sm payment-message" id="payment-message-guest">
            <i class="fa fa-facebook-square fa-lg"></i> 
            Sign in to 
            <a href="{{ $session_url }}">reserve a seat</a> 
            on this ride.
        </div>
        <div class="well well-sm payment-message" id="payment-message-passenger-enabled">
            <i class="fa fa-credit-card"></i> 
            Passengers are encouraged to 
            <a target="_blank" id="driver-facebook" href="#">contact drivers</a> before making a payment.
            In the event of a refund or dispute, please reach out directly to the Wheelzo team.
        </div>
        <div class="well well-sm payment-message" id="payment-message-passenger-disabled">
            <i class="fa fa-exclamation-triangle"></i> 
            Online payments have been disabled for this ride. <br>
            Passengers should contact the driver directly to 
            <a target="_blank" id="driver-facebook" href="#">make arrangements</a>.
        </div>
        <div class="well well-sm payment-message" id="payment-message-driver-enabled">
            <i class="fa fa-car"></i>
            Drivers can check for <a href="/me">account balances</a> on their profile. 
            Wheelzo collects a {{ WHEELZO_PAYMENT_COMMISSION*100 }}% commission 
            to cover maintenance fees such as processing charges from  
            <a target="_blank" href="https://stripe.com">Stripe</a>.
        </div>
        <div class="well well-sm payment-message" id="payment-message-driver-disabled">
            <i class="fa fa-car"></i>
            This ride will not receive online payments through Wheelzo. <br>
            Instead, passengers will be prompted to reach out directly through Facebook.
        </div>
        <div class="row text-center">
            <div class="col-xs-5">
                <span class="lead" id="ride-origin"></span>
            </div>
            <div class="col-xs-2">
                <i class="fa fa-arrow-circle-right fa-2x"></i>
                <br>
                <button class="btn btn-danger btn-xs" id="delete-ride">
                    Delete
                </button>
            </div>
            <div class="col-xs-5">
                <span class="lead" id="ride-destination"></span> 
                <span id="ride-dropoffs"></span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12" id="ride-comments"></div>
            @if ( $session )
                <div class="col-lg-12 media">
                    <div class="input-group">
                        <input type="text" class="form-control" id="write-comment" placeholder="" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="post-comment">
                                <i class="fa fa-comment fa-lg"></i>
                            </button>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/ride.js"></script>