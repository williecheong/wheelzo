<script type="text/ng-template" id="rrequest.html">
	<div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-bullhorn"></i>
            Request Ride
        </h4>
    </div>
    <div class="modal-body"> 
        <form class="" role="form">  
            <div class="form-group">  
                <label class="control-label" for="origin">Travelling</label>  
                <div class="row">
                    <div class="col-sm-6">
                        <input ng-model="input.origin" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:6" class="form-control" id="origin" placeholder="Origin" autocomplete="off">  
                    </div>
                    <div class="col-sm-6">
                        <input ng-model="input.destination" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:6" class="form-control" id="destination" placeholder="Destination" autocomplete="off">  
                    </div>
                </div>
            </div>
            <div class="form-group">  
                <label class="control-label" for="departure-date">Preferred Departure Date and Time</label>  
                <div class="row">
                    <div class="col-sm-8">
                        <input ng-model="input.startDate" datepicker-popup="MMMM d, yyyy" is-open="showCalendar" ng-focus="showCalendar=!showCalendar" datepicker-options="dateOptions" min-date="<?=time()*1000?>" ng-init="showCalendar=false" ng-disabled="loading" class="form-control" placeholder="ex. 23-Sep-2015" close-text="Close" />
                    </div>
                    <div class="col-sm-4">
                        <timepicker ng-model="input.startTime" minute-step="15" show-meridian="true" class="pull-right"></timepicker>
                    </div>
                </div>
            </div>
        </form>
        <div class="well well-sm mBottom5 mTop15">
            <strong>
                <i class="fa fa-bullhorn fa-lg"></i>
                Receive invitations 
                <span class="hidden-xs">
                    as drivers post matching rides
                </span>
            </strong>
            <p style="text-align:justify;">
                Posting a ride request allows drivers to find you on Wheelzo.
                Recommendations are made as drivers post rides going between the two specified cities on the preferred date.
                You will receive notifications on Facebook as drivers send out invitations to join rides.
            </p>
        </div>
    </div>
    <div class="modal-footer">
        <button ng-click="submit(input)" ng-disabled="loading" class="btn btn-success btn-block pLeft20 pRight20" style="white-space:normal;">
            <i class="fa fa-hand-stop-o fa-lg"></i> 
            Receive notifications from drivers posting similar rides
        </button>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/rrequest.js"></script>