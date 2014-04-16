/*******************
    WHEELZO EVENT HANDLERS
*******************/ 
    prepareRide = function( event ) {
        var rideID = $(this).data('ride-id');
        if ( !rides.hasOwnProperty(rideID) ) {
            alert("Ride is not available");
            return;
        }
        
        var thisRide = rides[rideID];
        var driver = publicUsers[ thisRide.driver_id ];
        var $modal = $('.modal#view-ride');

        $modal.find('a#driver-name')
              .attr('href', '//facebook.com/'+driver['facebook_id'])
              .html(driver['name']);

        $modal.find('a#driver-picture')
              .attr('href', '//facebook.com/'+driver['facebook_id']);

        $modal.find('img#driver-picture')
              .attr('src', '//graph.facebook.com/'+driver['facebook_id']+'/picture?width=200&height=200')
        
        $modal.find('#ride-departure')
              .html( moment(thisRide.start).format('dddd MMMM D, h:mm a') );        
        
        $modal.find('#ride-price')
              .html('$'+thisRide.price);
        
        $modal.find('#ride-passengers')
              .html( passengersTemplate(rideID) );

        $modal.find('#ride-origin')
              .html( thisRide.origin );
        
        $modal.find('#ride-destination')
              .html( thisRide.destination );
        
        $modal.find('#ride-dropoffs')
              .html( dropoffButtonTemplate(thisRide.drop_offs) );
            
        if ( thisRide.drop_offs.length > 0 ) {
            $modal.find('#show-dropoffs').popover({
                html        : true,
                trigger     : 'hover',
                placement   : 'auto top',
                title       : 'Other Drop-offs :',
                content     : dropoffContentTemplate( thisRide.drop_offs )
            });
        }

        $modal.find('#ride-comments')
              .html( commentsTemplate(thisRide.comments) );
        
        if ( session_id ) {
            if ( publicUsers[session_id].facebook_id == driver.facebook_id ) {
                $modal.find('input#write-comment').attr('placeholder', 'Write more details about your ride or respond to potential passengers');
            } else {
                $modal.find('input#write-comment').attr('placeholder', 'Write a request to join this ride or ask questions to the driver');    
            }
        }

        $modal.data('rideID', rideID);

        $modal.modal('toggle');
    }

    addDropoff = function( event ) {
        $button = $(this);
        $target = $button.closest('div#destination-group');
        var uid = uniqueid();

        $target.append( dropoffInputTemplate(uid) );
        
        var $newDropoff = $('div.dropoff#' + uid);
        $newDropoff.find('.dropoff-remover').click(function(){
            $newDropoff.remove();
        });

        $newDropoff.find('input.add_suggested_places').typeahead({
            source: defaultSuggestedPlaces
        });
    }

    saveRide = function( event ) {
        var $button = $(this);
        var $modal = $button.closest('.modal');
        
        $button.addClass('disabled');

        var validationFailed = validateRide( $modal );
        if ( validationFailed ) {
            alert( validationFailed );
            $button.removeClass('disabled');
            return;
        }

        postRide( extractModalRide($modal), $button );
    }

    saveComment = function( event ) {
        var $button = $(this);
        var $modal = $button.closest('.modal');
        
        $button.addClass('disabled');

        var rideID = $modal.data('rideID');
        var comment = $modal.find('input#write-comment').val();
        if ( comment == "" ) {
            alert("Write a comment");
            $button.removeClass('disabled');
            return false;
        }

        $.ajax({
            url: '/api/comments',
            data: {
                rideID : rideID,
                comment : comment
            },
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);

                if ( response.status == 'success' ){
                    var commentHtml = commentsTemplate( [response.comment] );

                    if ( $modal.find('div.dummy-comment').length > 0 ) {
                        $modal.find('#ride-comments').html( commentHtml );
                    } else {
                        $modal.find('#ride-comments').append( commentHtml );
                    }

                    $button.removeClass('disabled');                    
                } else {
                    alert(response.message);
                    $button.removeClass('disabled');
                }

                $modal.find('input#write-comment').val('');
                refreshRides();
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    }

    saveFeedback = function( event ) {
            var $button = $(this);
            $button.addClass('disabled');

            var email = $('input[name="feedback-email"]').val();
            var message = $('textarea[name="feedback-message"]').val();
            
            if ( message.length == 0 ) {
                alert("Message should not be empty");
                $button.removeClass('disabled');
                return false;
            }

            $.ajax({
                url: '/api/feedbacks',
                type: 'POST',
                dataType: "JSON",
                data: {
                    'email' : email,
                    'message': message
                },
                success: function(response) {
                    if ( response.status == 'success' ) {
                        $('input[name="feedback-email"]').attr('disabled', true);
                        $('textarea[name="feedback-message"]').attr('disabled', true);
                        $button.html('<i class="fa fa-check"></i> Message sent');
                    }
                    console.log(response.message);
                }, 
                error: function(response) {
                    alert('Fail: API could not be reached.');
                    $button.removeClass('disabled');
                    console.log(response);
                }
            });
        
        }
