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
                <hr>
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
                        <input type="text" class="form-control add_suggested_names" id="lookup-name" placeholder="Who are you trying to find?" autocomplete="off"><ul class="typeahead dropdown-menu" style="display: none; top: 34px; left: 15px;"><li class="active"><a href="#"><strong>Max Pikhte</strong>ryev</a></li></ul>  
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