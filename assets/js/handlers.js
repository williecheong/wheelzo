/*******************
    WHEELZO EVENT HANDLERS
*******************/ 
    prepareRide = function( event ) {
        var rideID = $(this).data('ride-id');
        if ( !rides.hasOwnProperty(rideID) ) {
            var r = confirm("The ride you are seeking is no longer available.\nWould you like to refresh Wheelzo?");
            if ( r == true ) {
                location.reload();
            } else {
                return;
            }
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

        $modal.find('#ride-comments')
              .html( commentsTemplate(thisRide.comments) );

        $modal.data('rideID', rideID);

        initializeRide( rideID )
        $modal.modal('show');
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
        var $input = $modal.find('input#write-comment');
        
        $button.addClass('disabled');
        $input.attr('disabled', true);
        
        var rideID = $modal.data('rideID');
        var comment = $input.val();

        if ( comment == "" ) {
            alert("Write a comment");
            $button.removeClass('disabled');
            $input.removeAttr('disabled');
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
                    refreshRides(function(){
                        $button.removeClass('disabled');
                        $input.removeAttr('disabled');
                        $input.val('');
                        $('tr[data-ride-id="'+rideID+'"]').trigger('click');
                    });

                } else {
                    $button.removeClass('disabled');
                    $input.removeAttr('disabled');
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                $input.removeAttr('disabled');
                console.log(response);
            }
        });
    }

    handlePassenger = function ( event ) {
        var $listItem = $(this);
        var passengerID = $listItem.attr('data-user-id');
        var user_rideID = $listItem.closest('div#passenger-box').attr('data-user_ride-id');

        if ( passengerID == '0' ) {
            deleteUser_ride(user_rideID);
        } else if ( user_rideID > 0 ) {
            putUser_ride(passengerID, user_rideID);
        } else {
            var $modal = $listItem.closest('.modal');
            var rideID = $modal.data('rideID');
            postUser_ride(passengerID, rideID);
        }
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
