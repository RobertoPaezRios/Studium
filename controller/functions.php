<?php 
include "./../model/db.php";

function isBlocked ($conn) {
  $ip = $_SERVER["REMOTE_ADDR"];
  $sql = "SELECT * FROM blocked_ips WHERE ip = '" . $ip . "'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["time_block"] < date("Y-m-d H:i:s")) {
      $sql = "DELETE FROM blocked_ips WHERE ip = '" . $ip . "'";
      $conn->query($sql);
      return false;
    } else {
      return true;
    }
  } else {
    return false;
  }
}


?>