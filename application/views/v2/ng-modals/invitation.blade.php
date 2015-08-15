<script type="text/ng-template" id="invitation.html">
    <div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-bullhorn"></i>
            Ride Request and Invitations
        </h4>
    </div>
    <div class="modal-body">
        <div ng-if="rrequest.invitations.length==0" class="well well-lg text-center lead">
            No invitations to show
        </div>
        <table ng-if="rrequest.invitations.length>0" class="table table-hover">
            <thead>
                <tr>
                    <th style="width:30%;">Origin</th>  
                    <th style="width:30%;">Destination</th>  
                    <th style="width:25%;">Departure</th> 
                    <th style="width:15%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="invitation in rrequest.invitations">
                    <td ng-bind="invitation.origin"></td>
                    <td ng-bind="invitation.destination"></td>
                    <td ng-bind="invitation.start | mysqlDateToIso | date:'MMM-d, h:mm a'"></td>
                    <td>
                        <a ng-click="openRideModal(invitation.id)" href="">
                            <i class="fa fa-external-link"></i> View
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>
            This is your request for a ride from 
            <strong>
                <span ng-bind="rrequest.origin"></span>
            </strong>
            to 
            <strong>
                <span ng-bind="rrequest.destination"></span>
            </strong>
            with preferred departure on
            <strong>
                <span ng-bind="rrequest.start | mysqlDateToIso | date:'EEEE, MMM-d'"></span>
            </strong>
            @
            <strong>
                <span ng-bind="rrequest.start | mysqlDateToIso | date:'h:mm a'"></span>
            </strong>.
        </p>
        <div class="well well-sm">
            <i class="fa fa-warning"></i> 
            Deleting this ride request will prevent you from getting any further Facebook notifications regarding this request.
            <button ng-click="deleteRrequest()" class="btn btn-danger btn-xs">
                <i class="fa fa-times"></i> Delete
            </button>
        </div>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/invitation.js"></script>