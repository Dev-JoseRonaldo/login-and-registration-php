<?php
require "../private/autoload.php";

// Variáveis para armazenar os dados do formulário e os erros
$email = $pwd = "";
$email_err = $pwd_err = "";
$err_msg = "";

// Valida e-mail
function validate_email($email) {
    if (empty($email)) {
        return "Please enter Email";
    }

    $email_regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if (!preg_match($email_regex, $email)) {
        return "Please enter a valid email!";
    }

    return "";
}

// Valida senha
function validate_password($pwd) {
    if (empty($pwd)) {
        return "Please enter Password";
    }
    return "";
}

// Realiza login
function login($email, $pwd, $conn) {
    $email_escaped = esc($email);
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_escaped);
    $stmt->execute();
    return $stmt->get_result();
}

// Processa cookies de "lembrar-me"
function process_remember_me($email, $remember) {
    if (isset($remember)) {
        setcookie("remember_email", $email, time() + 365 * 24 * 60 * 60);
        setcookie("remember", $remember, time() + 365 * 24 * 60 * 60);
    } else {
        setcookie("remember_email", $email, time() - 365 * 24 * 60 * 60);
        setcookie("remember", $remember, time() - 365 * 24 * 60 * 60);
    }
}

if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);
    $remember = isset($_POST['remember']) ? $_POST['remember'] : null;

    // Validações
    $email_err = validate_email($email);
    $pwd_err = validate_password($pwd);

    // Se não houver erros, continua com o login
    if (empty($email_err) && empty($pwd_err)) {
        $result = login($email, $pwd, $conn);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($pwd, $row['password'])) {
                // Iniciar uma nova sessão e regenerar o ID
                session_start();
                session_unset(); // Limpa qualquer sessão anterior
                session_destroy(); // Destroi a sessão anterior
                session_start(); // Inicia uma nova sessão
                session_regenerate_id(true); // Regenera o ID da sessão
                $_SESSION['name'] = $row['name'];

                // Redireciona para a página inicial
                header("Location: index.php");
                exit;

                // Processa cookies de "lembrar-me"
                process_remember_me($email, $remember);
            } else {
                $err_msg = "Incorrect Email or Password";
            }
        }
    }
}
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
            $checked = isset($_COOKIE['remember']) ? "checked" : "";
        ?>
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input
                type="text"
                class="form-control"
                name="email"
                id="email"
                placeholder="Enter your Email"
                value="<?= htmlspecialchars($display_email) ?>"
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
        <p>Not Registered? Register <a href="register.php">here</a></p>
    </form>
</div>

</body>
</html>
