<!-- Modal for creating a new ride -->
<div class="modal fade" id="create-ride" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    Create Ride
                </h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-sm-12">
                        <form class="" role="form">  
                            <div class="form-group">  
                                <label class="control-label" for="origin">Travelling</label>  
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control add_suggested_places" id="origin" placeholder="Origin" autocomplete="off">  
                                    </div>
                                    <div class="col-sm-6" id="destination-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control add_suggested_places" id="destination" placeholder="Destination" autocomplete="off">  
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" id="add-dropoff" title="Add drop off destinations" type="button">
                                                    <i class="fa fa-road fa-lg"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">  
                                <label class="control-label" for="departure-date">Departure Date and Time</label>  
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control datepicker" id="departure-date" placeholder="mm/dd/yyyy" readonly="readonly">  
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control timepicker" id="departure-time" placeholder="hh:mm pm" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">  
                                    <label class="control-label" for="price">Price: </label>
                                    $ <span class="slider-value lead" id="price">10</span>
                                    <div class="slider" id="price"></div> 
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="capacity">Capacity: </label>  
                                    <span class="slider-value lead" id="capacity">2</span> persons 
                                    <div class="slider" id="capacity"></div> 
                                </div>
                            </div>  
                        </form>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <div class="well well-sm non-rrequests-table-container">
                            <span class="lead">Recommended passengers for inviting to your ride</span>
                            <p>
                                <i class="fa fa-info-circle"></i> Send a Facebook notification to someone who has requested for a similar ride. This list automatically generates recommendations for people who are most likely to be interested in the ride you are going to publish.
                            </p>
                        </div>
                        <div class="well well-sm rrequests-table-container" style="display:none;">
                            <span class="lead">Set up an invitation by clicking on the row</span>
                            <table class="table table-hover table-condensed rrequests-table">
                                <thead>
                                    <tr>
                                        <th style="width:20%;">Requester</th>
                                        <th style="width:30%;">Origin</th>
                                        <th style="width:30%;">Destination</th>
                                        <th style="width:20%;">Preferred Departure</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rrequests as $rrequest)
                                        <tr data-rrequest-id="{{ $rrequest->id }}">
                                            <td>
                                                <a href="//facebook.com/{{ $users[$rrequest->user_id]['facebook_id'] }}" target="_blank">
                                                    {{ $users[$rrequest->user_id]['name'] }}
                                                </a>
                                            </td>
                                            <td>{{ $rrequest->origin }}</td>
                                            <td>{{ $rrequest->destination }}</td>
                                            <td>{{ date( 'M-d @ g:ia', strtotime($rrequest->start) ) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="post-ride">
                    <i class="fa fa-truck"></i> Publish
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing a ride -->
<div class="modal fade" id="view-ride" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
                <div class="well well-sm payment-message" id="payment-message-passenger">
                    <i class="fa fa-credit-card"></i> 
                    Passengers are encouraged to 
                    <a target="_blank" id="driver-facebook" href="#">contact drivers</a> before making a payment.
                    In the event of a refund or dispute, please reach out directly to the Wheelzo team.
                </div>
                <div class="well well-sm payment-message" id="payment-message-driver">
                    <i class="fa fa-car"></i>
                    Drivers can check for <a href="/me">account balances</a> on their profile. 
                    Wheelzo collects a {{ WHEELZO_PAYMENT_COMMISSION*100 }}% commission 
                    to cover maintenance fees such as processing charges from  
                    <a target="_blank" href="https://stripe.com">Stripe</a>.
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
        </div>
    </div>
</div>

<!-- Modal for creating a new request -->
<div class="modal fade" id="create-request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    Request Ride
                </h4>
            </div>
            <div class="modal-body"> 
                <form class="" role="form">  
                    <div class="form-group">  
                        <label class="control-label" for="origin">Travelling</label>  
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control add_suggested_places" id="request-origin" placeholder="Origin" autocomplete="off">  
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control add_suggested_places" id="request-destination" placeholder="Destination" autocomplete="off">  
                            </div>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label class="control-label" for="departure-date">Preferred Departure Date and Time</label>  
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control datepicker" id="request-departure-date" placeholder="mm/dd/yyyy" readonly="readonly">  
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control timepicker" id="request-departure-time" placeholder="hh:mm pm" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </form>  
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="post-request">
                    <i class="fa fa-bullhorn fa-lg"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for looking up other users -->
<div class="modal fade" id="lookup-users" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="fa fa-group"></i> Lookup
                </h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" class="form-control hide" id="lookup-id" value="1">
                        <input type="text" class="form-control add_suggested_names" id="lookup-name" placeholder="Who are you looking for? ¯\_(ツ)_/¯" autocomplete="off"> 
                    </div>
                </div>
                <div class="row">
                    <br>
                    <div class="col-xs-7 text-right">
                        <a id="lookup-picture" target="" href="#">
                            <img class="img-circle greyed-out" id="lookup-picture" src="/assets/img/empty_user.png">
                        </a>
                    </div>
                    <div class="col-xs-5 text-center">
                        <strong>REP</strong>
                        <a href="#about-scores"> 
                            <i class="fa fa-info-circle"></i>
                        </a>
                        <h3>
                            <span class="label label-success" id="lookup-score">
                                0.00
                            </span>
                        </h3>
                        <br>
                        <a class="btn btn-success btn-sm disabled" id="give-point" role="button">
                            <i class="fa fa-child fa-lg"></i> Vouch
                        </a>
                    </div>
                    <br>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 media">
                        <div class="input-group">
                            <input type="text" class="form-control" id="write-review" placeholder="Write a review for ..." autocomplete="off" disabled>
                            <span class="input-group-btn">
                                <button class="btn btn-default disabled" type="button" id="post-review">
                                    <i class="fa fa-comment fa-lg"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-12" id="lookup-reviews">
                        <div class="media dummy-review text-center">    
                            <em>
                                No Reviews to display...
                            </em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for reading FAQ -->
<div class="modal fade" id="read-faq" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="fa fa-comments-o fa-lg"></i> 
                    Frequently Asked Questions
                </h4>
            </div>
            <div class="modal-body">
                <div class="text-center" style="padding-bottom:5px;">
                    <span class="lead">Answers For Drivers</span>
                </div>
                <div class="well well-sm faq-box">
                    <strong data-mytoggler="p#faq1">
                        <i class="fa fa-arrow-circle-right"></i>
                        As a driver, how do I post a ride?
                    </strong>
                    <p id="faq1">
                        Sign into Wheelzo using Facebook. 
                        Then post a ride by entering departure and arrival locations, and starting date and time of your journey. 
                        Potential passengers looking for a similar ride will also appear as you create your posting. 
                        Feel free to send them an invitation to join your new ride.
                    </p>
                </div>
                <div class="well well-sm faq-box">
                    <strong data-mytoggler="p#faq2">
                        <i class="fa fa-arrow-circle-right"></i>
                        How does Wheelzo help me with the bookings on rides I've posted?
                    </strong>
                    <p id="faq2">
                        Notifications will be sent to your Facebook account when a potential passenger comments on your ride on Wheelzo.
                        Alternatively, potential passengers may also choose to send you a private message directly through Facebook.
                    </p>
                </div>
                <div class="well well-sm faq-box">
                    <strong data-mytoggler="p#faq3">
                        <i class="fa fa-arrow-circle-right"></i>
                        How can I use Wheelzo to collect online payments from my passengers?
                    </strong>
                    <p id="faq3">
                        Passengers are always encouraged to make direct contact with drivers prior to making payments.
                        When both parties are satisfied with the arrangement, drivers may direct passengers to Wheelzo to make an online payment.
                        Wheelzo holds onto the payment and releases it to the driver shortly after the ride is completed.
                    </p>
                </div>
                <div class="text-center" style="padding-bottom:5px;">
                    <span class="lead">Answers For Passengers</span>
                </div>
                <div class="well well-sm faq-box">
                    <strong data-mytoggler="p#faq4">
                        <i class="fa fa-arrow-circle-right"></i>
                        What can I do to book a ride that is right for me?
                    </strong>
                    <p id="faq4">
                        You may contact the driver by leaving a comment directly on the ride.
                        The driver will automatically be sent a Facebook notification when you do so.
                        Alternatively, you may access the driver's Facebook profile to send a private message.
                        Once arrangements have been made between the driver and yourself, feel free to make an online payment to the driver through Wheelzo.
                        Your payment will be locked in and released to the driver only after the ride has been completed. 
                    </p>
                </div>
                <div class="well well-sm faq-box">
                    <strong data-mytoggler="p#faq5">
                        <i class="fa fa-arrow-circle-right"></i>
                        What can I do if I am unable to find a ride?
                    </strong>
                    <p id="faq5">
                        First, make sure that the ride you are looking for doesn't exist by using the Wheelzo search bar.
                        If a suitable ride is indeed unavailable, then you may submit a ride request.
                        Drivers posting a matching ride in future will see your ride request, and will then be likely to send you an invitation to join. 
                    </p>
                </div>
                <div class="text-center" style="padding:20px;">
                    More questions? Let us <a href="mailto:info@wheelzo.com">know</a>.
                </div>
            </div>
        </div>
    </div>
</div>