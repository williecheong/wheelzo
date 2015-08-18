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
                <div class="" role="form">  
                    <div class="form-group">  
                        <label class="control-label" for="origin">Travelling</label>  
                        <div class="row">
                            <div class="col-sm-6">
                                <input ng-model="input.origin" ng-change="searchRrequests(input)" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:3" class="form-control" id="origin" placeholder="Origin" autocomplete="off">  
                            </div>
                            <div class="col-sm-6" id="destination-group">
                                <div class="input-group">
                                    <input ng-model="input.destination" ng-change="searchRrequests(input)" ng-disabled="loading" typeahead="place for place in suggestedPlaces | filter:$viewValue | limitTo:3" class="form-control" id="destination" placeholder="Destination" autocomplete="off">  
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
                                <input ng-model="input.startDate" ng-change="searchRrequests(input)" datepicker-popup="MMMM d, yyyy" is-open="showCalendar" ng-focus="showCalendar=!showCalendar" datepicker-options="dateOptions" min-date="<?=time()*1000?>" ng-init="showCalendar=false" ng-disabled="loading" class="form-control" placeholder="ex. 23-Sep-2015" close-text="Close" />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label" for="departure-time">Departure Time</label>
                                <timepicker ng-model="input.startTime" minute-step="15" show-meridian="true"></timepicker>
                            </div>
                            <div class="col-sm-3 col-xs-5">  
                                <label class="control-label" for="price">
                                    <span class="visible-lg visible-md">Asking from each passenger</span>
                                    <span class="visible-sm visible-xs">Price</span>
                                </label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input ng-model="input.price" ng-disabled="loading" class="form-control" id="price" placeholder="Price" valid-number>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-7">
                                <label class="control-label" for="capacity">
                                    <span class="visible-lg visible-md">Number of seats available</span>
                                    <span class="visible-sm visible-xs">Capacity</span>
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
                </div>
            </div>
            <div class="col-sm-12">
                <div ng-if="searchingNow" class="well well-sm text-center">
                    <span class="lead">
                        <i class="fa fa-spinner fa-spin"></i>
                        Searching for potential passengers...
                    </span>
                </div>
                <div ng-if="!searchingNow && firstSearchExecuted && rrequests.length==0" class="well well-sm text-center">
                    <p class="lead mBottom5">
                        No potential passengers 
                        <span class="hidden-xs">¯\_(ツ)_/¯</span>
                    </p>
                    <p class="text-muted">
                        Tip: If you have a flexible schedule, driving on a different day might help
                    </p>
                </div>
                <div ng-if="!searchingNow && !firstSearchExecuted && rrequests.length==0" class="well well-sm">
                    <span class="lead visible-xs">Recommended Passengers</span>
                    <span class="lead hidden-xs">Recommended passengers for inviting to your ride</span>
                    <p>
                        <i class="fa fa-info-circle"></i> 
                        Send a Facebook notification to someone who has requested for a similar ride. 
                        Recommendations are automatically generated by searching for people travelling between the same cities on the day of.
                    </p>
                </div>
                <div ng-if="!searchingNow && rrequests.length>0" class="well well-sm">
                    <span class="lead">Set up an invitation by clicking on the row</span>
                    <table class="table table-hover table-condensed rrequests-table">
                        <thead>
                            <tr>
                                <th style="width:17.5%;">Requester</th>
                                <th style="width:27.5%;">Origin</th>
                                <th style="width:27.5%;">Destination</th>
                                <th style="width:17.5%;">Preferred Departure</th>
                                <th style="width:10%;">Invite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="rrequest in rrequests | limitTo:7">
                                <td>
                                    <a ng-click="openReviewModal(rrequest.user_id)" ng-bind="rrequest.user_name | shortenString:18" href="" target="_blank"></a>
                                </td>
                                <td>
                                    <a tooltip="<% rrequest.origin %>" tooltip-placement="right" class="clickable">
                                        <i class="fa fa-flag fa-border"></i>
                                    </a>
                                    <span ng-bind="rrequest.origin | shortenString:28"></span>
                                </td>
                                <td>
                                    <a tooltip="<% rrequest.destination %>" tooltip-placement="right" class="clickable">
                                        <i class="fa fa-flag fa-border"></i>
                                    </a>
                                    <span ng-bind="rrequest.destination | shortenString:28"></span>
                                </td>
                                <td ng-bind="rrequest.start | mysqlDateToIso | date:'MMM-d @ hh:mm a'">{{ date( 'M-d @ g:ia', strtotime($rrequest->start) ) }}</td>
                                <td class="">
                                    <i ng-if="!input.invitees[rrequest.id]" ng-click="input.invitees[rrequest.id]=true" class="fa fa-toggle-off fa-lg hoverable6 clickable"></i>
                                    <i ng-if="input.invitees[rrequest.id]" ng-click="input.invitees[rrequest.id]=false" class="fa fa-toggle-on fa-lg hoverable8 clickable"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="well well-sm">
                    <span class="lead visible-xs">
                        <a tooltip-html-unsafe="Permissions for Wheelzo must be configured to allow posting to your <img src='/assets/img/friends.png' style='margin-bottom:2px;max-height:15px;'>" tooltip-placement="right" href="https://www.facebook.com/bookmarks/apps" target="_blank">
                            <i class="fa fa-share-alt-square"></i>
                        </a>
                        Publish on Facebook
                        <i ng-show="loadingFacebookGroups" class="fa fa-spinner fa-spin"></i>
                    </span>
                    <span class="lead hidden-xs">
                        <a tooltip-html-unsafe="Permissions for Wheelzo must be configured to allow posting to your <img src='/assets/img/friends.png' style='margin-bottom:2px;max-height:15px;'>" tooltip-placement="right" href="https://www.facebook.com/bookmarks/apps" target="_blank">
                            <i class="fa fa-share-alt-square"></i>
                        </a>
                        Publish ride on Facebook
                        <i ng-show="loadingFacebookGroups" class="fa fa-spinner fa-spin"></i>
                    </span>
                    <div class="row">
                        <div ng-if="!loadingFacebookGroups && groups.length==0" class="text-center">
                            <span class="lead">No relevant groups found</span>
                        </div>
                        <div ng-repeat="group in groups" class="col-sm-4">
                            <label class="text-ellipsis clickable">
                                <input ng-model="input.shareToGroups[group.id]" ng-disabled="loading" type="checkbox">
                                <span ng-bind="group.name" tooltip="<% group.name %>" tooltip-placement="top"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="pull-left checkbox mTop5">
            <label>
                <input ng-model="input.allowPayments" ng-disabled="loading" type="checkbox"> 
                Allow 
                <span class="hidden-xs">passengers to make</span>
                online payments
            </label>
        </div>
        <button ng-click="submit(input)" ng-disabled="loading" class="btn btn-success">
            <i class="fa fa-truck"></i> Publish Ride
        </button>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/drive.js"></script>