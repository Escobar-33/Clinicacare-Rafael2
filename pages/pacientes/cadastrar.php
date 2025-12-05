<?php 
include "../../includes/conexao.php";
include "../../includes/token.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!validarToken($_POST["token"])) {
        die("Token inválido!");
    }

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);

    if ($nome === "" || $email === "" || $telefone === "") {
        $msg = "Preencha todos os campos!";
    } else {
        // Regra de negócio: e-mail único
        $verifica = $con->query("SELECT * FROM pacientes WHERE email='$email'");
        if ($verifica->num_rows > 0) {
            $msg = "E-mail já está cadastrado!";
        } else {
            $con->query("INSERT INTO pacientes (nome,email,telefone) 
                         VALUES ('$nome','$email','$telefone')");
            $msg = "Paciente cadastrado com sucesso!";
        }
    }
}
?>

<h2>Cadastrar Paciente</h2>

<p><?= $msg ?></p>

<form method="POST">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    Nome: <input type="text" name="nome"><br><br>
    Email: <input type="email" name="email"><br><br>
    Telefone: <input type="text" name="telefone"><br><br>
    <button type="submit">Salvar</button>
</form>
