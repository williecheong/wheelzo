/*******************
    WHEELZO POST RIDE
*******************/
    $('.btn#post-ride').click(function(){
        var $button = $(this);
        var $modal = $button.closest('.modal');
        
        $button.addClass('disabled');

        var validationFailed = validateRide( $modal );
        if ( validationFailed ) {
            alert( validationFailed );
            $button.removeClass('disabled');
            return;
        }

        $.ajax({
            url: '/api/rides',
            data: extractModalRide( $modal ),
            type: 'POST',
            dataType: "JSON",
            success: function( response ) {
                console.log(response);
                
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
    });
    
    $('.btn#post-comment').click(function(){
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
                console.log(response);
                
                setTimeout(function() {
                    // Simple page refresh for now
                    $button.html('<i class="fa fa-refresh"></i>');
                    location.reload();
                }, 1500);
            }, 
            error: function(response) {
                alert('Fail: API could not be reached.');
                $button.removeClass('disabled');
                console.log(response);
            }
        });
    });

/*******************
    WHEELZO EVENT BINDINGS
*******************/
    $('.modal#view-ride').modal({
        show : false
    });

    $('tr[data-ride-id]').click(function(){
        var rideID = $(this).data('ride-id');
        var driver = publicUsers[ rides[rideID].driver_id ];
        var $modal = $('.modal#view-ride');

        $modal.find('a#driver-name')
              .attr('href', 'https://facebook.com/'+driver['facebook_id'])
              .html(driver['name']);

        $modal.find('a#driver-picture')
              .attr('href', 'https://facebook.com/'+driver['facebook_id']);

        $modal.find('img#driver-picture')
              .attr('src', '//graph.facebook.com/'+driver['facebook_id']+'/picture?width=200&height=200')
        
        $modal.find('#ride-departure')
              .html( moment(rides[rideID].start).format('dddd MMMM D, h:mm a') );        
        
        $modal.find('#ride-price')
              .html('$'+rides[rideID].price);
        
        $modal.find('#ride-passengers')
              .html( passengersTemplate(rideID) );

        $modal.find('#ride-origin')
              .html( rides[rideID].origin );
        
        $modal.find('#ride-destination')
              .html( rides[rideID].destination );
        
        $modal.find('#ride-comments')
              .html( commentsTemplate(rides[rideID].comments) );
        
        $modal.data('rideID', rideID);

        $modal.modal('toggle');
    });

    $('.add_suggested_places').typeahead({
        source: [
            "Waterloo, UW Davis Center",
            "Toronto, Union Square",
            "Toronto, Yorkdale Mall"
        ]
    });

/*******************
    GENERAL EVENT BINDINGS
*******************/ 
    //Toggles the view of channels on each product
    $('[data-mytoggler]').click(function(){
        var toToggle = $(this).data('mytoggler');
        $( toToggle ).toggle('slow');
    });

    // Initializing sliders
    $('.slider#price').slider({
        value: 10,
        min: 0,
        max: 30,
        step: 1,
        slide: function( event, ui ) {
            $('.slider-value#price').text( ui.value );
        }
    });

    $('.slider#capacity').slider({
        value: 2,
        min: 1,
        max: 6,
        step: 1,
        slide: function( event, ui ) {
            $('.slider-value#capacity').text( ui.value );
        }
    });

    // Initializing qtip for better tooltips
    $('[title!=""]').qtip({
        position: { my: 'top right', at: 'bottom right' }
    });

    // Initializing datepicker
    $('.datepicker').datepicker({
        minDate: 0
    });

    // Initializing timepicker
    $('.timepicker').timepicker({
        timeFormat: 'hh:mm tt',
        stepMinute: 15
    });

    // Initializing table sorter
    var dataTable = $('table').dataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bInfo": false,
        "bAutoWidth": false,
        "sDom" : ''
    });
    $('input#search-box').on('keypress focusout', function(){
        dataTable.fnFilter( $(this).val() );
    });

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

    function passengersTemplate( rideID ) {
        var html = '';
        var capacity = parseInt( rides[rideID].capacity );
        var colSize = Math.floor( 12 / capacity );
        var count = 0 ;

        $.each(rides[rideID].passengers, function(key, value){
            var passenger = publicUsers[value.user_id];
            html += '<div class="col-xs-'+colSize+'">'+
                    '    <img class="img-circle" id="passenger-picture" src="//graph.facebook.com/'+passenger.facebook_id+'/picture?width=200&height=200">'+
                    '</div>';
            count++;
        });

        while ( count < capacity ) {
            html += '<div class="col-xs-'+colSize+'">'+
                    '    <img class="img-circle" id="passenger-picture" src="/assets/img/empty_user.png">'+
                    '</div>';
            count++;
        }

        return html;
    }

    function commentsTemplate( comments ) {
        var html = '';
        var count = 0;
        $.each(comments, function(key, commentObject){
            html += '<div class="single-comment">'+
                    '    <img class="img-rounded pull-left single-comment-image" src="//graph.facebook.com/'+publicUsers[commentObject.user_id].facebook_id+'/picture?type=square">'+
                    '    <div class="single-comment-details">'+
                    '        <div id="single-comment-message">'+
                    '            ' + commentObject.comment +
                    '        </div>'+
                    '        <small class="single-comment-meta">'+
                    '           by ' + publicUsers[commentObject.user_id].name + ' @ ' + moment(commentObject.last_updated).format('dddd MMMM D, h:mm a') +
                    '        </small>'+
                    '    </div>'+
                    '</div>';
            count++;
        }); 

        if ( count == 0 ) {
            html = '<div class="single-comment text-center">'+
                   '    <em>No comments to display ...</em>'+
                   '</div>';
        }

        return html;
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