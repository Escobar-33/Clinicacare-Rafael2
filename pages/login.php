<?php
session_start();
include "../includes/conexao.php";
include "../includes/token.php";

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $token = $_POST['token'];

    if (!validarToken($token)) {
        die("Falha de segurança: token inválido.");
    }

    $sql = "SELECT * FROM usuarios WHERE email='$email' LIMIT 1";
    $res = $conexao->query($sql);

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];

            header("Location: ../index.php");
            exit;
        }
    }

    $erro = "E-mail ou senha incorretos";
}
?>

<?php include "../includes/header.php"; ?>

<div class="card p-4">
    <h2 class="mb-3">Login</h2>

    <?php if ($erro) { echo "<div class='alert alert-danger'>$erro</div>"; } ?>

    <form method="POST">
        <input type="hidden" name="token" value="<?= gerarToken(); ?>">

        <div class="mb-3">
            <label>E-mail:</label>
            <input type="email" name="email" required class="form-control">
        </div>

        <div class="mb-3">
            <label>Senha:</label>
            <input type="password" name="senha" required class="form-control">
        </div>

        <button class="btn btn-primary w-100">Entrar</button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>
