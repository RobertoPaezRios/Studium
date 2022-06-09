<?php 
session_start();
include "./../model/db.php";

$sql = "SELECT status FROM users WHERE email = '".$_POST["email"]."'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row["status"] == 0) {
    $sql = "DELETE FROM users WHERE email = '".$_POST["email"]."'";
    $conn->query($sql);
    $_SESSION["message"] = "The user isn't verified yet, the account have been deleted.";
    $_SESSION["color"] = "danger";
    $_SESSION["login"] = false;
    header("Location: ./../index.php");
} else if ($row["status"] == 2) {
    $_SESSION["message"] = "This account is banned.";
    $_SESSION["color"] = "danger";
    $_SESSION["login"] = false;
    header("Location: ./../index.php");
} else if ($row["status"] == 1) {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $_SESSION["email"] = $email;
        $password = hash("sha256", $_POST["password"]);
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //Send a verification code to the user
            $code = rand(100000, 999999);
            $sql = "UPDATE users SET vcode = '" . $code . "', vcode_date = '" . date("Y-m-d H:i:s") . "' WHERE email = '" . $email . "'";
            if ($conn->query($sql) !== TRUE) {
                header("Location: ./../views/login.php");
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
                  <p>This is your verification code, write it on 
                  our website to finish the login:</p>
                  <div>
                    <h2>$code</h2>
                  </div>
                </body>  
              </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Studium <info@studium.robertopaez.com> \r\n";
            if (mail($emailSend, $title, $message, $headers)) {
                $_SESSION["message"] = "We have sent you a verification code to your email";
                $_SESSION["color"] = "success";
                header("Location: ./../views/verify_login.php");
            } else {
                $_SESSION["message"] = "Can't send email, please try again";
                $_SESSION["color"] = "danger";
                header("Location: ./../views/login.php");
            }
        } else {
            $_SESSION["message"] = "User not found!";
            $_SESSION["color"] = "danger";
            $_SESSION["login"] = false;
            header("Location: ./../views/login.php");
        }
    }
}
?>