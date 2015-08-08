<script type="text/ng-template" id="drive.html">
    <div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-car"></i>
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
                                <input ng-model="input.origin" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:6" class="form-control" id="origin" placeholder="Origin" autocomplete="off">  
                            </div>
                            <div class="col-sm-6" id="destination-group">
                                <div class="input-group">
                                    <input ng-model="input.destination" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:6" class="form-control" id="destination" placeholder="Destination" autocomplete="off">  
                                    <span class="input-group-btn">
                                        <button ng-click="addDropoff()" ng-disabled="loading" class="btn btn-default" tooltip="Add drop off destinations" tooltip-placement="left">
                                            <i class="fa fa-road fa-lg"></i>
                                        </button>
                                    </span>
                                </div>
                                <div ng-repeat="dropoff in input.dropoffs track by $index" class="right-inner-addon">
                                    <a ng-click="removeDropoff($index)" class="" href="">
                                        <i class="fa fa-times fa-lg"></i>
                                    </a>
                                    <input ng-model="input.dropoffs[$index]" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:6" class="form-control" placeholder="Drop off location" autocomplete="off">
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="form-group">  
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label" for="departure-date">Departure Date</label>  
                                <input ng-model="input.startDate" datepicker-popup="MMMM d, yyyy" is-open="showCalendar" ng-focus="showCalendar=!showCalendar" datepicker-options="dateOptions" min-date="<?=time()*1000?>" ng-init="showCalendar=false" ng-disabled="loading" class="form-control" placeholder="ex. 23-Sep-2015" close-text="Close" />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label" for="departure-time">Departure Time</label>
                                <timepicker ng-model="input.startTime" minute-step="15" show-meridian="true"></timepicker>
                            </div>
                            <div class="col-sm-3 col-xs-6">  
                                <label class="control-label" for="price">
                                    Asking from each passenger
                                </label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input ng-model="input.price" ng-disabled="loading" class="form-control" id="price" placeholder="Price" valid-number>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <label class="control-label" for="capacity">
                                    Number of seats available
                                </label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input ng-model="input.capacity" ng-disabled="loading" class="form-control" id="capacity" placeholder="Capacity" valid-number>
                                        <div class="input-group-addon">persons</div>
                                    </div>
                                </div>
                            </div>
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
        <div class="pull-left checkbox mTop5">
            <label>
                <input ng-model="input.allowPayments" ng-disabled="loading" type="checkbox"> 
                Allow passengers to make online payments
            </label>
        </div>
        <button ng-click="submit(input)" ng-disabled="loading" class="btn btn-success">
            <i class="fa fa-truck"></i> Publish Ride
        </button>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/drive.js"></script>