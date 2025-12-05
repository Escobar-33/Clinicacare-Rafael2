<?php
include "../includes/conexao.php";
include "../includes/header.php";

$res = $conn->query("SELECT id, nome, email, telefone, data_nascimento FROM pacientes ORDER BY nome");
?>

<div class="card p-4">
  <h3>Lista de Pacientes</h3>

  <table class="table table-striped mt-3">
    <thead>
      <tr><th>Nome</th><th>Email</th><th>Telefone</th><th>Data Nasc.</th><th>Ações</th></tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($row['nome'])?></td>
        <td><?=htmlspecialchars($row['email'])?></td>
        <td><?=htmlspecialchars($row['telefone'])?></td>
        <td><?=htmlspecialchars($row['data_nascimento'])?></td>
        <td>
          <!-- futuramente: link editar / excluir -->
          <!-- Example: editar -> pages/editar_paciente.php?id=... -->
          <a href="#" class="btn btn-sm btn-outline-secondary disabled">Editar</a>
          <a href="#" class="btn btn-sm btn-outline-danger disabled">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="/index.php" class="btn btn-secondary">Voltar</a>
</div>

<?php include "../includes/footer.php"; ?>
