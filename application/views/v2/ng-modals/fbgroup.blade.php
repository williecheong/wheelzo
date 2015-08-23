<script type="text/ng-template" id="fbgroup.html">
    <div class="modal-header visible-xs" style="background:none;border-bottom:0px;">
        <button ng-click="cancel()" type="button" style="color:#333;" class="close">&times;</button>
    </div>
    <div class="modal-body">
        <div class="text-center">
            <span class="lead">
                <i class="fa fa-cubes"></i>
                My Groups
            </span>
        </div>
        <div style="max-height:200px;overflow:scroll;">
            <table class="table table-condensed">
                <tbody style="max-height:200px;">
                    <tr ng-if="personalFbgroups.length==0">
                        <td class="text-center">
                            <strong>No groups to show...</strong>
                            <br> ¯\_(ツ)_/¯
                        </td>
                    </tr>
                    <tr ng-repeat="fbgroup in personalFbgroups">
                        <td>
                            <button ng-click="dropFromPersonal(fbgroup.facebook_id)" ng-disabled="loading" class="btn btn-danger btn-xs pull-right mRight10">
                                <i class="fa fa-times"></i>
                                Drop
                            </button>
                            <a href="<% fbgroup.facebook_id | fbGroup %>" target="_blank">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <span ng-bind="fbgroup.name"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="well well-sm">
            <i class="fa fa-exclamation-triangle"></i>
            To publish a ride to any Facebook group, you must already be registered as a member.
            Facebook policies do not allow outsiders and non-members to make posts inside a group.
        </div>

        <div class="text-center">
            <span class="lead">
                <i class="fa fa-globe"></i>
                Known Groups
            </span>
        </div>
        <div style="max-height:200px;overflow:scroll;">
            <table class="table table-condensed">
                <tbody>
                    <tr ng-if="unpersonalFbgroups.length==0">
                        <td class="text-center">
                            <strong>No groups to show...</strong>
                            <br> ¯\_(ツ)_/¯
                        </td>
                    </tr>
                    <tr ng-repeat="fbgroup in unpersonalFbgroups">
                        <td>
                            <button ng-click="addToPersonal(fbgroup.facebook_id)" ng-disabled="loading" class="btn btn-success btn-xs pull-right mRight10">
                                <i class="fa fa-plus-square-o"></i>
                                Add
                            </button>
                            <a href="<% fbgroup.facebook_id | fbGroup %>" target="_blank">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <span ng-bind="fbgroup.name"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="opaque5 mLeft10 mRight10">

        <form>
            <div class="input-group">
                <input ng-model="inputFacebookId" ng-disabled="loading" type="text" class="form-control" placeholder="e.g. 372772186164295">
                <span class="input-group-btn">
                    <button ng-click="introduceFbgroup(inputFacebookId)" ng-disabled="loading" class="btn btn-info">
                        <i class="fa fa-globe fa-lg"></i>
                        <span class="hidden-xs">Introduce</span>
                    </button>
                </span>
            </div>
            <p class="text-muted mLeft5 mRight20">
                <i class="fa fa-facebook-square"></i>
                Introduce an unknown rideshare group from Facebook and help grow the community
            </p>
        </form>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/fbgroup.js"></script>