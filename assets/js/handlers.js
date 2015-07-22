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
              .attr('href', fbProfile(driver['facebook_id']))
              .html(driver['name']);

        $modal.find('a#driver-picture')
              .attr('href', fbProfile(driver['facebook_id']));

        $modal.find('img#driver-picture')
              .attr('src', fbImage(driver['facebook_id']))
        
        $modal.find('#ride-departure')
              .html( moment(thisRide.start).format('dddd MMM D, h:mma') );        
        
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

        $modal.find('a#go-to-ride')
              .attr('href', '?ride='+rideID);

        $modal.data('rideID', rideID);

        initializeRide( rideID )
        $modal.modal('show');
    }

    prepareRrequest = function( event ) {
        var rrequestID = $(this).data('rrequest-id');
        var thisRrequest = myRrequests[rrequestID];
        
        var $modal = $('.modal#view-rrequest');

        $modal.find('#rrequest-departure')
              .html( moment(thisRrequest.start).format('dddd MMMM D, h:mm a') );
        
        $modal.find('#rrequest-origin')
              .html( thisRrequest.origin );
        
        $modal.find('#rrequest-destination')
              .html( thisRrequest.destination );

        $modal.find('table.invitations-table tbody')
              .html( invitationsTableTemplate(rrequestID) );

        $modal.data('rrequestID', rrequestID);
        $modal.modal('show');
    }

    addDropoff = function( event ) {
        $button = $(this);
        $target = $button.closest('div#destination-group');
        var uid = uniqueid();

        $target.append( dropoffInputTemplate(uid) );
        
        var $newDropoff = $('div.dropoff#' + uid);
        $newDropoff.find('.dropoff-remover').click(function(){
            $newDropoff.find('input').val('');
            $newDropoff.find('input').trigger('focusout');
            $newDropoff.remove();
        });

        $newDropoff.find('input.add_suggested_places').typeahead({
            source: defaultSuggestedPlaces
        });

        $newDropoff.find('input').on('keyup focusout', searchRrequests)
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

    removeRide = function( event ) {
        var r = confirm("Delete this ride permanently?");
        if ( r == true ) {
            var $button = $(this);
            var $modal = $button.closest('.modal');
            var rideID = $modal.data('rideID');
            
            $button.addClass('disabled');

            deleteRide( rideID, $button ); 
        } else {
            return;
        }
    }

    saveRrequest = function( event ) {
        var $button = $(this);
        var $modal = $button.closest('.modal');
        
        $button.addClass('disabled');

        var validationFailed = validateRrequest( $modal );
        if ( validationFailed ) {
            alert( validationFailed );
            $button.removeClass('disabled');
            return;
        }

        postRrequest( extractModalRrequest($modal), $button );
    }

    removeRrequest = function( event ) {
        var r = confirm("Delete this ride request permanently?");
        if ( r == true ) {
            var $button = $(this);
            var $modal = $button.closest('.modal');
            var rrequestID = $modal.data('rrequestID');
            
            $button.addClass('disabled');

            deleteRrequest( rrequestID, $button ); 
        } else {
            return;
        }
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
            url: '/api/v1/comments',
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

    savePoint = function( event ) {
        var $button = $(this);
        var $modal = $button.closest('.modal');
        
        var receiverID = $modal.find('input#lookup-id').val();
        $button.addClass('disabled');        
        
        $.ajax({
            url: '/api/v1/points',
            data: {
                receiver_id : receiverID
            },
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);

                if ( response.status == 'success' ){
                    refreshUsers(function(){
                        $('input#lookup-id').trigger('change');    
                    });

                } else {
                    $button.removeClass('disabled');
                    alert(response.message);
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    }

    saveReview = function( event ){
        var $button = $(this);
        var $modal = $button.closest('.modal');
        var $input = $modal.find('input#write-review');
        
        $button.addClass('disabled');
        $input.attr('disabled', true);
        
        var receiverID = $modal.find('input#lookup-id').val();
        var review = $input.val();

        if ( review == "" ) {
            alert("Write a review");
            $button.removeClass('disabled');
            $input.removeAttr('disabled');
            return false;
        }

        $.ajax({
            url: '/api/v1/reviews',
            data: {
                receiver_id : receiverID,
                review : review
            },
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);

                if ( response.status == 'success' ){
                    $('input#lookup-id').trigger('change')
                    $input.val('');
                    
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

    removeReview = function( event ) {
        var $button = $(this);        
        var reviewID = $button.attr('id');
        $button.off('click', removeReview);

        deleteReview( reviewID, $button ); 
    }

    handlePayment = function ( event ) {
        var $listItem = $(this);
        var thisRide = rides[$listItem.attr('data-ride-id')];
        var thisDriver = publicUsers[thisRide.driver_id];

        var stripeHandler = StripeCheckout.configure({
            key: stripePublicKey,
            image: fbImage(thisDriver.facebook_id),
            token: function(token) {
                // You can access the token ID with `token.id`
                postUser_ride(thisRide.id, token);
            }
        });

        stripeHandler.open({
            name: 'Reserve Seat',
            image: fbImage(thisDriver.facebook_id),
            description : 'Make a payment to ' + thisDriver.name,
            amount : parseFloat(thisRide.price) * 100,
            currency : 'CAD',
            allowRememberMe : false
        });

        event.preventDefault();
    }

    lookupUser = function ( event ) {
        var user_id = $('input#lookup-id').val();
        // Populate the lookup modal here...
        var $modal = $(this).closest('.modal');
        
        $modal.find('a#lookup-picture').attr('href', fbProfile(publicUsers[user_id].facebook_id) )
                                       .attr('target', '_blank' );
        $modal.find('img#lookup-picture').attr('src', fbImage(publicUsers[user_id].facebook_id) )
                                         .removeClass('greyed-out');

        $modal.find('span#lookup-score').html(publicUsers[user_id].score);
        $modal.find('input#write-review').attr('placeholder', 'Write a review for ' + publicUsers[user_id].name);

        if ( session_id == false || session_id == user_id ) {
            $modal.find('.btn#give-point').addClass('disabled');
            $modal.find('.btn#post-review').addClass('disabled');
            $modal.find('input#write-review').attr('disabled', true);
        } else {
            $modal.find('.btn#give-point').removeClass('disabled');
            $modal.find('.btn#post-review').removeClass('disabled');
            $modal.find('input#write-review').removeAttr('disabled');
        }

        getReviews( user_id, $modal.find('div#lookup-reviews') );
    }

    searchRidesByOrigin = function ( event ) {
        var searchTerm = $('input#search-origin').val();
        rideTable.fnFilterAll(searchTerm, 0);
    }

    searchRidesByDestination = function ( event ) {
        var searchTerm = $('input#search-destination').val();
        rideTable.fnFilterAll(searchTerm, 1);
    }

    searchRrequests = function ( event ) {
        var rowValue = '';
        var columnName = $(this).attr('id');

        if ( columnName == 'origin' ) {
            columnName = '1';
            rowValue = filterCity( $(this).val() );

        } else if ( columnName == 'destination' || columnName == 'dropoff-field' ) {
            columnName = '2';
            
            var destinations = [];
            var destination = $('input#destination').val();
            destination = destination.trim();
            if ( destination != '' ) {
                destinations.push( filterCity(destination) );
            }

            $('.modal#create-ride').find('div.dropoff').each(function(){
                var tempDropoff = $(this).find('input').val();
                tempDropoff = tempDropoff.trim();
                if ( tempDropoff !== '' ) {
                    destinations.push( filterCity(tempDropoff) );
                }
            });

            rowValue = destinations.join('|');

        } else if ( columnName == 'departure-date' ) {
            columnName = '3';
            if ( $(this).val() ) {
                rowValue = moment( $(this).val() ).format('MMM-DD');            
            }
        }

        // var searchParam = {};
        // searchParam[columnName] = rowValue;
        // rrequestTable.fnMultiFilter( searchParam );
        $('table.rrequests-table').DataTable().column( columnName ).search(
            rowValue,
            true,
            false
        ).draw();

        if ( $('table.rrequests-table tbody tr').length < 6 
            || ( $('input#origin').val() != '' 
                 && $('input#destination').val() != '' 
                 && $('input#departure-date').val() != '' ) ) {
            $('div.non-rrequests-table-container').hide();
            $('div.rrequests-table-container').show();
        } else {
            $('div.rrequests-table-container').hide();
            $('div.non-rrequests-table-container').show();
            $('table.rrequests-table tbody tr').removeClass('success');
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
            url: '/api/v1/feedbacks',
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
