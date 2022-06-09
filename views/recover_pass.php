<?php session_start(); ?>
<?php include "./../partials/header.php"; ?>

<div class="row justify-content-center vh-100 align-items-center">
  <div class="card col-5">
    <h1 class="text text-center">Recover your Password</h1><br><br>
    <?php if (isset($_SESSION["message"]) && isset($_SESSION["color"])) { ?>
      <div class="alert alert-<?= $_SESSION["color"]; ?> alert-dismissible fade show" role="alert">
      <?= $_SESSION["message"]; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php } unset($_SESSION["message"], $_SESSION["color"]); ?>
    <form action="./../controller/validate_recover.php" method="POST">
      <div class="mb-3">
        <label for="verify-code">Your Email</label>
        <input type="email" class="form-control" name="recover-email" required maxlength="255">
      </div>
      <div class="mb-3">
        <input type="submit" class="btn btn-outline-primary col-12" name="recover-btn" value="Recover Password">
      </div>
    </form>
  </div>
</div>

<?php include "./../partials/footer.php"; ?>