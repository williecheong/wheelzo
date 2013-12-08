<!DOCTYPE html>
<html>
<head>
  <title>UW Carpool</title>
  <!-- Bootstrap -->
  <link rel="stylesheet"  href="css/bootstrap.css" media="screen">

  




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

<!-- 
<form action="newride.php" method="post">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>

<form class="well">  
  <label>Label name</label>  
  <input type="text" class="span3" placeholder="Type something…">  
  <span class="help-inline">Associated help text!</span>  
  <label class="checkbox">  
    <input type="checkbox"> Check me out  
  </label>  
  <button type="submit" class="btn">Submit</button>  
</form>  


<form class="well form-inline">  
  <input type="text" class="input-small" placeholder="Email">  
  <input type="password" class="input-small" placeholder="Password">  
  <label class="checkbox">  
    <input type="checkbox"> Remember me  
  </label>  
  <button type="submit" class="btn">Sign in</button>  
</form> 

Welcome <?php //echo $_GET["name"]; ?><br>
Your email address is: <?php //echo $_GET["email"]; ?> 

-->


 <div class="container">  
      
        <div class="page-header">  
            <h1>New Ride</h1>  
        </div>  
        <form method="POST" action="createride.php" class="form-horizontal">  
            
            <div class="control-group">  
                <label class="control-label" for="input1">DriverID</label>  
                <div class="controls">  
                    <input type="text" name="id" id="input1" placeholder="">  
                </div>  
            </div> 

            <div class="control-group">  
                <label class="control-label" for="input2">To</label>  
                <div class="controls">  
                    <input type="text" name="to" id="input2" placeholder="">  
                </div>  
            </div>  

            <div class="control-group">  
                <label class="control-label" for="input3">From</label>  
                <div class="controls">  
                    <input type="text" name="from" id="input3" rows="8" placeholder="">
                </div>  
            </div>  

            <div class="control-group">  
                <label class="control-label" for="input4">When</label>  
                <div class="controls">  
                    <input type="text" name="when" id="input4" rows="8" placeholder="">  
                </div>  
            </div>

            <div class="control-group">  
                <label class="control-label" for="input5">Price</label>  
                <div class="controls">  
                    <input type="text" name="price" id="input5" rows="8" placeholder="">
                </div>  
            </div> 

            <div class="control-group">  
                <label class="control-label" for="input6">Capacity</label>  
                <div class="controls">  
                    <input type="text" name="capacity" id="input6" rows="8" placeholder="">
                </div>  
            </div> 



            <div class="form-actions">  
                <input type="hidden" name="save" value="contact">  
                <button type="submit" class="btn btn-primary">Send</button>  
            </div>  


        </form>  
          
    </div>  

<!-- end of container -->
</div>

</body>


<?php

// close the connection
// mysqli_close($con);


?>


</html>