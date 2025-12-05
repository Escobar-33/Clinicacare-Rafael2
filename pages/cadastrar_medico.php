<?php
include "../includes/conexao.php";
include "../includes/header.php";

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $especialidade = trim($_POST['especialidade'] ?? '');

    if ($nome === '' || $especialidade === '') {
        $errors[] = "Preencha os campos obrigatórios (nome, especialidade).";
    } else {
        // checar duplicidade por nome+especialidade (ou poderia usar CRM se existisse)
        $stmt = $conn->prepare("SELECT id FROM medicos WHERE nome = ? AND especialidade = ?");
        $stmt->bind_param('ss', $nome, $especialidade);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Já existe este médico cadastrado com essa especialidade.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO medicos (nome, especialidade) VALUES (?,?)");
        $stmt->bind_param('ss', $nome, $especialidade);
        if ($stmt->execute()) {
            $success = "Médico cadastrado com sucesso.";
            $nome = $especialidade = '';
        } else {
            $errors[] = "Erro ao cadastrar médico: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="card p-4">
  <h3>Cadastrar Médico</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><ul>
      <?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?>
    </ul></div>
  <?php endif; ?>

  <form method="post" class="mt-3">
    <div class="mb-3">
      <label class="form-label">Nome *</label>
      <input type="text" name="nome" class="form-control" value="<?=htmlspecialchars($nome ?? '')?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Especialidade *</label>
      <input type="text" name="especialidade" class="form-control" value="<?=htmlspecialchars($especialidade ?? '')?>" required>
    </div>

    <button class="btn btn-primary" type="submit">Salvar</button>
    <a href="/index.php" class="btn btn-secondary">Voltar</a>
  </form>
</div>

<?php include "../includes/footer.php"; ?>
