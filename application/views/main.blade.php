@layout('base')

@section('title')
    Wheelzo
@endsection

@section('search_placeholder')
    "Search through all active rides on Wheelzo..."
@endsection

@section('jumbotron')
    <div class="jumbotron" id="introduction">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <h3>
                        <i class="fa fa-search fa-2x"></i> Search
                    </h3>
                    <span style="text-align:justify;">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. 
                    </span>
                </div>
                <div class="col-sm-4">
                    <h3>
                        <i class="fa fa-calendar fa-2x"></i> Manage
                    </h3>
                    <span style="text-align:justify;">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. 
                    </span>
                </div>
                <div class="col-sm-4">
                    <h3>
                        <i class="fa fa-facebook-square fa-2x"></i> Stay Informed
                    </h3>
                    <span style="text-align:justify;">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. 
                    </span>
                </div>
            </div>
        </div>
    </div>
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
                        {{ date( 'M j, l @ g:ia', strtotime($ride->start) ) }}
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