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

        // Initializes the posting of a ride when Publish button is clicked
        $('.btn#post-ride').on('click', saveRide);

        // Initializes the posting of a comment when Send button is clicked
        $('.btn#post-comment').on('click', saveComment);

        // Initializes the adding of a new dropoff location
        $('.btn#add-dropoff').on('click', addDropoff);

        // Initializes the posting of a feedback message submission
        $('.btn[name="send-feedback"]').on('click', saveFeedback);

        // Toggles the view of channels on each product
        $('[data-mytoggler]').click(function(){
            var toToggle = $(this).data('mytoggler');
            $( toToggle ).toggle('slow');
        });

        // Initializing sliders
        $('.slider#price').slider({
            value: 10,
            min: 0,
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
            position: { my: 'top right', at: 'bottom right' }
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

    }