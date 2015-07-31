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
@endsection

@section('side_search_bar')
    <div class="row mTop15 mBottom5 mLeft5 mRight5 visible-xs">
        <div class="col-xs-12">
            <input placeholder="Leaving from ..." class="form-control opaque9">
        </div>
        <div class="col-xs-12">
            <input placeholder="Going to ..." class="form-control opaque9">
        </div>
    </div>
@endsection

@section('main_body')
    <section id="main-content">
        <section class="wrapper site-min-height">
            <h3>
                <i class="fa fa-angle-right"></i>
                Discover Our Panels
            </h3>
            <div class="row mt">
                <div class="col-lg-12"> 
                </div>
            </div>
        </section>
    </section><!-- /MAIN CONTENT -->
@endsection

@section('custom_modals')
    @include('v2/ng-modals/drive')
@endsection

@section('custom_js')
    <script src="/assets/js/v2/main.js"></script>
@endsection