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
    <table class="table table-hover">
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
