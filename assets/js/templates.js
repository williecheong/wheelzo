/*******************
    WHEELZO HTML TEMPLATE FUNCTIONS
*******************/ 
    function passengersTemplate( rideID ) {
        var html = '';
        
        var isOwner = ( rides[rideID].driver_id == session_id );
        var ownerClass = isOwner ? ' ride-owner' : '' ;

        var capacity = parseInt( rides[rideID].capacity );
        var colSizes = [];
        if      ( capacity == 1 ) { colSizes = [12]; } 
        else if ( capacity == 2 ) { colSizes = [6,6]; } 
        else if ( capacity == 3 ) { colSizes = [4,4,4]; } 
        else if ( capacity == 4 ) { colSizes = [3,3,3,3]; } 
        else if ( capacity == 5 ) { colSizes = [4,4,4,6,6]; } 
        else if ( capacity == 6 ) { colSizes = [4,4,4,4,4,4]; } 
        else if ( capacity == 7 ) { colSizes = [3,3,3,3,4,4,4]; }
        else { for(var i=0; i < capacity; i++) colSizes[i] = 2; }
        var count = 0 ;

        $.each(rides[rideID].passengers, function(key, value){
            var passenger = publicUsers[value.user_id];
            html += '<div class="col-xs-'+colSizes[count]+'">'+
                    '    <img class="img-circle'+ownerClass+'" id="passenger-picture" data-passenger-id="'+value.user_id+'" src="//graph.facebook.com/'+passenger.facebook_id+'/picture?width=200&height=200">'+
                    '</div>';
            count++;
        });

        while ( count < capacity ) {
            html += '<div class="col-xs-'+colSizes[count]+'">'+
                    '    <img class="img-circle'+ownerClass+'" id="passenger-picture" data-passenger-id="" src="/assets/img/empty_user.png">'+
                    '</div>';
            count++;
        }

        return html;
    }

    function commentsTemplate( comments ) {
        var html = '';
        var count = 0;
        $.each(comments, function(key, commentObject){
            html += '<div class="media">'+
                    '    <a class="pull-left" href="//facebook.com/'+publicUsers[commentObject.user_id].facebook_id+'">'+
                    '        <img class="img-rounded media-object" src="//graph.facebook.com/'+publicUsers[commentObject.user_id].facebook_id+'/picture?type=square">'+
                    '    </a>'+
                    '    <div class="media-body">'+
                    '        <div id="single-comment-message">'+
                    '            ' + commentObject.comment +
                    '        </div>'+
                    '        <small class="single-comment-meta">'+
                    '            <a href="//facebook.com/'+publicUsers[commentObject.user_id].facebook_id + '">' + publicUsers[commentObject.user_id].name + '</a> @ ' + moment(commentObject.last_updated).format('dddd MMMM D, h:mm a') +
                    '        </small>'+
                    '    </div>'+
                    '</div>';
            count++;
        }); 

        if ( count == 0 ) {
            html = '<div class="media dummy-comment text-center">'+
                   '    <em>No comments to display ...</em>'+
                   '</div>';
        }

        return html;
    }

    function dropoffInputTemplate( id ) {
        var html = '';
        html += '<div class="right-inner-addon dropoff" id="'+id+'">'+
                '    <a class="dropoff-remover" href="#">'+
                '        <i class="fa fa-times"></i>'+
                '    </a>'+
                '    <input type="text" class="form-control add_suggested_places" placeholder="Dropoff location">'+
                '</div>';
        return html;
    }

    function dropoffButtonTemplate( dropOffs ) {
        var html = '';
        if ( dropOffs.length > 0 ) {
            html += '<a href="#" id="show-dropoffs"> '+
                    '   <i class="fa fa-flag-checkered fa-lg fa-border"></i>'+
                    '</a>';
        }
    
        return html;    
    }

    function dropoffContentTemplate( dropOffs ) {
        var html = '';
        html += '<ul class="fa-ul">';
        $.each(dropOffs, function(key, dropOff){
            html += '<li><i class="fa-li fa fa-flag"></i>' + dropOff + '</li>';
        });
        html += '</ul>';
        
        return html;
    }