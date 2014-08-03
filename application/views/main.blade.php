@layout('base')

@section('title')
    Wheelzo
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
    @endif 
@endsection

@section('search_placeholder')
    "Search through all active rides on Wheelzo..."
@endsection

@section('right_navs')
@endsection

@section('jumbotron')
    <div class="jumbotron" id="introduction">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h3>
                        <i class="fa fa-search fa-2x"></i> Search
                    </h3>
                    <div style="text-align:justify;">
                        Find a ride quickly. Search by location, departure time, drop-off spots or the driver's name. Just start typing.
                    </div>
                </div>
                <div class="col-sm-3"> 
                    <h3> 
                        <i class="fa fa-group fa-2x"></i> Lookup
                    </h3> 
                    <div style="text-align:justify;">
                        Be confident that your drivers and passengers are trustworthy: everyone is held accountable by the community.
                    </div>
                </div>
                <div class="col-sm-3">
                    <h3>
                        <i class="fa fa-home fa-2x"></i> Manage
                    </h3>
                    <div style="text-align:justify;">
                        View only the rides you care about in one click. Any rides you drive or a passenger of are on your personal page.
                    </div>
                </div>
                <div class="col-sm-3">
                    <h3 style="white-space:nowrap;">
                        <i class="fa fa-facebook-square fa-2x"></i> Stay Informed
                    </h3>
                    <div style="text-align:justify;">
                        @if ( !$session )
                            Receive <a href="{{ $session_url }}">facebook</a> notifications when people are interested in your rides, or when a driver agrees to pick you up.
                        @else
                            Receive facebook notifications when people are interested in your rides, or when a driver agrees to pick you up.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('table')
    <table class="table table-hover rides-table">
        <thead>
            <tr>
                <th class="origin">Origin</th>  
                <th class="destination">Destination</th>  
                <th class="departure">Departure</th> 
                <th class="price">Price</th>
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
                        <span style="display:none;">
                            {{ strtotime($ride->start) }}
                        </span>
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
        </tbody>
    </table>
@endsection
