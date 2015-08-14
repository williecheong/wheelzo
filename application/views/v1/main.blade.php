@layout('v1/base')

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
    <div class="jumbotron" id="introduction-container">
        <div class="container">
            <a href="/v2" class="btn btn-block btn-default">
                <i class="fa fa-star"></i>
                Use the new version 
                <span class="hidden-xs">that is over v{{ CURRENT_VERSION }}</span>
            </a>
            <div class="row" id="introduction">
                <div class="col-sm-3">
                    <h3>
                        <i class="fa fa-shield fa-2x" style="color:forestgreen;"></i> Assurance
                    </h3>
                    <div style="text-align:justify;">
                        Pay through Wheelzo to guarantee peace of mind and reliability. Drivers and passengers are held accountable.
                    </div>
                </div>
                <div class="col-sm-3"> 
                    <h3> 
                        <i class="fa fa-group fa-2x" style="color:goldenrod;"></i> Lookup
                    </h3> 
                    <div style="text-align:justify;">
                        Be confident that your drivers and passengers are trustworthy: everyone is held accountable by the community.
                    </div>
                </div>
                <div class="col-sm-3">
                    <h3>
                        <i class="fa fa-home fa-2x" style="color:purple;"></i> Manage
                    </h3>
                    <div style="text-align:justify;">
                        View only the rides you care about in one click. Any rides you drive or a passenger of are on your personal page.
                    </div>
                </div>
                <div class="col-sm-3">
                    <h3 style="white-space:nowrap;">
                        <i class="fa fa-facebook-square fa-2x" style="color:#3b5998;"></i> Stay Informed
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
    @if ( count($rides) < 10 )
    <div class="table-responsive">
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
                            {{ date( 'M-d, l @ g:ia', strtotime($ride->start) ) }}
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
    </div>
    @else
        <span class="lead" data-mytoggler="table.rides-table#today" data-mytoggler-style="fold">
            <i class="fa fa-calendar"></i>
            <a href="#today">
                Today
            </a>
        </span>
        <div class="table-responsive">
            <table class="table table-hover rides-table rides-table-sectioned" id="today">
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
                        @if ( date('Y-m-d', strtotime('today')) == date('Y-m-d', strtotime($ride->start)) ) 
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
                                    {{ date( 'M-d, l @ g:ia', strtotime($ride->start) ) }}
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
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>

        <span class="lead" data-mytoggler="table.rides-table#tomorrow" data-mytoggler-style="fold">
            <i class="fa fa-calendar"></i>
            <a href="#tomorrow">
                Tomorrow
            </a>
        </span>
        <div class="table-responsive">
            <table class="table table-hover rides-table rides-table-sectioned" id="tomorrow">
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
                        @if ( date('Y-m-d', strtotime('tomorrow')) == date('Y-m-d', strtotime($ride->start)) ) 
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
                                    {{ date( 'M-d, l @ g:ia', strtotime($ride->start) ) }}
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
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>

        <span class="lead" data-mytoggler="table.rides-table#future" data-mytoggler-style="fold">
            <i class="fa fa-calendar"></i>
            <a href="#future">
                In Future
            </a>
        </span>
        <div class="table-responsive">
            <table class="table table-hover rides-table rides-table-sectioned" id="future">
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
                        @if ( strtotime('tomorrow + 1 day') < strtotime($ride->start) ) 
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
                                    {{ date( 'M-d, l @ g:ia', strtotime($ride->start) ) }}
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
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
    @endif
@endsection
