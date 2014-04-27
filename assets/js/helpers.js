/*******************
    REST EXECUTION HELPERS
*******************/ 
    function postRide( saveRide, $button ) {
        $.ajax({
            url: '/api/rides',
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
            url: '/api/rides/index/' + rideID,
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

    function postUser_ride( passengerID, rideID ) {
        $.ajax({
            url: '/api/user_rides',
            data: {
                "rideID" : rideID,
                "passengerID" : passengerID
            },
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                if ( response.status == 'success' ){
                    refreshRides(function(){
                        $('tr[data-ride-id="'+response.user_ride.ride_id+'"]').trigger('click');
                    });
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                console.log(response);
            }
        });
    }

    function putUser_ride( passengerID, user_rideID ) {
        $.ajax({
            url: '/api/user_rides',
            data: {
                "user-rideID" : user_rideID,
                "passengerID" : passengerID
            },
            type: 'PUT',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if ( response.status == 'success' ){
                    refreshRides(function(){
                        $('tr[data-ride-id="'+response.user_ride.ride_id+'"]').trigger('click');
                    });
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                console.log(response);
            }
        });
    }

    function deleteUser_ride( user_rideID ) {
        $.ajax({
            url: '/api/user_rides/index/' + user_rideID,
            type: 'DELETE',
            dataType: "JSON",
            success: function( response ) {
                console.log(response.message);
                
                if ( response.status == 'success' ){
                    refreshRides(function(){
                        $('tr[data-ride-id="'+response.ride_id+'"]').trigger('click');
                    });
                }
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
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

        var data = {
            origin          : $modal.find('input#origin').val(),
            destination     : $modal.find('input#destination').val(),
            departureDate   : $modal.find('input#departure-date').val(),
            departureTime   : $modal.find('input#departure-time').val(),
            price           : $modal.find('span#price').text(),
            capacity        : $modal.find('span#capacity').text(),
            dropOffs        : dropoffs
        };

        return data;
    }

    function refreshRides( callback ) {
        var url = "/api/rides";
        if ( location.pathname == '/me' ) {
            url = "/api/rides/me";
        }

        $.get( url, function( response ) {
            rides = JSON.parse(response);
            console.log("Object rides has been refreshed.")
            callback();
        });
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