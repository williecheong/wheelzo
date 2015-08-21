<script type="text/ng-template" id="about.html">
	<div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-comments-o fa-lg"></i> 
            About Wheelzo
        </h4>
    </div>
    <div class="modal-body">
        <div class="row well well-sm" style="margin-top:-15px;">
            <div class="col-sm-3">
                <h3>
                    <i class="fa fa-shield fa-2x" style="color:forestgreen;"></i> Assurance
                </h3>
                <div style="text-align:justify;">
                    Pay online to guarantee peace of mind and reliability. Drivers and passengers are held accountable.
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
                <h3>
                    <i class="fa fa-facebook-square fa-2x" style="color:#3b5998;"></i> Notify
                </h3>
                <div style="text-align:justify;">
                    Receive facebook notifications when people are interested in your rides, or when a driver agrees to pick you up.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-center" style="padding-bottom:5px;">
                    <span class="lead">
                        <i class="fa fa-car fa-lg"></i> 
                        For Drivers
                    </span>
                </div>
                <div class="well well-sm faq-box">
                    <strong ng-click="driver1=!driver1">
                        <i class="fa fa-arrow-circle-right"></i>
                        As a driver, how do I post a ride?
                    </strong>
                    <p ng-show="driver1">
                        Sign into Wheelzo using Facebook. 
                        Then post a ride by entering departure and arrival locations, and starting date and time of your journey. 
                        Potential passengers looking for a similar ride will also appear as you create your posting. 
                        Feel free to send them an invitation to join your new ride.
                    </p>
                </div>
                <div class="well well-sm faq-box">
                    <strong ng-click="driver2=!driver2">
                        <i class="fa fa-arrow-circle-right"></i>
                        How does Wheelzo help me with my rides?
                    </strong>
                    <p ng-show="driver2">
                        Notifications will be sent to your Facebook account when a potential passenger comments on your ride on Wheelzo.
                        Alternatively, potential passengers may also choose to send you a private message directly through Facebook.
                    </p>
                </div>
                <div class="well well-sm faq-box">
                    <strong ng-click="driver3=!driver3">
                        <i class="fa fa-arrow-circle-right"></i>
                        I want to collect online payments.
                    </strong>
                    <p ng-show="driver3">
                        Passengers are always encouraged to make direct contact with drivers prior to making payments.
                        When both parties are satisfied with the arrangement, drivers may direct passengers to Wheelzo to make an online payment.
                        Wheelzo holds onto the payment and releases it to the driver shortly after the ride is completed.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center" style="padding-bottom:5px;">
                    <span class="lead">
                        <i class="fa fa-group fa-lg"></i>
                        For Passengers
                    </span>
                </div>
                <div class="well well-sm faq-box">
                    <strong ng-click="passenger1=!passenger1">
                        <i class="fa fa-arrow-circle-right"></i>
                        How should I book a ride?
                    </strong>
                    <p ng-show="passenger1">
                        You may contact the driver by leaving a comment directly on the ride.
                        The driver will automatically be sent a Facebook notification when you do so.
                        Alternatively, you may access the driver's Facebook profile to send a private message.
                        Once arrangements have been made between the driver and yourself, feel free to make an online payment to the driver through Wheelzo.
                        Your payment will be locked in and released to the driver only after the ride has been completed. 
                    </p>
                </div>
                <div class="well well-sm faq-box">
                    <strong ng-click="passenger2=!passenger2">
                        <i class="fa fa-arrow-circle-right"></i>
                        I cannot find a ride.
                    </strong>
                    <p ng-show="passenger2">
                        First, make sure that the ride you are looking for doesn't exist by using the Wheelzo search bar.
                        If a suitable ride is indeed unavailable, then you may submit a ride request.
                        Drivers posting a matching ride in future will see your ride request, and will then be likely to send you an invitation to join. 
                    </p>
                </div>
            </div>
        </div>
        <div class="text-center pTop10 pBottom5">
            Read about our <a href="/privacy">privacy terms</a>
            <br>
            Contact us at <a href="mailto:info@wheelzo.com">info@wheelzo.com</a>
            <div class="mTop5">
                <a class="btn btn-xs" href="https://facebook.com/wheelzo" style="background-color:#354C8C;color:whitesmoke;" target="_blank">
                    <i class="fa fa-facebook-square fa-lg"></i> 
                    Like Us
                </a>
                <a class="btn btn-default btn-xs" href="https://twitter.com/gowheelzo" style="color:#333;" target="_blank">
                    <i class="fa fa-twitter fa-lg" style="color:#4099FF;"></i> 
                    Follow
                </a>
            </div>
        </div>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/about.js"></script>