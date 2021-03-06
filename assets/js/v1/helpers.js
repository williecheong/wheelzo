/*******************
    REST EXECUTION HELPERS
*******************/ 
    function postRide( saveRide, $button ) {
        $.ajax({
            url: '/api/v1/rides',
            data: saveRide,
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if (response.status == 'success') {
                    $button.html('<i class="fa fa-refresh"></i> Refreshing');
                    setTimeout(function() {
                        // Simple page refresh for now
                        location.href = '/?ride=' + response.ride.id;
                    }, 1500);                    
                } else {
                    $button.removeClass('disabled');
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    }

    function deleteRide( rideID, $button ) {
        $.ajax({
            url: '/api/v1/rides/index/' + rideID,
            type: 'DELETE',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if (response.status == 'success') {
                    $button.html('<i class="fa fa-refresh"></i> Refreshing');               
                    setTimeout(function() {
                        // Simple page refresh for now
                        location.reload();
                    }, 1500);
                } else {
                    $button.removeClass('disabled');
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    }

    function postUser_ride( rideID, stripeToken ) {
        $.ajax({
            url: '/api/v2/user_rides',
            data: {
                "rideID" : rideID,
                "stripeToken" : stripeToken.id,
                "receiptEmail" : stripeToken.email
            },
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                refreshRides(function(){
                    $('tr[data-ride-id="'+rideID+'"]').trigger('click');
                });
            }, 
            error: function(response) {
                alert('Error: ' + response.message);
                console.log(response);
            }
        });
    }

    function postRrequest( saveRrequest, $button ) {
        $.ajax({
            url: '/api/v1/rrequests',
            data: saveRrequest,
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if (response.status == 'success') {
                    $button.html('<i class="fa fa-refresh"></i> Refreshing');
                    setTimeout(function() {
                        // Simple page refresh for now
                        location.reload();
                    }, 1500);                    
                } else {
                    $button.removeClass('disabled');
                }
            },
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    }

    function deleteRrequest( rrequestID, $button ) {
        $.ajax({
            url: '/api/v1/rrequests/index/' + rrequestID,
            type: 'DELETE',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if (response.status == 'success') {
                    $button.html('<i class="fa fa-refresh"></i> Refreshing');               
                    setTimeout(function() {
                        // Simple page refresh for now
                        location.reload();
                    }, 1500);
                } else {
                    $button.removeClass('disabled');
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    }

    function getReviews( user_id, $reviewBox ) {
        $reviewBox.html('<i class="fa fa-cog fa-spin fa-2x"></i>').addClass('text-center');
        $.ajax({
            url: '/api/v1/reviews?receiver_id=' + user_id,
            type: 'GET',
            dataType: "JSON",
            success: function( reviews ) {
                $reviewBox.removeClass('text-center').html( reviewsTemplate(reviews) );
                $('a[href="#delete-review"]').on('click', removeReview);
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                console.log(response);
            }
        });
    }

    function deleteReview( reviewID, $button ) {
        $.ajax({
            url: '/api/v1/reviews/index/' + reviewID,
            type: 'DELETE',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if (response.status == 'success') {
                    $button.html('<i class="fa fa-refresh fa-spin"></i>');
                    setTimeout(function() {
                        $('input#lookup-id').trigger('change')
                    }, 1500);
                } else {
                    alert(response.message);
                    $button.on('click', removeReview);
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.'); 
                $button.on('click', removeReview);
                console.log(response);
            }
        });
    }

/*******************
    WHEELZO HELPER FUNCTIONS
*******************/ 
    function validateRide( $modal ) {
        var ride = extractModalRide( $modal );
        
        if ( ride.origin.length == 0 || ride.destination.length == 0 ) {
            return 'Origin and destination cannot be empty.';

        } else if ( ride.origin == ride.destination ) {
            return 'Origin and destination cannot be the same.';

        } else if ( ride.departureDate.length == 0 || ride.departureTime == 0 ) {
            return 'Departure date and time must be specified.';
        
        } else if ( ride.capacity > 7 ) {
            return 'Are you driving a bus?';

        } else if (ride.allowPayments != 0 && ride.allowPayments != 1) {
            return 'So do you want online payments or not?';
        }

        return false;
    }

    function extractModalRide( $modal ) {
        var dropoffs = [];
        $modal.find('div.dropoff').each(function(){
            var tempDropoff = $(this).find('input').val();
            tempDropoff = tempDropoff.trim();
            if ( tempDropoff !== '' ) {
                dropoffs.push( tempDropoff );
            }
        });

        var invitees = [];
        $modal.find('tr[data-rrequest-id].success').each(function(){
            invitees.push( $(this).data('rrequest-id') );
        });

        var data = {
            origin          : $modal.find('input#origin').val(),
            destination     : $modal.find('input#destination').val(),
            departureDate   : $modal.find('input#departure-date').val(),
            departureTime   : $modal.find('input#departure-time').val(),
            price           : $modal.find('span#price').text(),
            capacity        : $modal.find('span#capacity').text(),
            allowPayments   : $modal.find('input#allow-payments').is(':checked') ? 1 : 0,
            dropOffs        : dropoffs,
            invitees        : invitees
        };

        return data;
    }

    function refreshRides( callback ) {
        var url = "/api/v1/rides";
        if ( location.pathname == '/me' ) {
            url = "/api/v1/rides/me";
        }

        $.get( url, function( response ) {
            rides = JSON.parse(response);
            console.log("Object rides has been refreshed.")
            callback();
        });
    }

    function refreshUsers( callback ) {
        var url = "/api/v1/users";

        $.get( url, function( response ) {
            publicUsers = JSON.parse(response);
            console.log("Object publicUsers has been refreshed.")
            callback();
        });   
    }

    function validateRrequest( $modal ) {
        var rrequest = extractModalRrequest( $modal );
        
        if ( rrequest.origin.length == 0 || rrequest.destination.length == 0 ) {
            return 'Origin and destination cannot be empty.';

        } else if ( rrequest.origin == rrequest.destination ) {
            return 'Origin and destination cannot be the same.';

        } else if ( rrequest.departureDate.length == 0 || rrequest.departureTime == 0 ) {
            return 'Departure date and time must be specified.';
        }

        return false;
    }

    function extractModalRrequest( $modal ) {
        var data = {
            origin          : $modal.find('input#request-origin').val(),
            destination     : $modal.find('input#request-destination').val(),
            departureDate   : $modal.find('input#request-departure-date').val(),
            departureTime   : $modal.find('input#request-departure-time').val()
        };

        return data;
    }

/*******************
    GENERAL HELPER FUNCTIONS
*******************/ 
    function fbProfile( fbID ) {
        return '//facebook.com/' + fbID ;
    }

    function fbImage( fbID, type ) {
        if ( type == 'square' ) {
            return '//graph.facebook.com/'+fbID+'/picture?type=square';        
        } else {
            return '//graph.facebook.com/'+fbID+'/picture?width=200&height=200';
        }
    }

    function filterCity( input ) {
        var commaExplode = input.split(',');
        if ( !commaExplode[0] ) {
            // If it is not found, we have a problem
            return input;
        }

        var spaceExplode = commaExplode[0].split(' ');
        if ( !spaceExplode[0] ) {
            return commaExplode[0];
        }

        return spaceExplode[0];
    }

    function shortenString( subject, size ) {
        if ( subject.length > size ) {
            return subject.substring(0, size-3) + '...';
        } else {
            return subject;
        }
    }

    /*******************
        Adapted from: 
        http://stackoverflow.com/questions/3231459/create-unique-id-with-javascript
    *******************/ 
    function uniqueid(){
        // always start with a letter (for DOM friendlyness)
        var idstr=String.fromCharCode(Math.floor((Math.random()*25)+65));
        do {                
            // between numbers and characters (48 is 0 and 90 is Z (42-48 = 90)
            var ascicode=Math.floor((Math.random()*42)+48);
            if (ascicode<58 || ascicode>64){
                // exclude all chars between : (58) and @ (64)
                idstr+=String.fromCharCode(ascicode);    
            }                
        } while (idstr.length<32);

        return (idstr);
    }

    /*******************
        Adapted from: 
        http://stackoverflow.com/questions/5560248/programmatically-lighten-or-darken-a-hex-color-or-rgb-and-blend-colors
    *******************/ 
    function shadeColor(color, percent) {  
        var num = parseInt(color.slice(1),16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, G = (num >> 8 & 0x00FF) + amt, B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
    }

    /*******************
        Adapted from: 
        http://stackoverflow.com/questions/1740700/how-to-get-hex-color-value-rather-than-rgb-value
    *******************/ 
    function rgb2hex(rgb) {
        if (  rgb.search("rgb") == -1 ) {
            return rgb;
        } else {
            rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
            function hex(x) {
                return ("0" + parseInt(x).toString(16)).slice(-2);
            }
            return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]); 
        }
    }