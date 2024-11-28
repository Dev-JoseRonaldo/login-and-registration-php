<?php
  session_start();
  
  include "cfg/dbconnect.php";
  $email = $pwd = "";
  $email_err = $pwd_err = "";
  $err_msg = "";

  $error = false;

  if(isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);

    //validate inputs
    if($email == "") {
      $email_err = "Please enter Email";
      $error = true;
    } 

    if($pwd == "") {
      $pwd_err = "Please enter Password";
      $error = true;
    }

    // se não deu erro, continua o login
    if (!$error) {
      $sql = "select * from users where email = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_pwd = $row['password'];
        if (password_verify($pwd, $stored_pwd)) {
          $_SESSION['name'] = $row['name'];
          header("location:index.php");
        } else {
          $err_msg = "Incorrect Email or Password";
        }
      }
    }

  }
  include "topmenu.php";
?>
<div class="container-md">
  <h1 class="title">Login</h1>
  <div class="show-err">
      <?php 
      if (!empty($err_msg)) { ?>
        <div class="alert alert-danger">
          <?= $err_msg; ?>
        </div>
      <?php } ?>
  </div>

  <form action="" method="post">
    <div class="mb-4">
      <label for="email" class="form-label">Email</label>
      <input
        type="text"
        class="form-control"
        name="email"
        id="email"
        placeholder="Enter your Email"
        value="<?= $email?>"
      />
      <div class="text-danger input-err"><?= $email_err ?></div>
    </div>

    <div class="mb-4">
      <label for="pwd" class="form-label">Password</label>
      <input
        type="password"
        class="form-control"
        name="pwd"
        id="pwd"
        placeholder="Enter your password"
      />
      <div class="text-danger input-err"><?= $pwd_err ?></div>
    </div>

    <div class="form-check">
      <input
        class="form-check-input"
        name="remember"
        id="remember"
        type="checkbox"
        value="checkedValue"
        aria-label="Remember me Checkbox"
      /> Remember me
      <div class="text-danger"></div>
    </div>

    <div class="text-center mb-4">
      <button
        type="submit"
        name="submit"
        class="btn btn-primary"
      >
        Login
      </button>
    </div>
    <p>Not Registerd? Register <a href="register.php">here</a></p>
  </form>
</div>
</body>
</html>