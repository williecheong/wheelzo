<!DOCTYPE html>
<html>
<head>
  <title>Wheelzo</title>
  <!-- Bootstrap -->
  <link rel="stylesheet"  href="css/bootstrap.css" media="screen">


  <?php

  function loadUserRows($con) {

    $result = mysqli_query($con,"SELECT * FROM user");

    global $userRows;

    // $index = 0;
    while($row = mysqli_fetch_array($result)) {
      $userRows[$row['UserID']] = $row;
      // $index = $index + 1;
    }

  } // end of loadRows()

  function loadRideRows($con) {

    $result = mysqli_query($con,"SELECT * FROM ride");

    global $rideRows;

    $index = 0;
    while($row = mysqli_fetch_array($result)) {
      $rideRows[$row['RideID']] = $row;
      $index = $index + 1;
    }

  } // end of loadRows()

  function loadPassengerRows($con) {

    $result = mysqli_query($con,"SELECT * FROM passenger");

    global $passengerRows;

    $index = 0;
    while($row = mysqli_fetch_array($result)) {
      $passengerRows[$row['PassengerID']] = $row;
      $index = $index + 1;
    }

  } // end of loadRows()

// User related methods

  // function getId($con, $userid) {
  //   global $userRows;
  //   echo $userRows[$userid]['UserID'];
  // }

  function getUser($con, $userid) {

    global $userRows;

    echo $userRows[$userid]['Name'];

} // end of getUser()

function getXp($con, $userid) {
  // rownum is the rowid in the table
  global $userRows;
  echo $userRows[$userid]['xp'];

} 

function newUser($con) {
  $result = mysqli_query($con,"INSERT INTO user ('UserID','Name','xp') VALUES ('', 'buttonclick', '')");
}

// lists passengers that dont have a ride (might not implement this)

function generatePassengerTableRow($con, $userid) {

  echo "
  <tr>
  <td>
  <button type=\"button\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-road\"></span></button>
  </td>  
  <td>";
  echo "U of W";          // from
  echo "</td>  <td>";
  echo "???";               // to
  echo "</td>  <td>";
  echo getUser($con, $userid); // name of driver
  echo "</td>  <td>";
  echo getXp($con, $userid); // xp level of the driver
  echo "</td>  <td>"; 
  echo "now";               // date of ride
  echo "</td>  </tr>";

}

function generateAllPassengerRows($con){

  $currentrow = 0;

  global $userRows;

  // loops over each user 
  foreach ($userRows as $key => $value) {
    generatePassengerTableRow($con, $key);
  }

}

function getDriverNameFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT user.Name FROM user, ride WHERE ride.DriverID = user.UserID AND ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getToFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.To FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getFromFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.From FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getPriceFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.Price FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getXpFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT user.xp FROM user,ride WHERE ride.DriverID = user.UserID AND ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getWhenFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.When FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getNumPassengersFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.NumPassengers FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}

function getCapacityFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.Capacity FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}
function getDescriptionFromRideId($con, $rideid) {
  $result = mysqli_query($con,"SELECT ride.Description FROM ride WHERE ride.RideID = $rideid");
  return mysqli_fetch_array($result)[0];
}


function generateRideTableRow($con, $rideid) {

  echo "
  <tr>
  <td>
  <a href=\"ride.php?ride=$rideid\"><button type=\"button\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-plus\"></span></button></a>     
  </td>  
  <td>";                                                                                                      // ^ this should link to the rideprofile 
  echo getNumPassengersFromRideId($con, $rideid);echo"/";echo getCapacityFromRideId($con, $rideid);          // capacity
  echo "</td>  <td>";
  echo getFromFromRideId($con, $rideid);          // from
  echo "</td>  <td>";
  echo getToFromRideId($con, $rideid);            // to
  echo "</td>  <td>";
  echo getDriverNameFromRideId($con, $rideid);    // name of driver
  echo "</td>  <td>";
  echo getPriceFromRideId($con, $rideid);         // price
  echo "</td>  <td>";
  echo getXpFromRideId($con, $rideid);            // xp level of the driver
  echo "</td>  <td>"; 
  echo getWhenFromRideId($con, $rideid);          // date of ride
  echo "</td>  </tr>";

}

function generateAllRideRows($con){
  global $rideRows;
  // loops over each user 
  foreach ($rideRows as $key => $value) {
    generateRideTableRow($con, $key);
  }
}


//opens a connection to the db for doing stuff!
$con=mysqli_connect("localhost","uwuser","","uwcarpool");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// mysqli_query($con,"INSERT INTO user (ID, NAME, XP)
// VALUES ('', 'UW',0)");

// mysqli_query($con,"INSERT INTO user VALUES ('', 'UW2',1)");

// retrieve whole user table
loadUserRows($con); 
loadRideRows($con); 
loadPassengerRows($con); 

?>



</head>


<body background="img/bg.png">



  <div class="container">



    <nav class="navbar navbar-inverse" role="navigation">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-globe"></span></a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="#">From UW</a></li>
          <li><a href="#">To UW</a></li>
        </ul>

        <form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-default">Go</button>
        </form>

        <ul class="nav navbar-nav navbar-right">
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav> <!-- end of nav bar-->


    <!-- Button for creating new rides -->
    <div class="btn-toolbar">
          <a href="newride.php"> <button type="button" class="btn btn-primary btn-lg">Start a Ride</button> </a>
    </div>

  <br>

    <!-- table begins -->

    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">Current Rides</div>

      <!-- Table (responsive scrolls on small screens)-->
      <div class="table-responsive">
        <table class="table">
          <thead>  

            <tr>  
              <th>Join</th>
              <th>Crew</th>
              <th>From</th>  
              <th>To</th>  
              <th>Name</th>  
              <th>Price</th>  
              <th>XP</th>  
              <th>Departure</th>  
            </tr>  

          </thead>  

          <tbody>  

            <?php generateAllRideRows($con); ?>

          </tbody>  
        </table>
        <!-- responsive ends -->
      </div>
      <!-- table ends -->
    </div>





    <div><!-- 

      <iframe src="https://www.facebook.com/plugins/registration?
      client_id=282192178572651&
      redirect_uri=http://www.terabrite.ca/
      fields=name,birthday,gender,location,email"
      scrolling="auto"
      frameborder="no"
      style="border:none"
      allowTransparency="true"
      width="100%"
      height="330">
    </iframe>

  </div> -->



  <!-- End of Container-- >
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.js"></script>




<!-- Scripts 

<script>
$(document).ready(function(){
  $("tr").click(function(){
    $(this).hide();
  });
});
</script>

<script>
$('.nav li a').on('click', function() {
    $(this).parent().parent().find('.active').removeClass('active');
    $(this).parent().addClass('active');
});
</script>
-->

</body>


<?php

// close the connection
mysqli_close($con);


?>


</html>


