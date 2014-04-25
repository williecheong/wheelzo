@layout('base')

@section('title')
    Wheelzo
@endsection

@section('search_placeholder')
    "Search through all active rides on Wheelzo..."
@endsection

@section('jumbotron')
    {{--
    <div class="jumbotron" id="introduction">
        <div class="container">
            <div class="row">
                <div class="col-sm-2 text-center">
                    <a href="/">
                        <img class="brand-logo" src="/assets/img/logo.png">
                    </a>
                </div>
                <div class="col-sm-10">
                    <h1>
                        <a id="toggle-getting-started" href="#">Together</a> on the road...
                    </h1>
                    <p>
                        Wheelzo v{{ CURRENT_VERSION }}
                        @if ( $session )
                            <a class="btn btn-danger btn-xs" id="facebook-session" href="{{ $session_url }}" title="Logout">
                                <i class="fa fa-sign-out fa-lg"></i> {{ $users[$session]['name'] }}
                            </a>
                        @else
                            <a class="btn btn-primary btn-xs" id="facebook-session" href="{{ $session_url }}">
                                <i class="fa fa-facebook-square fa-lg"></i> Login with Facebook
                            </a>
                        @endif
                        <!--
                        <a class="btn btn-link btn-xs pull-right" href="#" data-mytoggler="div#introduction,div#compact-bar">
                            <i class="fa fa-caret-up fa-lg"></i> Hide
                        </a>
                        -->
                    </p>
                </div>
            </div>
        </div>
    </div> 
    --}}
@endsection

@section('table')
    <div class="table-responsive">
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
    </div>
@endsection