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
                            <img class="img-circle" id="driver-picture" src="">
                        </p>
                        <a class="lead" id="driver-name" href="#"></a>
                    </div>
                    <div class="row col-sm-8">
                        <div class="col-lg-12 text-center">
                            <h1>
                                <span class="lead"><strong>$12</strong> - February 10, 10:15 am</span>
                            </h1>
                        </div>
                        <div class="row col-lg-12 text-center">
                            <div class="col-xs-4">
                                <img class="img-circle" id="passenger-picture" src="//graph.facebook.com/max.pikhteryev/picture?width=200&height=200">
                            </div>
                            <div class="col-xs-4">    
                                <img class="img-circle" id="passenger-picture" src="//graph.facebook.com/max.pikhteryev/picture?width=200&height=200">
                            </div>
                            <div class="col-xs-4">    
                                <img class="img-circle" id="passenger-picture" src="//graph.facebook.com/max.pikhteryev/picture?width=200&height=200">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-xs-5">
                        <span class="lead">Waterloo, Davis Center</span>
                    </div>
                    <div class="col-xs-2">
                        <i class="fa fa-arrow-right fa-lg"></i>
                    </div>
                    <div class="col-xs-5">
                        <span class="lead">Toronto, Union Square</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


