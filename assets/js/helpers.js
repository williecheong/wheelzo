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
                
                setTimeout(function() {
                    // Simple page refresh for now
                    $button.html('<i class="fa fa-refresh"></i> Refreshing');
                    location.reload();
                }, 1500);
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
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
        
        } else if ( ride.capacity > 6 ) {
            return 'Are you driving a bus?';
        }

        return false;
    }

    function extractModalRide( $modal ) {
        var data = {
            origin          : $modal.find('input#origin').val(),
            destination     : $modal.find('input#destination').val(),
            departureDate   : $modal.find('input#departure-date').val(),
            departureTime   : $modal.find('input#departure-time').val(),
            price           : $modal.find('span#price').text(),
            capacity        : $modal.find('span#capacity').text()
        };

        return data;
    }

/*******************
    GENERAL HELPER FUNCTIONS
*******************/ 
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