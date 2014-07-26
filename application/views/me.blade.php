@layout('base')

@section('title')
    Wheelzo :: {{ $users[$session]['name'] }}
@endsection

@section('sub_title')
    <a class="navbar-brand visible-xs" href="//facebook.com/{{ $users[$session]['facebook_id'] }}">
        {{ $users[$session]['name'] }}
    </a>
@endsection

@section('my_rides')
    active
@endsection

@section('search_placeholder')
    "Search through your rides on Wheelzo..."
@endsection

@section('jumbotron')
@endsection

@section('table')
    <table class="table table-hover rides-table">
        <thead>
            <tr>
                <th class="origin">Origin</th>  
                <th class="destination">Destination</th>  
                <th class="departure">Departure</th> 
                <th class="price">Type</th>
                <th class="ninja-header">Driver</th>
                <th class="ninja-header">Dropoffs</th>
                <th class="ninja-header">Encoded ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $rides as $ride )
                <tr data-ride-id="{{ $ride->id }}">
                    <td>{{ $ride->origin }}</td>
                    <td>
                        {{ $ride->destination }} 
                        <?php if ( count($ride->drop_offs) > 0 ) { ?> 
                            <a href="#">
                                <i class="fa fa-flag-checkered fa-border" title="{{count($ride->drop_offs)}} drop-off locations"></i>
                            </a>
                        <?php } ?>
                    </td>
                    <td>
                        {{ date( 'M d, l @ g:ia', strtotime($ride->start) ) }}
                    </td>
                    <td>
                        @if ( $ride->driver_id == $session )
                            <i class="fa fa-user"></i> Driver
                        @elseif ( $ride->is_personal )
                            <i class="fa fa-users"></i> Passenger
                        @else                               
                            ${{ $ride->price }}
                        @endif
                    </td>
                    <td class="ninja-field">{{ $users[$ride->driver_id]['name'] }}</td>
                    <td class="ninja-field">{{ implode(', ', $ride->drop_offs) }}</td>
                    <td class="ninja-field">{{ encode_to_chinese($ride->id) }}</td>
                </tr>
            @endforeach
                
            @foreach( $my_rrequests as $my_rrequest )
                <tr data-rrequest-id="{{ $my_rrequest->id }}">
                    <td>{{ $my_rrequest->origin }}</td>
                    <td>
                        {{ $my_rrequest->destination }}
                    </td>
                    <td>
                        <span style="display:none;">
                            {{ strtotime($ride->start) }}
                        </span>
                        {{ date( 'M d, l @ g:ia', strtotime($my_rrequest->start) ) }}
                    </td>
                    <td>
                        <i class="fa fa-bullhorn"></i> Request
                    </td>
                    <td class="ninja-field"></td>
                    <td class="ninja-field"></td>
                    <td class="ninja-field"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('custom_modals')
    <!-- Modal for viewing a rrequest -->
    <div class="modal fade" id="view-rrequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        Ride Request and Invitations
                    </h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover invitations-table">
                        <thead>
                            <tr>
                                <th class="origin">Origin</th>  
                                <th class="destination">Destination</th>  
                                <th class="departure">Departure</th> 
                                <th class="price">View</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <p>
                        This is your request for a ride from 
                        <strong>
                            <span id="rrequest-origin"></span>
                        </strong>
                        to 
                        <strong>
                            <span id="rrequest-destination"></span>
                        </strong>
                        with preferred departure on
                        <strong>
                            <span id="rrequest-departure"></span>
                        </strong>
                    </p>
                    <div class="well well-sm">
                        <i class="fa fa-warning"></i> 
                        Deleting this ride request will prevent you from getting any further Facebook notifications regarding this request.
                        <button class="btn btn-danger btn-xs" id="delete-rrequest">
                            <i class="fa fa-times"></i> Delete
                        </button>
                    </div>     
                </div>
            </div>
        </div>
    </div>
@endsection
