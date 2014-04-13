<!-- Modal for creating a new ride -->
<div class="modal fade" id="create-ride" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    Create Ride
                </h4>
            </div>
            <div class="modal-body"> 
                <form class="" role="form">  
                    <div class="form-group">  
                        <label class="control-label" for="origin">Travelling</label>  
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control add_suggested_places" id="origin" placeholder="Origin" autocomplete="off">  
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control add_suggested_places" id="destination" placeholder="Destination" autocomplete="off">  
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
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <p>
                            <a id="driver-picture" href="#">
                                <img class="img-circle" id="driver-picture" src="">
                            </a>
                        </p>
                        <a class="lead" id="driver-name" href="#"></a>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <h3 class="col-lg-12 text-center">
                                <strong id="ride-price"></strong> 
                                -
                                <span class="lead" id="ride-departure"></span>
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
                        <i class="fa fa-arrow-right fa-lg"></i>
                    </div>
                    <div class="col-xs-5">
                        <span class="lead" id="ride-destination"></span>
                    </div>
                </div>
                <div class="row well well-sm">
                    <div class="col-lg-12 user-comments">
                        <div class="row">
                            <div class="col-xs-2">
                                <img class="img-thumbnail" width="100%" src="//graph.facebook.com/willie.cheong.10/picture?type=square">
                            </div>
                            <div class="col-xs-10">
                                <p>
                                    Hahaha this is a message
                                </p>
                                <small>
                                    Tuesday February 11, 5:00am
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-2">
                                <img class="img-thumbnail" width="100%" src="//graph.facebook.com/willie.cheong.10/picture?type=square">
                            </div>
                            <div class="col-xs-10">
                                <p>
                                    Hahaha this is a message
                                </p>
                                <small>
                                    Tuesday February 11, 5:00am
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php if ( $session ) { ?>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="write-comment" placeholder="Leave a comment here, <?= $users[$session]['name']; ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" id="post-comment" type="button">
                                            <i class="fa fa-envelope"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


