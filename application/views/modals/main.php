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
                <form class="form-horizontal" role="form">  
                    <div class="form-group">  
                        <label class="col-sm-3 control-label" for="input1">DriverID</label>  
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="id" id="input1" placeholder="">    
                        </div>
                    </div> 
                    <div class="form-group">  
                        <label class="col-sm-3 control-label" for="input2">To</label>  
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="to" id="input2" placeholder="">  
                        </div>
                    </div>  
                    <div class="form-group">  
                        <label class="col-sm-3 control-label" for="input3">From</label>  
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="from" id="input3" rows="8" placeholder="">  
                        </div>
                    </div>  
                    <div class="form-group">  
                        <label class="col-sm-3 control-label" for="input4">When</label>  
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="when" id="input4" rows="8" placeholder="">    
                        </div>
                    </div>
                    <div class="form-group">  
                        <label class="col-sm-3 control-label" for="input5">Price</label>  
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="price" id="input5" rows="8" placeholder="">  
                        </div>
                    </div> 
                    <div class="form-group">  
                        <label class="col-sm-3 control-label" for="input6">Capacity</label>  
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="capacity" id="input6" rows="8" placeholder="">  
                        </div>
                    </div> 
                </form>  
            </div>
        </div>
    </div>
</div>    