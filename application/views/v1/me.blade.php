@layout('v1/base')

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
    <div class="container" id="personal-profile">
        <header class="jumbotron row">
            <div class="col-sm-3 text-center">
                <a target="_blank" href="//facebook.com/{{ $session_user->facebook_id }}" style="margin:0px auto;">
                    <img class="img-circle img-responsive" id="driver-picture" style="display:block;margin:auto;" src="//graph.facebook.com/{{ $session_user->facebook_id }}/picture?width=200&height=200">
                </a>
            </div>
            <div class="col-sm-9">
                <div class="visible-xs text-center">
                    <h3>
                        Balance: ${{ number_format((float)$session_user->balance, 2, '.', '') }}
                        <span style="cursor:pointer;" title="Contact the Wheelzo team to withdraw this balance">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </h3>
                </div>
                <div class="hidden-xs">
                    <div class="row">
                        <div class="col-lg-6">
                            <?php $exploded_name = explode(' ', $session_user->name ); ?>
                            <h3 style="margin-top:10px;">
                                <i class="fa fa-paw"></i>
                                About {{ $exploded_name[0] }}:
                            </h3>
                            <div style="padding-left:20px;">
                                <li>
                                    Taken a ride with 
                                    {{ $session_user_statistics['total_carpools_taken'] }} 
                                    {{ pluralize($session_user_statistics['total_carpools_taken'], 'other carpooler', 'other carpoolers') }} 
                                </li>
                                <li>
                                    Written {{ $session_user_statistics['total_reviews_written'] }}
                                    {{ pluralize($session_user_statistics['total_reviews_written'], 'user review', 'user reviews') }} 
                                    and {{ $session_user_statistics['total_comments'] }} 
                                    {{ pluralize($session_user_statistics['total_comments'], 'comment', 'comments') }} 
                                </li>
                                <li>
                                    Driven {{ $session_user_statistics['total_rides'] }} 
                                    {{ pluralize($session_user_statistics['total_rides'], 'journey', 'journeys') }} 
                                    and taken on {{ $session_user_statistics['total_passengers'] }} 
                                    {{ pluralize($session_user_statistics['total_passengers'], 'passenger', 'passengers') }}
                                </li>
                                <li>
                                    Collected 
                                    <strong>
                                        ${{ number_format((float)$session_user->balance, 2, '.', '') }} 
                                    </strong>
                                    as a driver through Wheelzo
                                    <span style="cursor:pointer;" title="Contact the Wheelzo team to withdraw this balance">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                </li>
                                <li>
                                    Want <a href="#" data-toggle="modal" data-target="#write-feedback">more statistics</a>? Let us know!
                                </li>
                            </div>
                        </div>
                        <div class="col-lg-6 visible-lg">
                            <h3 style="margin-top:10px;">
                                <i class="fa fa-users"></i>
                                Community Feedback:
                            </h3>
                            <div class="well well-sm" style="max-height:100%;">
                                @if (count($session_user_points) == 0)
                                    <em>Nobody has vouched for you yet.</em>
                                @else
                                    <?php $supporters = array(); foreach($session_user_points as $point){ if(!in_array($point->giver_id, $supporters)){$supporters[] = $point->giver_id;}} ?>
                                        @foreach($supporters as $key => $supporter_id) 
                                            @if ($key < count($supporters)-2)
                                                <a href="/me?user={{$supporter_id }}">{{ $users[$supporter_id]['name'] }}</a>,
                                            @elseif ($key < count($supporters)-1)
                                                <a href="/me?user={{$supporter_id }}">{{ $users[$supporter_id]['name'] }}</a> and
                                            @else
                                                <a href="/me?user={{$supporter_id }}">{{ $users[$supporter_id]['name'] }}</a>
                                            @endif
                                        @endforeach
                                        {{ pluralize(count($supporters), 'has', 'have') }} 
                                        vouched for you.
                                @endif
                                @if (count($session_user_reviews) == 0)
                                    <em>You have not received any reviews from the Wheelzo community.</em>
                                @else
                                    You have received 
                                    <a href="/me?user={{$session_user->id}}">{{ count($session_user_reviews) }} {{ pluralize(count($session_user_reviews), 'review', 'reviews') }}</a>
                                    from the Wheelzo community.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
@endsection

@section('table')
    <div class="table-responsive">
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
                    
                @foreach( $my_rrequests as $my_rrequest )
                    <tr data-rrequest-id="{{ $my_rrequest->id }}">
                        <td>{{ $my_rrequest->origin }}</td>
                        <td>
                            {{ $my_rrequest->destination }}
                        </td>
                        <td>
                            <span style="display:none;">
                                {{ strtotime($my_rrequest->start) }}
                            </span>
                            {{ date( 'M-d, l @ g:ia', strtotime($my_rrequest->start) ) }}
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
    </div>
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
