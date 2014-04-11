<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/assets/img/favicon.ico" type="image/x-icon">
        <title>Wheelzo</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans&subset=latin,latin-ext,cyrillic,cyrillic-ext">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/basic/jquery.qtip.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="jumbotron">
            <div class="container">
                <div class="row" id="introduction">
                    <div class="col-sm-2 text-center">
                        <a href="/">
                            <img src="/assets/img/logo.png" width="100%">
                        </a>
                    </div>
                    <div class="col-sm-10">
                        <h1>
                            <a id="toggle-getting-started" href="#">Together</a> on the road...
                        </h1>
                        <p>
                            Wheelzo v{{ CURRENT_VERSION }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <a href="#" data-toggle="modal" data-target="#create-ride">
                        <i class="fa fa-plus-square fa-2x"> New ride</i>
                    </a>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="search-box" placeholder="Search">
                </div>                    
            </div>
             <div class="table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Origin</th>  
                            <th>Destination</th>  
                            <th>Departure</th> 
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $rides as $ride )
                            <tr data-ride-id="{{ $ride->id }}">
                                <td>{{ $ride->origin }}</td>
                                <td>{{ $ride->destination }}</td>
                                <td>{{ date( 'M j, g:ia', strtotime($ride->start) ) }}</td>
                                <td>{{ $ride->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- Footer -->
            <footer>
                <hr>
                <span>
                    <a href="#introduction">
                        &copy; Wheelzo v{{ CURRENT_VERSION }}
                    </a>
                </span>
                <span>
                    @if ( $session )
                        <a class="btn btn-danger btn-xs" href="{{ $session_url }}">
                            <i class="fa fa-sign-out fa-lg"></i> Logout of {{ $session }}
                        </a>
                    @else
                        <a class="btn btn-primary btn-xs" href="{{ $session_url }}">
                            <i class="fa fa-facebook fa-lg"></i> Login with Facebook
                        </a>
                    @endif
                </span>
            </footer><!-- /.footer -->
        </div><!-- /container -->
        
        <?php $this->load->view('modals/main'); ?>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/basic/jquery.qtip.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="/assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
        <script src="/assets/js/main.js"></script>
        <script>
            var rides = {{ json_encode($rides) }} ;
            var users = {{ json_encode($users) }}
        </script>
    </body>
</html>




