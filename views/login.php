<?php session_start(); ?>
<?php include "./../partials/header.php"; ?>
<?php include "./../partials/navbar.php"; ?>

<div class="row justify-content-center vh-100 align-items-center">
  <div class="card col-5">
    <div class="card-body">
      <h1 class="text text-center">Login</h1><br>
      <?php if (isset($_SESSION["message"]) && isset($_SESSION["color"])) { ?>
        <div class="alert alert-<?= $_SESSION["color"]; ?> alert-dismissible fade show" role="alert">
          <?= $_SESSION["message"]; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php } unset($_SESSION["message"], $_SESSION["color"]); ?>
      <form action="./../controller/validate_login.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" name="email" maxlength="255" required aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required maxlength="255">
        </div>
        <button type="submit" class="btn btn-outline-primary col-12">Login</button>
      </form><br>
      <a href="./create_account.php">Don't have an account? Register</a>
    </div>
  </div>
</div>
  
<?php include "./../partials/footer.php"; ?>