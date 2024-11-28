<?php 
  if(!isset($_SESSION) || session_id() === "" || session_status() === PHP_SESSION_NONE) {
    session_start();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous"
  >
  <link rel="stylesheet" href="../../css/style.css">
 
  <title>Login and Registration</title>
</head>
<body>
  <div class="topmenu">
    <ul class="menubar">
      <?php 
        if(isset($_SESSION['name'])) { ?>
          <li class="user">
            <span>Welcome <?= $_SESSION['name'] ?></span>
            <a href="logout.php">Logout</a></li>
      <?php 
        } else { ?>
      <li><a href="index.php">Home</a></li>
      <li><a href="register.php">Register</a></li>
      <li><a href="login.php">Login</a></li>
      <?php 
        } ?>
    </ul>
  </div>
  
</body>
</html>