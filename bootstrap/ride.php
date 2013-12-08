<!DOCTYPE html>
<html>
<head>
	<title>UW Carpool</title>
	<!-- Bootstrap -->
	<link rel="stylesheet"  href="css/bootstrap.css" media="screen">

	
	<?php

	$rideid = $_GET['ride'];

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

	//passenger functions
	function getNameFromPassengerId($con, $passengerid) {
		$result = mysqli_query($con,"SELECT user.Name FROM user,passenger WHERE passenger.PassengerID = $passengerid AND user.UserID = passenger.UserID");
		return mysqli_fetch_array($result)[0];
	}
	function getFbLinkFromPassengerId($con, $passengerid) {
		// $result = mysqli_query($con,"SELECT user.Name FROM user,passenger WHERE passenger.PassengerID = $passengerid AND user.UserID = passenger.UserID");
		// return mysqli_fetch_array($result)[0];
	}
	function getCellFromPassengerId($con, $passengerid) {
		$result = mysqli_query($con,"SELECT user.Cell FROM user,passenger WHERE passenger.PassengerID = $passengerid AND user.UserID = passenger.UserID");
		return mysqli_fetch_array($result)[0];
	}


	function loadPassengersFromRideID($con, $rideid) {
		// this function loads all the passenger id's 

		$result = mysqli_query($con,"SELECT passenger.* FROM ride,passenger WHERE ride.RideID = $rideid AND passenger.RideID = ride.RideID");

		global $passengerRows;

	    $index = 0;
	    while($row = mysqli_fetch_array($result)) {
	      $passengerRows[$row['PassengerID']] = $row;
	      $index = $index + 1;
	    }
		 
	}

	function generatePassengerTableRow($con, $passengerid) {
		//returns a row of the current ride's passenger table
		echo "
		<tr> 
		<td>";                                                                                                  
		echo getNameFromPassengerId($con, $passengerid);          // Name
		echo "</td>  <td>";
		echo "<a href=\"\">Link</a>";//getFbLinkFromPassengerId($con, $passengerid);          // Facebook Profile (link?)
		echo "</td>  <td>";
		echo getCellFromPassengerId($con, $passengerid);            // cellphone number
		echo "</td>  </tr>";

	}

	function generateAllPassengerRows($con, $rideid){
		global $passengerRows;
		// loops over each passenger 
		// (if) in case there are no passengers
		if ($passengerRows != null) {
			foreach ($passengerRows as $key => $value) {
				generatePassengerTableRow($con, $key);
			}
		}
	}

$con=mysqli_connect("localhost","uwuser","","uwcarpool");
	// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

loadPassengersFromRideID($con, $rideid)

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
		</nav> <!-- end of nav bar-->

		<div class="panel panel-default">

			<img src="..." alt="..." class="img-rounded" width="200px" height="200px">

			<!-- Button to join ride-->
			<form method="POST" action="joinride.php" class="form-horizontal">

	            <div class="form-actions"> 
	            	<!-- Submits the rideid -->
	                <input type="hidden" name="rideid" value="<?php echo $rideid;?>">  
	            	<button type="submit" class="btn btn-primary">Join</button>
				</div>
				<!-- <input name="rideid" id="inout1" rows="8" placeholder=""> -->
			</form>

			<h3> <span class="label label-default">From</span>  </h3>
			<div class="well well-default"> <h2> <?php echo getFromFromRideId($con, $rideid); ?> </h2> </div>
			<h3> <span class="label label-default">To</span> </h3>
			<div class="well well-default"> <h2> <?php echo getToFromRideId($con, $rideid); ?> </h2> <br> </div>
			<h3> <span class="label label-default">Driver</span> </h3>
			<div class="well well-default"> <h2> <?php echo getDriverNameFromRideId($con, $rideid); ?> </h2> <br> </div>
			<h3> <span class="label label-default">Cost</span> </h3>
			<div class="well well-default"> <h2> <?php echo "$" . getPriceFromRideId($con, $rideid); ?> </h2> <br> </div>

			<br>

			<h1><span class="label label-default">Info</span></h1>
			<div class="well well-default"> <h4> <?php echo getDescriptionFromRideId($con, $rideid); ?> </h4> </div>


		</div>


		<!-- table begins -->

		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">Passengers ( <?php echo getCapacityFromRideId($con, $rideid)?> maximum ) </div>

			<!-- Table (responsive scrolls on small screens)-->
			<div class="table-responsive">
				<table class="table">
					<thead>  

						<tr>  
							<th>Name</th>  
							<th>Facebook Profile</th>
							<th>Cell #</th>  
						</tr>  

					</thead>  

					<tbody>  

						<?php generateAllPassengerRows($con, $rideid); 
						// echo getCapacityFromRideId($con,$rideid);

						?>

					</tbody>  
				</table>
				<!-- responsive ends -->
			</div>
			<!-- table ends -->
		</div>



		<!-- end of container -->
	</div>

</body>


<?php

// close the connection
// mysqli_close($con);


?>


</html>