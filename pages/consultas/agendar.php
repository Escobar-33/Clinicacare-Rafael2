<?php 
include "../../includes/conexao.php";
include "../../includes/token.php";

$msg = "";

$pacientes = $con->query("SELECT * FROM pacientes ORDER BY nome");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!validarToken($_POST["token"])) die("Token inválido!");

    $paciente_id = $_POST["paciente_id"];
    $data = $_POST["data"];
    $hora = $_POST["hora"];

    if ($paciente_id == "" || $data == "" || $hora == "") {
        $msg = "Todos os campos são obrigatórios!";
    } 
    else if ($data < date("Y-m-d")) {
        $msg = "Não é permitido agendar no passado!";
    }
    else {
        // Regra: evitar conflito de horário
        $check = $con->query("
            SELECT * FROM consultas 
            WHERE data_consulta='$data' AND hora_consulta='$hora'
        ");

        if ($check->num_rows > 0) {
            $msg = "Esse horário já está ocupado!";
        } else {
            $con->query("
                INSERT INTO consultas (paciente_id, data_consulta, hora_consulta)
                VALUES ($paciente_id, '$data', '$hora')
            ");
            $msg = "Consulta agendada com sucesso!";
        }
    }
}
?>

<h2>Agendar Consulta</h2>

<p><?= $msg ?></p>

<form method="POST">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

    Paciente:<br>
    <select name="paciente_id">
        <option value="">Selecione</option>
        <?php while($p = $pacientes->fetch_assoc()) { ?>
        <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php } ?>
    </select><br><br>

    Data: <input type="date" name="data"><br><br>
    Hora: <input type="time" name="hora"><br><br>

    <button type="submit">Agendar</button>
</form>
