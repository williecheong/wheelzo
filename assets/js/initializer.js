/*******************
    LOADED ONCE TO INITIALIZE ALL THE PLUGINS
*******************/ 
    function initializeMain() {
        // Initializes the modal for viewing a ride
        $('.modal#view-ride').modal({
            show : false
        });

        // Initializes the preparation for ride view on click of a row
        $('tr[data-ride-id]').on('click', prepareRide);

        // Initializes the preparation for rrequest view on click of a row
        $('table.rides-table tbody tr[data-rrequest-id]').on('click', prepareRrequest);
        $('.btn#delete-rrequest').on('click', removeRrequest);

        $('table.rrequests-table tbody tr[data-rrequest-id]').on('click', function(){
            $(this).toggleClass('success');
        });

        // Initializes the posting of a ride when Publish button is clicked
        $('.btn#post-ride').on('click', saveRide);

        // Initializes the posting of a request when Submit button is clicked
        $('.btn#post-request').on('click', saveRrequest);

        // Initializes the posting of a comment when Send button is clicked
        $('.btn#post-comment').on('click', saveComment);
        $('input#write-comment').on('keyup', function(event) {
            if ( event.keyCode == 13 ){
                $('.btn#post-comment').trigger('click');
            }
        });

        // Initializes the adding of a new dropoff location
        $('.btn#add-dropoff').on('click', addDropoff);

        // Initializes the posting of a feedback message submission
        $('.btn[name="send-feedback"]').on('click', saveFeedback);

        // Toggles the view of channels on each product
        $('[data-mytoggler]').click(function(){
            var $element = $(this);
            var toToggle = $element.data('mytoggler');
            var toggleStyle = $element.data('mytoggler-style');
            if ( toggleStyle ) {
                $( toToggle ).toggle(toggleStyle);
            } else {
                $( toToggle ).toggle('fast');
            }
        });

        // Initializing sliders
        $('.slider#price').slider({
            value: 10,
            min: 5,
            max: 35,
            step: 1,
            slide: function( event, ui ) {
                $('.slider-value#price').text( ui.value );
            }
        });

        $('.slider#capacity').slider({
            value: 2,
            min: 1,
            max: 7,
            step: 1,
            slide: function( event, ui ) {
                $('.slider-value#capacity').text( ui.value );
            }
        });

        // Initializing qtip for better tooltips
        $('[title!=""]').qtip({
            style: {
                classes: 'qtip-dark qtip-shadow qtip-rounded nowrap'
            },
            position: { 
                my: 'top right', 
                at: 'bottom right' 
            }
        });

        // Initializing datepickers
        $('.datepicker').datepicker({
            minDate: 0
        });

        // Initializing timepickers
        $('.timepicker').timepicker({
            timeFormat: 'hh:mm tt',
            stepMinute: 15
        });

        // Initializing suggested places recommender
        $('.add_suggested_places').typeahead({
            source: defaultSuggestedPlaces
        });

        $('.add_suggested_names').typeahead({
            source: function(query, process){
                map = {};
                users = [];

                $.each(publicUsers, function (user_id, user_info) {
                    var i = 1;
                    var toDisplay = user_info.name;
                    
                    while ( map[toDisplay] ) { 
                        toDisplay += ' (' + i + ')';
                        i++;
                    }
                    
                    map[toDisplay] = user_id;
                    users.push(toDisplay);
                });

                process( users );
            },
            updater: function(item){
                user_id = map[item];
                $('input#lookup-id').val( user_id )
                                    .trigger('change');
                return publicUsers[user_id].name;
            }
        });

        $('input#lookup-id').on('change', lookupUser);

        $('.btn#give-point').on('click', savePoint);
        
        $('.btn#post-review').on('click', saveReview);
        $('input#write-review').on('keyup', function(event) {
            if ( event.keyCode == 13 ){
                $('.btn#post-review').trigger('click');
            }
        });

        $('a[href="#about-scores"]').popover({
            html        : true,
            placement   : 'left',
            trigger     : 'hover',
            title       : '<strong>Reputation Points?</strong>',
            content     : 'A higher number means more <em>street cred</em>. Vouch for others only when they deserve it, because you had a positive ride experience with them. Help preserve balance in the world <i class="fa fa-heart"></i>'
        });


        // Initializing table sorter
        rideTable = $('table.rides-table').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": false,
            "order": [[ 2, "asc" ]],
            "sDom" : ''
        });
        
        $('input#search-origin').on('keyup focusout', searchRidesByOrigin);
        $('input#search-destination').on('keyup focusout', searchRidesByDestination);

        // Preparing the initial view using JS
        if ( loadRide != false ) {
            rideTable.fnFilterAll( loadRide );
            $('tr[data-ride-id]').trigger('click');

            $('table.rides-table-sectioned').hide();
            $('tr[data-ride-id]').closest('table.rides-table-sectioned').show();
        } else {
            if ( loadUser != false && publicUsers[loadUser] ) {
                $('.modal#lookup-users').modal('show');
                $('input#lookup-name').val( publicUsers[loadUser].name );
                $('input#lookup-id').val(loadUser).trigger('change');
            }            
        }

        rrequestTable = $('table.rrequests-table').dataTable({
            "bPaginate": false,
            "sScrollY": "50px",
            "bLengthChange": false,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": false,
            "sDom" : ''
        });
        
        $('input#origin, input#destination, input#departure-date').on('keyup focusout change', searchRrequests);
    }

    function initializeRide( rideID ) {        
        var thisRide = rides[rideID];
        var driver = publicUsers[ thisRide.driver_id ];
        var $modal = $('.modal#view-ride');

        if ( thisRide.drop_offs.length > 0 ) {
            $modal.find('#show-dropoffs').popover({
                html        : true,
                placement   : 'auto',
                trigger     : 'hover',
                title       : 'Drop-off locations :',
                content     : dropoffContentTemplate( thisRide.drop_offs )
            });
        }

        $('.btn#delete-ride').off('click', removeRide).hide();
        $('div.payment-message#payment-message-guest').show();
        $('div.payment-message#payment-message-driver').hide();
        $('div.payment-message#payment-message-passenger-enabled').hide();
        $('div.payment-message#payment-message-passenger-disabled').hide();
            
        if ( session_id ) {
            var message = '';
            $('div.payment-message#payment-message-guest').hide();
            if ( publicUsers[session_id].facebook_id == driver.facebook_id ) {
                $('div.payment-message#payment-message-driver').show();
                message = 'Write about your ride or respond to potential passengers';
                if (thisRide.passengers.length == 0) {
                    $('.btn#delete-ride').on('click', removeRide).show();
                }
            } else {
                if (thisRide.allow_payments == 1) {
                    $('div.payment-message#payment-message-passenger-enabled').show();
                } else {
                    $('div.payment-message#payment-message-passenger-disabled').show();
                }
                message = 'Write a request to join or ask questions to the driver';
                $('a#open-payment').on('click', handlePayment);
            }

            $modal.find('input#write-comment').attr('placeholder', message);
        }    
    }

