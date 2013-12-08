<?php

function getName($con) {

  $result = mysqli_query($con,"SELECT * FROM user");


  while($row = mysqli_fetch_array($result)) {
    echo $row['ID'] . "     " . $row['NAME'] . "     " . $row['XP'];
    echo "<br>";
  }

} // end of getName()

function newUser($con) {
    $result = mysqli_query($con,"INSERT INTO user (UserID,Name,xp) VALUES ('', 'linkuser','')");
}


echo "<h1> You just added a user! </h1>";

//opens a connection to the db for doing stuff!
$con=mysqli_connect("localhost","uwuser","","uwcarpool");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

newUser($con);




$result = mysqli_query($con,"SELECT * FROM user");

echo "<table border='1'>
<tr>
<th>id</th>
<th>name</th>
<th>xp</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['UserID'] . "</td>";
  echo "<td>" . $row['Name'] . "</td>";
  echo "<td>" . $row['xp'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysqli_close($con);



?>

<!-- redirects back to index -->
<!-- <meta http-equiv="refresh" content="0; url=index.php" /> -->
