<?php
$servername = "us-cdbr-east-03.cleardb.com";
$username = "b2ca9b9dc1e4b4";
$password = "6c118034";
$db = "heroku_08380f7097b9ade";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$db);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}
echo "<br>";
