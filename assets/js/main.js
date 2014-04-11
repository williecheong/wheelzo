/*******************
    WHEELZO EVENT BINDINGS
*******************/
    $('.modal#view-ride').modal({
        show : false
    });

    $('tr[data-ride-id]').click(function(){
        var rideID = $(this).data('ride-id');
        var driver = users[ rides[rideID].driver_id ];
        $('.modal#view-ride').find('a#driver-name')
                             .attr('href', 'https://facebook.com/'+driver['facebook_id'])
                             .html(driver['email']);
        $('.modal#view-ride').modal('toggle');
    });

/*******************
    GENERAL EVENT BINDINGS
*******************/ 
    //Toggles the view of channels on each product
    $('[data-mytoggler]').click(function(){
        var toToggle = $(this).data('mytoggler');
        $( toToggle ).toggle('slow');
    });

    // Initializing qtip for better tooltips
    $('[title!=""]').qtip({
        position: { my: 'top right', at: 'bottom right' }
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

    // Facebook logout
    $('.btn#user-logout').click(function(){
        navigator.id.logout()
    });

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