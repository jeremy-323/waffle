<?php
$servername = "us-cdbr-east-03.cleardb.com";
$username = "b2ca9b9dc1e4b4";
$password = "6c118034";
$db = "heroku_08380f7097b9ade";
$sql = 'SELECT * FROM example_table WHERE store_url="' . $requests['shop'] . '" LIMIT 1';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo $row['store_url'];
echo $row['access_token'];
// Create connection
$conn = mysqli_connect($servername, $username, $password,$db);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}
echo "<br>";
