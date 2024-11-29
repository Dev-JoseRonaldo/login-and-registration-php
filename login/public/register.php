<?php 
  require "../private/autoload.php"; 

  $name = $email = $pwd = $conf_pwd = "";
  $name_err = $email_err = $pwd_err = $conf_pwd_err = "";
  $succ_msg = $err_msg = "";

  // Valida name
  function validate_name($name) {
    $name_regex = "/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/";
    if (empty($name)) {
      return "Please enter Name";
    } elseif (!preg_match($name_regex, $name)) {
      return "Please enter a valid name!";
    }
    return "";
  }

  // Valida email
  function validate_email($email, $conn) {
    if (empty($email)) {
      return "Please enter Email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return "Please enter a valid email!";
    } else {
      $email_escaped = esc($email);
      $sql = "SELECT * FROM users WHERE email = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $email_escaped);
      $stmt->execute();
      $result = $stmt->get_result();

      // enumeração de usuários
      if ($result->num_rows > 0) {
        return "Email already registered";
      }
    }
    return "";
  }

  // Valida senha
  function validate_password($pwd, $conf_pwd) {
    if (empty($pwd)) {
      return "Please enter Password";
    }
    if (empty($conf_pwd)) {
      return "Please enter Confirm Password";
    }
    if ($pwd !== $conf_pwd) {
      return "Passwords do not match";
    }
    return "";
  }

  // Processamento do formulário
  if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);
    $conf_pwd = trim($_POST['conf_pwd']);

    // Validação dos dados
    $name_err = validate_name($name);
    $email_err = validate_email($email, $conn);
    $pwd_err = validate_password($pwd, $conf_pwd);

    // Se não houver erros, continua com o registro
    if (!$name_err && !$email_err && !$pwd_err) {
      // Criptografa a senha
      $pwd = password_hash($pwd, PASSWORD_DEFAULT);

      // Escapa os dados para evitar injeções SQL
      $name_escaped = esc($name);
      $email_escaped = esc($email);
      $pwd_escaped = esc($pwd);

      // Inseri no banco de dados
      $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
      try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name_escaped, $email_escaped, $pwd_escaped);
        $stmt->execute();
        $succ_msg = "Registration successful. Please login <a href='login.php'>here</a>";
      } catch (Exception $e) {
        $err_msg = $e->getMessage();
      }
    }
  }
?>

<div class="container-md">
  <h1 class="title">Registration</h1>
  
  <!-- Exibição de mensagens de erro e sucesso -->
  <div class="show-err">
    <?php if ($succ_msg): ?>
      <div class="alert alert-success">
        <?= $succ_msg; ?>
      </div>
    <?php endif; ?>
    
    <?php if ($err_msg): ?>
      <div class="alert alert-danger">
        <?= $err_msg; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Formulário de registro -->
  <form action="" method="post">
    <!-- Campo de nome -->
    <div class="mb-4">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" name="name" id="name" placeholder="Enter your Name" value="<?= htmlspecialchars($name) ?>" />
      <div class="text-danger input-err"><?= $name_err ?></div>
    </div>

    <!-- Campo de e-mail -->
    <div class="mb-4">
      <label for="email" class="form-label">Email</label>
      <input type="text" class="form-control" name="email" id="email" placeholder="Enter your Email" value="<?= htmlspecialchars($email) ?>" />
      <div class="text-danger input-err"><?= $email_err ?></div>
    </div>

    <!-- Campo de senha -->
    <div class="mb-4">
      <label for="pwd" class="form-label">Password</label>
      <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter your password" />
      <div class="text-danger input-err"><?= $pwd_err ?></div>
    </div>

    <!-- Campo de confirmação de senha -->
    <div class="mb-4">
      <label for="conf_pwd" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" name="conf_pwd" id="conf_pwd" placeholder="Confirm password" />
      <div class="text-danger input-err"><?= $conf_pwd_err ?></div>
    </div>

    <!-- Opção de mostrar senha -->
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="showPassword" onclick="showPwd()" />
      <label class="form-check-label" for="showPassword">Show Password</label>
    </div>

    <!-- Botão de registro -->
    <div class="text-center mb-4">
      <button type="submit" name="submit" class="btn btn-primary">Register</button>
    </div>

    <!-- Link para login -->
    <p>Already Registered? Login <a href="login.php">here</a></p>
  </form>
</div>

<script>
  // Função para alternar a visibilidade das senhas
  function showPwd() {
    let pwd = document.getElementById("pwd");
    let conf_pwd = document.getElementById("conf_pwd");

    // Alternar entre tipo 'password' e 'text' para mostrar/esconder as senhas
    if (pwd.type === "password") {
      pwd.type = "text";
    } else {
      pwd.type = "password";
    }

    if (conf_pwd.type === "password") {
      conf_pwd.type = "text";
    } else {
      conf_pwd.type = "password";
    }
  }
</script>

</body>
</html>
