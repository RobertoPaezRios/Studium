<?php
session_start();
include "./../model/db.php";

$sql = "SELECT * FROM users WHERE email = '".$_SESSION["email"]."'";
//$conn->query($sql);
if ($result = $conn->query($sql)) {
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["vcode"] == $_POST["verify-code"] && $row["vcode_date"] > date("Y-m-d H:i:s")) {
      $_SESSION["message"] = "Logged to the account successfully";
      $_SESSION["color"] = "success";
      $_SESSION["login"] = true;
      $sql = "UPDATE users SET vcode = NULL, status = 1 WHERE email = '".$_SESSION["email"]."'";
      $conn->query($sql);
      $emailSend = $_SESSION["email"] . ",";
      $title = "Welcome to Studium";
      $message = "
        <html>
          <head>
            <title>$title</title>
          </head>
          <body>
            <h1>Welcome to Studium!</h1>
            <p>Your account have been logged in a new device.
            If it wasn't you, change your password and log in to your account</p>
          </body>  
        </html>
      ";
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: Studium <info@studium.robertopaez.com> \r\n";
      mail($emailSend, $title, $message, $headers);
      header("Location: ./../index.php");
    } else {
      $sql = "SELECT attempts FROM users WHERE email = '".$_SESSION["email"]."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $attempts = $row["attempts"];
      if ($attempts > 2) {
        $_SESSION["message"] = "No attempts left. This account is banned for 30 minutes.";
        $_SESSION["color"] = "danger";
        $_SESSION["login"] = false;
        $sql = "UPDATE users SET vcode = NULL, status = 2 WHERE email = '".$_SESSION["email"]."';
        INSERT INTO blocked_ips (ip, blocked_date, time_blocked) VALUES ('".$_SERVER["REMOTE_ADDR"]."', 
        '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s", strtotime("+30 minutes"))."');";
        $conn->query($sql);
        header("Location: ./../index.php");
      } else {
        $sql = "UPDATE users SET attempts = '".$attempts++."' WHERE email = '".$_SESSION["email"]."'";
        $conn->query($sql);
      }
      if ($row["vcode"] == $_POST["verify-code"] && $row["vcode_date"] < date("Y-m-d H:i:s")) {
        $_SESSION["message"] = "Time expired, try to login again.";
        $_SESSION["color"] = "danger";
        $_SESSION["login"] = false;
        $sql = "UPDATE users SET vcode = NULL, vcode_date = NULL";
        $conn->query($sql);
        header("Location: ./../views/verify_login.php");
      }
      if ($row["vcode"] != $_POST["verify-code"]) {
        $_SESSION["message"] = "Check you are writing the code correctly, 
        try to login again.";
        $_SESSION["color"] = "danger";
        $_SESSION["login"] = false;
        $sql = "UPDATE users SET vcode = NULL, vcode_date = NULL";
        $conn->query($sql);
        header("Location: ./../views/verify_login.php");
      }
    }
  } else {
    $_SESSION["message"] = "There are 0 users found!";
    $_SESSION["color"] = "danger";
    $_SESSION["login"] = false;
    header("Location: ./../views/login.php");
  }
} else {
  $_SESSION["message"] = "Something went wrong, please try again1";
  $_SESSION["color"] = "danger";
  $_SESSION["login"] = false;
  header("Location: ./../views/verify_login.php");
}

?>