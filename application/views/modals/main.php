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
                                <input type="text" class="form-control" id="origin" placeholder="Origin">  
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="destination" placeholder="Destination">  
                            </div>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label class="control-label" for="departure-date">Departure Date and Time</label>  
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="departure-date" placeholder="<?= date('m/d/Y'); ?>">  
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="departure-time" placeholder="<?= date('h:ia'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">  
                            <label class="control-label" for="price">Price</label>      
                            <input type="text" class="form-control" id="price" placeholder="Price">  
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label" for="capacity">Capacity</label>  
                            <select class="form-control" id="capacity">
                                <option value="1">1</option>
                                <option value="2" selected>2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>  
                </form>  
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">
                    <i class="fa fa-truck"></i> Post ride
                </buttom>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing a ride -->
<div class="modal fade" id="view-ride" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    Driver: <a class="lead" id="driver-name" href="#"></a>
                </h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                </div>
            </div>
        </div>
    </div>
</div>    