<?php
session_start(); 
include "./../model/db.php";


if (!empty($_POST["email"]) && !empty("password")) {
  if (mb_strlen($_POST["email"]) < 255 && mb_strlen($_POST["password"]) < 255) {
    //Check the email
    $email = $_POST["email"];
    $emailDom = explode ("@", $email);
    if ($emailDom[1] == "g.educaand.es") {
      if (validateEmail($email, $conn)) {
        $code = rand(100000, 999999);
        $_SESSION["email"] = $email;
        $_SESSION["password"] = hash("sha256", $_POST["password"]);
        $expireDate = date("Y-m-d H:i:s", strtotime("+1 minute"));
        $sql = "INSERT INTO users (email, password, vcode, vcode_date) VALUES (
          '" . $email . "', '" . hash("sha256", $_POST["password"]) . "', '" . $code . "',
          '" . $expireDate . "')";
        if ($conn->query($sql) !== TRUE) {
          unset($_SESSION["email"], $_SESSION["password"]);
          $_SESSION["message"] = "Error: cannot create account";
          $_SESSION["color"] = "danger";
          $_SESSION["login"] = false;
          header("Location: ./../views/create_account.php");
        }
        $emailSend = $email . ",";
        $title = "Welcome to Studium";
        $message = "
          <html>
            <head>
              <title>$title</title>
            </head>
            <body>
              <h1>Welcome to Studium!</h1>
              <p>Your verification code is:</p>
              <div>
                <h2>$code</h2>
              </div><br>
              <h3>This code will expire in 60 seconds!</h3>
            </body>  
          </html>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Studium <info@studium.robertopaez.com> \r\n";
        if (mail($emailSend, $title, $message, $headers)) {
          $_SESSION["message"] = "We have sent you a verification code to your email";
          $_SESSION["color"] = "success";
          header("Location: ./../views/verify.php");
        } else {
          $_SESSION["message"] = "Something went wrong, please try again";
          $_SESSION["color"] = "danger";
          header("Location: ./../views/create_account.php");
        }
      } else {
        $_SESSION["message"] = "That email is already in use.";
        $_SESSION["color"] = "danger";
        header("Location: ./../views/create_account.php");
      }
    } else {
      $_SESSION["message"] = "Email must finish with @g.educaand.es";
      $_SESSION["color"] = "danger";
      header("Location: ./../views/create_account.php");
    }
  } else {
    $_SESSION["message"] = "Email and Password must be less than 255 characters";
    $_SESSION["color"] = "danger";
    header("Location: ./../views/create_account.php");
  }
} else {
  $_SESSION["message"] = "Email and Password are required";
  $_SESSION["color"] = "danger";
  header("Location: ./../views/create_account.php");
}

function validateEmail (string $email, $conn) {
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    return false;
  } else {
    return true;
  }
}

?>