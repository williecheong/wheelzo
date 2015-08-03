<script type="text/ng-template" id="rrequest.html">
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
</script>

<script src="/assets/js/v2/ng-modals/rrequest.js"></script>