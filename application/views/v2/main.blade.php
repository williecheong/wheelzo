@layout('v2/base')

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

@section('main_body')
@endsection