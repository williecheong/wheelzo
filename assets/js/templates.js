/*******************
    WHEELZO HTML TEMPLATE FUNCTIONS
*******************/ 
    function passengersTemplate( rideID ) {
        var html = '';
        
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
            html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box">'+
                    '    <a target="_blank" href="'+fbProfile(passenger.facebook_id)+'">'+
                    '        <img class="img-circle hoverable" id="passenger-picture" src="'+fbImage(passenger.facebook_id)+'">'+
                    '    </a>'+
                    '</div>';
            count++;
        });

        if ( rides[rideID].driver_id == session_id || session_id == false) { // isOwner
            // Owners will see vacant stock
            while ( count < capacity ) {
                html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box">'+
                        '    <img class="img-circle" id="passenger-picture" src="/assets/img/empty_user.png">'+
                        '</div>';
                count++;
            }
        } else { // is not owner
            // Non owners get a button that will bring up stripe
            while ( count < capacity ) {
                html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box">'+
                        '    <a href="#" id="open-payment" data-ride-id="'+rideID+'">'+
                        '        <img class="img-circle hoverable" id="passenger-picture" src="/assets/img/payment.png">'+
                        '    </a>'+
                        '</div>';
                count++;
            }
        }
        return html;
    }

    function commentsTemplate( comments ) {
        var html = '';
        var count = 0;
        $.each(comments, function(key, commentObject){
            html += '<div class="media">'+
                    '    <a class="pull-left" target="_blank" href="'+fbProfile(publicUsers[commentObject.user_id].facebook_id)+'">'+
                    '        <img class="img-rounded media-object" src="'+fbImage(publicUsers[commentObject.user_id].facebook_id, 'square')+'">'+
                    '    </a>'+
                    '    <div class="media-body">'+
                    '        <div id="single-comment-message">'+
                    '            ' + commentObject.comment +
                    '        </div>'+
                    '        <small class="single-comment-meta">'+
                    '            <a target="_blank" href="'+fbProfile(publicUsers[commentObject.user_id].facebook_id)+'">' + publicUsers[commentObject.user_id].name + '</a> @ ' + moment(commentObject.last_updated).format('dddd MMMM D, h:mm a') +
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

    function reviewsTemplate( reviews ) {
        var html = '';
        var count = 0;
        $.each(reviews, function(key, reviewObject){
            var printName = publicUsers[reviewObject.giver_id].name;
            printName = printName.split(' ');
            printName = shortenString( printName[0], 7 );

            html += '<div class="media">'+
                    '    <div class="pull-left">'+
                    '        <img class="img-rounded media-object" id="reviewer-picture" src="/assets/img/empty_user.png">'+
                    '    </div>'+
                    '    <div class="media-body">'+
                    '        <div id="single-review-message">'+
                    '            ' + reviewObject.review +
                    '        </div>'+
                    '        <small class="single-review-meta">'+
                    '            Reviewed @ ' + moment(reviewObject.last_updated).format('MMM D, YYYY');
            
            if ( reviewObject.giver_id == session_id ) {
                html += '        <a href="#delete-review" id="'+reviewObject.id+'"><i class="fa fa-trash-o fa-border"></i></a>';
            }

            html += '        </small>'+
                    '    </div>'+
                    '</div>';
            count++;
        }); 

        if ( count == 0 ) {
            html = '<div class="media dummy-review text-center">'+
                   '    <em>No Reviews to display ...</em>'+
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
                '    <input type="text" class="form-control add_suggested_places" id="dropoff-field" placeholder="Dropoff location">'+
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

    function invitationsTableTemplate( rrequestID ) {
        var html = '';
        var rrequest = myRrequests[rrequestID];
        var count = 0;
        $.each(rrequest.invitations, function(key, ride){
            // In the order of Origin, Destination, Departure, View(link)
            html += '<tr>'+
                    '    <td>'+ ride.origin +'</td>'+
                    '    <td>'+ ride.destination +'</td>'+
                    '    <td>'+ moment( ride.start ).format( 'MMMM D, h:mm a' ) +'</td>'+
                    '    <td>'+
                    '        <a href="/?ride='+ride.id+'">'+
                    '            <i class="fa fa-link"></i> Ride'+
                    '        </a>'+
                    '    </td>'+
                    '</tr>';
            count++;
        });

        if ( count == 0 ) {
            html += 'No invitations yet.';
        }

        return html;
    }