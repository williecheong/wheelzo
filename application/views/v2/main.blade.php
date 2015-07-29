@layout('v2/base')

@section('title')
    Wheelzo
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
    @endif 
@endsection

@section('custom_css')
    <link rel="stylesheet" href="/assets/css/v2/main.css">
@endsection

@section('top_search_bar')
    <div style="width:50%;" class="row top-menu pull-right mTop15 hidden-xs">
        <div class="col-xs-6">
            <input placeholder="Leaving from ..." class="form-control opaque9">
        </div>
        <div class="col-xs-6">
            <input placeholder="Going to ..." class="form-control opaque9">
        </div>
    </div>
    <div class="top-menu pull-right mTop15 visible-xs">
        <button style="background:#D3D6D4;" class="btn">
            <i class="fa fa-bars"></i>   
        </button>
    </div>
@endsection

@section('side_search_bar')
@endsection

@section('main_body')
@endsection

@section('custom_js')
@endsection