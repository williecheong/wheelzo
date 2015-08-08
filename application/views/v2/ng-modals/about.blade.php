<script type="text/ng-template" id="about.html">
	<div class="modal-header">
        <button ng-click="cancel()" type="button" class="close">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
            <i class="fa fa-comments-o fa-lg"></i> 
            Frequently Asked Questions
        </h4>
    </div>
    <div class="modal-body">
        <div class="text-center" style="padding-bottom:5px;">
            <span class="lead">
                <i class="fa fa-car fa-lg"></i> 
                For Drivers
            </span>
        </div>
        <div class="well well-sm row faq-box">
            <strong ng-click="driver1=!driver1" class="col-sm-12">
                <i class="fa fa-arrow-circle-right"></i>
                As a driver, how do I post a ride?
            </strong>
            <p ng-show="driver1" class="col-md-9">
                Sign into Wheelzo using Facebook. 
                Then post a ride by entering departure and arrival locations, and starting date and time of your journey. 
                Potential passengers looking for a similar ride will also appear as you create your posting. 
                Feel free to send them an invitation to join your new ride.
            </p>
        </div>
        <div class="well well-sm row faq-box">
            <strong ng-click="driver2=!driver2" class="col-sm-12">
                <i class="fa fa-arrow-circle-right"></i>
                How does Wheelzo help me with my rides?
            </strong>
            <p ng-show="driver2" class="col-md-9">
                Notifications will be sent to your Facebook account when a potential passenger comments on your ride on Wheelzo.
                Alternatively, potential passengers may also choose to send you a private message directly through Facebook.
            </p>
        </div>
        <div class="well well-sm row faq-box">
            <strong ng-click="driver3=!driver3" class="col-sm-12">
                <i class="fa fa-arrow-circle-right"></i>
                I want to collect online payments.
            </strong>
            <p ng-show="driver3" class="col-md-9">
                Passengers are always encouraged to make direct contact with drivers prior to making payments.
                When both parties are satisfied with the arrangement, drivers may direct passengers to Wheelzo to make an online payment.
                Wheelzo holds onto the payment and releases it to the driver shortly after the ride is completed.
            </p>
        </div>
        <div class="text-center" style="padding-bottom:5px;">
            <span class="lead">
                <i class="fa fa-group fa-lg"></i>
                For Passengers
            </span>
        </div>
        <div class="well well-sm row faq-box">
            <strong ng-click="passenger1=!passenger1" class="col-sm-12">
                <i class="fa fa-arrow-circle-right"></i>
                How should I book a ride?
            </strong>
            <p ng-show="passenger1" class="col-md-9">
                You may contact the driver by leaving a comment directly on the ride.
                The driver will automatically be sent a Facebook notification when you do so.
                Alternatively, you may access the driver's Facebook profile to send a private message.
                Once arrangements have been made between the driver and yourself, feel free to make an online payment to the driver through Wheelzo.
                Your payment will be locked in and released to the driver only after the ride has been completed. 
            </p>
        </div>
        <div class="well well-sm row faq-box">
            <strong ng-click="passenger2=!passenger2" class="col-sm-12">
                <i class="fa fa-arrow-circle-right"></i>
                I cannot find a ride.
            </strong>
            <p ng-show="passenger2" class="col-md-9">
                First, make sure that the ride you are looking for doesn't exist by using the Wheelzo search bar.
                If a suitable ride is indeed unavailable, then you may submit a ride request.
                Drivers posting a matching ride in future will see your ride request, and will then be likely to send you an invitation to join. 
            </p>
        </div>
        <div class="text-center" style="padding:20px;">
            More questions? Let us <a href="mailto:info@wheelzo.com">know</a>.
        </div>
    </div>
</script>

<script src="/assets/js/v2/ng-modals/about.js"></script>