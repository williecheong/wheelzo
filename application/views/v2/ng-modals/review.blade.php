<script type="text/ng-template" id="review.html">
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-group"></i> Lookup
        </h4>
    </div>
    <div class="modal-body"> 
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" class="form-control hide" id="lookup-id" value="1">
                <input type="text" class="form-control add_suggested_names" id="lookup-name" placeholder="Who are you looking for? ¯\_(ツ)_/¯" autocomplete="off"> 
            </div>
        </div>
        <div class="row">
            <br>
            <div class="col-xs-7 text-right">
                <a id="lookup-picture" target="" href="#">
                    <img class="img-circle greyed-out" id="lookup-picture" src="/assets/img/empty_user.png">
                </a>
            </div>
            <div class="col-xs-5 text-center">
                <strong>REP</strong>
                <a href="#about-scores"> 
                    <i class="fa fa-info-circle"></i>
                </a>
                <h3>
                    <span class="label label-success" id="lookup-score">
                        0.00
                    </span>
                </h3>
                <br>
                <a class="btn btn-success btn-sm disabled" id="give-point" role="button">
                    <i class="fa fa-child fa-lg"></i> Vouch
                </a>
            </div>
            <br>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12 media">
                <div class="input-group">
                    <input type="text" class="form-control" id="write-review" placeholder="Write a review for ..." autocomplete="off" disabled>
                    <span class="input-group-btn">
                        <button class="btn btn-default disabled" type="button" id="post-review">
                            <i class="fa fa-comment fa-lg"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-lg-12" id="lookup-reviews">
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