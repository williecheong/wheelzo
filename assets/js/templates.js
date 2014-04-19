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

        if ( rides[rideID].driver_id == session_id ) { // isOwner
            var commentersHTML = listofCommentersTemplate(rides[rideID].comments);

            $.each(rides[rideID].passengers, function(key, value){
                var passenger = publicUsers[value.user_id];
                var shortName = passenger.name.split(' ');
                shortName = shortenString( shortName[0], 7 );

                html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box" data-user_ride-id="'+value.id+'">'+
                        '    <div class="btn-group">'+                        
                        '        <a type="button" class="btn btn-default btn-xs" href="'+fbProfile(passenger.facebook_id)+'">'+
                        '            <i class="fa fa-facebook-square"></i> ' + shortName +
                        '        </a>'+
                        '        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">'+
                        '            <span class="caret"></span>'+
                        '            <span class="sr-only">Toggle Dropdown</span>'+
                        '        </button>'+
                        '        <ul class="dropdown-menu text-left" role="menu">'+
                        '            ' + commentersHTML + 
                        '        </ul>'+
                        '    </div>'+
                        '</div>';
                count++;
            });

            while ( count < capacity ) {
                html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box" data-user_ride-id="0">'+
                        '    <div class="btn-group">'+                        
                        '        <button type="button" class="btn btn-default btn-xs">'+
                        '            Empty'+
                        '        </button>'+
                        '        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">'+
                        '            <span class="caret"></span>'+
                        '            <span class="sr-only">Toggle Dropdown</span>'+
                        '        </button>'+
                        '        <ul class="dropdown-menu text-left" role="menu">'+
                        '            ' + commentersHTML +
                        '        </ul>'+
                        '    </div>'+
                        '</div>';
                count++;
            }

        } else { // is not owner
            $.each(rides[rideID].passengers, function(key, value){
                var passenger = publicUsers[value.user_id];
                html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box">'+
                        '    <a href="'+fbProfile(passenger.facebook_id)+'">'+
                        '        <img class="img-circle hoverable" id="passenger-picture" src="'+fbImage(passenger.facebook_id)+'">'+
                        '    </a>'+
                        '</div>';
                count++;
            });

            while ( count < capacity ) {
                html += '<div class="col-xs-'+colSizes[count]+'" id="passenger-box">'+
                        '    <img class="img-circle" id="passenger-picture" src="/assets/img/empty_user.png">'+
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
                    '    <a class="pull-left" href="'+fbProfile(publicUsers[commentObject.user_id].facebook_id)+'">'+
                    '        <img class="img-rounded media-object" src="'+fbImage(publicUsers[commentObject.user_id].facebook_id, 'square')+'">'+
                    '    </a>'+
                    '    <div class="media-body">'+
                    '        <div id="single-comment-message">'+
                    '            ' + commentObject.comment +
                    '        </div>'+
                    '        <small class="single-comment-meta">'+
                    '            <a href="'+fbProfile(publicUsers[commentObject.user_id].facebook_id)+'">' + publicUsers[commentObject.user_id].name + '</a> @ ' + moment(commentObject.last_updated).format('dddd MMMM D, h:mm a') +
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

    function listofCommentersTemplate( comments ) {
        var html = '';
        var commenters = {};
        var count = 0;

        $.each(comments, function(key, comment){
            commenter_id = comment.user_id;
            commenters[commenter_id] = publicUsers[commenter_id];
            count++;
        });

        if ( count > 0 ) {
            $.each(commenters, function(userID, user){
                html += '<li id="potential-passenger" data-user-id="'+userID+'">'+
                        '    <a href="#">' + 
                        '        ' + user.name + 
                        '    </a>'+
                        '</li>'; 
            });
        } else {
            html += '<li class="disabled">'+
                    '    <a href="#">' + 
                    '        <em>Nobody yet...</em>' + 
                    '    </a>'+
                    '</li>';
        }

        return html;
    }