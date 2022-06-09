<?php
session_start();
include "./../model/db.php";

$sql  = "SELECT * FROM users WHERE email = '".$_SESSION["email"]."'";
if ($conn->query($sql) === TRUE) {
  if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if ($row["vcode"] == $_POST["verify-code"] && $row["vcode_date"] > date("Y-m-d H:i:s")) {
        $_SESSION["message"] = "Logged to the account successfully";
        $_SESSION["color"] = "success";
        $_SESSION["login"] = true;
        header("Location: ./../index.php");
      } else {
        $_SESSION["message"] = "Something went wrong, please check you
        are writing the code correctly";
        $_SESSION["color"] = "danger";
        $_SESSION["login"] == false;
        header("Location: ./../views/login.php");
      }
    } else {
      $_SESSION["message"] = "There are 0 users found!";
      $_SESSION["color"] = "danger";
      $_SESSION["login"] == false;
      header("Location: ./../views/login.php");
    }
  } else {
    $_SESSION["message"] = "Something went wrong, please try again1";
    $_SESSION["color"] = "danger";
    $_SESSION["login"] == false;
    header("Location: ./../views/verify_login.php");
  }
} else {
  $_SESSION["message"] = "Something went wrong, please try again2" . mysqli_error($conn);
  $_SESSION["color"] = "danger";
  $_SESSION["login"] == false;
  header("Location: ./../views/login.php");
}

?>