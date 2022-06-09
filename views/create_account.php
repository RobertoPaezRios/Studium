<?php session_start(); ?>
<?php include "./../partials/header.php"; ?>
<?php include "./../partials/navbar.php"; ?>
<?php include "./../controller/functions.php";
if (isBlocked($conn)) { 
  $query = "SELECT * FROM blocked_ips WHERE ip = '" . $_SERVER["REMOTE_ADDR"] . "'";
  $result = $conn->query($query);
  $row = $result->fetch_assoc();
  $_SESSION["message"] = "Your device blocked till it's " . $row["time_blocked"];
  $_SESSION["color"] = "danger";
  $_SESSION["login"] = false;
  header("Location: ./../index.php"); 
}?>

<div class="row justify-content-center vh-100 align-items-center">
  <div class="card col-5">
    <div class="card-body">
      <h1 class="text text-center">Create Account</h1><br>
      <?php if (isset($_SESSION["message"]) && isset($_SESSION["color"])) { ?>
      <div class="alert alert-<?= $_SESSION["color"]; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION["message"]; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php } unset($_SESSION["message"], $_SESSION["color"]); ?>
      <form action="./../controller/validate_create_account.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" name="email" maxlength="255" required aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required maxlength="255">
        </div>
        <button type="submit" class="btn btn-outline-primary col-12">Create Account</button>
      </form><br>
      <a href="./login.php">Already have an account? Login</a>
    </div>
  </div>
</div>
  
<?php include "./../partials/footer.php"; ?>