<?php 
include "../../includes/conexao.php";
include "../../includes/token.php";

$id = $_GET["id"];
$msg = "";

$pac = $con->query("SELECT * FROM pacientes WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!validarToken($_POST["token"])) {
        die("Token inválido!");
    }

    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];

    if ($nome == "" || $telefone == "") {
        $msg = "Preencha tudo!";
    } else {
        $con->query("UPDATE pacientes SET nome='$nome', telefone='$telefone' WHERE id=$id");
        $msg = "Atualizado com sucesso!";
    }
}
?>

<h2>Editar Paciente</h2>
<p><?= $msg ?></p>

<form method="POST">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    Nome: <input type="text" name="nome" value="<?= $pac['nome'] ?>"><br><br>
    Telefone: <input type="text" name="telefone" value="<?= $pac['telefone'] ?>"><br><br>
    <button type="submit">Salvar</button>
</form>
