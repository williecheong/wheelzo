<!DOCTYPE html>
<html>
<head>
  <title>UW Carpool</title>
  <!-- Bootstrap -->
  <link rel="stylesheet"  href="css/bootstrap.css" media="screen">

</head>

<body background="img/bg.png")>

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
      $id = $_POST["id"];  
      $to = $_POST["to"];  
      $from = $_POST["from"];  
      $when = $_POST["when"];  
      $price = $_POST["price"];  
      $capacity = $_POST["capacity"];  


      echo $id;
      echo "<br>";
      echo $to;
      echo "<br>";
      echo $from;
      echo "<br>";
      echo $when;
      echo "<br>";
      echo $price;
      echo "<br>";
      echo $capacity;
      echo "<br>";



      function newRide($id,$to,$from,$when,$price,$capacity){

        //opens a connection to the db for doing stuff!
        $con=mysqli_connect("localhost","mapikhte","mysql","mapikhte");
        // Check connection
        if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $result = mysqli_query($con,"INSERT INTO ride (`DriverID`,`To`,`From`,`When`,`Price`,`Capacity`) VALUES ('$id', '$to','$from','$when','$price','$capacity')");


        echo $result;

      } // end of newRide()



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

newRide($id,$to,$from,$when,$price,$capacity);

header("Location: index.php?s=".urlencode("Thank you for your message.")); exit;  

?>  

</div>
</div>

</body>


<?php

// close the connection
// mysqli_close($con);


?>


</html>