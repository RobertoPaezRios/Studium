<?php 
include "./../model/db.php";
session_start();

//Comprobar codigo

if (isset($_POST["verify-code"])) {
  $sql = "SELECT * FROM users WHERE email = '" . $_SESSION["email"] . "'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["vcode"] == $_POST["verify-code"] && $row["vcode_date"] > date("Y-m-d H:i:s")) {
      $sql = "UPDATE users SET vcode = NULL, status = 1 WHERE email = '" . $_SESSION["email"] . "'";
      if ($conn->query($sql) === TRUE) {
        unset($_SESSION["email"], $_SESSION["password"]);
        $_SESSION["message"] = "Account created successfully";
        $_SESSION["color"] = "success";
        $_SESSION["login"] = true;
        header("Location: ./../index.php");
      } else {
        $sql = "DELETE FROM users WHERE email = '" . $_SESSION["email"] . "'";
        $conn->query($sql);
        unset($_SESSION["email"], $_SESSION["password"]);
        $_SESSION["message"] = "Can't verify the account please try again";
        $_SESSION["color"] = "danger";
        $_SESSION["login"] = false;
        header("Location: ./../views/create_account.php");
      }
    } else {
      $sql = "DELETE FROM users WHERE email = '" . $_SESSION["email"] . "'";
      if ($conn->query($sql) === TRUE) {
        unset($_SESSION["email"], $_SESSION["password"]);
        $_SESSION["message"] = "Incorrect code, try to create the account again";
        $_SESSION["color"] = "danger";
        $_SESSION["login"] = false;
        header("Location: ./../views/create_account.php");
      }
    }
  } else {
    $_SESSION["message"] = "0 users found!";
    $_SESSION["color"] = "danger";
    header("Location: ./../views/verify.php");
  }
} else {
  $_SESSION["message"] = "The verify code is missing";
  $_SESSION["color"] = "danger";
  header("Location: ./../views/verify.php");
}

?>