<?php
include "../includes/conexao.php";
include "../includes/header.php";

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nasc = $_POST['data_nascimento'] ?? '';

    // validações básicas
    if ($nome === '' || $email === '' || $data_nasc === '') {
        $errors[] = "Preencha os campos obrigatórios (nome, email, data de nascimento).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    } else {
        // checar duplicidade de email
        $stmt = $conn->prepare("SELECT id FROM pacientes WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Já existe paciente cadastrado com esse e-mail.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO pacientes (nome, email, telefone, data_nascimento) VALUES (?,?,?,?)");
        $stmt->bind_param('ssss', $nome, $email, $telefone, $data_nasc);
        if ($stmt->execute()) {
            $success = "Paciente cadastrado com sucesso.";
            // limpar variáveis
            $nome = $email = $telefone = $data_nasc = '';
        } else {
            $errors[] = "Erro ao cadastrar paciente: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="card p-4">
  <h3>Cadastrar Paciente</h3>

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
      <label class="form-label">Email *</label>
      <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($email ?? '')?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Telefone</label>
      <input type="text" name="telefone" class="form-control" value="<?=htmlspecialchars($telefone ?? '')?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Data de Nascimento *</label>
      <input type="date" name="data_nascimento" class="form-control" value="<?=htmlspecialchars($data_nasc ?? '')?>" required>
    </div>

    <button class="btn btn-success" type="submit">Salvar</button>
    <a href="/index.php" class="btn btn-secondary">Voltar</a>
  </form>
</div>

<?php include "../includes/footer.php"; ?>
