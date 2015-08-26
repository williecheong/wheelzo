<script type="text/ng-template" id="drive.html">
    <div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-dashboard"></i>
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
                    <div class="visible-xs">
                        <span class="lead">Recommended Passengers</span>
                    </div>
                    <div class="hidden-xs">
                        <span class="lead">Recommended passengers for inviting to your ride</span>
                    </div>
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
            <div ng-if="permissions.publish_actions" class="col-sm-12">
                <div class="well well-sm">
                    <span class="pull-right">
                        <button ng-if="input.shareToFacebook" ng-click="input.shareToFacebook=false" ng-disabled="loading" class="btn btn-danger btn-xs">
                            <i class="fa fa-toggle-on"></i> OFF
                        </button>
                        <button ng-if="!input.shareToFacebook" ng-click="input.shareToFacebook=true" ng-disabled="loading" class="btn btn-success btn-xs">
                            <i class="fa fa-toggle-off"></i> ON
                        </button>
                    </span>
                    <span class="lead">
                        <a tooltip-html-unsafe="Permissions must be configured<br class='visible-xs'> to allow posting to your <img src='/assets/img/friends.png' style='margin-bottom:2px;max-height:15px;'>" tooltip-placement="right" href="https://www.facebook.com/bookmarks/apps" target="_blank">
                            <i class="fa fa-cog"></i>
                        </a>
                        Publish to Facebook
                    </span>
                    <div ng-if="!input.shareToFacebook" class="row">
                        <div class="col-md-8">
                            <div class="well well-sm">
                                <i class="fa fa-facebook-square"></i>
                                Reach a wider audience by allowing Wheelzo to publish this ride to Facebook.
                            </div>
                        </div>
                    </div>
                    <div ng-if="input.shareToFacebook" class="row">
                        <div class="col-md-5">
                            <div class="well well-sm mBottom5">
                                <div class="row" style="height:98px;overflow:scroll;">
                                    <div ng-if="fbgroups.length==0" class="text-center pTop15">
                                        <strong>No groups to show...</strong>
                                        <br> ¯\_(ツ)_/¯
                                    </div>
                                    <div ng-repeat="fbgroup in fbgroups" class="col-xs-12">
                                        <label class="clickable text-ellipsis">
                                            <input ng-model="input.shareToFacebookGroups[fbgroup.facebook_id]" ng-disabled="loading" type="checkbox"> 
                                            <% fbgroup.name %>
                                        </label>
                                    </div>
                                </div>
                                <hr class="opaque5 mTop5 mBottom5 mLeft5 mRight5">
                                <div class="text-center">
                                    <a ng-click="openFbgroupModal()" href="">
                                        <small>
                                            <i class="fa fa-cubes"></i> 
                                            Edit list
                                        </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <textarea ng-model="input.shareToFacebookMessage" ng-disabled="loading" placeholder="e.g. space in the trunk for baggage..." rows="5" class="form-control resize-none"></textarea>
                            <small class="text-muted">
                                <i class="fa fa-thumb-tack"></i>
                                Optional: Include more information about this ride.<br class="hidden-xs">
                                The message and a direct link will be posted to the selected Facebook groups. 
                            </small>
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