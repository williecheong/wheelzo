<script type="text/ng-template" id="review.html">
	<div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-group"></i> 
            Lookup
        </h4>
    </div>
    <div class="modal-body"> 
        <div class="row">
            <div class="col-lg-12">
                <p class="mBottom5 text-center">
                    <small>
                        <strong>ABOUT</strong>
                    </small>
                    <br>
                    <span ng-bind="user.name || 'LOADING'" class="lead"></span>
                </p>
            </div>
        </div>
        <hr class="opaque5 mTop5 mBottom15">
        <div class="row">
            <div class="col-xs-7 text-right">
                <a href="<% user.facebook_id | fbProfile %>" target="_blank" id="lookup-picture">
                    <img ng-src="<% (user.facebook_id | fbImage) || '/assets/img/empty_user.png'%>" class="img-circle greyed-out hoverable8" id="lookup-picture">
                </a>
            </div>
            <div class="col-xs-5 text-center">
                <strong>
                    Reputation Points
                    <i class="fa fa-paw"></i>
                </strong>
                <h3 class="mTop10">
                    <span ng-bind="user.score" class="label label-success">0.00</span>
                </h3>
                <br>
                <a ng-click="submitPoint()" ng-disabled="loading||session.user_id==0||session.user_id==user.id" class="btn btn-success btn-sm">
                    <i class="fa fa-child fa-lg"></i> Vouch
                </a>
            </div>
            <br>
        </div>
        <hr class="opaque5 mTop15 mBottom15">
        <div class="row">
            <div class="col-lg-12 media">
                <div class="input-group">
                    <input ng-model="inputReview" ng-disabled="loading||session.user_id==0||session.user_id==user.id" type="text" class="form-control" placeholder="Write a review for ..." autocomplete="off">
                    <span class="input-group-btn">
                        <button ng-click="submitReview(inputReview)" ng-disabled="loading||session.user_id==0||session.user_id==user.id" class="btn btn-default">
                            <i class="fa fa-comment fa-lg"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div ng-if="user.reviews.length>0" class="col-lg-12" id="lookup-reviews">
                <div ng-repeat="reviewObject in user.reviews" class="media">
                    <div class="pull-left">
                        <img class="img-rounded media-object" id="reviewer-picture" src="/assets/img/empty_user.png">
                    </div>
                    <div class="media-body">
                        <div ng-bind="reviewObject.review" id="single-review-message"></div>
                        <small class="single-review-meta">
                            Reviewed @ <% reviewObject.last_updated | mysqlDateToIso | date:'longDate' %>
                            <a ng-if="session.user_id==reviewObject.giver_id" ng-click="deleteReview(reviewObject.id)" href=""><i class="fa fa-trash-o fa-border"></i></a>
                        </small>
                    </div>
                </div>
            </div>
            <div ng-if="user.reviews.length==0" class="col-lg-12" id="lookup-reviews">
                <div class="media dummy-review text-center">    
                    <em>
                        No Reviews to display...
                    </em>
                </div>
            </div>
        </div>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/review.js"></script>