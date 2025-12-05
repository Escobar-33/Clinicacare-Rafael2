<?php
include "../includes/conexao.php";
include "../includes/header.php";

$errors = [];
$success = '';

// load patients and doctors for selects
$pacientes_res = $conn->query("SELECT id, nome FROM pacientes ORDER BY nome");
$medicos_res = $conn->query("SELECT id, nome FROM medicos ORDER BY nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paciente_id = intval($_POST['paciente_id'] ?? 0);
    $medico_id = intval($_POST['medico_id'] ?? 0);
    $data_consulta = $_POST['data_consulta'] ?? '';
    $hora_consulta = $_POST['hora_consulta'] ?? '';

    // basic validation
    if (!$paciente_id || !$medico_id || !$data_consulta || !$hora_consulta) {
        $errors[] = "Preencha todos os campos obrigatórios.";
    } else {
        // rule 1: date not in past
        $hoje = date('Y-m-d');
        if ($data_consulta < $hoje) $errors[] = "Não é permitido agendar em datas passadas.";

        // rule 2: working hours 08:00 - 18:00
        if ($hora_consulta < '08:00' || $hora_consulta > '18:00') $errors[] = "Horário fora do expediente (08:00 - 18:00).";

        // rule 3: doctor cannot have two consultations at same date+time
        $stmt = $conn->prepare("SELECT id FROM consultas WHERE medico_id = ? AND data_consulta = ? AND horario = ?");
        $stmt->bind_param('iss', $medico_id, $data_consulta, $hora_consulta);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) $errors[] = "Este médico já possui consulta nesse dia/horário.";
        $stmt->close();

        // rule 4: patient cannot have more than one consultation the same day
        $stmt = $conn->prepare("SELECT id FROM consultas WHERE paciente_id = ? AND data_consulta = ?");
        $stmt->bind_param('is', $paciente_id, $data_consulta);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) $errors[] = "O paciente já possui uma consulta neste dia.";
        $stmt->close();

        // rule 5: patient name length (fetch name)
        $stmt = $conn->prepare("SELECT nome FROM pacientes WHERE id = ?");
        $stmt->bind_param('i', $paciente_id);
        $stmt->execute();
        $stmt->bind_result($nomePaciente);
        if ($stmt->fetch()) {
            if (mb_strlen(trim($nomePaciente)) < 3) $errors[] = "Nome do paciente muito curto.";
        } else {
            $errors[] = "Paciente inválido.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO consultas (paciente_id, medico_id, data_consulta, horario) VALUES (?,?,?,?)");
        $stmt->bind_param('iiss', $paciente_id, $medico_id, $data_consulta, $hora_consulta);
        if ($stmt->execute()) {
            $success = "Consulta agendada com sucesso.";
        } else {
            $errors[] = "Erro ao agendar: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="card p-4">
  <h3>Agendar Consulta</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><ul>
      <?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?>
    </ul></div>
  <?php endif; ?>

  <form method="post" class="mt-3">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Paciente *</label>
        <select name="paciente_id" class="form-select" required>
          <option value="">-- selecione --</option>
          <?php while($p = $pacientes_res->fetch_assoc()): ?>
            <option value="<?=$p['id']?>"><?=htmlspecialchars($p['nome'])?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Médico *</label>
        <select name="medico_id" class="form-select" required>
          <option value="">-- selecione --</option>
          <?php while($m = $medicos_res->fetch_assoc()): ?>
            <option value="<?=$m['id']?>"><?=htmlspecialchars($m['nome'])?> (<?=htmlspecialchars($m['especialidade'] ?? '')?>)</option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Data *</label>
        <input type="date" name="data_consulta" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Horário * (08:00 - 18:00)</label>
        <input type="time" name="hora_consulta" class="form-control" required>
      </div>
    </div>

    <button class="btn btn-warning" type="submit">Agendar</button>
    <a href="/index.php" class="btn btn-secondary">Voltar</a>
  </form>
</div>

<?php include "../includes/footer.php"; ?>
