<?php 
  include "cfg/dbconnect.php";
  $name = $email = $pwd = $conf_pwd = "";
  $name_err = $email_err = $pwd_err = $conf_pwd_err = "";
  $succ_msg = $err_msg = "";

  $error = false;

  if(isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);
    $conf_pwd = trim($_POST['conf_pwd']);

    //validate inputs
    if($name == "") {
      $name_err = "Please enter Name";
      $error = true;
    }

    if($email == "") {
      $email_err = "Please enter Email";
      $error = true;
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid Email format";
      $error = true;
    } else {
      $sql = "select * from users where email = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      // Ronaldo: Enumeração de usuários abaixo
      if ($result->num_rows>0) {
        $email_err = "Email already registered";
        $error = true;
      }
    }

    if($pwd == "") {
      $pwd_err = "Please enter Password";
      $error = true;
    }

    if($conf_pwd == "") {
      $conf_pwd_err = "Please enter Confirm Password";
      $error = true;
    }

    if ($pwd != "" && $conf_pwd != "") {
      if ($pwd != $conf_pwd) {
        $conf_pwd_err = "Passwords do not match";
        $error = true;
      }
    }

    // se não deu erro, continua o cadastro
    if (!$error) {
      $pwd = password_hash($pwd, PASSWORD_DEFAULT);
      $sql = "insert into users (name, email, password) values (?, ?, ?)";

      try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $pwd);
        $stmt->execute();
        $succ_msg = "Registration sucessful. Please login <a href='login.php'>here</a>";
      } catch(Exception $e) {
        $err_msg = $e->getMessage();
      }
    }

  }
  include "topmenu.php";
?>
<div class="container-md">
  <h1 class="title">Registration</h1>
  <div class="show-err">
    <?php 
      if (!empty($succ_msg)) { ?>
        <div class="alert alert-success">
          <?= $succ_msg; ?>
        </div>
      <?php } ?>
  
      <?php 
      if (!empty($err_msg)) { ?>
        <div class="alert alert-danger">
          <?= $err_msg; ?>
        </div>
      <?php } ?>
  </div>

  <form action="" method="post">
    <div class="mb-4">
      <label for="name" class="form-label">Name</label>
      <input
        type="text"
        class="form-control"
        name="name"
        id="name"
        placeholder="Enter your Name"
        value="<?= $name?>"
      />
      <div class="text-danger input-err"><?= $name_err ?></div>
    </div>

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

    <div class="mb-4">
      <label for="conf_pwd" class="form-label">Confirm Password</label>
      <input
        type="password"
        class="form-control"
        name="conf_pwd"
        id="conf_pwd"
        placeholder="Confirm password"
      />
      <div class="text-danger input-err"><?= $conf_pwd_err ?></div>
    </div>

    <div class="form-check">
      <input
        class="form-check-input"
        name=""
        id=""
        type="checkbox"
        value="checkedValue"
        aria-label="Show Password Checkbox"
        onclick="showPwd()"
      /> Show Password
      <div class="text-danger"></div>
    </div>

    <div class="text-center mb-4">
      <button
        type="submit"
        name="submit"
        class="btn btn-primary"
      >
        Register
      </button>
    </div>
    <p>Already Registerd? Login <a href="login.php">here</a></p>
  </form>
</div>

<script>
  function showPwd() {
    let pwd = document.getElementById("pwd");
    let conf_pwd = document.getElementById("conf_pwd");
    console.log(conf_pwd)

    if (pwd.type === "text") {
      pwd.type = "password";
    } else {
      pwd.type = "text";
    }

    if (conf_pwd.type === "text") {
      conf_pwd.type = "password";
    } else {
      conf_pwd.type = "text";
    }
  }
</script>
</body>
</html>