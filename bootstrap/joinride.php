<!DOCTYPE html>
<html>
<head>
  <title>UW Carpool</title>
  <!-- Bootstrap -->
  <link rel="stylesheet"  href="css/bootstrap.css" media="screen">

  <?php

  function getCapacityFromRideId($con, $rideid) {
    $result = mysqli_query($con,"SELECT ride.Capacity FROM ride WHERE ride.RideID = $rideid");
    return mysqli_fetch_array($result)[0];
  }

  function getNumPassengersFromRideId($con, $rideid) {
    $result = mysqli_query($con,"SELECT ride.NumPassengers FROM ride WHERE ride.RideID = $rideid");
    return mysqli_fetch_array($result)[0];
  }

  function checkIfRideFull($con, $rideid) {
    if (getNumPassengersFromRideId($con, $rideid) == getCapacityFromRideId($con, $rideid)) {
      return true;
    } else {
      return false;
    }
  }

//opens a connection to the db for doing stuff!
$con=mysqli_connect("localhost","uwuser","","uwcarpool");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

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
        <a class="navbar-brand" href="."><span class="glyphicon glyphicon-road"></span></a>
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
    </nav> <!-- end of nav bar -->


    <!-- end of container -->
  </div>

  <div class="container">
    <div class="panel panel-default">


      <?php  


// // check for form submission - if it doesn't exist then send back to contact form  
// if (!isset($_POST["save"]) || $_POST["save"] != "contact") {  
//     header("Location: contact-form.php"); exit;  
// }  
      
// get the posted data  
      $rideid = 1;
      $rideid = $_POST["rideid"];  
      //$userid = $_POST["to"];

      echo "ride id: " . $rideid;
      echo "<br>";
      echo getNumPassengersFromRideId($con, $rideid);
      echo "/";
      echo getCapacityFromRideId($con, $rideid);
      // echo $userid;
      // echo "<br>";
      // echo checkIfRideFull($con, $rideid);
      echo "<br>";

      if (!checkIfRideFull($con, $rideid)) {
        //if ride isn't full we can join it!
        echo "added to waitlist!";
        //joinRide() =)
      } else {
        echo "Looks like someone snagged it before you.";
      }


// // check that a name was entered  
// if (empty ($name))  
//     $error = "You must enter your name.";  
// // check that an email address was entered  
// elseif (empty ($email_address))   
//     $error = "You must enter your email address.";  
// // check for a valid email address  
// elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email_address))  
//     $error = "You must enter a valid email address.";  
// // check that a message was entered  
// elseif (empty ($message))  
//     $error = "You must enter a message.";  

// // check if an error was found - if there was, send the user back to the form  
// if (isset($error)) {  
//     header("Location: contact-form.php?e=".urlencode($error)); exit;  
// }  

// // write the email content  
// $email_content = "Name: $name\n";  
// $email_content .= "Email Address: $email_address\n";  
// $email_content .= "Message:\n\n$message";  

// // send the email  
// mail ("mail@example.com", "New Contact Message", $email_content);  


// send the user back to the form  

// header("Location: index.php?s=".urlencode("Thank you for your message.")); exit;  

?>  

</div>
</div>

</body>


<?php

// close the connection
// mysqli_close($con);


?>


</html>