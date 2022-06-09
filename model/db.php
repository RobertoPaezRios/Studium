<?php
$servername = "localhost";
$username = "rahyafag_rahyafag";
$password = 'Ba$na$na$2022';
$dbname = "rahyafag_studium";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>