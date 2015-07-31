<script type="text/ng-template" id="drive.html">
    <div class="modal-header">
        <h1>
            <i class="fa fa-calendar"> </i> Create New Event
            <button class="btn btn-default pull-right" ng-click="cancel()"><i class="fa fa-close"></i></button>
        </h1>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" role="form">
            <h2><small><i>Enter Event Information</i></small></h2>
            <div class="form-group">
                <!-- Event Name -->
                <div class="col-md-6">
                    <label for="inputName">Give your event a name</label>
                    <input ng-model="input.name" ng-disabled="loading" type="name" class="form-control" id="inputName" placeholder="Event Name">
                </div>
                <!-- Date -->
                <div class="col-md-3">
                    <label for="inputStartDate">Date of event</label>
                    <input ng-model="input.start" datepicker-popup="dd-MMM-yyyy" is-open="$parent.showStartCalendar" ng-focus="openStartCalendar($event)" class="form-control" id="inputStartDate" placeholder="ex. 23-Sep-2015" datepicker-options="dateOptions" close-text="Close" />
                </div>
                <div class="col-md-3">
                    <label for="inputStartTime">Start time</label>
                    <timepicker ng-model="input.start" minute-step="15" show-meridian="true"></timepicker>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3">
                    <label for="inputRestrictions">Event restrictions</label>
                    <select ng-model="input.restriction" ng-disabled="loading" ng-options="restriction.value as restriction.label for restriction in selectOptions.restrictions" class="form-control" id="inputRestrictions"></select> 
                </div>
                <div class="col-md-3">
                    <label for="inputDeadline">Deadline</label>
                    <input ng-model="input.deadline" datepicker-popup="dd-MMM-yyyy" is-open="$parent.showDeadlineCalendar" ng-focus="openDeadlineCalendar($event)" class="form-control" id="inputDeadline" placeholder="ex. 23-Sep-2015" datepicker-options="dateOptions" close-text="Close" />
                </div>
                <div class="col-md-3">
                    <label for="inputIncluded">What is included</label>
                    <input ng-model="input.included" ng-disabled="loading" type="text" class="form-control" id="inputIncluded" placeholder="Golf, cart, meal, etc...">
                </div>
                <div class="col-md-3">
                    <label for="inputCost">Player cost </label><i class="fa fa-info-circle" tooltip-placement="right" tooltip="The entered price will be increased by 2.5%"></i>
                    <input ng-hide="input.playerCostMulti" ng-model="input.playerCostRegular" ng-disabled="loading" type="price" class="form-control" id="inputCost" placeholder="Price" valid-decimal>
                    <div ng-show="input.playerCostMulti">
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2">N</span>
                          <input ng-model="input.playerCost0" type="text" class="form-control" placeholder="Non-Member" aria-describedby="sizing-addon2" valid-decimal>
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2">B</span>
                          <input ng-model="input.playerCost1" type="text" class="form-control" placeholder="Bronze" aria-describedby="sizing-addon2" valid-decimal>
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2">S</span>
                          <input ng-model="input.playerCost2" type="text" class="form-control" placeholder="Silver" aria-describedby="sizing-addon2" valid-decimal>
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2">G</span>
                          <input ng-model="input.playerCost3" type="text" class="form-control" placeholder="Gold" aria-describedby="sizing-addon2" valid-decimal>
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2">D</span>
                          <input ng-model="input.playerCost4" type="text" class="form-control" placeholder="Diamond" aria-describedby="sizing-addon2" valid-decimal>
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2">D+</span>
                          <input ng-model="input.playerCost5" type="text" class="form-control" placeholder="Diamond +" aria-describedby="sizing-addon2" valid-decimal>
                        </div>
                    </div>
                    <div class="pull-right">
                        <input ng-model="input.playerCostMulti" type="checkbox" id="showMultiplePrices">
                        <label for="showMultiplePrices" style="color:#9e9e9e; font-style:italic; font-size:14px"> Multiple Prices</label>
                    </div>
                </div>
            </div>
    
            <h2><small><i>Additional Information</i></small></h2>
            <div class="form-group">
                <div class="col-lg-6">
                    <label>Event description</label>
                    <textarea ng-model="input.description" ng-disabled="loading" class="form-control" rows="6" placeholder="Enter description here..."></textarea>
                </div>
                <div class="col-lg-6">
                    <label>Rules and organization</label>
                    <textarea ng-model="input.rules" ng-disabled="loading" class="form-control" rows="6" placeholder="Enter rules here..."></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button ng-click="submit(input)" ng-disabled="loading" class="btn btn-success"><i class="fa fa-check"></i> Create Event</button>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/drive.js"></script>