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
              .attr('href', '//facebook.com/'+driver['facebook_id'])
              .html(driver['name']);

        $modal.find('a#driver-picture')
              .attr('href', '//facebook.com/'+driver['facebook_id']);

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
            "Mississauga, Square One",
            "Toronto, North York",
            "Toronto, Union Square",
            "Toronto, Finch/Yonge subway station",
            "Toronto, Yorkdale Mall",
            "Toronto, York University",
            "Toronto, Downsview subway station",
            "Markham, Pacific Mall",
            "Markham, Don Mills subway station",
            "Richmond Hill",
            "Scarborough",
            "Markham",
            "Vaughan"
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

