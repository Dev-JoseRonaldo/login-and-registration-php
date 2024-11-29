<?php
  session_start();
  
  require "../private/autoload.php";
  $email = $pwd = "";
  $email_err = $pwd_err = "";
  $err_msg = "";

  $error = false;

  if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);

    if (isset($_POST['remember'])) {
      $remember = $_POST['remember'];
    }

    //validate inputs
    if($email == "") {
      $email_err = "Please enter Email";
      $error = true;
    }

    $email_regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    
    if(!preg_match($email_regex, $email)) {
      $email_err = "Please enter a valid email!";
      $error = true;
    }

    if($pwd == "") {
      $pwd_err = "Please enter Password";
      $error = true;
    }

    // se não deu erro, continua o login
    if (!$error) {
      $email_escaped = esc($email);

      $sql = "select * from users where email = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $email_escaped);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_pwd = $row['password'];
        if (password_verify($pwd, $stored_pwd)) {
          // reseta a sessão

          session_unset();
          session_destroy();
          session_start();

          $_SESSION['name'] = $row['name'];
          header("location:index.php");

          if(isset($_POST['remember'])) {
            // add cookie
              setcookie("remember_email", $email, time() + 365*24*60*60);
              setcookie("remember", $remember, time() + 365*24*60*60);
          } else {
            // delete cookie
            setcookie("remember_email", $email, time() - 365*24*60*60);
            setcookie("remember", $remember, time() - 365*24*60*60);
          }

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
    <?php 
      $display_email = isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : $email;

      $checked = isset($_COOKIE['remember']) ? "checked" : (!empty($remember) ? "checked" : "");
    ?>
    <div class="mb-4">
      <label for="email" class="form-label">Email</label>
      <input
        type="text"
        class="form-control"
        name="email"
        id="email"
        placeholder="Enter your Email"
        value="<?= $display_email?>"
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
        <?= $checked ?>
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