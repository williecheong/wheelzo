@layout('v2/base')

@section('title')
    Privacy
    @if ( ENVIRONMENT != 'production' )
        :: {{ ucfirst(ENVIRONMENT) }}
    @endif 
@endsection

@section('description')
    <meta name="description" content="Privacy policies at Wheelzo">
    <link rel="image_src"  href="/assets/img/logo_200x200.png">
    <meta property="og:image" content="{{ base_url() }}assets/img/logo_200x200.png"/>
    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" />
@endsection

@section('custom_css')
    <link rel="stylesheet" href="/assets/css/v2/main.css">
@endsection

@section('main_body')
    <div>
        <h3 class="mLeft10">
            <i class="fa fa-user-secret"></i>
            Privacy Terms
        </h3>
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="well">
                    <p>
                        Wheelzo is the sole owner of the information collected on our site and mobile apps. We will not sell, share, or rent information to others in ways different from what is disclosed in this statement.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <span class="lead mLeft20">
            <i class="fa fa-tags"></i>
            Cookies
        </span>
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="well pTop10">
                    <p>
                        We use technologies like cookies (small files stored by your browser), web beacons, or unique device identifiers to anonymously identify your computer or device so we can deliver a better experience.  Usage of cookie does not provide us the ability to access any personal information while on our site. Cookie for example, is used so that you do not have to enter in a password twice while on our site. Our site can still be used if cookie is disabled.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <span class="lead mLeft20">
            <i class="fa fa-database"></i>
            Data
        </span>
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="well pTop10">
                    <p>
                        We also may collect personally identifiable information that you provide to us, such as your name, phone number or email address indicated on Facebook. If authorized by you, we may also access profile and other information from Facebook.
                    </p>
                    <p>
                        Wheelzo is not designed to associate personal information with your activities (such as pages you view or things you click on or search for).
                    </p>
                    <p>
                        We do not knowingly contact or collect personal information from children under 13. If you believe we have inadvertently collected such information, please contact us so we can promptly obtain parental consent or remove the information.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <span class="lead mLeft20">
            <i class="fa fa-user-times"></i>
            Account Deletion
        </span>
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="well pTop10">
                    <p>
                        Your account can be deleted at any point by uninstalling the Wheelzo app from Facebook. We may keep your name, email address, and other information indefinitely. You can request for these information to be permanently deleted by contacting us. 
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <span class="lead mLeft20">
            <i class="fa fa-envelope"></i>
            Mailing List
        </span>
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="well pTop10">
                    <p>
                        With your permission, we will send updates, newsletters, or other relevant announcements to your indicated email address. If you wish to un-subscribe to our mailing list at any time, please contact us. 
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <span class="lead mLeft20">
            <i class="fa fa-shield"></i>
            Security
        </span>
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="well pTop10">
                    <p>
                        The security of the information you have provided to us is our priority. If you have any questions or concerns regarding the security of our site, please contact us. 
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection