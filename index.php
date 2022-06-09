<?php include "./partials/header.php"; ?>
<?php include "./partials/navbar.php"; ?>
<?php include "./model/db.php"; ?>
<?php unset($_SESSION["email"], $_SESSION["password"], $_SESSION["attempts"]); ?>

<?php 
//Check if an user isn't verified yet

$query = "SELECT * FROM users;";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if ($row["status"] == 0) {
      $sql = "DELETE FROM users WHERE email = '" . $row["email"] . "'";
      $conn->query($sql);
    }
  }
}

?>

<?php if (isset($_SESSION["message"]) && isset($_SESSION["color"])) { ?>
  <div class="alert alert-<?= $_SESSION["color"]; ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION["message"]; ?>
    <button type="button" class="btn-close" style="outline: none;" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php } unset($_SESSION["message"], $_SESSION["color"]); ?>

<?php 

if (isset($_SESSION["login"])) {
  if ($_SESSION["login"]) {
    echo "Si";
  } else {
    echo "No";
  }
}

?>

<?php include "./partials/footer.php"; ?>